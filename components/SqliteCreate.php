<?php

namespace app\components;

use yii;
use yii\base\Component;
use yii\data\ArrayDataProvider;
use yii\web\HttpException;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use SQLite3;

class SqliteCreate extends Component {

    public $menu = [];
    public $tables = [];
    public $fp;
    public $file_name;
    public $enableZip = false;
    public $_path = null;
    public $back_temp_file = 'db_backup_';
    private $_truncate_table = [];
    public $db_name;
    public $export_db;
    public $_organisation_code;
    public $_organisation_type;
    public $android_db;
    public $is_offline = FALSE;

    protected function getPath() {
        if (empty($this->_path)) {
            $this->_path = Yii::$app->basePath . '/installation-identity/';

            if (!file_exists($this->_path)) {
                mkdir($this->_path);
                chmod($this->_path, '777');
            }
        }
        return $this->_path;
    }

    //use for Android
    public function getTables($dbName = null, $geo = false) {
        $tables = [];
        $results = $this->android_db->query('SELECT * FROM sqlite_master WHERE type="table"');
        if ($geo) {
            $results = $this->android_db->query('SELECT * FROM sqlite_master WHERE type="table" and name in ("tbl_states", "tbl_districts", "tbl_sub_districts", "tbl_villages", "tbl_hamlets", "tbl_states_local", "tbl_districts_local", "tbl_sub_districts_local", "tbl_villages_local", "tbl_hamlets_local")');
        }
        while ($row = $results->fetchArray()) {
//            $table_name = $row['tbl_name'];
//            if (!in_array($table_name, ['android_metadata', 'room_master_table', 'sqlite_sequence', 'tbl_states', 'tbl_districts', 'tbl_sub_districts', 'tbl_villages', 'tbl_hamlets'])) {
            $tables[] = $row['tbl_name'];
//            }
        }
//        $sql = 'SHOW TABLES';
//        $cmd = $this->export_db->createCommand($sql);
//        $tables = $cmd->queryColumn();
        return $tables;
    }

    public function getDataDcs($android_tables, $dcs_code, $bmc_code, $mcc_plant_code, $plant_code, $org_code, $org_type, $union_code = '') {
        try {
            $current_date = date('Y-m-d');
            if ($this->is_offline) {
                $sqls = 'SELECT *  FROM tbl_table_list';
            } else {
                $sqls = 'SELECT *  FROM tbl_table_list where is_offline=0';
            }

            $cmd = Yii::$app->db->createCommand($sqls);
            $tables = $cmd->queryAll();
            foreach ($tables as $field) {
                try {
                    $tables_fields = [];
                    $tableName = $field['table_name'];
                    $insert_table = $tableName;
                    //$insert_table = $tableName == 'tbl_route_mapping' ? 'tbl_route' : $tableName;
                    // $insert_table = $tableName == 'tbl_route_mapping_sources' ? 'tbl_route_mapping' : $tableName;
                    if ($tableName == 'tbl_route_mapping') {
                        $insert_table = 'tbl_route';
                    } else if ($tableName == 'tbl_route_mapping_sources') {
                        $insert_table = 'tbl_route_mapping';
                    }

                    if (in_array($insert_table, $android_tables)) {
                        $insert_data = '';
                        $results = $this->android_db->query('PRAGMA table_info(' . $insert_table . ')');
                        while ($row = $results->fetchArray()) {
                            $tables_fields[] = $row['name'];
                        }
                        $tables_fields = implode(',', $tables_fields);
                        $fields = str_replace(',', ',' . $field['table_name'] . '.', $tables_fields);
                        $fields = $field['table_name'] . '.' . $fields;
                        if ($field['is_main'] == 1) {
                            if ($field['key_field'] == NULL) {
                                $sql = 'SELECT ' . $fields . ' FROM ' . $tableName;
                            } else {
                                if ($field['key_field'] == 'to_dest') {
                                    $sql = 'SELECT ' . $fields . ' FROM ' . $tableName . ' where (' . $field['key_field'] . ' is NULL or (' . $field['key_field'] . " in ($bmc_code) and lower(to_type) = 'bmc')" . ' or (' . $field['key_field'] . " in ($mcc_plant_code) and lower(to_type) = 'mcc'))";
                                } else {
                                    $sql = 'SELECT ' . $fields . ' FROM ' . $tableName . ' where ' . $field['key_field'] . " in (${$field['key_field']})";
                                }
                            }
                        } else {
                            $sql = 'SELECT distinct ' . $fields . ' FROM ' . $tableName . ' inner join ' . $field['primary_table'] . ' on ' . $tableName . '.' . $field['child_key'] . '=' . $field['primary_table'] . '.' . $field['child_key'] . ' where ' . $field['primary_table'] . '.' . $field['key_field'] . " in (${$field['key_field']})";
                        }

                        if (in_array($tableName, ['tbl_purchase_rate_applicability', 'tbl_purchase_rate', 'tbl_purchase_rate_based', 'tbl_purchase_rate_details'])) {
                            $sql.= ' and tbl_purchase_rate_applicability.is_active=1 and tbl_purchase_rate_applicability.wef_date >= (select TOP(1) wef_date from tbl_purchase_rate_applicability where ' . $field['key_field'] . " in (${$field['key_field']})" . '  and tbl_purchase_rate_applicability.is_active=1 and CAST(wef_date as date) <= \''.$current_date.'\' order by wef_date DESC)';
                        }
                        $cmd = $this->export_db->createCommand($sql);
                        $dataReader = $cmd->queryAll();
                        if (!empty($dataReader)) {
                            $i = 1;
                            $saveData = [];
                            $db = $this->export_db;
                            $dbName = $this->getDsnAttribute('Database', $db->dsn);
                            $sql = "SELECT DATA_TYPE,COLUMN_NAME  FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_CATALOG='{$dbName}' and table_name = '{$tableName}'";
                            $cmd = $this->export_db->createCommand($sql);
                            $dataType = $cmd->queryAll();
                            foreach ($dataReader as $table_data) {
                                $this->setInsertData($table_data, $dataType, $insert_data);
                                $insert_data = '(' . $insert_data . ')';
                                $saveData[] = $insert_data;
                                $insert_data = '';
                                if (($i % 100 == 0) || $i == count($dataReader)) {
                                    $insertData = implode(',', $saveData);
                                    //$I_QUERY = 'insert into ' . $insert_table . ' (' . $tables_fields . ') VALUES ' . $insertData . ';';
                                    $this->android_db->exec('insert into ' . $insert_table . ' (' . $tables_fields . ') VALUES ' . $insertData . ';');
                                    $saveData = [];
                                    if ($i == count($dataReader)) {
                                        $i = 0;
                                    }
                                }
                                $i++;
                            }
                        }
                    }
                } catch (\Exception $e) {
                    fwrite($this->fp, $insert_table . PHP_EOL);
                    fwrite($this->fp, 'insert into ' . $insert_table . ' (' . $tables_fields . ') VALUES (' . $insert_data . ');' . PHP_EOL);
                    fwrite($this->fp, $e . PHP_EOL);
                    fwrite($this->fp, '---------' . PHP_EOL);
                    fwrite($this->fp, '---------' . PHP_EOL);
                    return FALSE;
                }
            }
        } catch (\Exception $e) {
            fwrite($this->fp, $insert_table . PHP_EOL);
            fwrite($this->fp, 'insert into ' . $insert_table . ' (' . $tables_fields . ') VALUES (' . $insert_data . ');' . PHP_EOL);
            fwrite($this->fp, $e . PHP_EOL);
            fwrite($this->fp, '---------' . PHP_EOL);
            fwrite($this->fp, '---------' . PHP_EOL);
            return FALSE;
        }
        return TRUE;
    }

    //use for Android
    public function createSqlFileDcs($fileName, $dcs_code, $bmc_code, $mcc_plant_code, $plant_code, $org_code, $org_type, $union_code = '') {
        set_time_limit(5400);
        $this->android_db = new SQLite3('installation-identity/' . $fileName);
        $this->db_name = $org_code;
        $this->back_temp_file = $fileName;
        $this->export_db = Yii::$app->db;
        $android_tables = $this->getTables();
        $this->file_name = Yii::$app->basePath . '/installation-identity/error.txt';
        $this->fp = fopen($this->file_name, 'w+');
        $this->android_db->exec('PRAGMA synchronous = OFF;');
        $this->android_db->exec('PRAGMA foreign_keys = OFF;');
        $response = $this->getDataDcs($android_tables, $dcs_code, $bmc_code, $mcc_plant_code, $plant_code, $org_code, $org_type, $union_code);
        $this->android_db->exec('PRAGMA foreign_keys = ON;');
        $this->android_db->exec('PRAGMA synchronous = ON;');
        $this->android_db->close();
        return $response;
    }

    private function getDsnAttribute($name, $dsn) {
        if (preg_match('/' . $name . '=([^;]*)/', $dsn, $match)) {
            return $match[1];
        } else {
            return null;
        }
    }

    public function setInsertData($table_data, $dataType, &$insert_data) {
        foreach ($table_data as $key => $value) {
            $index = array_search($key, array_column($dataType, 'COLUMN_NAME'));
            if (is_null($value)) {
                $value = 'NULL';
                if (in_array($dataType[$index]['DATA_TYPE'], ['bit', 'binary', 'int', 'bigint', 'decimal', 'float', 'numeric', 'smallint'])) {
                    $value = 0;
                }
            } else {
                if (in_array($dataType[$index]['DATA_TYPE'], ['bit', 'binary'])) {
                    if (ord($value) === 1 || ord($value) === 0) {
                        $value = addslashes(ord($value));
                    } else {
                        $value = addslashes(chr(ord($value)));
                    }
                } else if ($dataType[$index]['DATA_TYPE'] === 'char') {
                    $value = addslashes(ord($value));
                } else {
                    $value = "'" . str_replace("'", "''", $value) . "'";
                }
            }
            $insert_data.=$value . ",";
        }
        $insert_data = rtrim($insert_data, ',');
    }

}
