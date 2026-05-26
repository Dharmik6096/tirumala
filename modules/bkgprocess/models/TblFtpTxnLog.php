<?php

namespace app\modules\bkgprocess\models;

use Yii;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblUnions;
use app\modules\bkgprocess\models\TblFileCreator;
use yii\db\ActiveQuery;
use app\modules\organisation\models\TblDcs;
use app\modules\bkgprocess\Bkgprocess;
use app\components\FTPConnection;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\sms\models\TblApiMaster;
use yii\helpers\Url;
use app\modules\sms\models\TblAlertNotification;
use app\modules\details\models\TblContactDetails;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * This is the model class for table "tbl_ftp_txn_log".
 *
 * @property integer $ftp_txn_log_id
 * @property string $txn_type
 * @property string $file_path
 * @property integer $total_count
 * @property integer $success_count
 * @property integer $error_count
 * @property string $module_name
 * @property string $module_code
 * @property string $mcc_plant_code
 * @property string $union_code
 * @property string $txn_datetime
 * @property string $created_at
 * @property string $created_by
 * @property string $local_path
 * @property string $ftp_type
 * @property string $ftp_host
 * @property string $ftp_username
 * @property string $ftp_password
 * @property string $ftp_port
 * @property string $ftp_path
 * @property integer $file_status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $file_name
 * @property integer $status
 * @property string $old_file_path
 * @property string $old_local_path
 * @property integer $file_creator_id
 */
class TblFtpTxnLog extends \app\models\ChildModel {

    public $f_plant_code, $f_mcc_code, $f_union_code, $from_date, $to_date, $from_shift, $to_shift;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_ftp_txn_log';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['file_status', 'status'], 'default', 'value' => 0],
            [['txn_type'], 'default', 'value' => 'EIPL'],
            [['txn_type', 'file_path', 'module_name', 'module_code', 'mcc_plant_code', 'union_code', 'created_by', 'local_path', 'ftp_type', 'ftp_host', 'ftp_username', 'ftp_password', 'ftp_port', 'ftp_path', 'updated_by', 'file_name', 'old_file_path', 'old_local_path', 'zip_filename', 'file_date'], 'safe'],
            [['total_count', 'success_count', 'error_count', 'file_status', 'status', 'file_creator_id'], 'safe'],
            [['txn_datetime', 'created_at', 'updated_at', 'ref_code', 'pick_datetime', 'ftp_mode'], 'safe'],
            [['file_name'], 'unique', 'targetAttribute' => ['txn_type', 'file_name'], 'on' => 'EKOMILKZIP', 'message' => Yii::t('app/validation', 'File already uploaded')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'ftp_txn_log_id' => Yii::t('app', 'Ftp Txn Log ID'),
            'txn_type' => Yii::t('app', 'Txn Type'),
            'file_path' => Yii::t('app', 'File Path'),
            'total_count' => Yii::t('app', 'Total Count'),
            'success_count' => Yii::t('app', 'Success Count'),
            'error_count' => Yii::t('app', 'Error Count'),
            'module_name' => Yii::t('app', 'File Type'),
            'module_code' => Yii::t('app', 'Module Code'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'union_code' => Yii::t('app', 'Union'),
            'txn_datetime' => Yii::t('app', 'Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'local_path' => Yii::t('app', 'Local Path'),
            'ftp_type' => Yii::t('app', 'Ftp Type'),
            'ftp_host' => Yii::t('app', 'Ftp Host'),
            'ftp_username' => Yii::t('app', 'Ftp Username'),
            'ftp_password' => Yii::t('app', 'Ftp Password'),
            'ftp_port' => Yii::t('app', 'Ftp Port'),
            'ftp_path' => Yii::t('app', 'Ftp Path'),
            'file_status' => Yii::t('app', 'File Status'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'file_name' => Yii::t('app', 'File Name'),
            'status' => Yii::t('app', 'Status'),
            'old_file_path' => Yii::t('app', 'Old File Path'),
            'old_local_path' => Yii::t('app', 'Old Local Path'),
            'file_creator_id' => Yii::t('app', 'File Creator ID'),
            'zip_filename' => Yii::t('app', 'Zip File Name'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblFtpTxnLogQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblFtpTxnLogQuery(get_called_class());
    }

    public function exportData($data_array, $title = '', $output = [], $mccRefCode = '', $email = false, $ftp_upload = TRUE, $recall = false) {
        $eiplCode = Yii::$app->session->get('eiplCode');
        if (empty($eiplCode)) {
            $mccModelData = TblUnions::find()->where(['union_code' => $data_array['union_code']])->one();
            $eiplCode = !empty($mccModelData) ? $mccModelData->eipl_code : $eiplCode;
        }
        $data = new TblFileCreator();
        $data->attributes = $data_array;
        $txn = new TblFtpTxnLog();
        $FTPProcess = Bkgprocess::FTPProcess()[$data->module_name];
        if (empty($output)) {
            $param = explode(',', $FTPProcess['param']);
            $controls = [];
            foreach ($param as $key => $val) {
                $controls[$val] = $data_array[$val];
            }
            $output = \Yii::$app->general->getSpData($FTPProcess['sp_name'], $controls);
            $downLoadArray = [];
            foreach ($output as $detail) {
                $plant = ($data_array['module_name'] == 'TblBmcCollection' || $data_array['module_name'] == 'TblBmcCollectionWqSd' || $data_array['module_name'] == 'TblBmcCollection_collection' || $data_array['module_name'] == 'TblBmcCollection_dispatch') ? 'Plant Code' : (($data_array['module_name'] == 'TblBmcCollection_dodla_WQ') ? 'PLANT_CODE' : (($data_array['module_name'] == 'TblMilkCollection_cdpl_VM') ? 'Agent_Code' : (($data_array['module_name'] == 'TblBmcCollection_Ananda') ? 'MCC' : 'Plant')));
                if (!empty($detail[$plant]) && strtolower($detail[$plant]) != 'total') {
                    if (empty($downLoadArray[$detail[$plant]])) {
                        $downLoadArray[$detail[$plant]] = [];
                    }
                    $downLoadArray[$detail[$plant]][] = $detail;
                }
            }
            foreach ($downLoadArray as $bmc => $download) {
                if ($eiplCode == 'JERSEY') {
                    $report_type = 'VMCC';
                    $mccCode = (!empty($download[0]) && !empty($download[0]['Plant_Code'])) ? $download[0]['Plant_Code'] : $data_array['mcc_plant_code'];
                    $title = $mccCode . '_' . $bmc . '_' . $report_type . '_' . str_replace('-', '_', Yii::$app->controls->view_date($data_array['from_date'])) . '_' . $data_array['shift_code'];
                } elseif ($eiplCode == 'DODLA') {
                    $report_type = ($data_array['module_name'] == 'TblBmcCollection_dodla_WQ') ? 'WQ' : 'VM';
                    $title = $bmc . '_' . $report_type . '_' . str_replace('-', '_', Yii::$app->controls->view_date($data_array['from_date'])) . '_' . $data_array['shift_code'];
                } else if ($eiplCode == 'ANANDA') {
                    $FTPProcess['ftp_path'] .= 'Mcc' . $bmc;
                    $report_type = 'RMRD';
                    $collection_date = (!empty($download[0]) && !empty($download[0]['Collection_Date'])) ? $download[0]['Collection_Date'] : $data_array['from_date'];
                    $title = $report_type . '_' . $bmc . '_' . str_replace('-', '_', Yii::$app->controls->view_date($collection_date, 'php:dmY')) . '_' . date('His') . '_' . $data_array['shift_code'];
                } else {
                    $report_type = ($data_array['module_name'] == 'TblBmcCollection' || $data_array['module_name'] == 'TblBmcCollectionWqSd' || $data_array['module_name'] == 'TblBmcCollection_collection' || $data_array['module_name'] == 'TblBmcCollection_dispatch') ? 'WQ' : 'SD';
                    $title = $bmc . '_' . $report_type . '_' . str_replace('-', '_', Yii::$app->controls->view_date($data_array['from_date'])) . '_' . $data_array['shift_code'];
                }
                $txn->ref_code = $bmc;
                if (!empty($data_array['union_code'])) {
                    $txn->union_code = $data_array['union_code'];
                    $bmc_data = $txn->unionBmcCode;
                } else {
                    $bmc_data = $txn->bmcCode;
                }
                if (!empty($bmc_data)) {
                    $data->module_code = $bmc_data->bmc_code;
                    $data->mcc_plant_code = $bmc_data->mcc_plant_code;
                }
                $this->generateFiles($download, $FTPProcess, $data, $ftp_upload, $title, $mccRefCode, $email, $recall);
            }
        } else {
            $txn->ref_code = $data_array['module_code'];
            if (!empty($data_array['union_code'])) {
                $txn->union_code = $data_array['union_code'];
                $bmc_data = $txn->unionBmcCode;
            } else {
                $bmc_data = $txn->bmcCode;
            }
            if (!empty($bmc_data)) {
                $data->module_code = $bmc_data->bmc_code;
                $data->mcc_plant_code = $bmc_data->mcc_plant_code;
                $data->union_code = $bmc_data->union_code;
            }
            return $this->generateFiles($output, $FTPProcess, $data, $ftp_upload, $title, $mccRefCode, $email, $recall);
        }
    }

    public function generateFiles($output, $FTPProcess, $data, $ftp_upload = FALSE, $title = '', $mccRefCode = '', $email = FALSE, $recall = FALSE) {
        $name_formate = explode('+', $FTPProcess['export_title']);
        $fileName = $title;
        if (empty($fileName)) {
            foreach ($name_formate as $k => $v) {
                $name_part_array = explode(':', $v);
                $name_part = $name_part_array[0];
                $val = isset($data->{$name_part}) ? $data->{$name_part} : $name_part;
                if (isset($name_part_array[1]) && $name_part_array[1] == 'date') {
                    $val = str_replace('-', '_', Yii::$app->controls->view_date($val));
                }
                $fileName .= $val;
            }
        }
        $fileName .= $FTPProcess['ext'];
        $filePath = $FTPProcess['file_path'];
        $ftpPath = !empty($mccRefCode) ? $mccRefCode : $FTPProcess['ftp_path'];

        $implode_char = isset($FTPProcess['implode_char']) ? $FTPProcess['implode_char'] : ',';
        $append_ftp_path = isset($FTPProcess['append_ftp_path']) ? TRUE : FALSE;
        $skip_header = isset($FTPProcess['skip_header']) ? TRUE : FALSE;
        $append_ftp_collection_code = isset($FTPProcess['append_ftp_collection_code']) ? $FTPProcess['append_ftp_collection_code'] : '';

        /** csv generate * */
        if (!empty($output) && Yii::$app->general->checkDirectory($filePath)) {
            if ($FTPProcess['ext'] == '.xml') {
                $xmlTags = isset($FTPProcess['xml_tag']) ? $FTPProcess['xml_tag'] : 'MT_RMRD_File_SND,Header';
                $xmlContent = $this->convertArrayToXml($output, $xmlTags);
                file_put_contents($filePath . $fileName, $xmlContent);
            } else if (Yii::$app->session->get('eiplCode') == 'DODLA') {
                $objPHPExcel = new Spreadsheet();
                $sheet = $objPHPExcel->getActiveSheet();
                $sheet->setTitle('Sheet1');
                $sheet->fromArray(
                        array_keys($output[0]), // The data to set
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
                $successfilePath = $filePath . $fileName;
                $objWriter = IOFactory::createWriter($objPHPExcel, ($FTPProcess['ext'] == '.xlsx') ? IOFactory::WRITER_XLSX : IOFactory::WRITER_XLS);
                $objWriter->save($successfilePath);
            } else {
                $header = array_keys($output[0]);
                $txt_file = fopen($filePath . $fileName, "w");
                if (!$skip_header) {
                    fwrite($txt_file, implode($implode_char, $header) . PHP_EOL);
                }
                foreach ($output as $line) {
                    fwrite($txt_file, implode($implode_char, $line) . PHP_EOL);
                }
                fclose($txt_file);
            }
            //$data->save();
            return $this->saveLog($data, $filePath, $fileName, count($output), $ftp_upload, $ftpPath, $email, $append_ftp_path, $append_ftp_collection_code, $recall);
        }
        return FALSE;
        /** csv generate * */
        /** xlsx generate * */
        /*
          $header = array_keys($output[0]);
          $objPHPExcel = new Spreadsheet();
          $objPHPExcel->setActiveSheetIndex(0);
          $objPHPExcel->getDefaultStyle()
          ->getNumberFormat()
          ->setFormatCode(
          \PHPExcel_Style_NumberFormat::FORMAT_TEXT
          );
          $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
          $objPHPExcel->getActiveSheet()->getProtection()->setPassword("password");

          $column = 'A';
          $rowCount = 1;
          foreach ($header as $k => $v) {
          $objPHPExcel->getActiveSheet()->SetCellValue($column . $rowCount, $v);
          $column++;
          }
          $rowCount++;
          foreach ($output as $res) {
          $column = 'A';
          foreach ($res as $k => $v) {
          if (in_array($k, ['Net_Wt', 'FAT', 'CLR', 'SNF'])) {
          $objPHPExcel->getActiveSheet()->getStyle($column . $rowCount)
          ->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
          }
          $objPHPExcel->getActiveSheet()->SetCellValue($column . $rowCount, $v);
          $column++;
          }
          $rowCount++;
          }
          $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
          $filePath = $FTPProcess['file_path'];
          if (Yii::$app->general->checkDirectory($filePath)) {
          $objWriter->save($filePath . $fileName);
          return $this->saveLog($data, $filePath, $fileName, count($output));
          } */
        /** xlsx generate * */
    }

    private function saveLog($data, $filePath, $fileName, $count, $ftp_upload, $ftpPath, $email, $append_ftp_path, $append_ftp_collection_code, $recall, $ftpDetails = []) {
        if (!empty($ftpDetails)) {
            $ftpData = $ftpDetails;
        } else {
            $ftpDetail = new TblFtpDetail();
            $ftpDetail->ftp_connection_code = !empty($append_ftp_collection_code) ? $data->union_code . '_' . $append_ftp_collection_code : $data->union_code;
            $ftpData = $ftpDetail->getData();
        }
        if (!empty($ftpData)) {
            $ftp_file_path = (empty($ftpPath) ? $ftpData->ftp_path : ($append_ftp_path ? $ftpData->ftp_path . $ftpPath : $ftpPath));
            $ftp_file = new TblFtpTxnLog();
            $ftp_file->attributes = $ftpData->attributes;
            if (!empty($ftpDetails)) {
                $ftp_file->setAttributes($data);
                $ftp_file->ftp_host = !empty($ftpData->ftp_host) ? $ftpData->ftp_host : '';
            } else {
                $ftp_file->attributes = $data->attributes;
            }
            $ftp_file->total_count = $ftp_file->success_count = $count;
            $ftp_file->txn_datetime = date('Y-m-d H:i:s');
            $ftp_file->file_path = $ftp_file_path . '/' . $fileName;
            $ftp_file->file_name = $fileName;
            $ftp_file->local_path = $filePath . $fileName;
            $ftp_file->updated_at = NULL;
            $ftp_file->file_status = $ftp_file->status = 0;
            $ftp_file->ftp_path = $ftp_file_path;

            if ($ftp_upload) {
                $ftp = new FTPConnection();
                $ftp->ftp_type = $ftp_file->ftp_type;
                $ftp->ftp_host = $ftp_file->ftp_host;
                $ftp->ftp_username = $ftp_file->ftp_username;
                $ftp->ftp_password = $ftp_file->ftp_password;
                $ftp->ftp_port = $ftp_file->ftp_port;
                $ftp->conn_init = FALSE;
                $ftp->conn_close = FALSE;
                $ftp->make_dir = FALSE;
                $ftp->isPassiveFtp = !empty($ftpData->ftp_mode) && $ftpData->ftp_mode == 'active' ? false : true;
                $connection = $ftp->ConnectServer();
                $ftp_file->status = 3;
                $ftp_file->file_status = 0;
                if ($connection) {
                    $ftp_path = explode('/', $ftp_file->file_path);
                    unset($ftp_path[count($ftp_path) - 1]);
                    $ftp_path = implode('/', $ftp_path);
                    $local_path = explode('/', $ftp_file->local_path);
                    unset($local_path[count($local_path) - 1]);
                    $local_path = implode('/', $local_path);
                    $file_name = $ftp_file->file_name;
                    $ftp->ftp_path = $ftp_path;
                    $ftp->local_path = $local_path . '/';
                    $ftp->file_name = $file_name;
                    if ($ftp->UploadFile()) {
                        $ftp_file->status = 2;
                        $ftp_file->file_status = 1;
                        Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                            'message' => \Yii::t('app', 'FTP Files Uploaded Successfully.')]);
                        if ($email) {
                            $this->GenerateMail($data->mcc_plant_code, $fileName);
                        }
                    }
                    $ftp->CloseConnection();
                }
                $ftp_file->save();
                if (($recall) && ($ftp_file->status == 3)) {
                    return FALSE;
                }
                return $fileName;
            }

            if ($ftp_file->save()) {
                /* $model_name = Yii::$app->path->define($data->module_name);
                  $model = new $model_name();
                  //$model->updateAll(['t.tag_2' => 'A', 't.ftp_txn_file_name' => $fileName], ['tbl_dcs.mcc_plant_code' => $data->mcc_plant_code, 't.date_time_of_collection' => $data->applicable_date])
                  //      ->innerJoin('tbl_dcs', 'tbl_dcs.dcs_code = t.dcs_code');
                  $table = $model->tableSchema->name;
                  $query = "UPDATE t SET t.tag_2='A',t.ftp_txn_file_name='" . $fileName . "'"
                  . " from " . $table . " t inner join tbl_dcs d on d.dcs_code=t.dcs_code"
                  . " where d.mcc_plant_code='" . $data->mcc_plant_code . "' and t.date_time_of_collection='" . $data->applicable_date . "'";
                  $connection = \Yii::$app->db;
                  $command = $connection->createCommand($query);
                  $command->execute(); */
                return $fileName;
            }
        }
        return FALSE;
    }

    private function convertArrayToXml($output, $tags) {
        $tags = explode(',', $tags);
        $rootTag = array_shift($tags);
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><' . $rootTag . '></' . $rootTag . '>');
        foreach ($output as $rowData) {
            $currentNode = $xml;
            for ($i = 0; $i < count($tags); $i++) {
                $currentNode = $currentNode->addChild($tags[$i]);
            }
            foreach ($rowData as $key => $value) {
                $currentNode->addChild($key, htmlspecialchars($value !== null ? $value : ''));
            }
        }
        return $xml->asXML();
    }

    public function getPickRecords($ids = [], $limit = 100) {
        $datetime = date('Y-m-d H:i:s', strtotime('-1 hour'));

        $query = $this->find()
                ->where(['file_status' => $this->file_status, 'txn_type' => $this->txn_type, 'status' => $this->status]);
        if (!empty($ids)) {
            $query->andWhere(['ftp_txn_log_id' => $ids]);
        } else {
            $query->limit($limit);
        }
        $query->orderBy(['ftp_host' => SORT_ASC, 'ftp_username' => SORT_ASC, 'ftp_password' => SORT_ASC, 'ftp_port' => SORT_ASC, 'ftp_type' => SORT_ASC]);

        $pendingDataQuery = $this->find()
                ->where(['file_status' => $this->file_status, 'txn_type' => $this->txn_type, 'status' => 1])
                ->andWhere(['<', 'tbl_ftp_txn_log.pick_datetime', $datetime])
                ->orderBy(['ftp_host' => SORT_ASC, 'ftp_username' => SORT_ASC, 'ftp_password' => SORT_ASC, 'ftp_port' => SORT_ASC, 'ftp_type' => SORT_ASC])
                ->limit(10);

        return $unionQuery = (new ActiveQuery(TblFtpTxnLog::className()))->from([
                    'pending_data' => $query->union($pendingDataQuery, TRUE)
                ])->all();
    }

    public function updateFileStatus($value) {
        return $this->updateAll(['status' => 1, 'pick_datetime' => date('Y-m-d H:i:s')], ['ftp_txn_log_id' => $value]);
    }

    public function getmccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getunionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getCreatorId() {
        return $this->hasOne(TblFileCreator::className(), ['file_creator_id' => 'file_creator_id']);
    }

    public function getExistFileData() {
        return $this->find()
                        ->where(['file_name' => $this->file_name, 'txn_type' => $this->txn_type, 'file_status' => $this->file_status, 'file_path' => $this->file_path])
                        ->one();
    }

    public function CheckDirectory() {
        return $this->find()->where(['status' => 2, 'module_code' => $this->module_code, 'txn_type' => $this->txn_type])->count();
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'module_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['ref_code' => 'ref_code']);
    }

    public function GenerateMail($mcc, $FileName) {
        $contactModelData = TblContactDetails::find()->where(['module_code' => $mcc, 'module_name' => 'mccPlant', 'is_active' => 1, 'is_default' => 1])->one();
        if (!empty($contactModelData) && !empty($contactModelData->email_to) && !empty($FileName)) {
            $apiMaster = new TblApiMaster();
            $apiMaster->receiver_type = 'EMAIL';
            $apiMasterData = $apiMaster->getAPI();
            if (!empty($apiMasterData)) {
                $htmlContent = "";
                $message = "";
                $file_name = "";
                $file_path = "";
                $absoluteBaseUrl = Url::base(true);
                $path = str_replace('\\', '/', realpath(\Yii::$app->basePath . '/../')) . \Yii::$app->params['FTPDirPath'] . 'upload/';
//                $path = $absoluteBaseUrl . \Yii::$app->params['FTPDirPath'] . 'upload/';
                $file_name = $FileName;
                $file_path = $path . $file_name;
                $this->setHtmlContent($mcc, $htmlContent, $message);
                $notificationModel = new TblAlertNotification();
                $notificationModel->receiver_type = 'EMAIL';
                $notificationModel->message = $htmlContent;
                $notificationModel->header_info = $message;
                $notificationModel->send_status = 0;
                $notificationModel->content_id = $apiMasterData->api_master_id;
//                $notificationModel->refecence_code = $appModel->purchase_rate_code;
                $notificationModel->module_type = "FTP Upload";
                $notificationModel->entry_datetime = date('Y-m-d H:i:s');
                $notificationModel->send_mail = 1;
                $notificationModel->receiver_detail = $contactModelData->email_to; //'procurement@dodladairy.com,ccpdr@dodladairy.com';
                $notificationModel->other_receiver_detail = $contactModelData->email_cc;
                $notificationModel->filename = $file_name;
                $notificationModel->file_path = $file_path;
                $notificationModel->has_attachment = 2;
                $notificationModel->save();
            }
        }
    }

    public function setHtmlContent($mcc, &$htmlContent, &$message) {
        $mccModelData = TblMccPlant::find()->where(['mcc_plant_code' => $mcc])->one();
        $message = 'RMRD Report (' . $mccModelData->ref_code . '-' . $mccModelData->name . ')';
        $baseUrl = Yii::$app->request->baseUrl;
        $hostUrl = Url::base('http');
        $hostUrl = str_replace($baseUrl, '', $hostUrl);
        $htmlContent = '<p>Dear Sir, <br/><br/>';
        $htmlContent .= '<br/><br/>Please find the attached ' . $mccModelData->ref_code . '-' . $mccModelData->name . ' RMRD files. </p>';
        $htmlContent .= '<br/><br/>';
        $htmlContent .= '<p>Regards,';
        //        $htmlContent .= '<br/>Everest Instrument Pvt. Ltd.</p>';
    }

    public function UserMatchingRecords($user, $txntype) {
        return $this->find()->Where(['status' => 0, 'created_by' => $user, 'txn_type' => $txntype, 'module_name' => 'TblMilkCollection'])->count();
    }

    public function saveLogData($data, $filePath, $fileName, $count, $ftp_upload, $ftpPath, $email, $append_ftp_path, $append_ftp_collection_code, $recall, $ftpDetails) {
        return $this->saveLog($data, $filePath, $fileName, $count, $ftp_upload, $ftpPath, $email, $append_ftp_path, $append_ftp_collection_code, $recall, $ftpDetails);
    }

    public function getUnionBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['ref_code' => 'ref_code', 'union_code' => 'union_code']);
    }

}
