<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_chiller_info_history".
 *
 * @property integer $id
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
 * @property string $chiller_name
 * @property string $fix_rent
 * @property string $billing_method
 * @property string $sap_vendor_code
 * @property string $dcs_code
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblDcsChillerInfoHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dcs_chiller_info_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['chiller_info_code'], 'required'],
            [['chiller_info_code', 'chilling_capacity', 'is_active', 'originating_type'], 'integer'],
            [['min_qty', 'tds_percentage', 'fix_rent'], 'number'],
            [['installation_date', 'agreement_from_date', 'agreement_to_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['bmc_code', 'mcc_plant_code', 'plant_code', 'dcs_code'], 'string', 'max' => 12],
            [['owner_name', 'pan_no', 'chiller_name', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
            [['rate_type', 'operation_type'], 'string', 'max' => 10],
            [['agreement_no'], 'string', 'max' => 100],
            [['union_code'], 'string', 'max' => 3],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['created_by', 'updated_by', 'history_created_by'], 'string', 'max' => 14],
            [['billing_method'], 'string', 'max' => 50],
            [['sap_vendor_code'], 'string', 'max' => 225],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'chiller_info_code' => Yii::t('app', 'Chiller Info Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'owner_name' => Yii::t('app', 'Owner Name'),
            'rate_type' => Yii::t('app', 'Rate Type'),
            'chilling_capacity' => Yii::t('app', 'Chilling Capacity'),
            'min_qty' => Yii::t('app', 'Min Qty'),
            'pan_no' => Yii::t('app', 'Pan No'),
            'tds_percentage' => Yii::t('app', 'Tds Percentage'),
            'installation_date' => Yii::t('app', 'Installation Date'),
            'agreement_no' => Yii::t('app', 'Agreement No'),
            'agreement_from_date' => Yii::t('app', 'Agreement From Date'),
            'agreement_to_date' => Yii::t('app', 'Agreement To Date'),
            'is_active' => Yii::t('app', 'Is Active'),
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
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }
}
