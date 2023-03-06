<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "tbl_indent_master_history".
 *
 * @property integer $id
 * @property string $indent_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $member_code
 * @property string $dcs_code
 * @property string $bmc_code
 * @property string $mcc_plant_code
 * @property string $plant_code
 * @property string $union_code
 * @property string $indent_date
 * @property string $product_code
 * @property string $qty
 * @property string $status
 * @property string $status_date
 * @property string $status_by
 * @property string $status_remarks
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
class TblIndentMasterHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_indent_master_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['indent_code'], 'safe'],
            [['union_code', 'mcc_plant_code', 'plant_code', 'dcs_code', 'bmc_code', 'customer_type', 'customer_code', 'member_code', 'product_code', 'status', 'indent_date', 'qty', 'status_remarks'], 'safe'],
            [['indent_type', 'warehouse_code', 'rate', 'amount'], 'safe'],
            [['status_date', 'created_at', 'updated_at', 'history_created_at', 'created_by', 'updated_by', 'status_by', 'history_created_by'], 'safe'],
            [['operation_type', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'indent_code' => Yii::t('app', 'Indent Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'member_code' => Yii::t('app', 'Member Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'indent_date' => Yii::t('app', 'Indent Date'),
            'product_code' => Yii::t('app', 'Product Code'),
            'qty' => Yii::t('app', 'Qty'),
            'status' => Yii::t('app', 'Status'),
            'status_date' => Yii::t('app', 'Status Date'),
            'status_by' => Yii::t('app', 'Status By'),
            'status_remarks' => Yii::t('app', 'Status Remarks'),
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
