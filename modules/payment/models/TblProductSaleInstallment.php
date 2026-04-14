<?php

namespace app\modules\payment\models;

use Yii;

/**
 * This is the model class for table "tbl_product_sale_installment".
 *
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
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property integer $txfarmer_id
 * @property string $data_inserted_from
 * @property string $received_timestamp
 * @property string $previous_pending_amount
 * @property integer $type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 */
class TblProductSaleInstallment extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_sale_installment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_sale_installment_code'], 'required', 'on' => ['androidsync']],
                [['product_sale_installment_code', 'product_sale_code', 'data_inserted_from', 'customer_type', 'customer_code', 'dcs_code', 'bmc_code', 'mcc_plant_code', 'plant_code', 'union_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'main_amount', 'installment_amount', 'previous_pending_amount', 'installment_status', 'payment_cycle_applicability_code', 'payment_cycle_code', 'originating_type', 'txfarmer_id', 'type', 'installment_date', 'created_at', 'updated_at', 'received_timestamp'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_sale_installment_code' => Yii::t('app', 'Installment Code'),
            'product_sale_code' => Yii::t('app', 'Product Sale Code'),
            'customer_type' => Yii::t('app', 'Customer Type'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'dcs_code' => Yii::t('app', 'Society'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'plant_code' => Yii::t('app', 'Plant'),
            'union_code' => Yii::t('app', 'Union'),
            'main_amount' => Yii::t('app', 'Main Amount'),
            'installment_amount' => Yii::t('app', 'Installment Amount'),
            'installment_status' => Yii::t('app', 'Installment Status'),
            'installment_date' => Yii::t('app', 'Installment Date'),
            'payment_cycle_applicability_code' => Yii::t('app', 'Payment Cycle Applicability Code'),
            'payment_cycle_code' => Yii::t('app', 'Payment Cycle Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'txfarmer_id' => Yii::t('app', 'Txfarmer ID'),
            'data_inserted_from' => Yii::t('app', 'Data Inserted From'),
            'received_timestamp' => Yii::t('app', 'Received Timestamp'),
            'previous_pending_amount' => Yii::t('app', 'Previous Pending Amount'),
            'type' => Yii::t('app', 'Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
        ];
    }

}
