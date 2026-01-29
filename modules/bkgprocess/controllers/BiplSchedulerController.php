<?php

namespace app\modules\bkgprocess\controllers;

use yii\web\Controller;
use yii;
use app\modules\sap\models\SapModel;
use yii\data\ArrayDataProvider;
use PHPExcel;
use app\modules\bkgprocess\models\TblFtpTxnLog;
use app\components\FTPConnection;
use app\components\WebApi;
use app\modules\organisation\models\TblDcs;
use yii\filters\VerbFilter;
use app\controllers\ChildController;
use yii\db\Exception;
use app\modules\bkgprocess\models\BiplFtpCollection;
use app\modules\bkgprocess\models\BiplFtpDispatch;
use app\modules\bkgprocess\models\BiplFtpTankerDispatch;
use app\modules\bkgprocess\models\TblOrgFileCreator;
use app\modules\bkgprocess\models\TblOrgFileLog;
use app\modules\dcsoperation\models\TblMember;

class BiplSchedulerController extends ChildController {

    public $freeAccessActions = ['generate-master-data', 'download-files', 'process-bipl-files', 'upload-collection-files', 'upload-master-files', 'upload-error-files', 'create-ftp-folder', 'process-collection-data', 'upload-error-files-master', 'bipl-dcs-api-master', 'bipl-member-api-master'];
    public $errorPath = '';
    public $token = '';

    public function init() {
        parent::init();
        $this->errorPath = Yii::$app->params['biplDirPath'] . 'ErrorLogs/FTP';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [
            'ghost-access' => [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ],
        ];
    }

    public function actionGenerateMasterData() {
        $model = new TblOrgFileCreator();
        $model->file_status = 0;
        $model->status = 0;
        $modelData = $model->getPendingData();
        if (!empty($modelData)) {
            $ids = array_map(function($e) {
                return $e->org_file_creator_id;
            }, $modelData);
            $model->updateFileStatus($ids);
            foreach ($modelData as $data) {
                $org_model = new TblOrgFileLog();
                $org_model->module_code = $data->module_code;
                $org_model->generateBiplFiles($data->module_code, $data->file_type, $data->value1);
                $data->status = 2;
                $data->file_status = 1;
                $data->save(FALSE);
            }
        }
    }

    public function actionDownloadFiles() {
        $dcs_model = new TblDcs();
        $data = $dcs_model->getFtpCredentials();
        $process_count = 0;
        $cnt = 0;
        $connection = FALSE;
        if (!empty($data)) {
            foreach ($data as $records) {
                try {
                    if ($cnt == 0 || ($records['ftp_host'] != $data[$cnt - 1]['ftp_host'] || $records['ftp_username'] != $data[$cnt - 1]['ftp_username'] || $records['ftp_password'] != $data[$cnt - 1]['ftp_password'])) {
                        if ($connection) {
                            $ftp->CloseConnection();
                        }
                        $ftp = new FTPConnection();
                        $ftp->ftp_type = $records['ftp_type'];
                        $ftp->ftp_host = $records['ftp_host'];
                        $ftp->ftp_username = $records['ftp_username'];
                        $ftp->ftp_password = $records['ftp_password'];
                        $ftp->ftp_port = $records['ftp_port'];
                        $ftp->conn_init = FALSE;
                        $ftp->conn_close = FALSE;
                        $ftp->make_dir = FALSE;
                        $ftp->isPassiveFtp = !empty($records['ftp_mode']) && $records['ftp_mode'] == 'active' ? false : true;
                        $connection = $ftp->ConnectServer();
                    }
                    $cnt++;
                    if ($connection) {
                        foreach ($this->FTPSubFolder() as $folder => $detail_array) {
                            $folder_path = $records['ftp_path'] . '/' . $records['CP_Code'] . '/' . $folder . '/';
                            $folder_path = str_replace('//', '/', $folder_path);
                            $ftp->ftp_path = $folder_path;
                            $list = $ftp->ListFile();
                            if (!empty($list)) {
                                foreach ($detail_array as $detail) {
                                    foreach ($list as $file) {
                                        if (strtolower(substr($file, -4)) == strtolower($detail['ext'])) {
                                            $ftp->file_name = $file;
                                            $ftp_txn_model = new TblFtpTxnLog();
                                            $ftp_txn_model->file_name = $file;
                                            $ftp_txn_model->txn_type = 'BIPL';
                                            $ftp_txn_model->file_path = $folder_path . $file;
                                            $ftp_txn_model->file_status = 1;
                                            $ftp_txn_data = $ftp_txn_model->getExistFileData();
                                            if (empty($ftp_txn_data) && strlen($file) == 12) {
//                                                $local_path = \Yii::$app->params['biplDirPath'] . $records['CP_Code'] . '/' . $folder . '/';
                                                $local_path = \Yii::getAlias('@webroot') . '/' . \Yii::$app->params['biplDirPath'] . $records['CP_Code'] . '/' . $folder . '/';
                                                $local_path = str_replace('//', '/', $local_path);
                                                if (Yii::$app->general->checkDirectory($local_path)) {
                                                    $ftp->local_path = $local_path;
                                                    if ($ftp->DownloadFile()) {
                                                        $ftp_txn_model = new TblFtpTxnLog();
                                                        $ftp_txn_model->txn_type = 'BIPL';
                                                        $ftp_txn_model->local_path = $local_path . $file;
                                                        $ftp_txn_model->file_path = $folder_path . $file;
                                                        $ftp_txn_model->total_count = 0;
                                                        $ftp_txn_model->success_count = 0;
                                                        $ftp_txn_model->error_count = 0;
                                                        $ftp_txn_model->module_name = $detail['module_name'];
                                                        $ftp_txn_model->module_code = $records['module_code'];
                                                        $ftp_txn_model->ref_code = $records['CP_Code'];
                                                        $ftp_txn_model->file_name = $file;
                                                        $ftp_txn_model->ftp_type = $records['ftp_type'];
                                                        $ftp_txn_model->ftp_host = $records['ftp_host'];
                                                        $ftp_txn_model->ftp_username = $records['ftp_username'];
                                                        $ftp_txn_model->ftp_password = $records['ftp_password'];
                                                        $ftp_txn_model->ftp_port = $records['ftp_port'];
                                                        $ftp_txn_model->ftp_path = $records['ftp_path'];
                                                        $ftp_txn_model->ftp_mode = $records['ftp_mode'];
                                                        $ftp_txn_model->file_status = 1;
                                                        $ftp_txn_model->status = 0;
                                                        $ftp_txn_model->save();
                                                        /* if (strstr($path, 'EKOMILK')) {
                                                          $command = 'chmod 777 -R ' . $local_path . $file;
                                                          exec($command);
                                                          } */
                                                        $process_count++;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } catch (\Throwable $ex) {
                    var_dump($ex->getMessage());
                }
            }
        }
        Yii::$app->getSession()->setFlash('success', ['type' => 'success',
            'message' => $process_count . ' files downloaded successfully']);
    }

    public function actionProcessBiplFiles() {
        $model = new TblFtpTxnLog();
        $model->file_status = 1;
        $model->txn_type = 'BIPL';
        $model->status = 0;
        $modelData = $model->getPickRecords([], 30);
        if (!empty($modelData)) {
            $ids = array_map(function($e) {
                return $e->ftp_txn_log_id;
            }, $modelData);
            $update = $model->updateFileStatus($ids); // remove comment
            $this->process_files($modelData);
        }
    }

    public function actionUploadCollectionFiles($force_upload = FALSE) {
        $log_id = [];
        if ($force_upload) {
            $log_id = \Yii::$app->request->post()['selection'];
        } else {
            $this->actionUploadMasterFiles();
        }
        $model = new TblFtpTxnLog();
        $model->file_status = 0;
        $model->txn_type = 'EIPL';
        $model->status = 0;
        $modelData = $model->getPickRecords($log_id);
        if (!empty($modelData)) {
            $ids = array_map(function($e) {
                return $e->ftp_txn_log_id;
            }, $modelData);
            $update = $model->updateFileStatus($ids);
            $this->upload_files($modelData);
        }
    }

    public function actionUploadMasterFiles() {
        $model = new TblOrgFileLog();
        $model->file_status = 0;
        $model->status = 0;
        $modelData = $model->getPendingData();
        if (!empty($modelData)) {
            $ids = array_map(function($e) {
                return $e->org_file_log_id;
            }, $modelData);
            $model->updateFileStatus($ids);
            $this->upload_files($modelData);
        }
    }

    public function actionUploadErrorFiles() {
        $model = new TblFtpTxnLog();
        $model->file_status = 0;
        $model->txn_type = 'EIPL';
        $model->status = 3;
        $modelData = $model->getPickRecords();
        if (!empty($modelData)) {
            $ids = array_map(function($e) {
                return $e->ftp_txn_log_id;
            }, $modelData);
            $update = $model->updateFileStatus($ids);
            $this->upload_files($modelData, TRUE);
        }

        $model = new TblOrgFileLog();
        $model->file_status = 0;
        $model->status = 3;
        $modelData = $model->getPendingData();
        if (!empty($modelData)) {
            $ids = array_map(function($e) {
                return $e->org_file_log_id;
            }, $modelData);
            $model->updateFileStatus($ids);
            $this->upload_files($modelData, TRUE);
        }
    }

    public function actionProcessCollectionData() {
        $sp_name = 'DB_JOB_BIPL_Milk_Collection';
        \Yii::$app->general->getSpData($sp_name, [], TRUE);
    }

    private function upload_files($data, $create_dir = FALSE) {
        $success = 0;
        $error = 0;
        $cnt = 0;
        $connection = FALSE;
        foreach ($data as $row) {
            try {
                if ($cnt == 0 || ($row->ftp_host != $data[$cnt - 1]->ftp_host || $row->ftp_username != $data[$cnt - 1]->ftp_username || $row->ftp_password != $data[$cnt - 1]->ftp_password)) {
                    if ($connection) {
                        $ftp->CloseConnection();
                    }
                    $ftp = new FTPConnection();
                    $ftp->ftp_type = $row->ftp_type;
                    $ftp->ftp_host = $row->ftp_host;
                    $ftp->ftp_username = $row->ftp_username;
                    $ftp->ftp_password = $row->ftp_password;
                    $ftp->ftp_port = $row->ftp_port;
                    $ftp->conn_init = FALSE;
                    $ftp->conn_close = FALSE;
                    $ftp->make_dir = FALSE;
                    $ftp->isPassiveFtp = !empty($row->ftp_mode) && $row->ftp_mode == 'active' ? false : true;
                    $connection = $ftp->ConnectServer();
                }
                $cnt++;
                if ($connection) {
                    if ($create_dir) {
                        $ftpDir = \Yii::$app->general->getFTPDirStructure($row->ref_code);
                        foreach ($ftpDir as $dir) {
                            $ftp->ftp_path = 'EKOMILK' . $dir;
                            $localDirPath = \Yii::$app->params['biplDirPath'] . $dir;
                            $localDirPath = Yii::$app->basePath . '/' . str_replace(Yii::$app->basePath, '', $localDirPath);
                            $localDirPath = str_replace('\\', '/', $localDirPath);
                            $ftp->CreateDirectory();
                            Yii::$app->general->checkDirectory($localDirPath);
                        }
                    }
                    $ftp_path = explode('/', $row->file_path);
                    unset($ftp_path[count($ftp_path) - 1]);
                    $ftp_path = implode('/', $ftp_path);
                    $local_path = explode('/', $row->local_path);
                    unset($local_path[count($local_path) - 1]);
                    $local_path = implode('/', $local_path);
                    $file_name = $row->file_name;
                    $ftp->ftp_path = $ftp_path . '/';
                    $ftp->local_path = $local_path . '/';
                    $ftp->file_name = $file_name;
                    $row->status = ($row->status == 3) ? 4 : 3;
                    $row->file_status = 0;
                    if ($ftp->UploadFile()) {
                        $row->status = 2;
                        $row->file_status = 1;
                        if (!empty($row->old_file_path) && !empty($row->old_local_path)) {
                            $ftp_path = explode('/', $row->old_file_path);
                            unset($ftp_path[count($ftp_path) - 1]);
                            $ftp_path = implode('/', $ftp_path);
                            $ftp->ftp_path = $ftp_path . '/';
                            if ($ftp->DeleteFile()) {
                                $d_log = new TblFtpTxnLog();
                                $d_log->file_name = $row->file_name;
                                $d_log->txn_type = 'BIPL';
                                $d_log->file_path = $row->old_file_path;
                                $d_log->file_status = 1;
                                $d_log = $d_log->getExistFileData();
                                if (!empty($d_log)) {
                                    $d_log->file_status = $d_log->status;
                                    $d_log->save(FALSE);
                                }
                            }
                        }
                    }
                    if ($row->save(FALSE)) {
                        $success ++;
                    } else {
                        $error ++;
                    }
                }
            } catch (\yii\db\Exception $e) {
                $row->status = ($row->status == 3) ? 4 : 3;
                $row->file_status = 0;
                $row->save(FALSE);
                $error ++;
            }
        }
        Yii::$app->getSession()->setFlash('success', ['type' => 'success',
            'message' => $success . ' Files Uploaded.<br/>' . $error . ' Files Not Uploaded']);
    }

    private function process_files($modelData) {
        foreach ($modelData as $row) {
            try {
                $upload_log = new TblFtpTxnLog();
                $upload_log->attributes = $row->attributes;
                $datafile = $row->local_path;
                $local_path = explode('/', $datafile);
                $upload_file_name = $row->file_name;
                unset($local_path[count($local_path) - 1]);
                $local_path = implode('/', $local_path);
                $ftp_path = explode('/', $row->file_path);
                unset($ftp_path[count($ftp_path) - 1]);
                $ftp_path = implode('/', $ftp_path);
                $is_portal = (strpos($local_path, '/PORTALPDFILES') !== false);
                if ((strlen($upload_file_name) == 12 || $is_portal) && file_exists($datafile)) {
                    $is_bdf = (strtoupper(substr($upload_file_name, -4)) == '.BDF') ? TRUE : FALSE;
                    if ($is_bdf) {
                        $convertFileName = explode('.', $upload_file_name);
                        unset($convertFileName[count($convertFileName) - 1]);
                        $convertFileName = implode('.', $convertFileName);
                        $convertFileName = $convertFileName . '.csv';
                        $crnt_dir = getcwd();
                        $utility_path = \Yii::getAlias('@webroot') . '/' . Yii::$app->params['biplCollectionUtilityPath'];
                        chdir($utility_path);
//                        $cmd = 'Bennybdf.exe -i ' . $datafile . ' -o ' . $local_path . '/' . $convertFileName . ' -s , -f';
                        $cmd = './bdf2tpcsv_x86_64-linux_B -i ' . $datafile . ' -o ' . $local_path . '/' . $convertFileName . ' -s "," -f';
                        exec($cmd, $out, $retval);
                        chdir($crnt_dir);
                        $datafile = $local_path . '/' . $convertFileName; //        convert_bipl_file_path;
                    }
                    $data = $this->prepareDataFromFile($datafile, $row);
                    $transaction = $this->generalModel->saveTransaction($data[0], ['ftp collection', 'create']);
                    $ftp_save_path = '';
                    $status = 1;
                    $success_count = 0;
                    $error_count = 0;
                    if ($transaction == 'customRedirect' && empty($data[1])) {
                        $ftp_save_path = 'ARCHIVES/';
                        $status = 2;
                        $success_count = count($data[0]);
                    } else {
                        $ftp_save_path = 'ARCHIVES/ERRORS/';
                        $status = 3;
                        $error_count = count($data[0]);
                    }
                    $row->status = $status;
                    if (Yii::$app->general->checkDirectory($local_path . '/' . $ftp_save_path)) {
                        $upload = copy($local_path . '/' . $upload_file_name, $local_path . '/' . $ftp_save_path . $upload_file_name);
                        if ($upload) {
                            if (file_exists($local_path . '/' . $upload_file_name)) {
                                unlink($local_path . '/' . $upload_file_name);
                            }
                        }
                        if ($is_bdf) {
                            $upload = copy($local_path . '/' . $convertFileName, $local_path . '/' . $ftp_save_path . $convertFileName);
                            if ($upload) {
                                if (file_exists($local_path . '/' . $convertFileName)) {
                                    unlink($local_path . '/' . $convertFileName);
                                }
                            }
                        }
                    }
                    $row->success_count = $success_count;
                    $row->error_count = $error_count;
                    $row->total_count = $success_count + $error_count;

                    $upload_log->txn_type = 'EIPL';
                    $upload_log->old_file_path = $upload_log->file_path;
                    $upload_log->old_local_path = $upload_log->local_path;
                    $upload_log->file_path = $ftp_path . '/' . $ftp_save_path . $upload_file_name;
                    $upload_log->local_path = $local_path . '/' . $ftp_save_path . $upload_file_name;
                    $upload_log->file_status = 0;
                    $upload_log->status = 0;
                    if ($row->save(FALSE)) {
                        $is_portal ? '' : $upload_log->save(FALSE);
                    }
                    if (!empty($data[1])) {
                        $log_dir = Yii::$app->general->checkDirectory($this->errorPath);
                        if ($log_dir) {
                            $text = json_encode($data[1]);
                            $file_name = explode('.', $upload_file_name);
                            $file_name = $file_name[0];
                            Yii::$app->general->createLogFile($this->errorPath, $text, $file_name, $row->module_code);
                        }
                    }
                } else {
                    $row->status = 3;
                    $row->file_status = 3;
                    $row->save(FALSE);
                }
            } catch (\Throwable $ex) {
                $row->status = 3;
                $row->save(FALSE);
                var_dump($ex->getMessage());
            }
        }
    }

    protected function prepareDataFromFile($datafile, $row) {
        if (!empty($datafile)) {
            $module_code = $row->module_code;
            $cp_code = $row->ref_code;
            fopen($datafile, "r");
            $fileData = file($datafile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $count = count($fileData);
            $collection = [];
            $invalid = [];
            if ($count != 0) {
                $fields = explode(",", str_replace(' ', '_', str_replace(', ', ',', strtolower($fileData[0]))));
                $fields[0] = 'id';
                $i = 1;
                foreach ($fileData as $key => $fd) {
                    if ($key != 0 && strpos($fd, 'Total Number of Records') === false) {
                        $h_cnt = count($fields);
                        $l_data = explode(",", $fd);
                        $l_cnt = count($l_data);
                        if ($row->module_name == 'TblTankerDispatch') {
                            $h_cnt += 1;
                            unset($l_data[$l_cnt - 1]);
                        }
                        if ($h_cnt == $l_cnt) {
                            $data = array_combine($fields, $l_data);
                            if ($row->module_name == 'TblMilkCollection') {
//                                if (isset($data['person_position'])) {
                                if (true) {
//                                    if ($data['person_position'] == 'VENDOR') {
                                    if (true) {
                                        $collectionModel = new BiplFtpCollection();
                                        $data['dop_milksamplenum'] = $i;
                                        if (strpos($datafile, '/PORTALPDFILES/') === false) {
                                            $data['process_type'] = (strpos($datafile, '/MDATFILE/') === false) ? 'Benny-online' : 'Benny-PD';
                                        } else {
                                            $data['process_type'] = 'Benny-PD-P';
                                        }
                                        $i++;
                                    } else {
                                        $collectionModel = new BiplFtpDispatch();
                                    }
                                    $collectionModel->scenario = 'checkDate';
                                    $collectionModel->attributes = $data;
                                    if ($collectionModel->hasAttribute('ftp_txn_log_id')) {
                                        $collectionModel->ftp_txn_log_id = !empty($row->ftp_txn_log_id) ? $row->ftp_txn_log_id : NULL;
                                    }
                                    $collectionModel->amount = ($collectionModel->amount == 'NA') ? 0 : floatval($collectionModel->amount);
                                    $collectionModel->rate = ($collectionModel->rate == 'NA') ? 0 : floatval($collectionModel->rate);
                                    $collectionModel->fat = ($collectionModel->fat == 'NA') ? 0 : floatval($collectionModel->fat);
                                    $collectionModel->snf = ($collectionModel->snf == 'NA') ? 0 : floatval($collectionModel->snf);
                                    $collectionModel->quantity = ($collectionModel->quantity == 'NA') ? 0 : floatval($collectionModel->quantity);
                                    $collectionModel->awm = ($collectionModel->awm == 'NA') ? 0 : floatval($collectionModel->awm);
                                    if (empty($collectionModel->cp_code)) {
                                        $collectionModel->cp_code = $cp_code;
                                    }
                                    if ($collectionModel->process_type == 'Benny-PD-P') {
                                        $cp_code = $collectionModel->cp_code;
                                        $dcs = $collectionModel->dcsCode;
                                        $module_code = !empty($dcs) ? $dcs->dcs_code : NULL;
                                        $row->module_code = $module_code;
                                        $row->ref_code = $cp_code;
                                        $row->file_status = 2;
                                    }
                                    $collectionModel->dcs_code = $module_code;
                                    if ($collectionModel->validate()) {
                                        $collectionModel->date = Yii::$app->formatter->asDate($collectionModel->date, DATE_FORMAT);
                                        $collectionModel->scenario = 'default';
                                        $collectionModel->local_code = str_pad((int) $collectionModel->local_code, 4, '0', STR_PAD_LEFT);
                                        array_push($collection, $collectionModel);
                                    } else {
                                        $invalid[$key] = $collectionModel->getErrors();
                                    }
                                } else {
                                    $invalid[$key] = Yii::t('app', 'Wrong file format.');
                                }
                            } else if ($row->module_name == 'TblTankerDispatch') {
                                $collectionModel = new BiplFtpTankerDispatch();
                                $collectionModel->attributes = $data;
                                $collectionModel->scenario = 'checkDate';
                                $collectionModel->vehicle_no = $data['vehicle_no.'];
                                if (empty($collectionModel->cp_code))
                                    $collectionModel->cp_code = $cp_code;
                                if ($collectionModel->validate()) {
                                    $collectionModel->start_date = Yii::$app->formatter->asDate(str_replace("/", "-", $collectionModel->start_date), DATE_FORMAT);
                                    $collectionModel->end_date = Yii::$app->formatter->asDate(str_replace("/", "-", $collectionModel->end_date), DATE_FORMAT);
                                    $collectionModel->scenario = 'default';
//                                    $collectionModel->cp_code = (int) $collectionModel->cp_code;
                                    array_push($collection, $collectionModel);
                                } else {
                                    $invalid[$key] = $collectionModel->getErrors();
                                }
                            }
                        } else {
                            $invalid[$key] = 'Number of record values does not match number of fileds.';
                        }
                    }
                }
            }
            return [$collection, $invalid];
        }
        return false;
    }

    public function actionCreateFtpFolder() {
        $ftp = new FTPConnection();
        $ftp->ftp_type = '';
        $ftp->ftp_host = '';
        $ftp->ftp_username = '';
        $ftp->ftp_password = '';
        $ftp->ftp_port = '';
        $ftp->conn_init = FALSE;
        $ftp->conn_close = FALSE;
        $ftp->make_dir = FALSE;
        $connection = $ftp->ConnectServer();
        $module_code = ['777777777'];
        foreach ($module_code as $base_folder) {
            $sapDir = \Yii::$app->general->getSapDirStructure($base_folder);
            foreach ($sapDir as $dir) {
                $ftp->ftp_path = $dir;
                $ftp->CreateDirectory();
                Yii::$app->general->checkDirectory(\Yii::$app->params['sapDirPath'] . $dir);
            }
        }
    }

    public function actionUploadErrorFilesMaster() {
        $model = new TblOrgFileLog();
        $model->file_status = 0;
        $model->status = 3;
        $modelData = $model->getPendingData();
        if (!empty($modelData)) {
            $ids = array_map(function($e) {
                return $e->org_file_log_id;
            }, $modelData);
            $model->updateFileStatus($ids);
            $this->upload_files($modelData, TRUE);
        }
    }

    private function processMasterApi($modelClass, $endpointKey, $dataKey, $idKey, $extraIdKey = null) {
        $localModel = null;
        $current_ids = [];
        $current_extra_ids = null;
        try {
            $config = \Yii::$app->params['clienterp_authentication']['bipl_smart'];
            $base_url = $config['api_base_url'];
            if (!$this->AuthenticateRequest($config)) return;

            $localModel = new $modelClass();
            $masterData = $localModel->getMasterRecord();
            if (empty($masterData)) return;

            $current_ids = array_column($masterData[$dataKey], $idKey);
            $current_extra_ids = !empty($extraIdKey) && !empty($masterData[$extraIdKey]) ? $masterData[$extraIdKey] : $current_ids;
            $now = date('Y-m-d H:i:s');
            $initialUpdate = ['data_post_status' => 1, 'updated_at' => $now, 'picked_datetime' => $now];
            $localModel->updateStatus($initialUpdate, $current_extra_ids);
            try {
                $api = new WebApi();
                $api->return_actual = true;
                $api->serverUrl = $base_url;
                $api->apiurl = $config[$endpointKey];
                $api->body = json_encode($masterData);
                $api->is_header_merge = true;
                $api->header_info = ["Authorization: Bearer " . $this->token]; 
                $api->authentication = [];
                $result = $api->POSTDATA();
                $response = !empty($result) ? json_decode($result) : [];
                if (!empty($response)) {
                    $respTime = date('Y-m-d H:i:s');
                    if (!empty($response->data->remarks)) {
                        foreach ($response->data->remarks as $val) {
                            if(!isset($val->isSuccessful)){
                                $status = $val->integrationFlag ? 2 : 3;
                                $update = ['data_post_status' => $status, 'updated_at' => $respTime, 'response_datetime' => $respTime, 'resp_desc' => $val->remark];
                                $id = $val->$idKey;
                                if(!empty($extraIdKey) && property_exists($val, $extraIdKey)){
                                    $key = $val->$idKey . $val->$extraIdKey;
                                    $id = $current_extra_ids[$key] ?? $id; 
                                }
                                $localModel->updateStatus($update, $id);
                            } else {
                                $status = $val->isSuccessful ? 2 : 3;
                                $update = ['data_post_status' => $status, 'updated_at' => $respTime, 'response_datetime' => $respTime, 'resp_desc' => $val->message];
                                $localModel->updateStatus($update, $current_extra_ids);
                            }
                        }
                    } else {
                        $msg = !empty($response->message) ? $response->message : 'The record could not be sent. Please try again';
                        $updateData = ['data_post_status' => 3,'updated_at' => $respTime,'response_datetime' => $respTime,'resp_desc' => $msg];
                        $localModel->updateStatus($updateData, $current_extra_ids);
                    }
                }
            } catch (\Throwable $ex) {
                $this->handleApiError($ex, $localModel, $current_extra_ids);
            }
        } catch (\Throwable $ex) {
            $this->handleApiError($ex, $localModel, $current_extra_ids);
        }
    }

    private function handleApiError($ex, $model, $ids) {
        if ($model && !empty($ids)) {
            $now = date('Y-m-d H:i:s');
            $errorMsg = $ex->getMessage();
            $shortDesc = (strlen($errorMsg) > 800) ? substr($errorMsg, 0, 800) : $errorMsg;
            $updateData = ['data_post_status' => 3, 'updated_at' => $now, 'response_datetime' => $now, 'resp_desc' => json_encode($shortDesc)];
            $model->updateStatus($updateData, $ids);
        }
    }


    public function actionBiplDcsApiMaster() {
        $this->processMasterApi(TblDcs::class, 'dcs_endpoint', 'mppDetails', 'mppCode');
    }

    public function actionBiplMemberApiMaster() {
        $this->processMasterApi(TblMember::class, 'member_endpoint', 'farmerImport', 'memberCode', 'mppCode');
    }

    public function AuthenticateRequest($config){
        $api = new WebApi();
        $api->return_actual = true;
        $api->authentication = [];
        $api->serverUrl = $config['api_base_url'];
        $api->apiurl = $config['auth_endpoint'];
        $api->is_header_merge = false;
        $authentication = $config['authentication']['user'];
        $api->body = json_encode($authentication);
        $result = $api->POSTDATA();
        $response = !empty($result) ? json_decode($result) : [];
        if(!empty($response->isSuccessful)){
            $this->token = $response->data->token;
            return true;
        }
        return false;
    }
}
