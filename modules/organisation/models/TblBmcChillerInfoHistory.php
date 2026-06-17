<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_bmc_chiller_info_history".
 *
 * @property integer $id
 * @property integer $bmc_silos_info_code
 * @property string $silo_no
 * @property string $description
 * @property integer $manufacturer_code
 * @property string $model
 * @property string $wef_date
 * @property integer $storage_capacity
 * @property integer $chilling_capacity
 * @property string $owning_type
 * @property integer $milk_type_code
 * @property integer $is_active
 * @property string $module_name
 * @property string $module_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblBmcChillerInfoHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_chiller_info_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['chiller_info_code', 'bmc_code', 'owner_name', 'rate_type', 'chilling_capacity', 'min_qty', 'pan_no', 'tds_percentage', 'installation_date', 'agreement_no', 'agreement_from_date', 'agreement_to_date', 'is_active', 'is_default', 'union_code', 'plant_code', 'mcc_plant_code', 'created_at', 'updated_at', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'fix_rent', 'billing_method', 'sap_vendor_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
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
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

}
