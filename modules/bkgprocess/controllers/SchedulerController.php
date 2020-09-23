<?php

namespace app\modules\bkgprocess\controllers;

use yii;
use app\controllers\ChildController;
use app\modules\bkgprocess\Bkgprocess;
use app\modules\bkgprocess\models\TblFileCreator;
use app\modules\bkgprocess\models\TblFtpTxnLog;
use app\components\FTPConnection;
use app\modules\import\models\TblImportFileLog;
use ruskid\csvimporter\CSVImporter;
use ruskid\csvimporter\CSVReader;
use \app\modules\collection\models\TblBulkDataImport;
use yii\helpers\Url;
use PHPExcel;

class SchedulerController extends ChildController {

    public $freeAccessActions = ['update-complete-data', 'generate-file', 'upload-files'];
    public $errorPath = '';

    public function init() {
        parent::init();
        //$this->errorPath = Yii::$app->params['FTPDirPath'] . 'ErrorLogs/FTP';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [
        ];
    }

    public function actionUpdateCompleteData() {
        try {
            $FTPProcess = Bkgprocess::FTPProcess();
            foreach ($FTPProcess as $module => $detail) {
                $model_name = Yii::$app->path->define($detail['summary_model']);
                $model = new $model_name();
                $summary_data = $model->FTPPendingData();
                foreach ($summary_data as $data) {
                    $model->updateData($data, 1);
                }
                foreach ($summary_data as $data) {
                    $data_orignal = $data;
                    $controls = [];
                    $param = explode(',', $detail['param2']);
                    foreach ($param as $key => $value) {
                        $value_array = explode(':', $value);
                        $value = $value_array[0];
                        if (isset($value_array[1]) && $value_array[1] == 'date') {
                            $data[$value] = !empty($data[$value]) ? date('Y-m-d', strtotime($data[$value])) : date('Y-m-d');
                            if (isset($value_array[2])) {
                                $shift = !empty($data[$value_array[2]]) ? \Yii::$app->general->getshift($data[$value_array[2]]) : '00:00:00';
                                $data[$value] .=' ' . $shift . '.000';
                            }
                        }
                        $controls[$value] = !isset($data[$value]) ? '0' : $data[$value];
                    }
                    $output = \Yii::$app->general->getSpData($detail['sp_check_data'], $controls);
                    if (empty($output)) {
                        $model->updateData($data_orignal, 0, TRUE);
                    } else {
                        foreach ($output as $result) {
                            if ($result['Pending'] > 0) {
                                $model->updateData($data_orignal, 0, TRUE);
                            } else {
                                $file_create = new TblFileCreator();
                                $file_create->attributes = $data;
                                $file_create->module_name = $module;
                                $file_create->module_code = $file_create->mcc_plant_code;
                                $file_create->applicable_date = $data['shift_date'];
                                if ($file_create->save(FALSE)) {
                                    $model->updateData($data_orignal, 2, TRUE);
                                } else {
                                    $model->updateData($data_orignal, 0, TRUE);
                                }
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $ex) {
            //$model->updateData($data_orignal, 0, TRUE);
            var_dump($ex);
        }
    }

    public function actionGenerateFile() {
        try {
            $model = new TblFileCreator();
            $model->file_status = 0;
            $model->status = 0;
            $modelData = $model->getPendingData();
            if (!empty($modelData)) {
                $ids = array_map(function($e) {
                    return $e->file_creator_id;
                }, $modelData);
                $model->updateFileStatus($ids);
                foreach ($modelData as $data) {
                    $ftp_model = new TblFtpTxnLog();
                    $ftp_model->module_code = $data->attributes;
                    $FTPProcess = Bkgprocess::FTPProcess()[$data->module_name];
                    $param = explode(',', $FTPProcess['param']);
                    $controls = [];
                    foreach ($param as $key => $val) {
                        $controls[$val] = $data->{$val};
                    }
                    $output = \Yii::$app->general->getSpData($FTPProcess['sp_name'], $controls);
                    if ($ftp_model->generateFiles($output, $FTPProcess, $data)) {
                        $data->status = 2;
                        $data->file_status = 1;
                    } else {
                        $data->status = 3;
                    }
                    $data->save(FALSE);
                }
            }
        } catch (\Throwable $ex) {
            var_dump($ex);
        }
    }

    public function actionUploadFiles() {
        try {
            $model = new TblFtpTxnLog();
            $model->file_status = 0;
            $model->txn_type = 'EIPL';
            $model->status = 0;
            $modelData = $model->getPickRecords([]);
            if (!empty($modelData)) {
                $ids = array_map(function($e) {
                    return $e->ftp_txn_log_id;
                }, $modelData);
                $update = $model->updateFileStatus($ids);
                $this->upload_files($modelData);
            }
        } catch (\Throwable $ex) {
            var_dump($ex);
        }
    }

    private function upload_files($data, $create_dir = FALSE) {
        $success = 0;
        $error = 0;
        $cnt = 0;
        $connection = FALSE;
        foreach ($data as $row) {
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
                $connection = $ftp->ConnectServer();
            }
            if ($connection) {
                // if ($create_dir && $row->CheckDirectory() == '0') {
                if ($create_dir) {
                    $ftp->CreateDirectory();
                }
                $ftp_path = explode('/', $row->file_path);
                unset($ftp_path[count($ftp_path) - 1]);
                $ftp_path = implode('/', $ftp_path);
                $local_path = explode('/', $row->local_path);
                unset($local_path[count($local_path) - 1]);
                $local_path = implode('/', $local_path);
                $file_name = $row->file_name;
                $ftp->ftp_path = $ftp_path;
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
                        $ftp->ftp_path = '/' . $ftp_path . '/';
                        if ($ftp->DeleteFile()) {
                            /* $d_log = new TblFtpTxnLog();
                              $d_log->file_name = $row->file_name;
                              $d_log->txn_type = 'BIPL';
                              $d_log->file_path = $row->old_file_path;
                              $d_log->file_status = 1;
                              $d_log = $d_log->getRipFileData();
                              if (!empty($d_log)) {
                              $d_log->file_status = $d_log->status;
                              $d_log->save(FALSE);
                              } */
                        }
                    }
                }
                if ($row->save(FALSE)) {
                    $success ++;
                } else {
                    $error ++;
                }
            } else {
                $row->status = ($row->status == 3) ? 4 : 3;
                $row->file_status = 0;
                $row->save(FALSE);
                $error ++;
            }
        }
    }

    public function actionProcessImportFiles() {
        $model = new TblImportFileLog();
        $model->status = 0;
        $modelData = $model->getPickRecords([], 10);
        if (!empty($modelData)) {
            $ids = array_map(function($e) {
                return $e->log_id;
            }, $modelData);
            $update = $model->updateFileStatus($ids);
            $this->process_files_data($modelData);
        }
    }

    private function process_files_data($modelData) {
        foreach ($modelData as $row) {
            try {
                $flag = '';
                if ($row->file_type == 'bmc_collection') {
                    $flag = 'bmc-collection-bulk';
                    $sp_name = 'DB_JOB_PORTAL_BMC_Collection';
                } else if ($row->file_type == 'milk_collection') {
                    $flag = 'milk-collection-bulk';
                    $sp_name = 'DB_JOB_PORTAL_Milk_Collection';
                }
                if (!empty($flag)) {
                    $error_lines = [];
                    $success = 0;
                    $total_cnt = 0;
                    $command = Yii::$app->getDb()->createCommand('SELECT NEWID() as id')->queryOne();
                    $uuid = $command['id'];
                    $importer = new CSVImporter();
                    $importer->setData(new CSVReader([
                        'filename' => $row->file_path,
                        'fgetcsvOptions' => [
                            'delimiter' => ';'
                        ]
                    ]));
                    $config = \app\modules\import\importData::getLabels($flag);
                    $header = explode(',', $config['fields']);
                    $fileData = $importer->getData();
                    unset($fileData[0]);
                    foreach ($fileData as $line) {
                        $total_cnt++;
                        $data = array_combine($header, $line);
                        $model = new TblBulkDataImport();
                        $model->attributes = $data;
                        $model->uuid = $uuid;
                        $model->union_code = $row->union_code;
                        $model->shift_code = ($model->shift_code == 'M') ? 1 : 2;
                        $model->date_time_of_collection = !empty($model->date_time_of_collection) ? date('Y-m-d', strtotime($model->date_time_of_collection)) : '';
                        $model->date_time_of_collection = $model->date_time_of_collection . ' ' . \Yii::$app->general->getshift($model->shift_code);
                        if ($model->save()) {
                            $success++;
                        } else {
                            $data['response_msg'] = 'File Record error.';
                            $error_lines[] = $data;
                        }
                    }
                    $sp_param = [];
                    $sp_param[] = $uuid;
                    $sp_param[] = $row->created_by;
                    $sp_param[] = $row->union_code;
                    $sp_result = [];
                    if ($success > 0) {
                        \Yii::$app->general->getSpData($sp_name, $sp_param, TRUE);
                        $sp_result = \Yii::$app->general->getSpData($sp_name . '_ErrorList', [$uuid]);
                    }
                    $error_lines = array_merge($sp_result, $error_lines);
                    $filePath = NULL;
                    if (!empty($error_lines)) {
                        $column_header = array_keys($error_lines[0]);
                        $path = str_replace('\\', '/', realpath(\Yii::$app->basePath)) . '/web/bulkdata/' . $row->file_type . '/archive/';
                        if (Yii::$app->general->checkDirectory($path)) {
                            $absoluteBaseUrl = Url::base(true);
                            $objPHPExcel = new PHPExcel();
                            $sheet = $objPHPExcel->getActiveSheet();
                            $sheet->fromArray(
                                    $column_header, // The data to set
                                    NULL, // Array values with this value will not be set
                                    'A1'         // Top left coordinate of the worksheet range where
                                    //    we want to set these values (default is A1)
                            );
                            $sheet->fromArray(
                                    $error_lines, // The data to set
                                    NULL, // Array values with this value will not be set
                                    'A2'         // Top left coordinate of the worksheet range where
                                    //    we want to set these values (default is A1)
                            );
                            $filePath = $path . 'error_' . $row->file_name;
                            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                            $objWriter->save($filePath);
                            copy($row->file_path, $path . $row->file_name);
                            unlink($row->file_path);
                            $filePath = $absoluteBaseUrl . '/web/bulkdata/' . $row->file_type . '/archive/' . 'error_' . $row->file_name;
                        }
                    }
                    $row->total_count = $total_cnt;
                    $row->error_count = count($error_lines);
                    $row->success_count = $row->total_count - $row->error_count;
                    $row->status = 2;
                    $row->response_datetime = date('Y-m-d H:i:s');
                    $row->response_msg = 'File Processed';
                    $row->error_file_path = $filePath;
                    $row->save(FALSE);
                } else {
                    $row->status = 3;
                    $row->response_msg = 'Import Config Missing.';
                    $row->response_datetime = date('Y-m-d H:i:s');
                    $row->save(FALSE);
                }
            } catch (\Throwable $ex) {
                $row->status = 3;
                $row->response_msg = 'Unable to read file.';
                $row->response_datetime = date('Y-m-d H:i:s');
                $row->save(FALSE);
                var_dump($ex->getMessage());
            }
        }
    }

}
