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
use app\modules\import\controllers\DefaultController;
use app\modules\import\importData;
use app\modules\organisation\models\TblDcsDeactive;
use app\modules\organisation\models\TblDcs;
use app\modules\syncutility\models\TblSentbox;
use app\modules\organisation\models\TblCustomerDeactive;
use app\modules\organisation\models\TblDcsVendorStatus;
use app\modules\details\models\TblBankDetails;
use app\modules\details\models\TblContactDetails;
use app\modules\dcsoperation\models\TblMemberDeactive;
use app\modules\sms\models\TblApiMaster;
use app\modules\sms\models\TblAlertNotification;

class SchedulerController extends ChildController {

    public $freeAccessActions = ['update-complete-data', 'generate-file', 'upload-files', 'dcs-sentbox-generate'];
    public $errorPath = '';
    public $attachment_folder = '/web/alert-data/';

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
                                $data[$value] .= ' ' . $shift . '.000';
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
//        var_dump('test');die;
        $model = new TblImportFileLog();
        $model->status = 0;
        $modelData = $model->getPickRecords([], 10);
        if (!empty($modelData)) {
            $ids = array_map(function($e) {
                return $e->log_id;
            }, $modelData);
            $update = $model->updateFileStatus($ids);
            foreach ($modelData as $row) {
                if (strtolower($row->process_type) == 'background') {
                    $this->bulk_files_data($row);
                } else {
                    $this->process_files_data($row);
                }
            }
        }
    }

    private function process_files_data($row) {
        try {
            $flag = '';
            if ($row->file_type == 'bmc_collection') {
                $flag = 'bmc-collection-bulk';
                $sp_name = 'DB_JOB_PORTAL_BMC_Collection';
            } else if ($row->file_type == 'milk_collection') {
                $flag = 'milk-collection-bulk';
                $sp_name = 'DB_JOB_PORTAL_Milk_Collection';
            } else if ($row->file_type == 'milk_collection_qlty') {
                $flag = 'milk-collection-qlty-bulk';
                $sp_name = 'DB_JOB_PORTAL_Milk_Collection';
            } else if ($row->file_type == 'bmc_collection_mapped') {
                $flag = 'bmc-mapped-collection-bulk';
                $sp_name = 'DB_JOB_PORTAL_BMC_Collection';
            } else if ($row->file_type == 'bmc_collection') {
                $flag = 'bmc-collection-allow-bulk';
                $sp_name = 'DB_JOB_PORTAL_BMC_Collection_Allow';
            } else if ($row->file_type == 'milk_collection') {
                $flag = 'milk-collection-allow-bulk';
                $sp_name = 'DB_JOB_PORTAL_Milk_Collection_Allow';
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
                $config = importData::getLabels($flag);
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
                    $model->own_bmc_code = !empty($model->own_bmc_code) ? $model->own_bmc_code : $model->bmc_code;
                    $model->shift_code = (strtoupper($model->shift_code) == 'M') ? 1 : 2;
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
                $success_sp_result = [];
                if ($success > 0) {
                    \Yii::$app->general->getSpData($sp_name, $sp_param, TRUE);
                    $success_sp_result = \Yii::$app->general->getSpData($sp_name . '_ErrorList', [$uuid, 'SuccessList']);
                    $sp_result = \Yii::$app->general->getSpData($sp_name . '_ErrorList', [$uuid, 'ErrorList']);
                }
                $error_lines = array_merge($sp_result, $error_lines);
                $filePath = NULL;
                $successfilePath = NULL;
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
                        $filePath = '/web/bulkdata/' . $row->file_type . '/archive/' . 'error_' . $row->file_name;
                    }
                }
                if (!empty($success_sp_result)) {
                    $column_header = array_keys($success_sp_result[0]);
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
                                $success_sp_result, // The data to set
                                NULL, // Array values with this value will not be set
                                'A2'         // Top left coordinate of the worksheet range where
                                //    we want to set these values (default is A1)
                        );
                        $successfilePath = $path . 'success_' . $row->file_name;
                        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                        $objWriter->save($successfilePath);
//                    copy($row->file_path, $path . $row->file_name);
//                    unlink($row->file_path);
                        $successfilePath = '/web/bulkdata/' . $row->file_type . '/archive/' . 'success_' . $row->file_name;
                    }
                }
                $row->total_count = $total_cnt;
                $row->error_count = count($error_lines);
                $row->success_count = $row->total_count - $row->error_count;
                $row->status = 2;
                $row->response_datetime = date('Y-m-d H:i:s');
                $row->response_msg = 'File Processed';
                $row->error_file_path = $filePath;
                $row->success_file_path = $successfilePath;
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

    private function bulk_files_data($row) {
        try {
            $error_lines = [];
            $total_cnt = 0;
            $data = importData::getLabels($row->file_type);
            $table = (!empty($data['import_class'])) ? $data['import_class'] : $data['table_name'];
            $modelName = str_replace('_', ' ', $table);
            $modelName = str_replace(' ', '', ucwords($modelName));
            $className = Yii::$app->path->getModel($modelName);
            $eiplcode = Yii::$app->general->getClientCode($row->union_code);
            $unionKeyPattern = Yii::$app->general->getUnionKeyPattern($row->union_code);
            $data['import_union_code'] = $row->union_code;
            $data['import_eipl_code'] = $eiplcode;
            $data['import_key_pattern'] = $unionKeyPattern;
            $data['created_by'] = $row->created_by;
            $import = new DefaultController('', '');
            $values = $import->importCsv($row->file_name, $className, $data, 0, $row->file_type, '/web/bulkdata/' . $row->file_type . '/');
            $filePath = NULL;
            $successfilePath = NULL;
            $error_lines = [];
            $success_lines = [];
            if (!empty($values['allData']['error_lines'])) {
                $column_header = explode(',', $data['fields']);
                $column_header[] = 'response_msg';
                $error_lines = $values['allData']['error_lines'];
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
                    $filePath = '/web/bulkdata/' . $row->file_type . '/archive/' . 'error_' . $row->file_name;
                }
            }
            if (!empty($values['allData']['success_lines'])) {
                $column_header = explode(',', $data['fields']);
                $success_lines = $values['allData']['success_lines'];
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
                            $success_lines, // The data to set
                            NULL, // Array values with this value will not be set
                            'A2'         // Top left coordinate of the worksheet range where
                            //    we want to set these values (default is A1)
                    );
                    $successfilePath = $path . 'success_' . $row->file_name;
                    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                    $objWriter->save($successfilePath);
//                    copy($row->file_path, $path . $row->file_name);
//                    unlink($row->file_path);
                    $successfilePath = '/web/bulkdata/' . $row->file_type . '/archive/' . 'success_' . $row->file_name;
                }
            }
            $row->total_count = !empty($values['allData']['total_cnt']) ? $values['allData']['total_cnt'] : $total_cnt;
            $row->error_count = count($error_lines);
            $row->success_count = $row->total_count - $row->error_count;
            $row->status = 2;
            $row->response_datetime = date('Y-m-d H:i:s');
            $row->response_msg = 'File Processed';
            $row->error_file_path = $filePath;
            $row->success_file_path = $successfilePath;
            $row->save(FALSE);
        } catch (\Throwable $ex) {
            $row->status = 3;
            $row->response_msg = 'Unable to read file.';
            $row->response_datetime = date('Y-m-d H:i:s');
            $row->save(FALSE);
            var_dump($ex->getMessage());
        }
    }

    public function actionDcsSentboxGenerate() {
        $model = new TblDcsDeactive();
        $limit = 250;
        $deactiveData = $model->getDeactiveRecords(true, '', $limit);
        $this->setSentBox($model, $deactiveData, 'dcs_deactive_code', 'TblDcs', 'dcs_code', 0, 1, 2, 3);

        $activeData = $model->getActiveRecords($limit);
        $this->setSentBox($model, $activeData, 'dcs_deactive_code', 'TblDcs', 'dcs_code', 1, 4, 5, 6);

        $CustModel = new TblCustomerDeactive();
        $deactiveData = $CustModel->getDeactiveRecords(true, '', $limit);

        $this->setSentBox($CustModel, $deactiveData, 'customer_deactive_code', 'TblCustomerMaster', 'customer_code', 0, 1, 2, 3);
        $activeData = $CustModel->getActiveRecords($limit);
        $this->setSentBox($CustModel, $activeData, 'customer_deactive_code', 'TblCustomerMaster', 'customer_code', 1, 4, 5, 6);


        $MemberModel = new TblMemberDeactive();
        $deactiveData = $MemberModel->getDeactiveRecords(true, $limit);
        $this->setSentBox($MemberModel, $deactiveData, 'member_deactive_code', 'TblMember', 'member_code', 0, 1, 2, 3);

        $activeData = $MemberModel->getActiveRecords($limit);
        $this->setSentBox($MemberModel, $activeData, 'member_deactive_code', 'TblMember', 'member_code', 1, 4, 5, 6);
    }

    public function setSentBox($model, $data, $key, $masterModel, $f_key, $status, $u_status, $success, $error) {
        if (!empty($data)) {
            $ids = array_map(function($e) use ($key) {
                return $e->{$key};
            }, $data);
            $update = $model->updateFileStatus($ids, $u_status);
            foreach ($data as $row) {
                $model_name = Yii::$app->path->define($masterModel);
                $modelMaster = new $model_name();
                $existData = $modelMaster::find()->where([$f_key => $row->{$f_key}])->one();
                if (!empty($existData)) {
                    $existData->is_active = $status;
                    $sentboxArray = [];
                    $encrypt = $modelMaster->encryptModel($existData->attributes);
                    $existData->setAttributes($encrypt);
                    if (!empty($existData->customer_type)) {
                        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $existData->bmc_code);
                    } else {
                        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $existData->dcs_code);
                    }
                    foreach ($sentboxArray as $sent) {
                        $flag = 'UPDATE';
                        $sentbox = $this->sentboxModel($sent['code'], $sent['type'], $existData->union_code);
                        if (!($sentbox->setSentbox($existData, $flag))) {
                            $row->data_post_status = $error;
                            $row->response_datetime = date('Y-m-d H:i:s');
                            $row->resp_desc = 'SentBox Entry is not Generated';
                            $row->save(FALSE);
                        } else {
                            $row->data_post_status = $success;
                            $row->response_datetime = date('Y-m-d H:i:s');
                            $row->resp_desc = 'Sentbox Generated';
                            $row->save(FALSE);
                            $statusModel = new TblDcsVendorStatus();
                            $statusModel->dcs_vendor_code = \Yii::$app->general->getCodeAutoIncrement($statusModel);
                            $statusModel->union_code = $existData->union_code;
                            $statusModel->customer_type = !empty($existData->customer_type) ? $existData->customer_type : (!empty($existData->member_code) ? 'Member' : 'DCS');
                            $statusModel->customer_code = !empty($existData->customer_type) ? $existData->customer_code : (!empty($existData->member_code) ? $existData->member_code : $existData->dcs_code);
                            $existStatus = $statusModel::find()->where(['union_code' => $statusModel->union_code, 'customer_type' => $statusModel->customer_type, 'customer_code' => $statusModel->customer_code])->one();
                            $statusModel->is_active = $status;
                            if (!empty($existStatus)) {
                                $existStatus->updateAll(['is_active' => $status, 'updated_at' => date('Y-m-d H:i:s')], ['dcs_vendor_code' => $existStatus->dcs_vendor_code]);
                            } else {
                                $statusModel->save(FALSE);
                            }
                            if ($status == '0' && $statusModel->customer_type == 'DCS') {
                                $bankModel = new TblBankDetails();
                                $existbankModel = $bankModel::find()->where(['module_name' => 'society', 'module_code' => $statusModel->customer_code, 'is_active' => 1])->one();
                                if (!empty($existbankModel)) {
                                    $existbankModel->updateAll(['is_active' => 0, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => 'deactive'], ['module_code' => $statusModel->customer_code]);
                                }
                                $contactModel = new TblContactDetails();
                                $existcontactModel = $contactModel::find()->where(['module_name' => 'society', 'module_code' => $statusModel->customer_code, 'is_active' => 1])->one();
                                if (!empty($existcontactModel)) {
                                    $existcontactModel->updateAll(['is_active' => 0, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => 'deactive'], ['module_code' => $statusModel->customer_code, 'module_name' => 'society']);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function sentboxModel($code, $type, $union) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $union;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function actionShiftCollectionComplete() {
        $apiMaster = new TblApiMaster();
        $apiMaster->receiver_type = 'EMAIL';
        $apiMasterData = $apiMaster->getAPI();

        $date = date('d-m-Y');
        $today = date("d", strtotime($date));
        $lastDate = date('Y-m-d', strtotime('last day of previous month'));

        if (in_array($today, ['01', '06', '11', '16', '21', '26'])) {
            if ($today == '01') {
                $fromDate = date("Y-m", strtotime($lastDate)) . '-26' . ' 06:00:00.000';
                $toDate = $lastDate . ' 18:00:00.000';
            } else {
                $fromDate = date('Y-m-d', strtotime($date . ' -5 day')) . ' 06:00:00.000';
                $toDate = date('Y-m-d', strtotime($date . ' -1 day')) . ' 18:00:00.000';
            }

            if (!empty($apiMasterData)) {
                $htmlContent = "";
                $message = "";
                $file_name = "";
                $file_path = "";
                $this->setHtmlContent($htmlContent, $message, $file_name, $file_path, $fromDate, $toDate);
                $from = $apiMasterData->url;
                $to = $apiMasterData->token;
                $cc = 'vinay@everestinstruments.com';
                if (!empty($file_name)) {
                    $send = Yii::$app->alertnotification->sendEmail($from, $to, $cc, $message, $htmlContent, FALSE, $file_name, $file_path);
                    $notificationModel = new TblAlertNotification();
                    $notificationModel->receiver_type = 'EMAIL';
                    $notificationModel->message = $htmlContent;
                    $notificationModel->header_info = $message;
                    $notificationModel->send_status = 1;
                    $notificationModel->content_id = $apiMasterData->api_master_id;
                    $notificationModel->module_type = "Alert Mail Report";
                    $notificationModel->entry_datetime = date('Y-m-d H:i:s');
                    $notificationModel->send_mail = 1;
                    $notificationModel->receiver_detail = $to;
                    $notificationModel->filename = $file_name;
                    $notificationModel->file_path = $file_path;
                    $notificationModel->save();
                }
            }
        }
    }

    public function setHtmlContent(&$htmlContent, &$message, &$fileName, &$file_path, $fromDate, $toDate) {
        $message = 'Society Collection Summary Status (' . Yii::$app->formatter->asDatetime($fromDate . Yii::$app->getTimeZone(), 'php:d-m-Y') . ' To ' . Yii::$app->formatter->asDatetime($toDate . Yii::$app->getTimeZone(), 'php:d-m-Y') . ')';
        $baseUrl = Yii::$app->request->baseUrl;
        $hostUrl = Url::base('http');
        $hostUrl = str_replace($baseUrl, '', $hostUrl);
        $htmlContent = '<p>Dear Sir, <br/><br/>' . $message;
        $htmlContent .= '<br/><br/>Detailed report is attached herewith </p>';
        $htmlContent .= '<br/><br/>';
        $htmlContent .= '<p>Regards,';
        $htmlContent .= '<br/>Everest Instrument Pvt. Ltd.</p>';


        $sp_name = 'sp_mis_society_shift_collection_completed';
        $controls = [];
        $controls['union_code'] = '001';
        $controls['plant_code'] = '001005';
        $controls['mcc_plant_code'] = '001052';
        $controls['bmc_code'] = '0';
        $controls['dcs_code'] = '0';
        $controls['from_date'] = $fromDate;
        $controls['to_date'] = $toDate;
        $result = \Yii::$app->general->getSpData($sp_name, $controls);

        if (!empty($result)) {
            $output = $result;
            $datetime = date('dmY');
            $fileName = $datetime . '-SocietyCollectionSummary' . '.xls';
            $file_path = $this->CreateFile($fileName, $output);
        }
    }

    public function CreateFile($fileName, $output) {
        $column_header = array_keys($output[0]);
        $path = str_replace('\\', '/', realpath(\Yii::$app->basePath)) . $this->attachment_folder;
        if (\Yii::$app->general->checkDirectory($path)) {
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
                    $output, // The data to set
                    NULL, // Array values with this value will not be set
                    'A2'         // Top left coordinate of the worksheet range where
                    //    we want to set these values (default is A1)
            );
            $filePath = $path . $fileName;
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save($filePath);
            return $absoluteBaseUrl . $this->attachment_folder . $fileName;
        }
    }

}
