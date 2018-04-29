<?php

namespace app\components;

use yii;
use yii\base\Component;
use yii\data\ArrayDataProvider;
use yii\web\HttpException;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class SqlCreate extends Component {

    public $menu = [];
    public $tables = [];
    public $fp;
    public $file_name;
    public $enableZip = false;
    public $_path = null;
    public $back_temp_file = 'db_backup_';
    private $_truncate_table = ['auth_assignment', 'tbl_addressbook', 'identity_master', 'installation_identity', 'tbl_user_organization_mapping', 'tbl_sentbox', 'tbl_sync_history', 'tbl_inbox'];

    protected function getPath() {
//		if (isset ( $this->module->path ))
//			$this->_path = $this->module->path;
//		else
        $this->_path = Yii::$app->basePath . '/installation-identity/';

        if (!file_exists($this->_path)) {
            mkdir($this->_path);
            chmod($this->_path, '777');
        }
        return $this->_path;
    }

    public function getTables($dbName = null) {
        $sql = 'SHOW TABLES';
        $cmd = Yii::$app->db->createCommand($sql);
        $tables = $cmd->queryColumn();
        return $tables;
    }

    public function writeSp() {
        $sql = 'SHOW PROCEDURE STATUS WHERE Db = DATABASE() AND Type = "PROCEDURE"';
        $cmd = Yii::$app->db->createCommand($sql)->queryAll();

        fwrite($this->fp, 'DELIMITER $$' . PHP_EOL);
        foreach ($cmd as $row) {
            $sql = 'SHOW CREATE PROCEDURE ' . $row['Name'];
            $cmd = Yii::$app->db->createCommand($sql)->queryAll();
            fwrite($this->fp, 'DROP PROCEDURE IF EXISTS `' . $row['Name'] . '`$$' . PHP_EOL);
            fwrite($this->fp, $cmd[0]['Create Procedure'] . '$$' . PHP_EOL);
            fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        }
        fwrite($this->fp, 'DELIMITER ;' . PHP_EOL);
        return true;
    }

    public function StartBackup($addcheck = true) {
        $this->file_name = $this->path . $this->back_temp_file;
//		$this->file_name = $this->path . $this->back_temp_file .'_'. date ( 'Y.m.d_H.i.s' ) . '.sql';
        $this->fp = fopen($this->file_name, 'w+');

        if ($this->fp == null)
            return false;
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        if ($addcheck) {
            fwrite($this->fp, 'SET AUTOCOMMIT=0;' . PHP_EOL);
            fwrite($this->fp, 'START TRANSACTION;' . PHP_EOL);
            fwrite($this->fp, 'SET SQL_QUOTE_SHOW_CREATE = 1;' . PHP_EOL);
        }
        fwrite($this->fp, 'SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;' . PHP_EOL);
        fwrite($this->fp, 'SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;' . PHP_EOL);
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        $this->writeComment('START BACKUP');
        return true;
    }

    public function getColumns($tableName) {
        $sql = 'SHOW CREATE TABLE ' . $tableName;
        $cmd = Yii::$app->db->createCommand($sql);
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
        $cmd = Yii::$app->db->createCommand($sql);
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

    public function writeComment($string) {
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
        fwrite($this->fp, '-- ' . $string . PHP_EOL);
        fwrite($this->fp, '-- -------------------------------------------' . PHP_EOL);
    }

    public function EndBackup($addcheck = true) {

        $this->writeSp();
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

    public function download($file = null) {
        if (isset($file)) {
            $sqlFile = $this->path . basename($file);
            if (file_exists($sqlFile)) {
                $request = \Yii::$app->response->sendFile(( $sqlFile));
//				$request = Yii::$app->getRequest();
//				$request->sendFile ( basename ( $sqlFile ), file_get_contents ( $sqlFile ) );
            }
        } else {
            throw new HttpException(404, Yii::t('app', 'File not found'));
        }
    }

    private function add_slash_manual($itemValue, $tableName) {
        $str = '';
        foreach ($itemValue as $key => $value) {


            if (is_null($value))
                $value = 'NULL';
            else {
                $db = Yii::$app->getDb();
                $dbName = $this->getDsnAttribute('dbname', $db->dsn);
                $sql = "SELECT DATA_TYPE,TABLE_SCHEMA  FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='{$dbName}' and table_name = '{$tableName}' AND COLUMN_NAME = '{$key}'";
                $cmd = Yii::$app->db->createCommand($sql);
                $dataReader = $cmd->queryOne();
                if ($dataReader['DATA_TYPE'] === 'bit') {
                    if (ord($value) === 1 || ord($value) === 0) {
                        $value = "b'" . addslashes(ord($value)) . "'";
                    } else {
                        $value = "b'" . addslashes(chr(ord($value))) . "'";
                    }
                }
                else
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

}
