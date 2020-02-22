<?php

namespace app\modules\soap\models;

use Yii;

/**
 * This is the model class for table "tbl_master_data_intermediate".
 *
 * @property integer $id
 * @property string $ProductGroup_Code
 * @property string $ProductGroup_Name
 * @property string $Product_Code
 * @property string $ProductName
 * @property string $UnitPrice
 * @property string $PlantCode
 * @property string $PlantName
 * @property string $CCenter_Code
 * @property string $Center_Code
 * @property string $CenterName
 * @property string $MCCCode
 * @property string $VendorCode
 * @property string $RateChartID
 * @property integer $FarmerCollection
 * @property integer $CenterCollection
 * @property integer $VendorCollection
 * @property integer $ManualAutoMode
 * @property integer $ManualQualityMode
 * @property string $FMT
 * @property string $FMS
 * @property string $FET
 * @property string $FES
 * @property string $VMT
 * @property string $VMS
 * @property string $VET
 * @property string $VES
 * @property string $Farmer_Code
 * @property string $LocalCode
 * @property string $SAPFarmerCode
 * @property string $FarmerName
 * @property string $Mobile
 * @property string $Vendor_Code
 * @property string $VendorFirmName
 * @property string $Overhead
 * @property integer $BillingCycle
 * @property string $EffectiveDate
 * @property string $union_code
 * @property string $service_type
 * @property string $username
 * @property string $password
 * @property integer $log_id
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblMasterDataIntermediate extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_master_data_intermediate';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['ProductGroup_Code', 'ProductGroup_Name', 'Product_Code', 'ProductName', 'PlantCode', 'PlantName', 'CCenter_Code', 'Center_Code', 'CenterName', 'MCCCode', 'VendorCode', 'RateChartID', 'FMT', 'FMS', 'FET', 'FES', 'VMT', 'VMS', 'VET', 'VES', 'Farmer_Code', 'LocalCode', 'SAPFarmerCode', 'FarmerName', 'Mobile', 'Vendor_Code', 'VendorFirmName', 'union_code', 'service_type', 'username', 'password', 'created_by', 'updated_by'], 'string'],
            [['UnitPrice', 'Overhead'], 'number'],
            [['FarmerCollection', 'CenterCollection', 'VendorCollection', 'ManualAutoMode', 'ManualQualityMode', 'BillingCycle', 'log_id'], 'integer'],
            [['EffectiveDate', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'ProductGroup_Code' => Yii::t('app', 'Product Group  Code'),
            'ProductGroup_Name' => Yii::t('app', 'Product Group  Name'),
            'Product_Code' => Yii::t('app', 'Product  Code'),
            'ProductName' => Yii::t('app', 'Product Name'),
            'UnitPrice' => Yii::t('app', 'Unit Price'),
            'PlantCode' => Yii::t('app', 'Plant Code'),
            'PlantName' => Yii::t('app', 'Plant Name'),
            'CCenter_Code' => Yii::t('app', 'Ccenter  Code'),
            'Center_Code' => Yii::t('app', 'Center  Code'),
            'CenterName' => Yii::t('app', 'Center Name'),
            'MCCCode' => Yii::t('app', 'Mcccode'),
            'VendorCode' => Yii::t('app', 'Vendor Code'),
            'RateChartID' => Yii::t('app', 'Rate Chart ID'),
            'FarmerCollection' => Yii::t('app', 'Farmer Collection'),
            'CenterCollection' => Yii::t('app', 'Center Collection'),
            'VendorCollection' => Yii::t('app', 'Vendor Collection'),
            'ManualAutoMode' => Yii::t('app', 'Manual Auto Mode'),
            'ManualQualityMode' => Yii::t('app', 'Manual Quality Mode'),
            'FMT' => Yii::t('app', 'Fmt'),
            'FMS' => Yii::t('app', 'Fms'),
            'FET' => Yii::t('app', 'Fet'),
            'FES' => Yii::t('app', 'Fes'),
            'VMT' => Yii::t('app', 'Vmt'),
            'VMS' => Yii::t('app', 'Vms'),
            'VET' => Yii::t('app', 'Vet'),
            'VES' => Yii::t('app', 'Ves'),
            'Farmer_Code' => Yii::t('app', 'Farmer  Code'),
            'LocalCode' => Yii::t('app', 'Local Code'),
            'SAPFarmerCode' => Yii::t('app', 'Sapfarmer Code'),
            'FarmerName' => Yii::t('app', 'Farmer Name'),
            'Mobile' => Yii::t('app', 'Mobile'),
            'Vendor_Code' => Yii::t('app', 'Vendor  Code'),
            'VendorFirmName' => Yii::t('app', 'Vendor Firm Name'),
            'Overhead' => Yii::t('app', 'Overhead'),
            'BillingCycle' => Yii::t('app', 'Billing Cycle'),
            'EffectiveDate' => Yii::t('app', 'Effective Date'),
            'union_code' => Yii::t('app', 'Union Code'),
            'service_type' => Yii::t('app', 'Service Type'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'log_id' => Yii::t('app', 'Log ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

}
