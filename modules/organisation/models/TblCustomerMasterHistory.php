<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_customer_master_history".
 *
 * @property integer $id
 * @property string $customer_code
 * @property string $customer_name
 * @property string $address
 * @property integer $is_active
 * @property string $state_code
 * @property string $district_code
 * @property string $sub_district_code
 * @property string $village_code
 * @property string $hamlet_code
 * @property string $local_name
 * @property string $local_address
 * @property string $gst_no
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $customer_type
 * @property string $sap_code
 * @property string $refference_code
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblCustomerMasterHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_customer_master_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['customer_code', 'customer_code_ex', 'sap_vendor_code', 'ts_code_m', 'ts_code_e', 'pan_no'], 'safe'],
                [['customer_code', 'customer_name', 'address', 'state_code', 'district_code', 'sub_district_code', 'village_code', 'hamlet_code', 'local_name', 'local_address', 'gst_no', 'union_code', 'created_by', 'updated_by', 'operation_type', 'history_created_by', 'customer_type', 'sap_code', 'refference_code', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['is_active', 'originating_type', 'is_aadhar_verify'], 'safe'],
                [['created_at', 'updated_at', 'history_created_at', 'route_code'], 'safe'],
                [['ref_code', 'vendor_code', 'auto_code', 'data_post_id', 'data_post_status', 'picked_datetime', 'resp_status', 'resp_desc', 'response_datetime', 'aadhaar_no'], 'safe'],
                [['bmc_code', 'mcc_plant_code', 'plant_code', 'morning_kms', 'evening_kms', 'rate_chart_code', 'billing_payment_cycle', 'over_head', 'dcs_code', 'ccenter_code', 'mobile_no', 'old_bmc_code', 'old_mcc_plant_code', 'old_route_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'customer_name' => Yii::t('app', 'Customer Name'),
            'address' => Yii::t('app', 'Address'),
            'is_active' => Yii::t('app', 'Is Active'),
            'state_code' => Yii::t('app', 'State Code'),
            'district_code' => Yii::t('app', 'District Code'),
            'sub_district_code' => Yii::t('app', 'Sub District Code'),
            'village_code' => Yii::t('app', 'Village Code'),
            'hamlet_code' => Yii::t('app', 'Hamlet Code'),
            'local_name' => Yii::t('app', 'Local Name'),
            'local_address' => Yii::t('app', 'Local Address'),
            'gst_no' => Yii::t('app', 'Gst No'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'sap_code' => Yii::t('app', 'Sap Code'),
            'refference_code' => Yii::t('app', 'Refference Code'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }

}
