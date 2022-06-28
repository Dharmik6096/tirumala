<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\organisation\models\TblMccPlant;
use app\modules\dcsoperation\models\TblShift;
use app\modules\collection\models\TblMccShiftLock;

/**
 * This is the model class for table "tbl_mcc_shift_lock_staging".
 *
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
 */
class TblMccShiftLockStaging extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_shift_lock_staging';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['staging_code'], 'required'],
                [['date_time_of_collection', 'created_at', 'updated_at', 'picked_datetime', 'response_datetime'], 'safe'],
                [['qty', 'avg_fat', 'avg_snf', 'amount'], 'safe'],
                [['originating_type', 'data_post_status'], 'safe'],
                [['staging_code'], 'safe'],
                [['shift_lock_code'], 'safe'],
                [['mcc_plant_code'], 'safe'],
                [['shift_code'], 'safe'],
                [['created_by', 'updated_by'], 'safe'],
                [['originating_org_code', 'originating_org_type'], 'safe'],
                [['resp_status', 'resp_desc'], 'safe'],
                [['data_post_status'], 'default', 'value' => 0],
                [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'staging_code' => Yii::t('app', 'Staging Code'),
            'shift_lock_code' => Yii::t('app', 'Shift Lock Code'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'shift_code' => Yii::t('app', 'Shift'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'qty' => Yii::t('app', 'Qty'),
            'avg_fat' => Yii::t('app', 'Avg FAT'),
            'avg_snf' => Yii::t('app', 'Avg SNF'),
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
        ];
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getLockData() {
        return $this->hasOne(TblMccShiftLock::className(), ['shift_lock_code' => 'shift_lock_code']);
    }

    public function getLockShift($limit = '') {
        $query = $this->find()
               // ->andWhere(['or', ['data_post_status' => 0], ['is', 'data_post_status', NULL]])
                ->limit($limit)
                ->all();

        return $query;
    }

    public function updateFileStatus($ids) {
        return $this->updateAll(['data_post_status' => 1, 'picked_datetime' => date('Y-m-d H:i:s')], ['staging_code' => $ids]);
    }

}
