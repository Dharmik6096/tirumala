<?php

namespace app\modules\assetmanagement\models;

use Yii;
use app\modules\assetmanagement\models\TblStoreLocationType;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;

/**
 * This is the model class for table "tbl_store_location".
 *
 * @property string $store_location_code
 * @property string $store_location_name
 * @property string $store_location_type
 * @property string $reference_code
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $local_name
 */
class TblStoreLocation extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_store_location';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['store_location_type'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'store_location_type');
                }, 'on' => 'importCsv'],
            [['store_location_name', 'store_location_type', 'union_code'], 'required'],
            [['store_location_name', 'created_by', 'updated_by'], 'string'],
            [['is_active'], 'default', 'value' => 1],
            [['created_at', 'updated_at', 'reference_code'], 'safe'],
            [['reference_code'], 'number'],
            [['local_name'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
            [['store_location_type'], 'exist', 'skipOnError' => true, 'targetClass' => TblStoreLocationType::className(), 'targetAttribute' => ['store_location_type' => 'slt_code']],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
            ['reference_code', 'unique', 'targetAttribute' => ['reference_code', 'store_location_type'], 'skipOnEmpty' => TRUE, 'message' => Yii::t('app/validation', 'Reference Code has already been taken.')],
            [['store_location_name', 'sloc_code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'store_location_code' => Yii::t('app', 'Store Location Code'),
            'store_location_name' => Yii::t('app', 'Store Location Name'),
            'store_location_type' => Yii::t('app', 'Store Location Type'),
            'reference_code' => Yii::t('app', 'Reference Code'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'local_name' => Yii::t('app', 'Local Name'),
            'union_code' => Yii::t('app', 'Union'),
            'sloc_code' => Yii::t('app', 'Storage Location Code')
        ];
    }

    public function getStoreLocType() {
        return $this->hasOne(TblStoreLocationType::className(), ['slt_code' => 'store_location_type']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasMany(TblPlant::className(), ['plant_code' => 'reference_code']);
    }

    public function getMccPlantCode() {
        return $this->hasMany(TblMccPlant::className(), ['mcc_plant_code' => 'reference_code']);
    }

    public function getBmcCode() {
        return $this->hasMany(TblDcsBmc::className(), ['bmc_code' => 'reference_code']);
    }

    public function getDcsCode() {
        return $this->hasMany(TblDcs::className(), ['dcs_code' => 'reference_code']);
    }

    public function getRecord() {
        return $this->find()
                        ->where(['store_location_type' => $this->store_location_type, 'reference_code' => $this->reference_code])
                        ->one();
    }

    public function getToMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'reference_code']);
    }

    public function getToDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'reference_code']);
    }

}
