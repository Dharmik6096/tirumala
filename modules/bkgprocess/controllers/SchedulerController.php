<?php

namespace app\modules\bkgprocess\controllers;

use yii;
use app\controllers\ChildController;
use app\modules\bkgprocess\Bkgprocess;
use app\modules\bkgprocess\models\TblFileCreator;
use app\modules\bkgprocess\models\TblFtpTxnLog;
use app\modules\bkgprocess\models\TblDataExchangeConfig;
use app\modules\bkgprocess\models\TblFtpDetail;
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
use app\modules\collection\models\TblMccShiftLockStaging;
use app\modules\configuration\models\TblGenerateReportParam;
use app\modules\collection\models\TblMilkCollectionSummary;
use app\models\GeneralModel;
use app\models\UserHistory;
use webvimark\modules\UserManagement\models\User;
use app\modules\webservice\eipl\models\TblEiplAppLogin;
use app\modules\webservice\eipl\models\TblEiplAppLoginHistory;
use app\components\AMQPConnection;
use app\modules\tms\models\TblTask;
use app\modules\complaint\models\TblComplainEscalationTxnDetail;
use app\modules\complaint\models\TblComplainActivity;
use app\modules\complaint\models\TblComplain;
use app\modules\complaint\models\TblComplainHistory;
use app\modules\tms\models\TblUserAttendance;
use app\components\WebApi;
use app\modules\collection\models\TblBulkBillingImport;
use app\modules\collection\models\TblMilkCollection;
use app\modules\dcsoperation\models\TblMemberProvisional;
use app\modules\eipldpu\models\TblEiplPacketFileLog;
use app\modules\eipldpu\controllers\PendriveImportController;
use app\modules\organisation\controllers\TblCustomerMasterProvisionalController;
use app\modules\organisation\controllers\TblDcsProvisionalController;
use app\modules\organisation\models\TblCustomerMasterProvisional;
use app\modules\organisation\models\TblDcsProvisional;
use common\services\ImportFilesBackgroudService;
use common\services\ImportFilesService;
use app\modules\organisation\models\TblDcsProvisionalHistory;
use app\modules\dcsoperation\models\TblMemberProvisionalHistory;
use app\modules\organisation\models\TblCustomerMasterProvisionalHistory;

class SchedulerController extends ChildController {

    public $freeAccessActions = ['update-complete-data', 'generate-file', 'upload-files', 'dcs-sentbox-generate', 'process-import-files', 'process-import-files-background', 'sap-file-upload', 'alert-queue-post', 'generate-activity-alert', 'auto-complain-assign', 'process-attendance-data', 'milk-collection-ftp-upload-ananda', 'process-bulk-eipl-files', 'provisional-data-exchange','download-acknowledge-files','process-acknowledge-files'];
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
            $model->status = 0;
            $modelData = $model->getPendingData();
            if (!empty($modelData)) {
                $ids = array_map(function($e) {
                    return $e->file_creator_id;
                }, $modelData);
                $model->updateFileStatus($ids);
                foreach ($modelData as $data) {
                    $ftp_model = new TblFtpTxnLog();
                    $FTPProcess = Bkgprocess::FTPProcess()[$data->module_name];
                    $param = explode(',', $FTPProcess['param']);
                    $controls = [];
                    foreach ($param as $key => $val) {
                        $controls[$val] = $data->{$val};
                    }
                    $output = \Yii::$app->general->getSpData($FTPProcess['sp_name'], $controls);
                    if ($ftp_model->generateFiles($output, $FTPProcess, $data, FALSE, $data->file_name)) {
                        $data->status = 2;
                        $data->file_status = 1;
                    } else {
                        $data->status = 3;
                    }
                    $data->response_datetime = date('Y-m-d H:i:s');
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
                $ftp->isPassiveFtp = !empty($row->ftp_mode) && $row->ftp_mode == 'active' ? false : true;
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
        $importFilesService = new ImportFilesService();
        $importFilesService->ProcessImportFiles();
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
        $deactiveData = $MemberModel->getDeactiveRecords(true, '', $limit);
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
            $uniqueUnionConfigData = [];
            foreach ($data as $row) {
                $model_name = Yii::$app->path->define($masterModel);
                $modelMaster = new $model_name();
                $existData = $modelMaster::find()->where([$f_key => $row->{$f_key}])->one();
                if (!empty($existData)) {
                    if (method_exists($existData, 'updateChildRecord')) {
                        $existData->updateChildRecord($existData, $status);
                    }
                    $is_active = $existData->is_active;
                    $existData->is_active = $status;
                    $sentboxArray = [];
                    $encrypt = $modelMaster->encryptModel($existData->attributes);
                    $existData->setAttributes($encrypt);
                    $unionCode = $row->union_code;
                    if (!isset($uniqueUnionConfigData[$unionCode])) {
                        $uniqueUnionConfigData[$unionCode] = Yii::$app->general->getUnionConfiguration($unionCode, 'reset_data_on_deactivation', 'PORTAL');
                    }
                    if (!empty($uniqueUnionConfigData[$unionCode]) && $status == '0') {
                        $historyModelName = $model_name . 'History';
                        $modelHistory = new $historyModelName();
                        Yii::$app->operation->history($existData, $modelHistory, UPDATE);
                        $existData->resetData();
                    }
                    if (!empty($existData->customer_type)) {
                        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $existData->bmc_code);
                    } else {
                        $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $existData->dcs_code);
                    }
                    $sentboxGenerated = false;
                    $flag = 'UPDATE';
                    $sentbox = new TblSentbox();
                    $sentbox->source_org_id = $existData->union_code;
                    if (!($sentbox->setSentboxBatch($existData, $flag, $sentboxArray))) {
                        $sentboxGenerated = !empty($sentboxGenerated) ? $sentboxGenerated : false;
                    } else {
                        $sentboxGenerated = true;
                    }
                    if ($sentboxGenerated) {
                        $row->data_post_status = $success;
                        $row->response_datetime = date('Y-m-d H:i:s');
                        $row->resp_desc = 'Sentbox Generated';
                        if (!empty($uniqueUnionConfigData[$unionCode]) && $status == '0') {
                            $modelHistory->save();
                            $decrypt = $modelMaster->decryptModel($existData);
                            $existData->setAttributes($decrypt);
                            $existData->is_active = $is_active;
                            $existData->is_sentbox = FALSE;
                            $existData->save(TRUE, FALSE);
                            $row->remarks = trim($row->remarks . ' Deactivation CBPA Removed');
                        }
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
                        if ($status == '0' && strtolower($statusModel->customer_type) != 'member') {
                            $bankModel = new TblBankDetails();
                            $existbankModel = $bankModel::find()->where(['module_code' => $statusModel->customer_code, 'is_active' => 1])->andWhere(['in', 'module_name', ['society', 'customer']])->one();
                            if (!empty($existbankModel)) {
                                $existbankModel->updateAll(['is_active' => 0, 'is_default' => 0, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => 'deactive'], ['module_code' => $statusModel->customer_code]);
                            }
                            $contactModel = new TblContactDetails();
                            $existcontactModel = $contactModel::find()->where(['module_code' => $statusModel->customer_code, 'is_active' => 1])->andWhere(['in', 'module_name', ['society', 'customer']])->one();
                            if (!empty($existcontactModel)) {
                                $existcontactModel->updateAll(['is_active' => 0, 'is_default' => 0, 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => 'deactive'], ['module_code' => $statusModel->customer_code]);
                            }
                        }
                    } else {
                        $row->data_post_status = $error;
                        $row->response_datetime = date('Y-m-d H:i:s');
                        $row->resp_desc = 'SentBox Entry is not Generated';
                        $row->save(FALSE);
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

    public function CreateFile($fileName, $output, $folders = '') {
        $column_header = array_keys($output[0]);
        $folder = !empty($folders) ? $folders : $this->attachment_folder;
        $path = str_replace('\\', '/', realpath(\Yii::$app->basePath)) . $folder;
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
            return $absoluteBaseUrl . $folder . $fileName;
        }
    }

    public function actionAdulteratedMilk() {
        $apiMaster = new TblApiMaster();
        $apiMaster->receiver_type = 'EMAIL';
        $apiMasterData = $apiMaster->getAPI();

        if (!empty($apiMasterData)) {

            $message = 'ADULTERATED MILK COLLECTION AT MCC';
            $sp_name = 'portal_sp_adultrated_milk';
            $controls = [];
            $controls['date'] = date('Y-m-d');
            $Output = \Yii::$app->general->getSpData($sp_name, $controls);
            $mccWiseArray = [];
            if (!empty($Output)) {

                foreach ($Output as $result) {
                    $mccCode = $result['mcc_plant_code'];
                    if (empty($mccWiseArray[$mccCode])) {
                        $mccWiseArray[$mccCode] = [];
                    }
                    $mccWiseArray[$mccCode][] = $result;
                }
                if (!empty($mccWiseArray)) {

                    foreach ($mccWiseArray as $mccData) {
                        $htmlContent = "";
                        $this->setAdulteratedMilkHtmlContent($htmlContent, $mccData);
                        $from = $apiMasterData->url;
                        $contactModel = new TblContactDetails();
                        $contactModel->module_code = $mccData[0]['mcc_plant_code'];
                        $contactModel->module_name = 'mccPlant';
                        $contactData = $contactModel->getContactDetail();
                        if (!empty($contactData) && !empty($contactData->email_to)) {
                            $to = $contactData->email_to;
                            $cc = $contactData->email_cc;
                            $bcc = $contactData->email_bcc;

                            $send = Yii::$app->alertnotification->sendEmail($from, $to, $cc, $message, $htmlContent, FALSE, '', '', $bcc);
                            $notificationModel = new TblAlertNotification();
                            $notificationModel->receiver_type = 'EMAIL';
                            $notificationModel->message = $htmlContent;
                            $notificationModel->header_info = $message;
                            $notificationModel->send_status = 1;
                            $notificationModel->content_id = $apiMasterData->api_master_id;
                            $notificationModel->module_type = "Adultration Mail";
                            $notificationModel->entry_datetime = date('Y-m-d H:i:s');
                            $notificationModel->send_mail = 1;
                            $notificationModel->receiver_detail = $to;
                            $notificationModel->filename = NULL;
                            $notificationModel->file_path = NULL;
                            $notificationModel->save();
                        }
                    }
                }
            }
        }
    }

    public function setAdulteratedMilkHtmlContent(&$htmlContent, &$Output) {

        $labelArray = !empty($Output) ? array_keys($Output[0]) : [];
        $htmlContent .= "<table cellpadding='5'  cellspacing='0'><tbody>";
        $htmlContent .= "<tr>";
        $htmlContent .= "<td colspan='2'><b>ADULTERATED MILK REPORT</b></td>";
        $htmlContent .= "</tr>";
        $htmlContent .= "</tbody></table>";
        $htmlContent .= '<p>Dear AM, <br/>';
        $htmlContent .= '<br/><br/>Please find below details of adulterated milk collected with deviation at your MCC. </p>';
        $htmlContent .= '<br/>';

        $htmlContent .= "<table border='1'>";
        $htmlContent .= "<tr>";
        foreach ($labelArray as $a) {
            if ($a != 'mcc_plant_code') {
                $htmlContent .= "<td>" . $a . "</td>";
            }
        }
        $htmlContent .= "</tr>";
        foreach ($Output as $row) {
            $htmlContent .= "<tr>";
            foreach ($labelArray as $a) {
                if ($a != 'mcc_plant_code') {
                    $dispData = '';
                    if (isset($row[$a]) && $row[$a] != '' && $row[$a] != null) {
                        $dispData = $row[$a];
                    }
                    $value = $dispData;
                    if (!empty($value) && is_numeric($value) && (float) $value <= 100000000 && substr($value, 0, 1) != 0) {
                        $htmlContent .= "<td>" . $value . "</td>";
                    } else if (!empty($value) && is_numeric($value) && (float) $value <= 100000000 && (substr($value, 0, 1) == '.' || (substr($value, 0, 1) == '0' && substr($value, 1, 1) == '.'))) {
                        if (substr($value, 0, 1) == '.') {
                            $htmlContent .= "<td>0" . $value . "</td>";
                        } else {
                            $htmlContent .= "<td>" . substr($value, 0, 2) . "</td>";
                        }
                    } else {
                        $htmlContent .= "<td style=\"mso-number-format:'\@'\">" . $value . "</td>";
                    }
                }
            }
            $htmlContent .= "</tr>";
        }
        $htmlContent .= "</table>";
    }

    public function actionGenerateWqDispatchFile() {
        try {
            $union_codes = ['001', '003'];
            foreach ($union_codes as $k => $union_code) {
                $FTPProcess = Bkgprocess::FTPProcess()['TblBmcCollection_dispatch'];
                $param = explode(',', $FTPProcess['param']);
                $controls = [];
                foreach ($param as $key => $val) {
                    $controls[$val] = 0;
                }
                $controls['union_code'] = $union_code;
                $controls['from_date'] = date('Y-m-d h:i:s', strtotime(date('Y-m-d 06:00:00') . '- 12 days'));
                $controls['to_date'] = date('Y-m-d 18:00:00');
//            $controls['from_date'] = '2022-06-10 06:00:00';
//            $controls['to_date'] = '2022-06-10 18:00:00';
                $output = \Yii::$app->general->getSpData($FTPProcess['sp_name'], $controls);
                $downLoadArray = [];
                foreach ($output as $detail) {
                    $plant = 'Plant Code';
                    if (!empty($detail[$plant]) && strtolower($detail[$plant]) != 'total') {
                        $array_key = $detail[$plant] . '###' . $detail['Recpt Date'] . '###' . $detail['Shift Id'];
                        if (empty($downLoadArray[$array_key])) {
                            $downLoadArray[$array_key] = [];
                        }
                        $downLoadArray[$array_key][] = $detail;
                    }
                }
                foreach ($downLoadArray as $bmc => $download) {
                    $bmc = explode('###', $bmc)[0];
                    $report_type = Yii::t('app', 'WQ');
                    $shiftId = !empty($download[0]) && !empty($download[0]['Shift Id']) ? $download[0]['Shift Id'] : 1;
                    $from_date = date('Y-m-d', strtotime(str_replace('/', '-', $download[0]['Recpt Date'])));
                    $from_date .= ' ' . \Yii::$app->general->getshift($shiftId);
                    $title = $bmc . '_' . $report_type . '_' . str_replace('-', '_', Yii::$app->controls->view_date($from_date)) . '_' . $shiftId;
                    $data_array = [];
                    $data_array['module_name'] = 'TblBmcCollection_dispatch';
                    $data_array['module_code'] = $bmc;
                    $data_array['mcc_plant_code'] = $bmc;
                    $data_array['union_code'] = NULL;
                    $data_array['applicable_date'] = $from_date;
                    $data_array['shift_code'] = 1;
                    $data_array['bmc_code'] = NULL;
                    $data_array['from_date'] = $from_date;
                    $data_array['to_date'] = $from_date;
                    $ftp_model = new TblFtpTxnLog();
                    $result = $ftp_model->exportData($data_array, $title, $download);
                    if (!empty($result)) {
                        $controls['file_name'] = $result;
                        $ftp_model->ref_code = $data_array['module_code'];
                        $bmc_data = $ftp_model->bmcCode;
                        if (!empty($bmc_data)) {
                            $controls['mcc_plant_code'] = $bmc_data->mcc_plant_code; // $bmc_data->bmc_code;
                            $controls['bmc_code'] = $bmc_data->bmc_code; // $bmc_data->mcc_plant_code;
                            $controls['from_date'] = $controls['to_date'] = $from_date;
                        }
                        \Yii::$app->general->getSpData($FTPProcess['sp_name'] . '_update', $controls, TRUE);
                    }
                }
            }
        } catch (\Throwable $ex) {
            var_dump($ex);
        }
    }

    public function actionGenerateSdCollectionFile() {
        try {
            $model = new TblMccShiftLockStaging();
            $modelData = $model->getLockShift(25);
            if (!empty($modelData)) {
                $ids = array_map(function($e) {
                    return $e->staging_code;
                }, $modelData);
                $model->updateFileStatus($ids);
                foreach ($modelData as $data) {
                    $file_data = new TblFileCreator();
                    $mcc = $data->mccPlantCode;
                    $file_data->union_code = $mcc->union_code;
                    $file_data->mcc_plant_code = $data->mcc_plant_code;
                    $file_data->shift_code = $data->shift_code;
                    $file_data->applicable_date = date('Y-m-d H:i:s', strtotime($data->date_time_of_collection));
                    $file_data->module_code = $mcc->ref_code;
                    $file_data->module_name = 'TblMilkCollection';
                    $data_array = [];
                    $data_array['bmc_code'] = 0;
                    $data_array['from_date'] = $data_array['to_date'] = $file_data->applicable_date;
                    $ftp_model = new TblFtpTxnLog();
                    $FTPProcess = Bkgprocess::FTPProcess()[$file_data->module_name];
                    $param = explode(',', $FTPProcess['param']);
                    $controls = [];
                    foreach ($param as $key => $val) {
                        $controls[$val] = isset($file_data->{$val}) ? $file_data->{$val} : $data_array[$val];
                    }
                    $output = \Yii::$app->general->getSpData($FTPProcess['sp_name'], $controls);
                    if (!empty($output)) {
                        unset($output[count($output) - 1]);
                        $result = $ftp_model->generateFiles($output, $FTPProcess, $file_data);
                        if ($result) {
                            $data->resp_desc = $result;
                            $data->data_post_status = 2;
                        } else {
                            $data->data_post_status = 3;
                        }
                        $data->response_datetime = date('Y-m-d H:i:s');
                        $data->save(FALSE);
                    }
                }
            }
        } catch (\Throwable $ex) {
            var_dump($ex);
        }
    }

    public function actionGenerateReportFile() {
        $model = new TblGenerateReportParam();
        $modelData = $model->getPickRecords(10);
        if (!empty($modelData)) {
            $ids = array_map(function($e) {
                return $e->report_param_code;
            }, $modelData);
            $update = $model->updateFileStatus($ids);
            foreach ($modelData as $row) {
                $data = \app\modules\misreports\controllers\ReportsController::getLabels($row->report_key);
                $controls = [];
                $param = explode(',', $data['param']);
                foreach ($param as $key => $value) {
                    $value_array = explode(':', $value);
                    $value = $value_array[0];
                    $controls[$value] = $row->{$value};
                }
                $sp_name = $data['sp_name'];
                $output = \Yii::$app->general->getSpData($sp_name, $controls);

                if (!empty($output)) {
                    try {
                        $fileName = $data['title'] . '-' . date('Ymdhis') . '-' . $row->report_param_code . '.xls';
                        $file_path = $this->CreateFile(str_replace(' ', '', $fileName), $output, '/web/reports-files/');
                        $row->data_post_status = 2;
                        $row->resp_desc = 'File Generated';
                        $row->file_name = $file_path;
                        $row->save(FALSE);
                        $update = $model->updateFileName($row->report_param_code, 2, $file_path, $row->resp_desc);
                    } catch (\Throwable $ex) {
                        $row->status = 3;
                        $row->resp_desc = 'Unable to Generated file.';
                        $row->save(FALSE);
                        $update = $model->updateFileName($row->report_param_code, 3, '', $row->resp_desc);
                        var_dump($ex->getMessage());
                    }
                } else {
                    $row->data_post_status = 2;
                    $row->resp_desc = 'No Data Available.';
                    $row->file_name = '';
                    $row->save(FALSE);
                    $update = $model->updateFileName($row->report_param_code, 2, '', $row->resp_desc);
                }
            }
        }
    }

    public function actionProcessImportFilesBackground() {
        $importFilesBackgroundService = new ImportFilesBackgroudService();
        $importFilesBackgroundService->ProcessImportFilesBackground();
    }

    public function actionSapFileUpload() {
        $model = new TblMilkCollectionSummary();
        $modelData = $model->getPickRecords(10);
        if (!empty($modelData)) {
            $ids = array_map(function($e) {
                return $e->milk_collection_summary_code;
            }, $modelData);
            $update = $model->updateFileStatus($ids);
            $output = [];
            foreach ($modelData as $row) {
                $data_array['module_name'] = 'TblMilkCollection_cdpl_VM';
                $data_array['module_code'] = $row->dcs_code;
                $data_array['mcc_plant_code'] = $row->mcc_plant_code;
                $data_array['union_code'] = $row->union_code;
                $data_array['applicable_date'] = $row->date_time_of_collection;
                $data_array['shift_code'] = $row->shift_code;
                $data_array['bmc_code'] = $row->bmc_code;
                $data_array['dcs_code'] = $row->dcs_code;
                $data_array['from_date'] = $row->date_time_of_collection;
                $data_array['to_date'] = $row->date_time_of_collection;

                $ftp_model = new TblFtpTxnLog();
                $ftp_model->exportData($data_array, $title = '', $output);
                $update = $model->updateFileUploadStatus($row->milk_collection_summary_code);
            }
        }
    }

    public function actionUserDeactiveWefDateWise() {
        $model = new User();
        $modelData = $model->getPickRecords(10);

        if (!empty($modelData)) {
            $ids = array_map(function($e) {
                return $e->id;
            }, $modelData);
//            $update = $model->updateFileStatus($ids);
            $saveModel = [];

            foreach ($modelData as $row) {
                $historyModel = new UserHistory();
                Yii::$app->operation->history($row, $historyModel, UPDATE);
                $saveModel[] = $historyModel;
                $row->is_active = 0;
                $saveModel[] = $row;

                $appmodel = new TblEiplAppLogin();
                $appDataList = $appmodel->getAppDetail($row);
                if (!empty($appDataList)) {
                    foreach ($appDataList as $appData) {
                        $appHistoryModel = new TblEiplAppLoginHistory();
                        Yii::$app->operation->history($appData, $appHistoryModel, UPDATE);
                        $saveModel[] = $appHistoryModel;
                        $appData->is_active = 0;
                        $saveModel[] = $appData;
                    }
                }
                $generalModel = new GeneralModel();
                $transaction = $generalModel->saveTransaction($saveModel, [], ['User Deactivated', 'edit']);
            }
        }
    }

    public function actionAlertQueuePost() {
        $model = new TblAlertNotification();
        $model->send_status = 0;
        $modelData = $model->getPickRecords();
        if (!empty($modelData)) {
            $ids = array_map(function($e) {
                return $e->alert_notification_id;
            }, $modelData);
            $model->send_status = 1;
            $pick_datetime = date('Y-m-d H:i:s');
            $model->updateRecordStatus($ids);
            $amqp_connection = new AMQPConnection();
            if ($amqp_connection->ConnectServer()) {
                $i = 0;
                foreach ($modelData as $row) {
                    try {
                        $array = [];
                        $array['clientId'] = $row->eipl_code;
                        $array['type'] = $row->receiver_type;
                        $array = array_merge($array, (array) json_decode($row->header_info));
                        $array['recipient'] = $row->receiver_detail;
                        $array['placeholders'] = (array) json_decode($row->message);
                        $amqp_connection->queueName = $row->queue_name;
                        $amqp_connection->queueData = json_encode($array);
                        $declare_queue = TRUE;
                        if (!isset($modelData[$i - 1]) || ($modelData[$i - 1]->queue_name != $row->queue_name)) {
                            $declare_queue = $amqp_connection->DeclareQueue();
                        }
                        if ($declare_queue) {
                            $result = $amqp_connection->PublishToQueue();
                            $row->response_datetime = date('Y-m-d H:i:s');
                            if ($result) {
                                $row->send_status = 2;
                            } else {
                                $row->send_status = 3;
                            }
                        } else {
                            $row->response_datetime = date('Y-m-d H:i:s');
                            $row->send_status = 3;
                        }
                        $row->save(FALSE);
                    } catch (\Throwable $ex) {
                        $row->response_datetime = date('Y-m-d H:i:s');
                        $row->send_status = 3;
                        $row->save(FALSE);
                    }
                    $i++;
                }
                $amqp_connection->CloseConnection();
            } else {
                $model->send_status = 0;
                $model->updateRecordStatus($ids);
            }
        }
    }

    public function actionGenerateActivityAlert() {
        $model = new TblTask();
        $model->resp_status = 0;
        $modelData = $model->getPickRecords();

        if (!empty($modelData)) {
            $ids = array_map(function($e) {
                return $e->task_code;
            }, $modelData);
            $model->updatePickStatus($ids);

            foreach ($modelData as $row) {
                try {
                    $message = [];
                    $header = [];
                    $message[] = ['attributeAlias' => 'MESSAGE', 'attributeValue' => $row->title];
                    $messageJson = json_encode($message);
                    $header['apiFor'] = 'default';
                    $header['channel'] = 'default';
                    $header['templateAlias'] = 'GENERAL_PUSH_NOTIFICATION';
                    $header['templateFor'] = 'default';
                    $headerJson = json_encode($header);
                    $param = [];
                    $param['user_code'] = $row->user_code;
                    $param['union_code'] = $row->union_code;
                    $param['message_json'] = $messageJson;
                    $param['header_json'] = $headerJson;

                    \Yii::$app->general->getSpData('portal_generate_activity_alert', $param, TRUE);
                    $row->resp_status = 2;
                    $row->is_notified = 1;
                    $row->notified_datetime = date('Y-m-d H:i:s');
                    $row->updateProcessStatus();
                } catch (\yii\db\Exception $e) {
                    $row->resp_status = 3;
                    $row->updateProcessStatus();
                } catch (\Throwable $e) {
                    $row->resp_status = 3;
                    $row->updateProcessStatus();
                }
            }
        }
    }

    public function actionAutoComplainAssign() {
        $model = new TblComplainEscalationTxnDetail();
        $model->cron_status = 0;
        $modelData = $model->getPickRecords();

        if (!empty($modelData)) {
            $ids = array_map(function($e) {
                return $e->complain_escalation_txn_detail_code;
            }, $modelData);
            $model->updatePickStatus($ids);

            foreach ($modelData as $row) {
                try {
                    $row->updateStatusDiscard();

                    $data = $model->find()
                            ->where(['complain_code' => $row->complain_code])
                            ->andWhere(['>', 'level', $row->level])
                            ->andWhere('user_code is not null')
                            ->andWhere(['<>', 'status', 'Discard'])
                            ->orderBy(['level' => SORT_ASC])
                            ->one();

                    $row->cron_status = 2;
                    $row->updateStatus();


                    if (!empty($data)) {
                        $data->cron_status = 0;
                        $data->updateRecords();
                        if ($data->user_code != '') {
                            $complaint_activity = new TblComplainActivity();
                            $activityModel = TblComplainActivity::find()->where(['complain_code' => $data->complain_code, 'activity_type' => 'ASSIGN'])->orderBy('complain_activity_code', 'desc')->one();

                            if (!empty($activityModel)) {
                                $complaint_activity->complain_code = $data->complain_code;
                                $complaint_activity->activity_type = 'RE-ASSIGN';
                                $complaint_activity->entry_type = 'CRON';
                                $complaint_activity->user_code = $data->user_code;
                                $complaint_activity->union_code = $data->union_code;
                                $complaint_activity->remarks = 'Auto Assign By Escalation Matrix. You have to take action on this complaint.';
                                $complaint_activity->save();
                            }
                        }
                        $sp_param = [];
                        $sp_name = 'Proc_task_activity';
                        $sp_param[] = $data->union_code;
                        $sp_param[] = $data->complain_code;
                        $sp_param[] = !empty($row->user_code) ? $row->user_code : NULL;
                        $sp_param[] = $data->user_code;
                        $sp_param[] = NULL;

                        $result = \Yii::$app->general->getSpData($sp_name, $sp_param);

                        foreach ($result as $res) {
                            if ($res['retuns_value'] == 1 || $res['retuns_value'] == true) {
                                if (!empty($res['task_activity_code'])) {
                                    $txnDetail = TblComplainEscalationTxnDetail::find()->where(['complain_code' => $data->complain_code, 'status' => 'Allocated'])->one();
                                    if (!empty($txnDetail)) {
                                        $txnDetail->task_activity_code = $res['task_activity_code'];
                                        $txnDetail->save();
                                    }
                                }
                            }
                        }

                        $complain = new TblComplain();
                        $complainModel = TblComplain::find()->where(['complain_code' => $data->complain_code])->one();

                        if (!empty($complainModel)) {
                            $complain_history = new TblComplainHistory();
                            Yii::$app->operation->history($complainModel, $complain_history, UPDATE);
                            $complain_history->save();
                            $complainModel->user_code = $data->user_code;
                            $complainModel->complain_assignment_datetime = date('Y-m-d H:i:s');
                            $complainModel->complain_status = 'INPROGRESS';
                            $complainModel->save();
                        }
                    }
                } catch (\yii\db\Exception $e) {
                    $row->cron_status = 3;
                    $row->updateProcessStatus();
                } catch (\Throwable $e) {
                    $row->cron_status = 3;
                    $row->updateProcessStatus();
                }
            }
        }
    }

    public function actionProcessAttendanceData() {
        $currentTime = time();
        $startTimestamp = strtotime(date('Y-m-d') . ' 03:00');
        $endTimestamp = strtotime(date('Y-m-d') . ' 04:00');
        if ($currentTime >= $startTimestamp && $currentTime <= $endTimestamp) {
            $model = new TblUserAttendance();
            $data = $model->getAttendanceRecords();
            if (!empty($data)) {
                $ids = array_map(function ($e) {
                    return $e->attendance_code;
                }, $data);
                $model->updateApiStatus($ids);

                $withEmployeeIds = [];
                $withoutEmployeeIds = [];
                $jsonData = [];
                try {
                    foreach ($data as $attendanceRecords) {
                        if (!empty($attendanceRecords->userCode->employee_id)) {
                            $withEmployeeIds[] = $attendanceRecords->attendance_code;
                            $jsonData[] = [
                                'Supplier' => 'Milk',
                                'Empid' => $attendanceRecords->userCode->employee_id,
                                'EmpName' => $attendanceRecords->userCode->name,
                                'RMCode' => 'RMCode',
                                'RMName' => 'RMName',
                                'Trdate' => !empty($attendanceRecords->attendance_date) ? date('d-M-Y', strtotime($attendanceRecords->attendance_date)) : '',
                                'StartTime' => !empty($attendanceRecords->in_time) ? date('H:i:s', strtotime($attendanceRecords->in_time)) : '',
                                'Endtime' => !empty($attendanceRecords->out_time) ? date('H:i:s', strtotime($attendanceRecords->out_time)) : '',
                                'Duration' => $attendanceRecords->duration,
                                'Distance' => 0,
                                'Total_outlets' => 0,
                                'Customers_Visited' => 0,
                                'New_Points_Visited' => 0,
                                'Total_Visited' => 0,
                                'Shift_Type' => (strtotime($attendanceRecords->out_time) > strtotime('12:00:00')) ? 'PM' : 'AM',
                                'Route_Stopped_by' => 'User'
                            ];
                        } else {
                            $withoutEmployeeIds[] = $attendanceRecords->attendance_code;
                        }
                    }
                    $model->updateErrorApiStatus('Employee Code Value is Empty', $withoutEmployeeIds);
                    $api = new WebApi();
                    $api->serverUrl = Yii::$app->params['user_attendance_api_url'];
                    $api->authentication = FALSE;
                    $api->body = json_encode($jsonData);
                    $api->header_info = ['ApiKey: A9G3A9T3H6A6M2U1D8I3'];
                    $response = $api->ExchangeData();
                    if (!empty($response)) {
                        $status = $response[0]->status;
                        $msg = $response[0]->message;
                        if ($status == 'success') {
                            $model->updateSuccessApiStatus($msg, $withEmployeeIds);
                        } else {
                            $model->updateErrorApiStatus($msg, $withEmployeeIds);
                        }
                    } else {
                        $model->updateErrorApiStatus('Empty response', $withEmployeeIds);
                    }
                } catch (\Throwable $e) {
                    $errorMessage = substr($e->getMessage(), 0, 250);
                    $model->updateErrorApiStatus($errorMessage, $ids);
                }
            }
        }
    }

    public function actionMilkCollectionFtpUploadAnanda() {
        $model = new TblMilkCollection();
        $modelData = \Yii::$app->general->getSpData('rpt_MIS_SDSAPReport_Ananda_Ftp_Auto_Push', []);
        $data = $modelData;
        if (!empty($modelData)) {
            try {
                $bmcDateShiftData = [];
                foreach ($modelData as $code) {
                    $collData = explode('_', $code['ftp_txn_file_name']);
                    $date = $collData[2];
                    $shift = $collData[3];
                    $bmc = $collData[1];
                    $uniqueKey = $bmc . '_' . $date . '_' . $shift;
                    $bmcDateShiftData[$uniqueKey][] = $code;
                }

                foreach ($bmcDateShiftData as $uniqueKey => $mapData) {
                    $cnt = count($mapData);
                    $data_array = [];
                    $data_array['module_name'] = 'TblMilkCollection_Ananda';
                    $data_array['module_code'] = $mapData[0]['MCC'];
                    $data_array['mcc_plant_code'] = $mapData[0]['MCC'];
                    $data_array['union_code'] = $mapData[0]['union_code'];
                    $data_array['applicable_date'] = Yii::$app->formatter->asDate($mapData[0]['Date'], DATE_FORMAT) . ' ' . Yii::$app->general->getshift($mapData[0]['shift_code']);
                    $data_array['shift_code'] = $mapData[0]['shift_code'];
                    $data_array['bmc_code'] = NULL;
                    $data_array['from_date'] = $data_array['applicable_date'];
                    $data_array['to_date'] = $data_array['applicable_date'];

                    $modelDataOutput = array_map(function($item) {
                        unset($item['union_code'], $item['data_post_status'], $item['ftp_txn_file_name']);
                        return $item;
                    }, $mapData);
                    $title = $mapData[0]['ftp_txn_file_name'];
                    $ftp_model = new TblFtpTxnLog();
                    $result = $ftp_model->exportData($data_array, $title, $modelDataOutput, '', false, TRUE, TRUE);
                    if (!empty($result)) {
                        $model->updateProcessStatus('SUCCESS', '2', 2, $mapData[0]['data_post_status'], $mapData[0]['ftp_txn_file_name']);
                    } else {
                        $model->updateProcessStatus('ERROR', '0', 0, $mapData[0]['data_post_status'], $mapData[0]['ftp_txn_file_name']);
                    }
                }
            } catch (\yii\db\Exception $e) {
                $model->updateProcessStatus('ERROR', '0', 0, $mapData[0]['data_post_status'], $mapData[0]['ftp_txn_file_name']);
            } catch (\Throwable $e) {
                $model->updateProcessStatus('ERROR', '0', 0, $mapData[0]['data_post_status'], $mapData[0]['ftp_txn_file_name']);
            }
        }
    }

    public function actionProcessBulkEiplFiles() {
        $model = new TblEiplPacketFileLog();
        $model->file_status = 0;
        $model->status = 0;
        $modelData = $model->getPendingData();
        if (!empty($modelData)) {
            $ids = array_map(function($e) {
                return $e->file_id;
            }, $modelData);
            $model->updateFileStatus($ids);
            $file_id = implode(',', $ids);
            PendriveImportController::actionProcessFiles($file_id);

            $model->file_status = 2;
            $model->status = 2;
            $modelData = $model->getPendingData($ids);
            if (!empty($modelData)) {
                $ids = array_map(function($e) {
                    return $e->file_id;
                }, $modelData);
                PendriveImportController::savePacketData($ids);
            }
        }
    }

    public function actionProvisionalDataExchange() {
        $configModel = new TblDataExchangeConfig();
        $configModel->api_type = 'AWS';
        $data = $configModel->getDataExchangeConfig();
        $this->generateSaveFile($data);
    }

    public function generateSaveFile($data) {
        if (!empty($data)) {
            $decriptFields = ['PAN', 'Aadhaar'];
            foreach ($data as $value) {
                $update_ids = [];
                try {
                    $output = \Yii::$app->general->getSpData($value->sp_name, []);
                    if (!empty($output)) {
                        $modelName = $value->tbl_name;
                        $model_name = Yii::$app->path->define($modelName);
                        $model = new $model_name();
                        $modelKey = $value->update_key;
                        $updateKey = $value->update_key_with;
                        $update_ids = array_column($output, $updateKey);
                        if (!empty($update_ids)) {
                            $model->updateAll(['data_post_status' => 1, 'picked_datetime' => date('Y-m-d H:i:s')], ['in', $modelKey, $update_ids]);
                        }
                        $name = 'DF_';
                        if($value->tbl_name == 'TblDcsProvisional'){
                            $name = 'VLCC_';
                        } else if($value->tbl_name == 'TblMemberProvisional'){
                            $name = 'Farmer_';
                        }
                        $fileName = $name . date('YmdHis') . '.xls';
                        $folder = \Yii::$app->params['sap_data_files'] . 'vendor-data/';
                        $path = str_replace(['\\', '//'], '/', Yii::getAlias('@webroot') . '/' . $folder);
                        if (\Yii::$app->general->checkDirectory($path)) {
                            $objPHPExcel = new Spreadsheet();
                            $sheet = $objPHPExcel->getActiveSheet();
                            $header = array_keys($output[0]);
                            $sheet->fromArray($header, NULL, 'A1');

                            $rowIdx = 2;
                            foreach ($output as $line) {
                                $processedLine = array_map(function($val, $key) use ($decriptFields) {
                                    return (in_array($key, $decriptFields) && !empty($val))
                                        ? \Yii::$app->general->decryptData($val)
                                        : $val;
                                }, $line, array_keys($line));
                                $sheet->fromArray($processedLine, NULL, 'A' . $rowIdx++);
                            }

                            $filePath = $path . $fileName;
                            $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
                            $objWriter->save($filePath);

                            $nextDate = date("Y-m-d H:i:s", strtotime("+{$value->interval} minutes"));
                            $value->updateAll(['last_execution' => date('Y-m-d H:i:s'), 'next_execution' => $nextDate], ['data_exchange_code' => $value->data_exchange_code]);
                            $ftp_model = new TblFtpTxnLog();

                            $logData = new TblFileCreator();
                            $logData->module_name = $value->tbl_name;
                            $logData->union_code = $value->union_code;
                            $logData->module_code = $value->union_code;

                            $res = $ftp_model->saveLogData($logData, $path, $fileName, count($output), false, 'vendor-data/', false, false, 'AWS', false, []);
                            if ($res && !empty($update_ids)) {
                                $model->updateAll(['data_post_status' => 2, 'response_datetime' => date('Y-m-d H:i:s'), 'resp_desc' => $fileName], ['in', $modelKey, $update_ids]);
                            } else {
                                $model->updateAll(['data_post_status' => 3, 'response_datetime' => date('Y-m-d H:i:s'), 'resp_desc' => 'Log entry failed'], ['in', $modelKey, $update_ids]);
                            }
                        }
                    }
                } catch (\Throwable $ex) {
                    if (isset($model) && isset($modelKey) && !empty($update_ids)) {
                        $model->updateAll(['data_post_status' => 3, 'response_datetime' => date('Y-m-d H:i:s'), 'resp_desc' => substr($ex->getMessage(), 0, 800)], ['in', $modelKey, $update_ids]);
                    }
                }
            }
        }
    }

    public function actionDownloadAcknowledgeFiles() {
        $config = TblDataExchangeConfig::find()->select(['union_code'])->where(['api_type' => 'AWS'])->distinct()->one();
        if (!empty($config)) {
            $ftpDetail = new TblFtpDetail();
            $ftpDetail->ftp_connection_code = $config->union_code . '_AWS';
            $ftpData = $ftpDetail->getData();

            $ftp = new FTPConnection();

            $ftp->ftp_type = $ftpData->ftp_type;
            $ftp->ftp_host = $ftpData->ftp_host;
            $ftp->ftp_username = $ftpData->ftp_username;
            $ftp->ftp_password = $ftpData->ftp_password;
            $ftp->ftp_port = $ftpData->ftp_port;
            $ftp->isPassiveFtp = (!empty($ftpData->ftp_mode) && $ftpData->ftp_mode == 'active') ? false : true;
            $ftp->conn_close = FALSE;
            $ftp->conn_init = FALSE;
            $ftp->make_dir = FALSE;

            try {
                $ftpFolders = ['Success/', 'Error/'];
                foreach ($ftpFolders as $subFolder) {
                    $ftp->ftp_path = $subFolder;
                    $files = $ftp->ListFile();

                    $folder = \Yii::$app->params['sap_data_files'] . $subFolder;
                    $localPath = rtrim(str_replace(['\\', '//'], '/', Yii::getAlias('@webroot') . '/' . $folder), '/') . '/';

                    if (!\Yii::$app->general->checkDirectory($localPath) || empty($files)) {
                        continue;
                    }

                    foreach ($files as $file) {
                        if ($file == '.' || $file == '..' || empty($file) || strpos($file, '~$') === 0) {
                            continue;
                        }
                        if (strpos($file, '.xls') !== false || strpos($file, '.xlsx') !== false) {
                            $ftp->file_name = $file;
                            $ftp->local_path = $localPath;
                            $ftp->ftp_path = $subFolder;
                            $existingLog = TblFtpTxnLog::find()
                                ->where(['ftp_type' => 'AWS', 'file_name' => $file, 'ftp_path' => $subFolder])
                                ->exists();
                            if ($ftp->DownloadFile()) {
                                $ftp->RenameFile($subFolder . $file, $subFolder . 'Archive/' . $file);
                                if (!$existingLog) {
                                    $ftpLog = new TblFtpTxnLog();
                                    $ftpLog->txn_type    = 'EIPL';
                                    $ftpLog->union_code  = $config->union_code;
                                    $ftpLog->module_name = 'Provisional';
                                    $ftpLog->module_code = $config->union_code;
                                    $ftpLog->file_name   = $file;
                                    $ftpLog->local_path  = $localPath . $file;
                                    $ftpLog->ftp_path    = $subFolder;
                                    $ftpLog->file_path   = $subFolder . $file;
                                    $ftpLog->ftp_type    = $ftpData->ftp_type;
                                    $ftpLog->ftp_host    = $ftpData->ftp_host;
                                    $ftpLog->ftp_username = $ftpData->ftp_username;
                                    $ftpLog->ftp_password = $ftpData->ftp_password;
                                    $ftpLog->ftp_port    = $ftpData->ftp_port;
                                    $ftpLog->txn_datetime = date('Y-m-d H:i:s');
                                    $ftpLog->file_status = 0;
                                    $ftpLog->status      = 0;
                                    $ftpLog->total_count = 0;
                                    $ftpLog->success_count = 0;
                                    $ftpLog->error_count = 0;
                                    $ftpLog->save(FALSE);
                                }
                            } else {
                                \Yii::error('[DownloadAcknowledge] Failed to download file: ' . $file . ' from ' . $subFolder, __METHOD__);
                            }
                        }
                    }
                }
            } catch (\Throwable $ex) {
                \Yii::error('[DownloadAcknowledge] Error: ' . $ex->getMessage(), __METHOD__);
            }
        }
    }

    public function actionProcessAcknowledgeFiles() {
        $dcsCtrl = new TblDcsProvisionalController('dcs-provisional', \Yii::$app->getModule('organisation'));
        $custCtrl = new TblCustomerMasterProvisionalController('customer-provisional', \Yii::$app->getModule('organisation'));

        $pendingLogs = TblFtpTxnLog::find()
            ->where(['ftp_type' => 'AWS', 'file_status' => 0, 'status' => 0])
            ->all();

        if (empty($pendingLogs)) {
            return;
        }

        $logIds = array_map(function($l) { return $l->ftp_txn_log_id; }, $pendingLogs);
        TblFtpTxnLog::updateAll(['status' => 1, 'pick_datetime' => date('Y-m-d H:i:s')], ['ftp_txn_log_id' => $logIds]);

        foreach ($pendingLogs as $ftpLog) {
            $filePath = $ftpLog->local_path;
            $fileName = $ftpLog->file_name;
            $subFolder = $ftpLog->ftp_path;

            $folder = \Yii::$app->params['sap_data_files'] . $subFolder;
            $localPath = rtrim(str_replace(['\\', '//'], '/', \Yii::getAlias('@webroot') . '/' . $folder), '/') . '/';
            $archivePath = $localPath . 'Archive/';
            \Yii::$app->general->checkDirectory($archivePath);

            if (!file_exists($filePath)) {
                $ftpLog->status = 3;
                $ftpLog->save(FALSE);
                \Yii::error('[ProcessAcknowledge] File not found on disk: ' . $filePath, __METHOD__);
                continue;
            }

            $allRowProcessed = true;
            $successCount = 0;
            $errorCount = 0;
            $targetStatus = (stripos($subFolder, 'Success') !== false) ? 2 : 3;

            try {
                $headerMap = [];
                $lookup = ['everesttoken' => 'token', 'type' => 'type', 'vendorcode' => 'vendor', 'message' => 'message'];
                $collectedData = [];
                $tokensByType = ['DCS' => [], 'Farmer' => [], 'Dairy Farm' => []];

                $objPHPExcel = IOFactory::load($filePath);
                $sheet = $objPHPExcel->getActiveSheet();
                $maxRow = $sheet->getHighestRow();
                $maxCol = $sheet->getHighestDataColumn();
                $headerRow = $sheet->rangeToArray('A1:' . $maxCol . '1', NULL, TRUE, FALSE)[0];

                if (!empty($headerRow)) {
                    foreach ($headerRow as $colIndex => $colName) {
                        $clean = strtr(strtolower(trim($colName)), [' ' => '']);
                        if (isset($lookup[$clean])) {
                            $headerMap[$lookup[$clean]] = $colIndex;
                        }
                    }
                }

                for ($rowIdx = 2; $rowIdx <= $maxRow; $rowIdx++) {
                    $row = $sheet->rangeToArray('A' . $rowIdx . ':' . $maxCol . $rowIdx, NULL, TRUE, FALSE)[0];
                    $token = isset($headerMap['token']) ? trim($row[$headerMap['token']] ?? '') : '';
                    if ($token !== '') {
                        $type = isset($headerMap['type']) ? trim($row[$headerMap['type']] ?? '') : '';
                        $collectedData[] = [
                            'token' => $token,
                            'type' => $type,
                            'vendor' => isset($headerMap['vendor']) ? trim($row[$headerMap['vendor']] ?? '') : '',
                            'message' => isset($headerMap['message']) ? trim($row[$headerMap['message']] ?? '') : ''
                        ];
                        if (isset($tokensByType[$type])) $tokensByType[$type][] = $token;
                    }
                }

                $models = [
                    'DCS' => empty($tokensByType['DCS']) ? [] : TblDcsProvisional::find()->where(['in', 'data_post_id', $tokensByType['DCS']])->indexBy('data_post_id')->all(),
                    'Farmer' => empty($tokensByType['Farmer']) ? [] : TblMemberProvisional::find()->where(['in', 'data_post_id', $tokensByType['Farmer']])->indexBy('data_post_id')->all(),
                    'Dairy Farm' => empty($tokensByType['Dairy Farm']) ? [] : TblCustomerMasterProvisional::find()->where(['in', 'data_post_id', $tokensByType['Dairy Farm']])->indexBy('data_post_id')->all(),
                ];

                foreach ($collectedData as $row) {
                    $model = $models[$row['type']][$row['token']] ?? null;
                    if (!$model) {
                        $allRowProcessed = false;
                        $errorCount++;
                        continue;
                    }

                    $processed = false;
                    if ($targetStatus == 3) {
                        $historyModel = NULL;
                        switch ($row['type']) {
                            case 'DCS':
                                $historyModel = new TblDcsProvisionalHistory();
                                break;
                            case 'Farmer':
                                $historyModel = new TblMemberProvisionalHistory();
                                break;
                            case 'Dairy Farm':
                                $historyModel = new TblCustomerMasterProvisionalHistory();
                                $model->scenario = 'post_sap_data';
                                break;
                        }
                        if ($historyModel) {
                            Yii::$app->operation->history($model, $historyModel, UPDATE);
                        }
                        $model->data_post_status = 3;
                        $model->resp_desc = $row['message'];
                        $processed = $this->generalModel->saveTransaction($historyModel ? [$model, $historyModel] : [$model], ['Acknowledgement Error', 'edit']);
                    } else {
                        switch ($row['type']) {
                            case 'DCS':
                                if ($model->dcs_status == 1) {
                                    $processed = true;
                                    break;
                                }
                                $historyModel = new TblDcsProvisionalHistory();
                                Yii::$app->operation->history($model, $historyModel, UPDATE);
                                $model->scenario = 'approveDcs';
                                $model->vendor = $model->vendor_code;
                                $model->dcs_status = 1;
                                $model->sap_vendor_code = $row['vendor'];
                                $all_doc = [];
                                $dcsdoc = [];
                                $msgArr = [];
                                $processed = ($dcsCtrl->createDcs($model, [$model, $historyModel], $all_doc, $dcsdoc, $msgArr) === 'customRedirect');
                                if ($processed) {
                                    $baseDir = \Yii::$app->basePath . '/' . \Yii::$app->params['document_upload'];
                                    $dcsDir = $baseDir . 'dcs';
                                    $proDcsDir = $baseDir . 'provisional_dcs';
                                    for ($i = 0; $i < count($all_doc); $i++) {
                                        $docFileName = basename($dcsdoc[$i]);
                                        $file = $dcsDir . '/' . $docFileName;
                                        if (file_exists($proDcsDir . '/' . $all_doc[$i])) {
                                            if (copy($proDcsDir . '/' . $all_doc[$i], $file)) {
                                                unlink($proDcsDir . '/' . $all_doc[$i]);
                                            }
                                        }
                                    }
                                }
                                break;

                            case 'Farmer':
                                if ($model->member_status == 1) {
                                    $processed = true;
                                    break;
                                }
                                $historyModel = new TblMemberProvisionalHistory();
                                Yii::$app->operation->history($model, $historyModel, UPDATE);
                                $model->vendor_code = $row['vendor'];
                                $modelSave = [$historyModel];
                                $deleteModelList = [];
                                $unlink_files = [];
                                $attachments = [];
                                $masterdoc = [];
                                $errors = [];
                                $model->setChildTableSaveDelete($model, $modelSave, $deleteModelList, $unlink_files, $attachments, $masterdoc, $errors);
                                $processed = !empty($modelSave) && ($this->generalModel->saveDeleteTransaction([$model], $modelSave, $deleteModelList, ['Member Creation', 'create']) === 'customRedirect');
                                if ($processed) {
                                    $model->moveFiles($unlink_files, $attachments, $masterdoc);
                                }
                                break;

                            case 'Dairy Farm':
                                if ($model->customer_status == 1) {
                                    $processed = true;
                                    break;
                                }
                                $historyModel = new TblCustomerMasterProvisionalHistory();
                                Yii::$app->operation->history($model, $historyModel, UPDATE);
                                $model->customer_status = 1;
                                $model->sap_vendor_code = $row['vendor'];
                                $saveArr = [$model, $historyModel];
                                $all_doc = [];
                                $customerdoc = [];
                                $msgArr = [];
                                $custCtrl->createCustomer($model, $saveArr, $all_doc, $customerdoc, $msgArr);
                                $processed = !empty($saveArr) && ($this->generalModel->saveTransaction($saveArr, ['Customer Creation', 'create']) === 'customRedirect');
                                if ($processed) {
                                    $baseDir = \Yii::$app->basePath . '/' . \Yii::$app->params['document_upload'];
                                    $customerDir = $baseDir . 'customer';
                                    $proCustomerDir = $baseDir . 'provisional_customer';
                                    for ($i = 0; $i < count($all_doc); $i++) {
                                        $docFileName = basename($customerdoc[$i]);
                                        $file = $customerDir . '/' . $docFileName;
                                        if (file_exists($proCustomerDir . '/' . $all_doc[$i])) {
                                            if (copy($proCustomerDir . '/' . $all_doc[$i], $file)) {
                                                unlink($proCustomerDir . '/' . $all_doc[$i]);
                                            }
                                        }
                                    }
                                }
                                break;
                        }
                    }

                    if ($processed) {
                        $successCount++;
                    } else {
                        $allRowProcessed = false;
                        $errorCount++;
                    }
                }

                $ftpLog->total_count   = count($collectedData);
                $ftpLog->success_count = $successCount;
                $ftpLog->error_count   = $errorCount;
                $ftpLog->file_status   = $allRowProcessed ? 1 : 0;
                $ftpLog->status        = $allRowProcessed ? 2 : 3;
                $ftpLog->save(FALSE);

                if ($allRowProcessed) {
                    rename($filePath, $archivePath . $fileName);
                }
            } catch (\Exception $ex) {
                $allRowProcessed = false;
                $ftpLog->status = 3;
                $ftpLog->save(FALSE);
                \Yii::error('[ProcessAcknowledge] File: ' . $fileName . ' | Error: ' . $ex->getMessage() . ' | Line: ' . $ex->getLine(), __METHOD__);
            }
        }
    }
}
