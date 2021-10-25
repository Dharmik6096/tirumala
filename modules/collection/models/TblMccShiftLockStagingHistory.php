<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_mcc_shift_lock_staging_history".
 *
 * @property integer $id
 * @property string $staging_code
 * @property string $shift_lock_code
 * @property string $mcc_plant_code
 * @property string $shift_code
 * @property string $date_time_of_collection
 * @property string $qty
 * @property string $avg_fat
 * @property string $avg_snf
 * @property string $amount
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property integer $data_post_status
 * @property string $picked_datetime
 * @property string $resp_status
 * @property string $resp_desc
 * @property string $response_datetime
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 */
class TblMccShiftLockStagingHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_shift_lock_staging_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['staging_code'], 'safe'],
            [['date_time_of_collection', 'created_at', 'updated_at', 'picked_datetime', 'response_datetime', 'history_created_at'], 'safe'],
            [['qty', 'avg_fat', 'avg_snf', 'amount'], 'safe'],
            [['originating_type', 'data_post_status'], 'safe'],
            [['staging_code'], 'safe'],
            [['shift_lock_code'], 'safe'],
            [['mcc_plant_code'], 'safe'],
            [['shift_code'], 'safe'],
            [['created_by', 'updated_by', 'history_created_by'], 'safe'],
            [['originating_org_code', 'originating_org_type'], 'safe'],
            [['resp_status', 'resp_desc'], 'safe'],
            [['operation_type'], 'safe'],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'staging_code' => Yii::t('app', 'Statging Code'),
            'shift_lock_code' => Yii::t('app', 'Shift Lock Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'qty' => Yii::t('app', 'Qty'),
            'avg_fat' => Yii::t('app', 'Avg Fat'),
            'avg_snf' => Yii::t('app', 'Avg Snf'),
            'amount' => Yii::t('app', 'Amount'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'data_post_status' => Yii::t('app', 'Data Post Status'),
            'picked_datetime' => Yii::t('app', 'Picked Datetime'),
            'resp_status' => Yii::t('app', 'Resp Status'),
            'resp_desc' => Yii::t('app', 'Resp Desc'),
            'response_datetime' => Yii::t('app', 'Response Datetime'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }

}
