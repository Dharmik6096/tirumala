<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\dcsoperation\models\TblShift;

/**
 * This is the model class for table "tbl_bmc_collection_transfer".
 *
 * @property integer $tranfer_code
 * @property string $union_code
 * @property string $from_plant_code
 * @property string $from_mcc_plant_code
 * @property string $route_code
 * @property string $from_date
 * @property string $from_shift_code
 * @property string $to_date
 * @property string $to_shift_code
 * @property string $to_plant_code
 * @property string $to_mcc_plant_code
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
class TblBmcCollectionTransfer extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_collection_transfer';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['from_date', 'from_shift_code', 'to_date', 'to_shift_code'], 'safe'],
            [['union_code', 'from_plant_code', 'from_mcc_plant_code', 'to_plant_code', 'to_mcc_plant_code', 'route_code'], 'safe'],
            [['created_at', 'updated_at', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tranfer_code' => Yii::t('app', 'Tranfer Code'),
            'union_code' => Yii::t('app', 'Union'),
            'from_plant_code' => Yii::t('app', 'From Plant'),
            'from_mcc_plant_code' => Yii::t('app', 'From MCC'),
            'route_code' => Yii::t('app', 'Route'),
            'from_date' => Yii::t('app', 'From Date'),
            'from_shift_code' => Yii::t('app', 'From Shift'),
            'to_date' => Yii::t('app', 'To Date'),
            'to_shift_code' => Yii::t('app', 'To Shift'),
            'to_plant_code' => Yii::t('app', 'To Plant'),
            'to_mcc_plant_code' => Yii::t('app', 'To MCC'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'from_plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'from_mcc_plant_code']);
    }

    public function getToPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'to_plant_code']);
    }

    public function getToMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'to_mcc_plant_code']);
    }

    public function getRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

    public function getFromShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'from_shift_code']);
    }

    public function getToShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'to_shift_code']);
    }

}
