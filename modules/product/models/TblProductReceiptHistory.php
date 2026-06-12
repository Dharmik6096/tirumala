<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_product_receipt_history".
 *
 * @property integer $id
 * @property string $product_receipt_code
 * @property string $grn_no
 * @property string $grn_date
 * @property string $challan_no
 * @property string $challan_date
 * @property integer $challan_verified
 * @property string $description
 * @property string $vendor_type
 * @property string $vendor_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblProductReceiptHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_receipt_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_receipt_code', 'grn_no', 'challan_no', 'description', 'vendor_type', 'vendor_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_by', 'updated_by', 'history_created_by', 'operation_type', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['grn_date', 'challan_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
                [['challan_verified', 'originating_type', 'bill_no'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_receipt_code' => Yii::t('app', 'Product Receipt Code'),
            'grn_no' => Yii::t('app', 'Grn No'),
            'grn_date' => Yii::t('app', 'Grn Date'),
            'challan_no' => Yii::t('app', 'Challan No'),
            'challan_date' => Yii::t('app', 'Challan Date'),
            'challan_verified' => Yii::t('app', 'Challan Verified'),
            'description' => Yii::t('app', 'Description'),
            'vendor_type' => Yii::t('app', 'Vendor Type'),
            'vendor_code' => Yii::t('app', 'Vendor Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
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
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

}
