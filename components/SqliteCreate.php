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

    public function getProcess() {
        $sql = 'SELECT distinct process_name FROM tbl_table_list_federation_union';
        $cmd = Yii::$app->db->createCommand($sql);
        $tables = $cmd->queryColumn();
        return $tables;
    }

    //not used but require
    public function getProcessDcs() {
//        $sql = 'SELECT distinct process_name FROM tbl_table_list where process_name not in ("geo","attachment")';
        $sql = 'SELECT distinct `table` FROM tbl_table_list where process_name not in ("geo","attachment")';
        $cmd = Yii::$app->db->createCommand($sql);
        $tables = $cmd->queryColumn();
        return $tables;
    }

    public function getProcessGeo() {
        $sql = 'SELECT distinct process_name FROM tbl_table_list where process_name="geo"';
        $cmd = Yii::$app->db->createCommand($sql);
        $tables = $cmd->queryColumn();
        return $tables;
    }

    public function getProcessAttachment() {
        $sql = 'SELECT distinct process_name FROM tbl_table_list where process_name="attachment"';
        $cmd = Yii::$app->db->createCommand($sql);
        $tables = $cmd->queryColumn();
        return $tables;
    }

    public function writeSp() {
        $sql = 'SHOW PROCEDURE STATUS WHERE Db = DATABASE() AND Type = "PROCEDURE"';
        $cmd = $this->export_db->createCommand($sql)->queryAll();

        fwrite($this->fp, 'DELIMITER $$' . PHP_EOL);
        foreach ($cmd as $row) {
            $sql = 'SHOW CREATE PROCEDURE ' . $row['Name'];
            $cmd = $this->export_db->createCommand($sql)->queryAll();
            fwrite($this->fp, 'DROP PROCEDURE IF EXISTS `' . $row['Name'] . '`$$' . PHP_EOL);
            fwrite($this->fp, $cmd[0]['Create Procedure'] . '$$' . PHP_EOL);
            fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        }
        fwrite($this->fp, 'DELIMITER ;' . PHP_EOL);
        return true;
    }

    //not used
    public function StartBackup($addcheck = true) {
//        $this->SetDatabase($addcheck);
        return true;
    }

    //not used
    public function SetDatabase($addcheck = true) {
//        fwrite($this->fp, 'CREATE DATABASE  IF NOT EXISTS `' . $this->db_name . '` /*!40100 DEFAULT CHARACTER SET latin1 */;' . PHP_EOL);
//        fwrite($this->fp, 'USE `' . $this->db_name . '`;' . PHP_EOL);
//        if ($addcheck) {
//            fwrite($this->fp, 'SET AUTOCOMMIT=0;' . PHP_EOL);
//            fwrite($this->fp, 'START TRANSACTION;' . PHP_EOL);
//            fwrite($this->fp, 'SET SQL_QUOTE_SHOW_CREATE = 1;' . PHP_EOL);
//        }
//        fwrite($this->fp, 'SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;' . PHP_EOL);
//        fwrite($this->fp, 'SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;' . PHP_EOL);
//        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
//        $this->writeComment('START BACKUP');
        return true;
    }

    //not used
    public function getColumns($tableName) {
        $sql = 'SHOW CREATE TABLE ' . $tableName;
        $cmd = $this->export_db->createCommand($sql);
        $table = $cmd->queryOne();

        $create_query = $table ['Create Table'] . ';';

        $create_query = preg_replace('/^CREATE TABLE/', 'CREATE TABLE IF NOT EXISTS', $create_query);
        $create_query = preg_replace('/AUTO_INCREMENT\s*=\s*([0-9])+/', '', $create_query);
        if ($this->fp) {
            $this->writeComment('TABLE `' . addslashes($tableName) . '`');
            $final = 'DROP TABLE IF EXISTS `' . addslashes($tableName) . '`;' . PHP_EOL . $create_query . PHP_EOL . PHP_EOL;
            fwrite($this->fp, $final);
        } else {
            $this->tables [$tableName] ['create'] = $create_query;
            return $create_query;
        }
    }

    public function getData($tableName) {
// $tableName = 'tbl_bank_district';

        $sql = 'SELECT * FROM ' . $tableName;
        $cmd = $this->export_db->createCommand($sql);
        $dataReader = $cmd->query();
        $prefix = '';
        $data_string = '';
        if ($this->fp && !empty($dataReader)) {
            
        }
        $cnt = 0;
        foreach ($dataReader as $data) {
            if ($cnt == 0) {
                $itemNames = array_keys($data);
                $itemNames = array_map("addslashes", $itemNames);
                $items = join('`,`', $itemNames);
                $prefix = "INSERT INTO `$tableName` (`$items`) VALUES " . PHP_EOL;
            }
            $itemList = $this->add_slash_manual($data, $tableName); //array_map ( array($this, 'add_slash_manual'), $itemValues );
            $valueString = "(" . $itemList . "),";
            if ($valueString != "") {
                $data_string .= rtrim($valueString, ",") . "," . PHP_EOL;
            }
            $cnt++;
        }
        if ($this->fp && $cnt >= 1) {
            $this->writeComment('TABLE DATA ' . $tableName);
            $data_string = rtrim($data_string, PHP_EOL);
            $data_string = rtrim($data_string, ',') . ';' . PHP_EOL;
            $string = $prefix . $data_string;
            fwrite($this->fp, $string);
            $this->writeComment('TABLE DATA ' . $tableName);
            $final = PHP_EOL . PHP_EOL;
            fwrite($this->fp, $final);
        }
    }

    public function getDataDcs($android_tables, $dcs_code, $bmc_code, $mcc_code, $plant_code, $org_code, $org_type, $union_code = '') {
        try {
            $sqls = 'SELECT *  FROM tbl_table_list';
            $cmd = Yii::$app->db->createCommand($sqls);
            $tables = $cmd->queryAll();

            foreach ($tables as $field) {
                $tables_fields = [];
                $tableName = $field['table_name'];
                $tableName = $tableName == 'tbl_dcs_subcenter_bmc_info' ? 'tbl_bmc' : $tableName;
                $tableName = $tableName == 'tbl_route_mapping' ? 'tbl_route' : $tableName;
                $tableName = $tableName == 'tbl_route_mapping_sources' ? 'tbl_route_mapping' : $tableName;
                if (in_array($tableName, $android_tables)) {
                    $insert_data = '';
                    $results = $this->android_db->query('PRAGMA table_info(' . $tableName . ')');
                    while ($row = $results->fetchArray()) {
                        if (!in_array($row['name'], ['x_col1', 'x_col2', 'ex_col1', 'ex_col2'])) {
                            $tables_fields[] = $row['name'];
                        }
                    }
                    $tables_fields = implode(',', $tables_fields);
                    if ($tableName == 'tbl_bmc') {
                        $tableName = 'tbl_dcs_subcenter_bmc_info';
                    } else if ($tableName == 'tbl_route_mapping') {
                        $tableName = 'tbl_route_mapping_sources';
                    } else if ($tableName == 'tbl_route') {
                        $tableName = 'tbl_route_mapping';
                    }
//                    if (in_array($tableName, $process)) {
                    $sqls = 'SELECT *  FROM tbl_table_list where table_name= \'' . $tableName . '\'';
                    $cmd = Yii::$app->db->createCommand($sqls);
                    $field = $cmd->queryAll();
                    if (!empty($field)) {
                        $field = $field[0];
                        $fields = str_replace(',', ',' . $field['table_name'] . '.', $tables_fields);
                        $fields = $field['table_name'] . '.' . $fields;
                        $where = '';
                        if ($field['key_field'] == 'dcs_code') {
                            $where = $dcs_code;
                        } else if ($field['key_field'] == 'bmc_code') {
                            $where = $bmc_code;
                        } else if ($field['key_field'] == 'mcc_plant_code') {
                            $where = $mcc_code;
                        } else if ($field['key_field'] == 'plant_code') {
                            $where = $plant_code;
                        } else if ($field['key_field'] == 'union_code') {
                            $where = $union_code;
                        }
                        
                        $tableName = $field['table_name'];
                        if ($field['key_field'] == NULL) {
                            $sql = 'SELECT ' . $fields . ' FROM ' . $tableName;
                        } else {
                            if ($field['key_field'] == 'from_dest') {
                                $sql = 'SELECT ' . $fields . ' FROM ' . $tableName . ' where (' . $field['key_field'] . ' is NULL or ' . $field['key_field'] . " in ($dcs_code)) and from_type = 'society'";
                            } else if ($field['key_field'] == 'to_dest') {
                                $sql = 'SELECT ' . $fields . ' FROM ' . $tableName . ' where (' . $field['key_field'] . ' is NULL or ' . $field['key_field'] . " in ($bmc_code)) and to_type = 'bmc'";
                            } else {
                                $sql = 'SELECT ' . $fields . ' FROM ' . $tableName . ' where (' . $field['key_field'] . ' is NULL or ' . $field['key_field'] . " in ($where))";
                            }
                        }
                    }
                    $cmd = $this->export_db->createCommand($sql);
                    $dataReader = $cmd->queryAll();
                    if ($tableName == 'tbl_dcs_subcenter_bmc_info') {
                        $tableName = 'tbl_bmc';
                    } else if ($tableName == 'tbl_route_mapping') {
                        $tableName = 'tbl_route';
                    } else if ($tableName == 'tbl_route_mapping_sources') {
                        $tableName = 'tbl_route_mapping';
                    }
                    if (!empty($dataReader)) {
                        $str = '';
                        foreach ($dataReader as $table_data) {
                            $insert_data = '';
                            $insert_data = implode('####', $table_data);
                            $insert_data = '\'' . str_replace('\'', '"', $insert_data) . '\'';
                            $insert_data = str_replace('####', '\',\'', $insert_data);
                            $I_QUERY = 'insert into ' . $tableName . ' (' . $tables_fields . ') VALUES (' . $insert_data . ');';
                            $this->android_db->exec('insert into ' . $tableName . ' (' . $tables_fields . ') VALUES (' . $insert_data . ');');
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            fwrite($this->fp, $tableName . PHP_EOL);
            fwrite($this->fp, 'insert into ' . $tableName . ' (' . $tables_fields . ') VALUES (' . $insert_data . ');' . PHP_EOL);
            fwrite($this->fp, $e . PHP_EOL);
            fwrite($this->fp, '---------' . PHP_EOL);
            fwrite($this->fp, '---------' . PHP_EOL);
        }
    }

    public function getDataFederationUnion($tableName, $state_code, $district_code, $language_code, $union_code, $federation_code, $sub_district_code) {
        $sqls = 'SELECT *  FROM tbl_table_list_federation_union where process_name= "' . $tableName . '"';
        $cmd = Yii::$app->db->createCommand($sqls);
        $tables = $cmd->queryAll();

        foreach ($tables as $field) {
            if ($field['key_field'] != NULL && !isset(${$field['key_field']})) {
                $u_arrray = [];
                if (!empty($union_code)) {
                    $u_arrray = explode(',', $union_code);
                }
                $f_array = explode(',', $federation_code);
                $k_array = array_merge($u_arrray, $f_array);
                ${$field['key_field']} = implode(',', $k_array);
            }
            if (!empty(${$field['key_field']})) {
                $org_filter = explode(',', ${$field['key_field']});
                $org_filter = implode("','", $org_filter);
                $org_filter = "'" . $org_filter . "'";
            }
            if ($field['is_main'] == 1) {
                $tableName = $field['table'];
                if ($field['table'] == 'tbl_addressbook') {
                    $tableName = $field['table'] . '_portal';
                    $org_filter = "'$this->_organisation_type'";
                }
                if ($field['table'] == 'installation_identity') {
                    $org_filter = "'$this->_organisation_code'";
                }
                if ($field['key_field'] == NULL) {
                    $sql = 'SELECT * FROM ' . $tableName;
                } else {
                    if (empty(${$field['key_field']})) {
                        $sql = 'SELECT * FROM ' . $tableName . ' where (' . $field['key_field'] . ' is NULL)';
                    } else {
                        $sql = 'SELECT * FROM ' . $tableName . ' where (' . $field['key_field'] . ' is NULL or ' . $field['key_field'] . " in ($org_filter))";
                    }
                }
            } else {
                if (empty(${$field['key_field']})) {
                    $sql = 'SELECT distinct ' . $field['table'] . '.* FROM ' . $field['table'] . ' inner join ' . $field['primary_table'] . ' on ' . $field['table'] . '.' . $field['child_key'] . '=' . $field['primary_table'] . '.' . $field['child_key'] . ' where (' . $field['primary_table'] . '.' . $field['key_field'] . ' is NULL)';
                } else {
                    $sql = 'SELECT distinct ' . $field['table'] . '.* FROM ' . $field['table'] . ' inner join ' . $field['primary_table'] . ' on ' . $field['table'] . '.' . $field['child_key'] . '=' . $field['primary_table'] . '.' . $field['child_key'] . ' where (' . $field['primary_table'] . '.' . $field['key_field'] . ' is NULL or ' . $field['primary_table'] . '.' . $field['key_field'] . " in ($org_filter))";
                }
            }
            $tableName = $field['table'];
            if ($tableName == 'tbl_tax_depends') {
                $sql = 'select distinct tbl_tax_depends.* from tbl_tax_depends where tax_detail_id in (select tax_detail_id from tbl_tax_detail where tax_code in (select tax_code from tbl_tax where federation_code is null or federation_code in (' . $federation_code . '))) or tax_details_id in (select tax_detail_id from tbl_tax_detail where tax_code in (select tax_code from tbl_tax where federation_code is null or federation_code in ("' . $federation_code . '")))';
            } else if ($tableName == 'tbl_banks') {
                $sql = '(SELECT distinct ' . $field['table'] . '.* FROM ' . $field['table'] . ' inner join ' . $field['primary_table'] . ' on ' . $field['table'] . '.' . $field['child_key'] . '=' . $field['primary_table'] . '.' . $field['child_key'] . ' where (' . $field['primary_table'] . '.' . $field['key_field'] . ' is NULL or ' . $field['primary_table'] . '.' . $field['key_field'] . " in ($org_filter))) union (select * from tbl_banks where tbl_banks.nationalized_bank=1)";
            }
            $cmd = $this->export_db->createCommand($sql);
            $dataReader = $cmd->query();
            $prefix = '';
            $data_string = '';
            $data_string_long = '';
            if ($this->fp && !empty($dataReader)) {
                
            }
            $cnt = 0;
            $totcnt = 1;
            $local_key = [];

            $db = Yii::$app->getDb();
            $dbName = $this->getDsnAttribute('dbname', $db->dsn);
            $sql = "SELECT DATA_TYPE,COLUMN_NAME  FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='{$dbName}' and table_name = '{$tableName}'";
            $cmd = $this->export_db->createCommand($sql);
            $dataType = $cmd->queryAll();

            foreach ($dataReader as $data) {
                if ($field['primary_key'] != NULL) {
                    array_push($local_key, $data[$field['primary_key']]);
                }
                if ($cnt == 0) {
                    $itemNames = array_keys($data);
                    $itemNames = array_map("addslashes", $itemNames);
                    $items = join('`,`', $itemNames);
                    $prefix = "INSERT INTO `$tableName` (`$items`) VALUES " . PHP_EOL;
                }
                $itemList = $this->add_slash_manual($data, $tableName, $dataType); //array_map ( array($this, 'add_slash_manual'), $itemValues );
                $valueString = "(" . $itemList . "),";
                if ($valueString != "") {
                    $data_string .= rtrim($valueString, ",") . "," . PHP_EOL;
                }
                if ($cnt == 100 || $totcnt == count($dataReader)) {
                    $data_string = rtrim($data_string, PHP_EOL);
                    $data_string = rtrim($data_string, ',') . ';' . PHP_EOL;
                    $data_string = $prefix . $data_string;
                    $data_string_long.=$data_string;
                    $data_string = '';
                    $cnt = 1;
                } else {
                    $cnt++;
                }
                $totcnt++;
            }

            if ($this->fp && $cnt >= 1) {
                $this->writeComment('TABLE DATA ' . $tableName);
                $string = $data_string_long;
                for ($written = 0; $written < strlen($string); $written += $fwrite) {
                    $fwrite = fwrite($this->fp, substr($string, $written));
                    if ($fwrite === false) {
                        return $written;
                    }
                }
                $this->writeComment('TABLE DATA ' . $tableName);
                $final = PHP_EOL . PHP_EOL;
                fwrite($this->fp, $final);
                if ($field['primary_key'] != NULL && !empty($local_key)) {
                    $localTableName = $tableName . '_local';
                    $this->getDataLocal($localTableName, $field['primary_key'], $local_key, $language_code);
                }
            }
        }
    }

    public function getDataLocal($tableName, $key, $keyvalue, $language_code) {
        if ($key == 'id' && $tableName == 'tbl_shift_local') {
            $key = 'shift_code';
        }
        $keyvalue = implode("','", $keyvalue);
        $keyvalue = "'" . $keyvalue . "'";
        $sql = 'SELECT * FROM ' . $tableName . ' where ' . $key . " in ($keyvalue) and language_code in ($language_code) ";
        $cmd = $this->export_db->createCommand($sql);
        $dataReader = $cmd->query();
        $prefix = '';
        $data_string = '';
        $data_string_long = '';
        $cnt = 0;
        $totcnt = 1;

        $db = Yii::$app->getDb();
        $dbName = $this->getDsnAttribute('dbname', $db->dsn);
        $sql = "SELECT DATA_TYPE,COLUMN_NAME  FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='{$dbName}' and table_name = '{$tableName}'";
        $cmd = $this->export_db->createCommand($sql);
        $dataType = $cmd->queryAll();


        foreach ($dataReader as $data) {
            if ($cnt == 0) {
                $itemNames = array_keys($data);
                $itemNames = array_map("addslashes", $itemNames);
                $items = join('`,`', $itemNames);
                $prefix = "INSERT INTO `$tableName` (`$items`) VALUES " . PHP_EOL;
            }
            $itemList = $this->add_slash_manual($data, $tableName, $dataType); //array_map ( array($this, 'add_slash_manual'), $itemValues );
            $valueString = "(" . $itemList . "),";
            if ($valueString != "") {
                $data_string .= rtrim($valueString, ",") . "," . PHP_EOL;
            }
            if ($cnt == 100 || $totcnt == count($dataReader)) {
                $data_string = rtrim($data_string, PHP_EOL);
                $data_string = rtrim($data_string, ',') . ';' . PHP_EOL;
                $data_string = $prefix . $data_string;
                $data_string_long.=$data_string;
                $data_string = '';
                $cnt = 1;
            } else {
                $cnt++;
            }

            $totcnt++;
        }

        if ($this->fp && $cnt >= 1) {
            $this->writeComment('TABLE DATA ' . $tableName);
            // $data_string = rtrim($data_string, PHP_EOL);
            //$data_string = rtrim($data_string, ',') . ';' . PHP_EOL;
            //$string = $prefix . $data_string;
            $string = $data_string_long;
            //  fwrite($this->fp, $string);
            for ($written = 0; $written < strlen($string); $written += $fwrite) {
                $fwrite = fwrite($this->fp, substr($string, $written));
                if ($fwrite === false) {
                    return $written;
                }
            }
            $this->writeComment('TABLE DATA ' . $tableName);
            $final = PHP_EOL . PHP_EOL;
            fwrite($this->fp, $final);
        }
    }

    public function writeComment($string) {
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        fwrite($this->fp, '-- ' . $string . PHP_EOL);
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
    }

    public function EndBackup($addcheck = true, $close_file = true) {
        $this->UpdateQuery();
        $this->writeSp();
        $this->writeTrigger();
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        foreach ($this->_truncate_table as $table) {
            fwrite($this->fp, "TRUNCATE TABLE {$table};" . PHP_EOL);
        }
        fwrite($this->fp, 'SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;' . PHP_EOL);
        fwrite($this->fp, 'SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;' . PHP_EOL);

        if ($addcheck) {
            fwrite($this->fp, 'COMMIT;' . PHP_EOL);
        }
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        $this->writeComment('END BACKUP');
        if ($close_file) {
            fclose($this->fp);
            $this->fp = null;
            if ($this->enableZip) {

                $this->createZipBackup();
            }
        }
    }

    public function EndBackupGeo($addcheck = true) {
        fwrite($this->fp, 'SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;' . PHP_EOL);
        fwrite($this->fp, 'SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;' . PHP_EOL);

        if ($addcheck) {
            fwrite($this->fp, 'COMMIT;' . PHP_EOL);
        }
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        $this->writeComment('END BACKUP');
        fclose($this->fp);
        $this->fp = null;
        if ($this->enableZip) {

            $this->createZipBackup();
        }
    }

    private function createZipBackup() {
        $zip = new \ZipArchive ();
        $file_name = $this->file_name . '.zip';
        if ($zip->open($file_name, \ZipArchive::CREATE) === TRUE) {
            $zip->addFile($this->file_name, basename($this->file_name));
            $zip->close();

            @unlink($this->file_name);
        }
    }

    public function createSqlFile($fileName) {
        set_time_limit(1800);
        $this->back_temp_file = $fileName;
        $tables = $this->getTables();
        if (!$this->StartBackup()) {

// render error
            Yii::$app->user->setFlash('success', "Error");
            return $this->render('index');
        }

        foreach ($tables as $tableName) {
            $this->getColumns($tableName);
        }
        foreach ($tables as $tableName) {
            if (!in_array($tableName, $this->_truncate_table))
                $this->getData($tableName);
        }

        $this->EndBackup();

        return $this->file_name;
    }

    //use for Android
//    public function createSqlFileDcs($fileName, $dcs_code, $language_code, $user_code, $organization_code, $language_locale, $sub_center_code) {
    public function createSqlFileDcs($fileName, $dcs_code, $bmc_code, $mcc_code, $plant_code, $org_code, $org_type, $union_code = '') {
        set_time_limit(5400);
        $this->android_db = new SQLite3('installation-identity/' . $fileName);
        $this->db_name = $org_code;
        $this->back_temp_file = $fileName;
        $this->export_db = Yii::$app->db;
//        die('test');
        $android_tables = $this->getTables();
//        $tables = $this->getMainTables();
//        $process = $this->getProcessDcs();
        $this->file_name = Yii::$app->basePath . '/installation-identity/error.txt';
        $this->fp = fopen($this->file_name, 'w+');
//        foreach ($tables as $tableName) {
        $this->getDataDcs($android_tables, $dcs_code, $bmc_code, $mcc_code, $plant_code, $org_code, $org_type, $union_code);
//        }
//        $this->AddAttachmentDB($dcs_code, $language_code);
    }

    public function AddAttachmentDB($dcs_code, $language_code, $addData = TRUE) {
        $this->db_name = $this->db_name . '_attachment';
        $this->export_db = Yii::$app->db_attachment;
        $tables = $this->getTables();
        $this->SetDatabase();
        $process = $this->getProcessAttachment();
        foreach ($tables as $tableName) {
            $this->getColumns($tableName);
        }
        if ($addData) {
            foreach ($process as $tableName) {
                $this->getDataDcs($tableName, $dcs_code, $language_code, '', '');
            }
        }

        $this->EndBackupGeo();
    }

    public function createSqlFileGeo($fileName, $dcs_code, $language_code, $user_code, $language_locale, $sub_center_code) {
        set_time_limit(5400);
        $this->android_db = new SQLite3('android/' . $fileName);
        $this->back_temp_file = $fileName;
        $this->export_db = Yii::$app->db;
        $tables = $this->getTables(null, true);
//        $process = $this->getProcessGeo();
//        foreach ($tables as $tableName) {
        $this->getGeoDataDcs($tables, $dcs_code, $language_code, $user_code, $language_locale, $sub_center_code);
//        }
//        foreach ($process as $tableName) {
//            $this->getDataDcs($tableName, $dcs_code, $language_code, $user_code, $language_locale);
//        }
    }

    public function getGeoDataDcs($tables, $dcs_code, $language_code, $user_code, $language_locale, $sub_center_code) {
        $tableName = 'tbl_villages';
        try {
            if (!empty($tables)) {
                if (in_array($tableName, $tables)) {
                    $insert_data = '';
                    $data = '';
                    $tables_fields = '';
                    $this->getSelectedTableData($tableName, 'village_code', 'tbl_dcs', 'dcs_code', $dcs_code, $tables_fields, $data);
                    $village_code = '';
                    $sub_district_code = '';
                    $district_code = '';
                    $state_code = '';
                    if (!empty($data)) {
                        $village = $data[0];
                        $village_code = $village['village_code'];
                        $this->saveData($tables, $village, $tables_fields, true, $tableName, 'village_code', $village_code);
                    }

                    if (!empty($village_code)) {
                        $tableName = 'tbl_sub_districts';
                        if (in_array($tableName, $tables)) {
                            $data = '';
                            $tables_fields = '';
                            $this->getSelectedTableData($tableName, 'sub_district_code', 'tbl_villages', 'village_code', $village_code, $tables_fields, $data);
                            if (!empty($data)) {
                                $sub_district = $data[0];
                                $sub_district_code = $sub_district['sub_district_code'];
                                $this->saveData($tables, $sub_district, $tables_fields, true, $tableName, 'sub_district_code', $sub_district_code);
                            }
                        }
                    }

                    if (!empty($sub_district_code)) {
                        $tableName = 'tbl_districts';
                        if (in_array($tableName, $tables)) {
                            $data = '';
                            $tables_fields = '';
                            $this->getSelectedTableData($tableName, 'district_code', 'tbl_sub_districts', 'sub_district_code', $sub_district_code, $tables_fields, $data);
                            if (!empty($data)) {
                                $district = $data[0];
                                $district_code = $district['district_code'];
                                $this->saveData($tables, $district, $tables_fields, true, $tableName, 'district_code', $district_code);
                            }
                        }
                    }

                    if (!empty($district_code)) {
                        $tableName = 'tbl_states';
                        if (in_array($tableName, $tables)) {
                            $data = '';
                            $tables_fields = '';
                            $this->getSelectedTableData($tableName, 'state_code', 'tbl_districts', 'district_code', $district_code, $tables_fields, $data);
                            if (!empty($data)) {
                                $state = $data[0];
                                $state_code = $state['state_code'];
                                $this->saveData($tables, $state, $tables_fields, true, $tableName, 'state_code', $district_code);
                            }
                        }
                    }

                    if (!empty($village_code)) {
                        $tableName = 'tbl_hamlets';
                        if (in_array($tableName, $tables)) {
                            $data = '';
                            $tables_fields = '';
                            $this->getSelectedTableData($tableName, 'village_code', 'tbl_villages', 'village_code', $village_code, $tables_fields, $data);
                            if (!empty($data)) {
                                foreach ($data as $hamlet) {
                                    $hamlet_code = $hamlet['hamlet_code'];
                                    $this->saveData($tables, $hamlet, $tables_fields, true, $tableName, 'hamlet_code', $hamlet_code);
                                }
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            fwrite($this->fp, $tableName . PHP_EOL);
            fwrite($this->fp, 'insert into ' . $tableName . ' (' . $tables_fields . ') VALUES (' . $insert_data . ');' . PHP_EOL);
            fwrite($this->fp, $e . PHP_EOL);
            fwrite($this->fp, '---------' . PHP_EOL);
            fwrite($this->fp, '---------' . PHP_EOL);
        }
    }

    public function getSelectedTableData($tableName, $tableKey, $depTable, $depTableKey, $depTableKeyValue, &$tables_fields, &$data) {
        try {
            $results = $this->android_db->query('PRAGMA table_info(' . $tableName . ')');
            while ($row = $results->fetchArray()) {
                $tables_fields[] = $row['name'];
            }
            $tables_fields = implode(',', $tables_fields);
            $sqls = 'SELECT ' . $tables_fields . '  FROM ' . $tableName . ' where ' . $tableKey . ' in (select ' . $tableKey . ' from ' . $depTable . ' where ' . $depTableKey . ' = "' . $depTableKeyValue . '")';
            $cmd = Yii::$app->db->createCommand($sqls);
            $data = $cmd->queryAll();
        } catch (\Exception $e) {
            fwrite($this->fp, $tableName . PHP_EOL);
            fwrite($this->fp, 'insert into ' . $tableName . ' (' . $tables_fields . ') VALUES (' . $insert_data . ');' . PHP_EOL);
            fwrite($this->fp, $e . PHP_EOL);
            fwrite($this->fp, '---------' . PHP_EOL);
            fwrite($this->fp, '---------' . PHP_EOL);
        }
    }

    public function saveData($tables = [], $data, $tables_fields, $save_local = false, $tableName, $local_key = '', $code = '') {
        try {
            $insert_data = '';
            $insert_data = implode('####', $data);
            $insert_data = '\'' . str_replace('\'', '"', $insert_data) . '\'';
            $insert_data = str_replace('####', '\',\'', $insert_data);
            $this->android_db->exec('insert into ' . $tableName . ' (' . $tables_fields . ') VALUES (' . $insert_data . ');');
            if ($save_local) {
                $tableName = $tableName . '_local';
                if (in_array($tableName, $tables)) {
                    $tables_fields = [];
                    $insert_data = '';
                    $results = $this->android_db->query('PRAGMA table_info("' . $tableName . '")');
                    while ($row = $results->fetchArray()) {
                        $tables_fields[] = $row['name'];
                    }
                    $tables_fields = implode(',', $tables_fields);
                    $sqls = 'SELECT ' . $tables_fields . '  FROM ' . $tableName . ' where ' . $local_key . ' in (' . $code . ')';
                    $cmd = Yii::$app->db->createCommand($sqls);
                    $localData = $cmd->queryAll();
                    if (!empty($localData)) {
                        $localData = $localData[0];
                        $this->saveData([], $localData, $tables_fields, false, $tableName);
                    }
                }
            }
        } catch (\Exception $e) {
            fwrite($this->fp, $tableName . PHP_EOL);
            fwrite($this->fp, 'insert into ' . $tableName . ' (' . $tables_fields . ') VALUES (' . $insert_data . ');' . PHP_EOL);
            fwrite($this->fp, $e . PHP_EOL);
            fwrite($this->fp, '---------' . PHP_EOL);
            fwrite($this->fp, '---------' . PHP_EOL);
        }
    }

    public function createSqlFileFedrationUnion($fileName, $statecode, $districtcode, $languagecode, $union_code, $federation_code, $subdistrictcode, $organization_code, $organization_type) {
        set_time_limit(5400);
        $this->db_name = $organization_type . '_' . $organization_code;
        $this->back_temp_file = $fileName;
        $this->export_db = Yii::$app->db;
        $tables = $this->getTables();
        $process = $this->getProcess();
        if (!$this->StartBackup()) {
            Yii::$app->user->setFlash('success', "Error");
            return $this->render('index');
        }

        foreach ($tables as $tableName) {
            $this->getColumns($tableName);
        }
        foreach ($process as $tableName) {
            $this->getDataFederationUnion($tableName, $statecode, $districtcode, $languagecode, $union_code, $federation_code, $subdistrictcode);
        }

        $this->EndBackup(TRUE, FALSE);
        $this->AddAttachmentDB('', '', FALSE);
        $this->db_name = $organization_type . '_' . $organization_code;

        return $this->file_name;
    }

    public function download($file = null) {
        if (isset($file)) {
            $sqlFile = $this->path . basename($file);
            if (file_exists($sqlFile)) {
                $request = \Yii::$app->response->sendFile(( $sqlFile));
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            throw new HttpException(404, Yii::t('app', 'File not found'));
        }
    }

    private function add_slash_manual($itemValue, $tableName, $dataReader) {
        $str = '';
        foreach ($itemValue as $key => $value) {


            if (is_null($value))
                $value = 'NULL';
            else {
//                $db = Yii::$app->getDb();
//                $dbName = $this->getDsnAttribute('dbname', $db->dsn);
//                $sql = "SELECT DATA_TYPE,TABLE_SCHEMA  FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='{$dbName}' and table_name = '{$tableName}' AND COLUMN_NAME = '{$key}'";
//                $cmd = $this->export_db->createCommand($sql);
//                $dataReader = $cmd->queryOne();
                $index = array_search($key, array_column($dataReader, 'COLUMN_NAME'));
                if ($dataReader[$index]['DATA_TYPE'] === 'bit') {
                    if (ord($value) === 1 || ord($value) === 0) {
                        $value = "b'" . addslashes(ord($value)) . "'";
                    } else {
                        $value = "b'" . addslashes(chr(ord($value))) . "'";
                    }
                } else
                    $value = "'" . addslashes($value) . "'";
            }
            $str.=$value . ",";
        }
        $str = rtrim($str, ',');
        return $str;
    }

    private function getDsnAttribute($name, $dsn) {
        if (preg_match('/' . $name . '=([^;]*)/', $dsn, $match)) {
            return $match[1];
        } else {
            return null;
        }
    }

    public function UpdateQuery() {
        $this->writeComment('TABLE UPDATE tbl_addressbook');
        $string = 'UPDATE tbl_addressbook SET organization_code="' . $this->_organisation_code . '";' . PHP_EOL;
        fwrite($this->fp, $string);
        $this->writeComment('TABLE UPDATE tbl_addressbook');
        $tables = ['tbl_profiles_default', 'tbl_profile_role_mapping_default', 'tbl_dcs_general_config_default', 'tbl_ledger_has_subledger_default', 'tbl_ledger_mapping_asset_group_default', 'tbl_ledger_mapping_event_default', 'tbl_ledger_mapping_product_group_default', 'tbl_milk_coll_quality_param_config_default', 'tbl_milk_collection_config_default', 'tbl_milk_disp_rece_config_default', 'tbl_printer_config_default', 'tbl_subledger_ledger_config_default', 'tbl_tax_detail_ledger_mapping_default', 'tbl_voucher_type_ledger_config_default', 'tbl_farmer_bill_head_default', 'tbl_ledger_mapping_farmer_bill_head_default'];
        if ($this->_organisation_type == 'UNION') {
            $this->writeComment('TABLE UPDATE DEFAULT');
            foreach ($tables as $table) {
                $string = 'UPDATE ' . $table . ' SET union_code="' . $this->_organisation_code . '";' . PHP_EOL;
                fwrite($this->fp, $string);
            }
            $this->writeComment('TABLE UPDATE DEFAULT');
        }
        if ($this->_organisation_type == 'DCS') {
            $this->writeComment('TABLE UPDATE tbl_branch');
            $string = 'UPDATE tbl_branch SET union_code="' . substr($this->_organisation_code, 0, 3) . '";' . PHP_EOL;
            fwrite($this->fp, $string);
            $this->writeComment('TABLE UPDATE tbl_branch');
        }
    }

    public function writeTrigger() {
        $sql = 'SHOW TRIGGERS';
        $cmd = $this->export_db->createCommand($sql)->queryAll();

        fwrite($this->fp, 'DELIMITER $$' . PHP_EOL);
        foreach ($cmd as $row) {
            $sql = 'SHOW CREATE TRIGGER ' . $row['Trigger'];
            $cmd = $this->export_db->createCommand($sql)->queryAll();
            fwrite($this->fp, 'DROP TRIGGER IF EXISTS `' . $row['Trigger'] . '`$$' . PHP_EOL);
            fwrite($this->fp, $cmd[0]['SQL Original Statement'] . '$$' . PHP_EOL);
            fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        }
        fwrite($this->fp, 'DELIMITER ;' . PHP_EOL);
        return true;
    }

}
