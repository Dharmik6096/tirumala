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
                        if (in_array($tableName, ['tbl_product_stock'])) {
                            $tables_fields = ['product_stock_code', 'product_code', 'stock', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_at'];
                        }
                        $tables_fields = implode(',', $tables_fields);
                        $fields = str_replace(',', ',' . $field['table_name'] . '.', $tables_fields);
                        if ($field['table_name'] == 'tbl_dcs_milk_dispatch_txn') {
                            $fields = str_replace('tbl_dcs_milk_dispatch_txn.amount', 'tbl_dcs_milk_dispatch_txn.total_amount', $fields);
                        }
                        $fields = $field['table_name'] . '.' . $fields;
                        if (in_array($tableName, ['tbl_product_stock'])) {
                            $fields = str_replace('tbl_product_stock.product_stock_code', "concat('MCC-', tbl_product_stock.mcc_plant_code, REPLACE(product_code, CONCAT('PORTAL-', tbl_product_stock.union_code), '')) as product_stock_code", $fields);
                            $fields = str_replace('tbl_product_stock.stock', "sum(tbl_product_stock.stock) as stock", $fields);
                            $fields = str_replace('tbl_product_stock.created_at', "getdate() as created_at", $fields);
                        }
                        $sql = '';
                        if ($field['is_main'] == 1) {
                            if ($field['key_field'] == NULL) {
                                $sql = 'SELECT ' . $fields . ' FROM ' . $tableName;

                                if (in_array($tableName, ['tbl_product_stock', 'tbl_product_stock_transaction'])) {
                                    $whereMccStock = !empty($mcc_plant_code) ? $mcc_plant_code : '\'\'';
                                    $whereBmcStock = !empty($bmc_code) ? $bmc_code : '\'\'';
                                    $whereDcsStock = !empty($dcs_code) ? $dcs_code : '\'\'';
                                    if (strtolower($org_type) == 'mcc') {
                                        $sql .= " where $tableName.bmc_code   in ($whereBmcStock) and $tableName.bmc_code is null  and $tableName.dcs_code is null ";
                                    } else if (strtolower($org_type) == 'bmc') {
                                        $sql .= " where $tableName.bmc_code   in ($whereBmcStock) and $tableName.dcs_code is null ";
                                    } else if (strtolower($org_type) == 'vlc') {
                                        $sql .= " where $tableName.bmc_code   in ($whereBmcStock) and $tableName.dcs_code   in ($whereDcsStock) ";
                                    }
                                    if (in_array($tableName, ['tbl_product_stock'])) {
                                        $sql .= " group by tbl_product_stock.product_code, tbl_product_stock.union_code,tbl_product_stock.plant_code,tbl_product_stock.mcc_plant_code, tbl_product_stock.bmc_code,tbl_product_stock.dcs_code";
                                    }
                                }
                            } else {
                                if ($field['table_name'] == 'tbl_payment_cycle_applicability') {
                                    $whereKeyField = $bmc_code;
                                    $sql = 'SELECT ' . $fields . ' FROM ' . $tableName . ' where ' . $field['key_field'] . " in ($whereKeyField) AND tbl_payment_cycle_applicability.applicable_type = 'DCS' AND tbl_payment_cycle_applicability.applicable_for = 'BMC' ";
                                } else if ($field['key_field'] == 'to_dest') {
                                    $whereBmc = !empty($bmc_code) ? $bmc_code : '\'\'';
                                    $whereMcc = !empty($mcc_plant_code) ? $mcc_plant_code : '\'\'';
                                    $sql = 'SELECT ' . $fields . ' FROM ' . $tableName . ' where (' . $field['key_field'] . ' is NULL or (' . $field['key_field'] . " in ($whereBmc) and lower(to_type) = 'bmc')" . ' or (' . $field['key_field'] . " in ($whereMcc) and lower(to_type) = 'mcc'))";
                                } else if ($field['key_field'] == 'source_org_code') {
                                    $whereBmc = !empty($bmc_code) ? $bmc_code : '\'\'';
                                    $sql = 'SELECT ' . $fields . ' FROM ' . $tableName . ' where (' . $field['key_field'] . ' is NULL or (' . $field['key_field'] . " in ($whereBmc) and lower(source_org_type) = 'bmc'))";
                                } else if ($field['key_field'] == 'applicable_code') {
                                    $whereBmc = !empty($bmc_code) ? $bmc_code : '\'\'';
                                    $whereMcc = !empty($mcc_plant_code) ? $mcc_plant_code : '\'\'';
                                    $whereDcs = !empty($dcs_code) ? $dcs_code : '\'\'';
                                    if (strtolower($org_type) == 'vlc') {
                                        $sql = 'SELECT ' . $fields . ' FROM ' . $tableName . ' where (' . $field['key_field'] . ' is NULL or (' . $field['key_field'] . " in ($whereDcs) and lower(applicable_for) = 'dcs')) ";
                                    } else {
                                        $sql = 'SELECT ' . $fields . ' FROM ' . $tableName . ' where (' . $field['key_field'] . ' is NULL or (' . $field['key_field'] . " in ($whereDcs) and lower(applicable_for) = 'dcs')" . ' or (' . $field['key_field'] . " in ($whereBmc) and lower(applicable_for) != 'dcs'))";
                                    }
                                } else {
                                    $whereKeyField = !empty(${$field['key_field']}) ? ${$field['key_field']} : '\'\'';
                                    $sql = 'SELECT ' . $fields . ' FROM ' . $tableName;
                                    $whereKey = $field['key_field'];
                                    if ($tableName == 'tbl_member') {
                                        $currDate = date('Y-m-d');
                                        if ($field['key_field'] == 'dcs_code') {
                                            $whereKey = 'tbl_member.' . $field['key_field'];
                                        }
                                        $sql .= ' left join tbl_member_deactive md on md.member_code = tbl_member.member_code and (\'' . $currDate . '\' between CAST(md.from_date as date) and CAST(ISNULL(md.to_date, getdate()) as date)) ';
                                        $sql = str_replace('tbl_member.is_active', ' CASE WHEN md.from_date is null THEN tbl_member.is_active ELSE 0 END as is_active ', $sql);
                                        if ($org_type == 'BMC') {
                                            $sql .= ' inner join tbl_dcs d on d.dcs_code = tbl_member.dcs_code and d.is_bmc = 1 ';
                                        }
                                    } else if ($tableName == 'tbl_dcs') {
                                        $currDate = date('Y-m-d');
                                        if ($field['key_field'] == 'dcs_code') {
                                            $whereKey = 'tbl_dcs.' . $field['key_field'];
                                        }
                                        $sql .= ' left join tbl_dcs_deactive dd on dd.dcs_code = tbl_dcs.dcs_code and (\'' . $currDate . '\' between CAST(dd.from_date as date) and CAST(ISNULL(dd.to_date, getdate()) as date)) ';
                                        $sql = str_replace('tbl_dcs.is_active', ' CASE WHEN dd.from_date is null THEN tbl_dcs.is_active ELSE 0 END as is_active ', $sql);
                                    } else if ($tableName == 'tbl_customer_master') {
                                        $currDate = date('Y-m-d');
                                        if ($field['key_field'] == 'bmc_code') {
                                            $whereKey = 'tbl_customer_master.' . $field['key_field'];
                                        }
                                        $sql .= ' left join tbl_customer_deactive cd on cd.customer_code = tbl_customer_master.customer_code and (\'' . $currDate . '\' between CAST(cd.from_date as date) and CAST(ISNULL(cd.to_date, getdate()) as date)) ';
                                        $sql = str_replace('tbl_customer_master.is_active', ' CASE WHEN cd.from_date is null THEN tbl_customer_master.is_active ELSE 0 END as is_active ', $sql);
                                    }
                                    $sql .= ' where ' . $whereKey . " in ($whereKeyField)";
                                    if ($tableName == 'tbl_member') {
                                        
                                    }
                                }
                            }
                        } else {
                            $whereKeyField = !empty(${$field['key_field']}) ? ${$field['key_field']} : '\'\'';
                            $sql = 'SELECT distinct ' . $fields . ' FROM ' . $tableName . ' inner join ' . $field['primary_table'] . ' on ' . $tableName . '.' . $field['child_key'] . '=' . $field['primary_table'] . '.' . $field['child_key'] . ' where ' . $field['primary_table'] . '.' . $field['key_field'] . " in ($whereKeyField)";
                        }
                        if (!empty($sql) && in_array($tableName, ['tbl_purchase_rate_applicability', 'tbl_purchase_rate', 'tbl_purchase_rate_based', 'tbl_purchase_rate_details'])) {
                            $whereKeyField = !empty(${$field['key_field']}) ? ${$field['key_field']} : '\'\'';
                            $sql .= ' and tbl_purchase_rate_applicability.is_active=1 and tbl_purchase_rate_applicability.wef_date >= (select TOP(1) wef_date from tbl_purchase_rate_applicability where ' . $field['key_field'] . " in ($whereKeyField)" . '  and tbl_purchase_rate_applicability.is_active=1 and CAST(wef_date as date) <= \'' . $current_date . '\' order by wef_date DESC)';
                        }
                        if (!empty($sql)) {
                            $cmd = $this->export_db->createCommand($sql);
                            $dataReader = $cmd->queryAll();
                        } else {
                            $dataReader = [];
                        }
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
        $this->android_db = new SQLite3(Yii::$app->basePath . '/web/installation-identity/' . $fileName);
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
            $insert_data .= $value . ",";
        }
        $insert_data = rtrim($insert_data, ',');
    }

}
