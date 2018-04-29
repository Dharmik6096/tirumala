<?php

namespace app\components;

use yii\base\Component;
use phpseclib\Net\SFTP;
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

    public function ConnectServer() {
        return ($this->ftp_type == 'FTP') ? $this->FTP() : $this->SFTP();
    }

    private function FTP() {
        try {
            if ($this->connection = ftp_connect($this->ftp_host, $this->ftp_port)) {
                if (ftp_login($this->connection, $this->ftp_username, $this->ftp_password)) {
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
        if ($this->ConnectServer()) {
            return ($this->ftp_type == 'FTP') ? $this->FTPUpload() : $this->SFTPUpload();
        } else {
            return FALSE;
        }
    }

    private function FTPUpload() {
        try {
            ftp_pasv($this->connection, true);
            ftp_chdir($this->connection, $this->ftp_path);
            if (ftp_put($this->connection, $this->file_name, $this->local_path . $this->file_name, FTP_BINARY)) {
                ftp_close($this->connection);
                return TRUE;
            }
            ftp_close($this->connection);
            return FALSE;
        } catch (\ErrorException $e) {
            ftp_close($this->connection);
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

    public function DownloadFile() {
        if ($this->ConnectServer()) {
            return ($this->ftp_type == 'FTP') ? $this->FTPDownload() : $this->SFTPDownload();
        } else {
            return FALSE;
        }
    }

    private function FTPDownload() {
        try {
            ftp_pasv($this->connection, true);
            ftp_chdir($this->connection, $this->ftp_path);
            if (ftp_get($this->connection, $this->local_path . $this->file_name, $this->file_name, FTP_BINARY)) {
                ftp_close($this->connection);
                return TRUE;
            }
            ftp_close($this->connection);
            return FALSE;
        } catch (\ErrorException $e) {
            ftp_close($this->connection);
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

    public function ListFile() {
        if ($this->ConnectServer()) {
            return ($this->ftp_type == 'FTP') ? $this->FTPListFile() : $this->SFTPListFile();
        } else {
            return FALSE;
        }
    }

    private function FTPListFile() {
        try {
            ftp_pasv($this->connection, true);
            ftp_chdir($this->connection, $this->ftp_path);
            $files = array();
            $list = ftp_nlist($this->connection, $this->ftp_path);
            if (is_array($list)) {
                foreach ($list as $filename) {
                    $files[] = str_replace($this->ftp_path, '', $filename);
                }
            }
            ftp_close($this->connection);
            return $files;
        } catch (\ErrorException $e) {
            ftp_close($this->connection);
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

}

?> 