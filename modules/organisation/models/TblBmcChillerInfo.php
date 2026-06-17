<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_bmc_chiller_info".
 *
 * @property integer $chiller_info_code
 * @property string $bmc_code
 * @property string $owner_name
 * @property string $rate_type
 * @property integer $chilling_capacity
 * @property string $min_qty
 * @property string $pan_no
 * @property string $tds_percentage
 * @property string $installation_date
 * @property string $agreement_no
 * @property string $agreement_from_date
 * @property string $agreement_to_date
 * @property integer $is_active
 * @property string $mcc_plant_code
 * @property string $plant_code
 * @property string $union_code
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblBmcChillerInfo extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_chiller_info';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['chilling_capacity', 'installation_date', 'agreement_from_date', 'agreement_to_date', 'created_at', 'updated_at', 'is_active', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'chiller_name', 'fix_rent', 'billing_method', 'sap_vendor_code', 'is_default'], 'safe'],
            [['owner_name', 'rate_type', 'chilling_capacity', 'min_qty', 'pan_no', 'tds_percentage', 'installation_date', 'agreement_no', 'agreement_from_date', 'agreement_to_date', 'chiller_name', 'sap_vendor_code'], 'required', 'except' => ['setDefaultBmcChiller', 'deactivateBmcChiller']],
            [['owner_name', 'pan_no', 'rate_type', 'agreement_no', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type',], 'string'],
            [['is_active', 'originating_type'], 'integer'],
            [['min_qty', 'tds_percentage'], 'number'],
            [['tds_percentage'], 'number', 'max' => 100],
            [['is_active'], 'default', 'value' => 1],
            [['fix_rent', 'is_default'], 'default', 'value' => 0],
            [['sap_vendor_code'], 'string', 'max' => 225],
            [['pan_no'], function ($attribute, $params) {
                    Yii::$app->general->validatePancard($this, $attribute, $params);
                }, 'skipOnEmpty' => false, 'except' => ['setDefaultBmcChiller', 'deactivateBmcChiller']],
            [['sap_vendor_code'], 'unique', 'targetAttribute' => ['sap_vendor_code', 'union_code'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'except' => ['setDefaultBmcChiller', 'deactivateBmcChiller']],
            [['agreement_from_date', 'agreement_to_date'], 'ValidateData', 'except' => ['setDefaultBmcChiller', 'deactivateBmcChiller']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'chiller_info_code' => Yii::t('app', 'Chiller Info Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'owner_name' => Yii::t('app', 'Owner Name'),
            'rate_type' => Yii::t('app', 'Rate Type'),
            'chilling_capacity' => Yii::t('app', 'Chilling Capacity'),
            'min_qty' => Yii::t('app', 'Min Qty'),
            'pan_no' => Yii::t('app', 'PAN No'),
            'tds_percentage' => Yii::t('app', 'TDS(%)'),
            'installation_date' => Yii::t('app', 'Installation Date'),
            'agreement_no' => Yii::t('app', 'Agreement No'),
            'agreement_from_date' => Yii::t('app', 'Agreement From Date'),
            'agreement_to_date' => Yii::t('app', 'Agreement To Date'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_default' => Yii::t('app', 'Is Default'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'chiller_name' => Yii::t('app', 'Chiller Name'),
            'fix_rent' => Yii::t('app', 'Fix Rent'),
            'billing_method' => Yii::t('app', 'Billing Method'),
            'sap_vendor_code' => Yii::t('app', 'Sap Vendor Code'),
        ];
    }

    public function ValidateData() {
        if ($this->agreement_from_date > $this->agreement_to_date) {
            $this->addError('agreement_from_date', Yii::t('app/validation', 'From Date can not be greater than To Date.'));
        }
    }

}
