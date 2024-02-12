<?php

namespace app\modules\configuration\models;

use Yii;
use app\modules\usermanagement\models\User;

/**
 * This is the model class for table "tbl_report_txn_log".
 *
 * @property integer $report_txn_log_id
 * @property string $report_type
 * @property string $report_title
 * @property string $sp_name_or_report_path
 * @property string $input_param
 * @property string $search_param
 * @property string $export_file_name
 * @property string $file_type
 * @property string $file_name
 * @property string $file_path
 * @property string $user_code
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $status
 * @property string $pick_datetime
 * @property string $cron_pick_datetime
 * @property string $response_datetime
 * @property string $response_msg
 */
class TblReportTxnLog extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_report_txn_log';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['created_at', 'updated_at', 'pick_datetime', 'cron_pick_datetime', 'response_datetime', 'report_type', 'report_title',
            'sp_name_or_report_path', 'input_param', 'search_param', 'export_file_name', 'file_type', 'file_name', 'file_path',
            'user_code', 'union_code', 'created_by', 'updated_by', 'response_msg', 'status', 'decrypt_data'], 'safe'],
            [['status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'report_txn_log_id' => Yii::t('app', 'Report Txn Log ID'),
            'report_type' => Yii::t('app', 'Report Type'),
            'report_title' => Yii::t('app', 'Report Title'),
            'sp_name_or_report_path' => Yii::t('app', 'Sp Name Or Report Path'),
            'input_param' => Yii::t('app', 'Input Param'),
            'search_param' => Yii::t('app', 'Search Param'),
            'export_file_name' => Yii::t('app', 'Export File Name'),
            'file_type' => Yii::t('app', 'File Type'),
            'file_name' => Yii::t('app', 'File Name'),
            'file_path' => Yii::t('app', 'File Path'),
            'user_code' => Yii::t('app', 'User Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'status' => Yii::t('app', 'Status'),
            'pick_datetime' => Yii::t('app', 'Pick Datetime'),
            'cron_pick_datetime' => Yii::t('app', 'Cron Pick Datetime'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'response_msg' => Yii::t('app', 'Response Msg'),
            'decrypt_data' => Yii::t('app', 'Decrypt Data'),
        ];
    }

    public function getUserCode() {
        return $this->hasOne(User::className(), ['user_code' => 'user_code']);
    }

}
