<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_product_sale_installment_history".
 *
 * @property integer $id
 * @property string $product_sale_installment_code
 * @property string $product_sale_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $dcs_code
 * @property string $bmc_code
 * @property string $mcc_plant_code
 * @property string $plant_code
 * @property string $union_code
 * @property string $main_amount
 * @property string $installment_amount
 * @property integer $installment_status
 * @property string $installment_date
 * @property integer $payment_cycle_applicability_code
 * @property integer $payment_cycle_code
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
 */
class TblProductSaleInstallmentHistory extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_sale_installment_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['main_amount', 'installment_amount'], 'safe'],
                [['installment_status', 'payment_cycle_applicability_code', 'payment_cycle_code', 'originating_type'], 'safe'],
                [['installment_date', 'created_at', 'updated_at', 'history_created_at'], 'safe'],
                [['product_sale_installment_code', 'product_sale_code'], 'safe'],
                [['customer_type', 'customer_code'], 'safe'],
                [['dcs_code', 'bmc_code'], 'safe'],
                [['mcc_plant_code', 'plant_code'], 'safe'],
                [['union_code'], 'safe'],
                [['created_by', 'updated_by', 'history_created_by'], 'safe'],
                [['operation_type'], 'safe'],
                [['originating_org_code', 'originating_org_type', 'previous_pending_amount', 'type', 'x_col1', 'x_col2', 'x_col3'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'product_sale_installment_code' => 'Product Sale Installment Code',
            'product_sale_code' => 'Product Sale Code',
            'customer_type' => 'Customer Type',
            'customer_code' => 'Customer Code',
            'dcs_code' => 'Dcs Code',
            'bmc_code' => 'Bmc Code',
            'mcc_plant_code' => 'Mcc Plant Code',
            'plant_code' => 'Plant Code',
            'union_code' => 'Union Code',
            'main_amount' => 'Main Amount',
            'installment_amount' => 'Installment Amount',
            'installment_status' => 'Installment Status',
            'installment_date' => 'Installment Date',
            'payment_cycle_applicability_code' => 'Payment Cycle Applicability Code',
            'payment_cycle_code' => 'Payment Cycle Code',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'history_created_at' => 'History Created At',
            'history_created_by' => 'History Created By',
            'operation_type' => 'Operation Type',
            'originating_org_code' => 'Originating Org Code',
            'originating_org_type' => 'Originating Org Type',
            'originating_type' => 'Originating Type',
        ];
    }

}
