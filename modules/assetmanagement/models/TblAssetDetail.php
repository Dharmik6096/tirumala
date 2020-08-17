<?php

namespace app\modules\assetmanagement\models;

use Yii;
use app\modules\assetmanagement\models\TblAssetMaster;
use app\modules\assetmanagement\models\TblAssetGroup;
//use app\modules\organisation\models\TblManufacturer;
use app\modules\assetmanagement\models\TblStoreLocation;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblCustomerMaster;

/**
 * This is the model class for table "tbl_asset_detail".
 *
 * @property integer $asset_detail_code
 * @property string $asset_group_code
 * @property string $asset_code
 * @property string $store_location_code
 * @property string $serial_number
 * @property string $manufacturer_code
 * @property string $capacity
 * @property string $purchase_date
 * @property string $put_to_use_date
 * @property string $warranty_period
 * @property string $maintanance_duration_in_days
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblAssetDetail extends \app\models\ChildModel {

    public $is_serial_number, $store_location_type;
    public $to_plant, $to_mcc, $to_bmc, $to_dcs;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_asset_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['asset_group_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'asset_group_code');
                }, 'on' => 'importCsv'],
            [['asset_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'asset_code');
                }, 'on' => 'importCsv'],
            [['store_location_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'store_location_code');
                }, 'on' => 'importCsv'],
            [['manufacturer_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'customer_code');
                }, 'on' => 'importCsv'],
            [['asset_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblAssetMaster::className(), 'targetAttribute' => ['asset_code' => 'asset_code']],
            [['asset_code'], 'assignAutoData', 'skipOnError' => true, 'on' => 'importCsv'],
            [['asset_code', 'store_location_code', 'put_to_use_date', 'purchase_date', 'manufacturer_code'], 'required'],
            [['asset_group_code', 'asset_code', 'store_location_code', 'serial_number', 'created_by', 'updated_by'], 'string'],
            [['purchase_date', 'put_to_use_date', 'created_at', 'updated_at', 'is_serial_number', 'store_location_type', 'qty', 'make', 'to_plant', 'to_mcc', 'to_bmc', 'to_dcs'], 'safe'],
            [['warranty_period', 'maintanance_duration_in_days', 'capacity', 'qty'], 'number'],
            [['is_active'], 'default', 'value' => 1],
            [['put_to_use_date'], 'usedDateValidate'],
            [['manufacturer_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblCustomerMaster::className(), 'targetAttribute' => ['manufacturer_code' => 'customer_code']],
            [['store_location_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblStoreLocation::className(), 'targetAttribute' => ['store_location_code' => 'store_location_code']],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
            [['purchase_date', 'put_to_use_date'], 'date', 'format' => 'php:Y-m-d', 'message' => Yii::t('app/validation', 'The format of {attribute} is invalid. eg. 2019-12-01')],
            [['serial_number'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return Yii::$app->general->getforeignkey($model->assetCode, 'is_serial_number') == '1';
                }, 'whenClient' => "function (attribute, value) { 
              return $('#is_serial_number').val() == '1'; 
          }"],
            [['qty'], 'required', 'skipOnError' => true, 'when' => function ($model) {
                    return Yii::$app->general->getforeignkey($model->assetCode, 'is_serial_number') == '0';
                }, 'whenClient' => "function (attribute, value) { 
              return $('#is_serial_number').val() == '0'; 
          }"],
            [['asset_code'], 'checkUnique', 'skipOnError' => true],
            //    ['asset_code', 'unique', 'targetAttribute' => (Yii::$app->general->getforeignkey($this->assetCode, 'is_serial_number') == '1') ? ['serial_number', 'asset_code'] : ['purchase_date', 'asset_code', 'store_location_code'], 'skipOnEmpty' => TRUE, 'message' => Yii::t('app/validation', 'Asset/Serial No. has already been taken.')],
            [['qty'], 'default', 'value' => 1],
            [['make'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'asset_detail_code' => Yii::t('app', 'Asset Detail Code'),
            'asset_group_code' => Yii::t('app', 'Asset Group'),
            'asset_code' => Yii::t('app', 'Asset'),
            'store_location_code' => Yii::t('app', 'Store Location'),
            'serial_number' => Yii::t('app', 'Serial No.'),
            'manufacturer_code' => Yii::t('app', 'Vendor'),
            'capacity' => Yii::t('app', 'Capacity'),
            'purchase_date' => Yii::t('app', 'Purchase Date'),
            'put_to_use_date' => Yii::t('app', 'Put To Use Date'),
            'warranty_period' => Yii::t('app', 'Warranty Period(Month)'),
            'maintanance_duration_in_days' => Yii::t('app', 'Maintanance Duration(Days)'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'union_code' => Yii::t('app', 'Union'),
            'qty' => Yii::t('app', 'Qty'),
            'make' => Yii::t('app', 'Make'),
            'to_mcc' => Yii::t('app', 'To MCC'),
            'to_plant' => Yii::t('app', 'To Plant'),
            'to_dcs' => Yii::t('app', 'To DCS'),
        ];
    }

//
//    public function getManufacturerCode() {
//        return $this->hasOne(TblManufacturer::className(), ['id' => 'manufacturer_code']);
//    }
    public function getManufacturerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'manufacturer_code']);
    }

    public function getAssetCode() {
        return $this->hasOne(TblAssetMaster::className(), ['asset_code' => 'asset_code']);
    }

    public function getAssetGroupCode() {
        return $this->hasOne(TblAssetGroup::className(), ['asset_group_code' => 'asset_group_code']);
    }

    public function getStoreLocCode() {
        return $this->hasOne(TblStoreLocation::className(), ['store_location_code' => 'store_location_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function usedDateValidate($attribute, $params) {

        if (!empty($this->purchase_date) && !empty($this->put_to_use_date)) {

            if ($this->put_to_use_date < $this->purchase_date) {
                $this->addError($attribute, Yii::t('app/validation', 'Put Use Date Must be Greater Than Purchase Date.'));
                return false;
            }
        }
    }

    public function assignAutoData($attribute, $params) {
        $this->asset_group_code = $this->assetCode->asset_group_code;
        $this->union_code = $this->assetCode->union_code;
    }

    public function checkUnique($attribute, $params) {
        $data = 0;
        if (Yii::$app->general->getforeignkey($this->assetCode, 'is_serial_number') == '1') {
            $data = $this->find()->where(['serial_number' => $this->serial_number, 'asset_code' => $this->asset_code])
                    ->andWhere(['<>', 'asset_detail_code', $this->asset_detail_code])
                    ->count();
        }
        /* else {
          $data = $this->find()->where(['qty' => $this->qty, 'purchase_date' => $this->purchase_date, 'asset_code' => $this->asset_code, 'store_location_code' => $this->store_location_code])
          ->andWhere(['<>', 'asset_detail_code', $this->asset_detail_code])
          ->count();
          } */
        if ($data != 0) {
            $this->addError($attribute, Yii::t('app/validation', 'Asset/Serial No. has already been taken.'));
        }
    }

}
