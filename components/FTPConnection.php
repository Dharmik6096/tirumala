<?php

namespace app\components;

use yii\base\Component;
use phpseclib3\Net\SFTP;
use Yii;

class FTPConnection extends Component {

    public $ftp_type;
    public $ftp_host;
    public $ftp_username;
    public $ftp_password;
    public $ftp_port;
    public $ftp_path;
    public $local_path;
    public $file_name;
    public $connection;
    public $conn_close = TRUE;
    public $make_dir = TRUE;
    public $conn_init = TRUE;
    public $ftp_pasv = false;
    public $isPassiveFtp = true;

    public function ConnectServer() {
        if ($this->ftp_type == 'SELF') {
            return TRUE;
        }
        return ($this->ftp_type == 'FTP') ? $this->FTP() : $this->SFTP();
    }

    public function CloseConnection() {
        if ($this->ftp_type == 'SELF') {
            return TRUE;
        }
        return ($this->ftp_type == 'FTP') ? $this->FTPClose() : $this->SFTPClose();
    }

    private function FTPClose() {
        ftp_close($this->connection);
    }

    private function SFTPClose() {
        unset($this->connection);
    }

    private function FTP() {
        try {
            if ($this->connection = ftp_connect($this->ftp_host, $this->ftp_port)) {
                if (ftp_login($this->connection, $this->ftp_username, $this->ftp_password)) {
                    ftp_pasv($this->connection, $this->isPassiveFtp);
                    return TRUE;
                }
                ftp_close($this->connection);
            }
            return FALSE;
        } catch (\Exception $e) {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => \Yii::t('app', 'Invalid FTP Credential.')]);
            return false;
        }
    }

    private function SFTP() {
        try {
            $this->connection = new SFTP($this->ftp_host, $this->ftp_port);
            if ($this->connection->login($this->ftp_username, $this->ftp_password)) {
                return TRUE;
            }
            return FALSE;
        } catch (\Exception $e) {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => \Yii::t('app', 'Invalid SFTP Credential.')]);
            return false;
        }
    }

    public function UploadFile() {
        $dir = ($this->make_dir) ? $this->CreateDirectory() : TRUE;
        if ($dir) {
            if ($this->ftp_type == 'SELF') {
                return $this->LocalUpload();
            }
            $conn = ($this->conn_init) ? $this->ConnectServer() : TRUE;
            if ($conn) {
                return ($this->ftp_type == 'FTP') ? $this->FTPUpload() : $this->SFTPUpload();
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    private function FTPUpload() {
        try {
            ftp_set_option($this->connection, FTP_USEPASVADDRESS, false);
            ftp_pasv($this->connection, $this->isPassiveFtp);
            ftp_chdir($this->connection, $this->ftp_path);
            if (ftp_put($this->connection, $this->file_name, $this->local_path . $this->file_name, FTP_BINARY)) {
                ($this->conn_close) ? ftp_close($this->connection) : '';
                return TRUE;
            }
            ($this->conn_close) ? ftp_close($this->connection) : '';
            return FALSE;
        } catch (\ErrorException $e) {
            ($this->conn_close) ? ftp_close($this->connection) : '';
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => \Yii::t('app', 'Error while file copy.')]);
            return false;
        }
    }

    private function SFTPUpload() {
        try {
            if ($this->connection->put($this->ftp_path . $this->file_name, $this->local_path . $this->file_name, SFTP::SOURCE_LOCAL_FILE)) {
                return TRUE;
            }
            return FALSE;
        } catch (\Exception $e) {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => \Yii::t('app', 'Error while file copy.')]);
            return false;
        }
    }

    private function LocalUpload() {
        try {
            if (copy($this->local_path . '/' . $this->file_name, $this->ftp_path . '/' . $this->file_name)) {
                return TRUE;
            }
            return FALSE;
        } catch (\ErrorException $e) {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => \Yii::t('app', 'Error while file copy.')]);
            return false;
        }
    }

    public function DownloadFile() {
        if ($this->ftp_type == 'SELF') {
            return $this->LocalDownload();
        }
        if ($this->ConnectServer()) {
            return ($this->ftp_type == 'FTP') ? $this->FTPDownload() : $this->SFTPDownload();
        } else {
            return FALSE;
        }
    }

    private function FTPDownload() {
        try {
            ftp_pasv($this->connection, $this->isPassiveFtp);
            ftp_chdir($this->connection, $this->ftp_path);
            if (ftp_get($this->connection, $this->local_path . $this->file_name, $this->file_name, FTP_BINARY)) {
                ($this->conn_close) ? ftp_close($this->connection) : '';
                return TRUE;
            }
            ($this->conn_close) ? ftp_close($this->connection) : '';
            return FALSE;
        } catch (\ErrorException $e) {
            ($this->conn_close) ? ftp_close($this->connection) : '';
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => \Yii::t('app', 'Error while file copy.')]);
            return false;
        }
    }

    private function SFTPDownload() {
        try {
            if ($this->connection->get($this->ftp_path . $this->file_name, $this->local_path . $this->file_name)) {
                return TRUE;
            }
            return FALSE;
        } catch (\Exception $e) {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => \Yii::t('app', 'Error while file copy.')]);
            return false;
        }
    }

    private function LocalDownload() {
        try {
            if (copy($this->ftp_path . '/' . $this->file_name, $this->local_path . '/' . $this->file_name)) {
                return TRUE;
            }
            return FALSE;
        } catch (\ErrorException $e) {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => \Yii::t('app', 'Error while file copy.')]);
            return false;
        }
    }

    public function ListFile() {
        if ($this->ftp_type == 'SELF') {
            return $this->LocalListFile();
        }
        if ($this->ConnectServer()) {
            return ($this->ftp_type == 'FTP') ? $this->FTPListFile() : $this->SFTPListFile();
        } else {
            return FALSE;
        }
    }

    private function FTPListFile() {
        try {
            ftp_pasv($this->connection, $this->isPassiveFtp);
//            ftp_chdir($this->connection, $this->ftp_path);
            $files = array();
            $list = ftp_nlist($this->connection, $this->ftp_path);
            if (is_array($list)) {
                foreach ($list as $filename) {
                    $files[] = str_replace($this->ftp_path, '', $filename);
                }
            }
            ($this->conn_close) ? ftp_close($this->connection) : '';
            return $files;
        } catch (\ErrorException $e) {
            ($this->conn_close) ? ftp_close($this->connection) : '';
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => \Yii::t('app', 'Error while file copy.')]);
            return false;
        }
    }

    private function SFTPListFile() {
        try {
            $files = array();
            $list = $this->connection->nlist($this->ftp_path);
            if (is_array($list)) {
                if (($key = array_search('.', $list)) !== false) {
                    unset($list[$key]);
                }
                if (($key = array_search('..', $list)) !== false) {
                    unset($list[$key]);
                }
                $files = array_values($list);
            }
            return $files;
        } catch (\Exception $e) {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => \Yii::t('app', 'Error while file copy.')]);
            return false;
        }
    }

    private function LocalListFile() {
        try {
            $files = array();
            $list = scandir($this->ftp_path);
            if (is_array($list)) {
                foreach ($list as $filename) {
                    $files[] = str_replace($this->ftp_path, '', $filename);
                }
            }
            return $files;
        } catch (\ErrorException $e) {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => \Yii::t('app', 'Error while file copy.')]);
            return false;
        }
    }

    public function CreateDirectory() {
        if ($this->ftp_type == 'SELF') {
            return $this->LocalCreateDirectory();
        }
        $conn = ($this->conn_init) ? $this->ConnectServer() : TRUE;
        if ($conn) {
            return ($this->ftp_type == 'FTP') ? $this->FTPCreateDirectory() : $this->SFTPCreateDirectory();
        } else {
            return FALSE;
        }
    }

    private function FTPCreateDirectory() {
        try {
            ftp_pasv($this->connection, $this->isPassiveFtp);
            $directory = explode('/', $this->ftp_path);
            $path = '/';
            foreach ($directory as $dir) {
                if ($dir != '') {
                    $path .= $dir . '/';
                    try {
                        if (!ftp_chdir($this->connection, $path)) {
                            ftp_mkdir($this->connection, $path);
                        }
                    } catch (\ErrorException $e) {
                        ftp_mkdir($this->connection, $path);
                    }
                }
            }
            ($this->conn_close) ? ftp_close($this->connection) : '';
            return true;
        } catch (\ErrorException $e) {
            ($this->conn_close) ? ftp_close($this->connection) : '';
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => \Yii::t('app', 'Error while Create Directory.')]);
            return false;
        }
    }

    private function SFTPCreateDirectory() {
        return TRUE;
    }

    private function LocalCreateDirectory() {
        try {
            Yii::$app->general->checkDirectory($this->ftp_path);
            return true;
        } catch (\ErrorException $e) {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => \Yii::t('app', 'Error while Create Directory.')]);
            return false;
        }
    }

    public function RenameFile($old_path, $new_path) {
        if ($this->ftp_type == 'SELF') {
            return $this->LocalRename($old_path, $new_path);
        }
        $conn = ($this->conn_init) ? $this->ConnectServer() : TRUE;
        if ($conn) {
            return ($this->ftp_type == 'FTP') ? $this->FTPRename($old_path, $new_path) : $this->SFTPRename($old_path, $new_path);
        } else {
            return FALSE;
        }
    }

    private function FTPRename($old_path, $new_path) {
        try {
            if (!$this->ConnectServer()) {
                return FALSE;
            }
            if (ftp_rename($this->connection, $old_path, $new_path)) {
                ($this->conn_close) ? ftp_close($this->connection) : '';
                return TRUE;
            }
            ($this->conn_close) ? ftp_close($this->connection) : '';
            return FALSE;
        } catch (\ErrorException $e) {
            ($this->conn_close) ? ftp_close($this->connection) : '';
            return false;
        }
    }

    private function SFTPRename($old_path, $new_path) {
        try {
            if ($this->connection->rename($old_path, $new_path)) {
                return TRUE;
            }
            return FALSE;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function LocalRename($old_path, $new_path) {
        try {
            if (rename($old_path, $new_path)) {
                return TRUE;
            }
            return FALSE;
        } catch (\ErrorException $e) {
            return false;
        }
    }

    public function DeleteFile() {
        if ($this->ftp_type == 'SELF') {
            return $this->LocalDeleteFile();
        }
        $conn = ($this->conn_init) ? $this->ConnectServer() : TRUE;
        if ($conn) {
            return ($this->ftp_type == 'FTP') ? $this->FTPDeleteFile() : $this->SFTPDeleteFile();
        } else {
            return FALSE;
        }
    }

    private function FTPDeleteFile() {
        try {
            ftp_pasv($this->connection, $this->isPassiveFtp);
            ftp_chdir($this->connection, $this->ftp_path);
            if (ftp_delete($this->connection, $this->file_name)) {
                ($this->conn_close) ? ftp_close($this->connection) : '';
                return TRUE;
            }
            ($this->conn_close) ? ftp_close($this->connection) : '';
            return FALSE;
        } catch (\ErrorException $e) {
            ($this->conn_close) ? ftp_close($this->connection) : '';
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => \Yii::t('app', 'Error while file copy.')]);
            return false;
        }
    }

    private function SFTPDeleteFile() {
        return TRUE;
    }

    private function LocalDeleteFile() {
        try {
            if (unlink($this->ftp_path . '/' . $this->file_name)) {
                return TRUE;
            }
            return FALSE;
        } catch (\ErrorException $e) {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => \Yii::t('app', 'Error while file copy.')]);
            return false;
        }
    }

    public function GetFileContents() {
        if ($this->ConnectServer()) {
            return ($this->ftp_type == 'FTP') ? $this->FTPFileContent() : $this->SFTPFileContent();
        } else {
            return FALSE;
        }
    }

    public function SFTPFileContent() {
        return true;
    }

    private function FTPFileContent() {
        try {
            ftp_pasv($this->connection, $this->isPassiveFtp);
            $contents = fopen('ftp://' . $this->ftp_username . ':' . $this->ftp_password . '@' . $this->ftp_host . '/' . $this->ftp_path . '/' . $this->file_name, 'r');
            $array = [];
            while (!feof($contents)) {
                $array[] = fgets($contents);
            }
            ($this->conn_close) ? ftp_close($this->connection) : '';
            return $array;
        } catch (\ErrorException $e) {
            ($this->conn_close) ? ftp_close($this->connection) : '';
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => \Yii::t('app', 'Error while Get Contents from FTP.')]);
            return false;
        }
    }

}

?>