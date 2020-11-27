<?php

namespace app\modules\bkgprocess\models;

use Yii;
use PHPExcel;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblUnions;
use app\modules\bkgprocess\models\TblFileCreator;
use yii\db\ActiveQuery;

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
            [['txn_type', 'file_path', 'module_name', 'module_code', 'mcc_plant_code', 'union_code', 'created_by', 'local_path', 'ftp_type', 'ftp_host', 'ftp_username', 'ftp_password', 'ftp_port', 'ftp_path', 'updated_by', 'file_name', 'old_file_path', 'old_local_path'], 'string'],
            [['total_count', 'success_count', 'error_count', 'file_status', 'status', 'file_creator_id'], 'integer'],
            [['txn_datetime', 'created_at', 'updated_at', 'ref_code', 'pick_datetime'], 'safe'],
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
            'txn_datetime' => Yii::t('app', 'Txn Datetime'),
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
        ];
    }

    /**
     * @inheritdoc
     * @return TblFtpTxnLogQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblFtpTxnLogQuery(get_called_class());
    }

    public function generateFiles($output, $FTPProcess, $data) {
        $name_formate = explode('+', $FTPProcess['export_title']);
        $fileName = '';
        foreach ($name_formate as $k => $v) {
            $name_part_array = explode(':', $v);
            $name_part = $name_part_array[0];
            $val = isset($data->{$name_part}) ? $data->{$name_part} : $name_part;
            if (isset($name_part_array[1]) && $name_part_array[1] == 'date') {
                $val = str_replace('-', '_', Yii::$app->controls->view_date($val));
            }
            $fileName .= $val;
        }
        $fileName .= $FTPProcess['ext'];
        $filePath = $FTPProcess['file_path'];
        /** csv generate * */
        if (!empty($output) && Yii::$app->general->checkDirectory($filePath)) {
            $header = array_keys($output[0]);
            $txt_file = fopen($filePath . $fileName, "w");
            fwrite($txt_file, implode(',', $header) . PHP_EOL);
            foreach ($output as $line) {
                fwrite($txt_file, implode(',', $line) . PHP_EOL);
            }
            fclose($txt_file);
            return $this->saveLog($data, $filePath, $fileName, count($output));
        }
        return FALSE;
        /** csv generate * */
        /** xlsx generate * */
        /*
          $header = array_keys($output[0]);
          $objPHPExcel = new PHPExcel();
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

    private function saveLog($data, $filePath, $fileName, $count) {
        $ftpDetail = new TblFtpDetail();
        $ftpDetail->ftp_connection_code = $data->union_code;
        $ftpData = $ftpDetail->getData();
        if (!empty($ftpData)) {
            $ftp_file = new TblFtpTxnLog();
            $ftp_file->attributes = $ftpData->attributes;
            $ftp_file->attributes = $data->attributes;
            $ftp_file->total_count = $ftp_file->success_count = $count;
            $ftp_file->txn_datetime = date('Y-m-d H:i:s');
            $ftp_file->file_path = $ftpData->ftp_path . '/' . $fileName;
            $ftp_file->file_name = $fileName;
            $ftp_file->local_path = $filePath . $fileName;
            $ftp_file->updated_at = NULL;
            $ftp_file->file_status = $ftp_file->status = 0;
            if ($ftp_file->save()) {
                $model_name = Yii::$app->path->define($data->module_name);
                $model = new $model_name();
                //$model->updateAll(['t.tag_2' => 'A', 't.ftp_txn_file_name' => $fileName], ['tbl_dcs.mcc_plant_code' => $data->mcc_plant_code, 't.date_time_of_collection' => $data->applicable_date])
                //      ->innerJoin('tbl_dcs', 'tbl_dcs.dcs_code = t.dcs_code');
                $table = $model->tableSchema->name;
                $query = "UPDATE t SET t.tag_2='A',t.ftp_txn_file_name='" . $fileName . "'"
                        . " from " . $table . " t inner join tbl_dcs d on d.dcs_code=t.dcs_code"
                        . " where d.mcc_plant_code='" . $data->mcc_plant_code . "' and t.date_time_of_collection='" . $data->applicable_date . "'";
                $connection = \Yii::$app->db;
                $command = $connection->createCommand($query);
                $command->execute();
                return TRUE;
            }
        }
        return FALSE;
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

}
