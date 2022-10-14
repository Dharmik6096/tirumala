<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\organisation\models\TblMccPlant;
use app\modules\dcsoperation\models\TblShift;

/**
 * This is the model class for table "tbl_mcc_shift_lock".
 *
 * @property string $shift_lock_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $shift_code
 * @property string $date_time_of_collection
 * @property integer $data_lock
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblMccShiftLock extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_mcc_shift_lock';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['shift_lock_code'], 'required'],
            [['date_time_of_collection', 'union_code', 'plant_code', 'mcc_plant_code', 'shift_code', 'created_at', 'updated_at'], 'safe'],
            [['data_lock', 'originating_type'], 'integer'],
            [['created_by', 'updated_by'], 'safe'],
            [['originating_org_code', 'originating_org_type', 'vm_data_lock'], 'safe'],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['qty', 'avg_fat', 'avg_snf', 'amount', 'bmc_lock', 'member_lock', 'product_sale_lock'], 'safe'],
            [['data_lock', 'bmc_lock', 'member_lock', 'product_sale_lock', 'vm_data_lock'], 'default', 'value' => 0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'shift_lock_code' => Yii::t('app', 'Shift Lock Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'data_lock' => Yii::t('app', 'Data Lock'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'Channel Type'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'f_plant_code' => Yii::t('app', 'Plant Code'),
            'vm_data_lock' => Yii::t('app', 'VM Data Lock'),
        ];
    }

    public function getStatus($data) {
        return $query = $this->find()->where(['mcc_plant_code' => $data['mcc_plant_code'], 'cast(date_time_of_collection as date)' => date('Y-m-d', strtotime($data['date_time_of_collection'])), 'shift_code' => $data['shift_code']])->one();
//        return (!empty($query) && ($query->data_lock == 1)) ? 1 : 0;
    }

    public function getExistData() {
        return $this->find()->where(['mcc_plant_code' => $this->mcc_plant_code, 'cast(date_time_of_collection as date)' => date('Y-m-d', strtotime($this->date_time_of_collection)), 'shift_code' => $this->shift_code])->one();
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

}
