<?php

namespace app\modules\product\models;

use Yii;
use app\modules\product\models\TblProduct;
use app\modules\globalmaster\models\TblUnits;
use app\modules\syncutility\models\TblSentbox;
use app\modules\product\models\TblProductStock;
use app\modules\product\models\TblProductStockTransaction;
use app\modules\product\models\TblProductStockHistory;
use app\modules\product\models\TblProductStockTransactionHistory;

/**
 * This is the model class for table "tbl_product_receipt_transaction".
 *
 * @property string $product_receipt_transaction_code
 * @property string $product_requisition_code
 * @property string $requisition_transaction_code
 * @property string $product_code
 * @property string $product_receipt_code
 * @property string $requested_quantity
 * @property string $dispatched_quantity
 * @property string $received_quantity
 * @property string $rejected_quantity
 * @property string $rate
 * @property string $amount
 * @property string $discount
 * @property string $remark
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblProductReceiptTransaction extends \app\models\ChildModel {

    public $uom;
    public $is_sentbox = TRUE;
    public $saveDeleteChildRecords = TRUE;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_receipt_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['product_receipt_transaction_code'], 'required', 'on' => ['androidsync']],
            [['product_receipt_transaction_code', 'product_requisition_code', 'requisition_transaction_code', 'product_code', 'product_receipt_code', 'remark', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['requested_quantity', 'dispatched_quantity', 'received_quantity', 'rejected_quantity', 'rate', 'amount', 'discount'], 'safe'],
            [['created_at', 'updated_at'], 'safe'],
            [['originating_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_receipt_transaction_code' => Yii::t('app', 'Product Receipt Transaction Code'),
            'product_requisition_code' => Yii::t('app', 'Product Requisition Code'),
            'requisition_transaction_code' => Yii::t('app', 'Requisition Transaction Code'),
            'product_code' => Yii::t('app', 'Product'),
            'product_receipt_code' => Yii::t('app', 'Product Receipt Code'),
            'requested_quantity' => Yii::t('app', 'Requested Quantity'),
            'dispatched_quantity' => Yii::t('app', 'Dispatched Quantity'),
            'received_quantity' => Yii::t('app', 'Received Quantity'),
            'rejected_quantity' => Yii::t('app', 'Rejected Quantity'),
            'rate' => Yii::t('app', 'Rate'),
            'amount' => Yii::t('app', 'Amount (Rs.)'),
            'discount' => Yii::t('app', 'Discount'),
            'remark' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'uom' => Yii::t('app', 'UOM'),
        ];
    }

    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    public function getMasterCode() {
        return $this->hasOne(TblProductReceipt::className(), ['product_receipt_code' => 'product_receipt_code']);
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];

        if (!empty($this->masterCode->dcs_code)) {
            $array = [];
            $array['code'] = $this->masterCode->dcs_code;
            $array['type'] = 'VLC';
            $sentboxArray = [$array]; //Yii::$app->general->getSentBoxCodes('', '', '', '', $this->masterCode->dcs_code);
        } else if (!empty($this->masterCode->bmc_code)) {
            $array = [];
            $array['code'] = $this->masterCode->bmc_code;
            $array['type'] = 'BMC';
            $sentboxArray = [$array]; //Yii::$app->general->getSentBoxCodes('', '', $this->masterCode->bmc_code, '', '');
        } else if (!empty($this->masterCode->mcc_plant_code)) {
            $array = [];
            $array['code'] = $this->masterCode->mcc_plant_code;
            $array['type'] = 'MCC';
            $sentboxArray = [$array]; //Yii::$app->general->getSentBoxCodes('', $this->masterCode->mcc_plant_code, '', '', '');
        }
        foreach ($sentboxArray as $sent) {
            $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : ($insert) ? 'INSERT' : 'UPDATE';
            $sentbox = $this->sentboxModel($sent['code'], $sent['type']);
            if (!isset($this->is_sentbox) || (isset($this->is_sentbox) && $this->is_sentbox === TRUE)) {
                if (!($sentbox->setSentbox($this, $flag))) {
                    throw new UserException("SentBox Entry is not created so transaction is rollback!");
                }
            }
        }
    }

    private function sentboxModel($code, $type) {
        $sentbox = new TblSentbox();
        $sentbox->dest_org_id = $code;
        $sentbox->source_org_id = $this->masterCode->union_code;
        $sentbox->dest_org_type = $type;
        $dateTime = date('Y-m-d H:i:s');
        $microtime = date('Y-m-d H:i:s', strtotime($dateTime . ' +5 minute')) . '.' . gettimeofday()["usec"];
        $sentbox->posting_timestamp = $microtime;
        return $sentbox;
    }

    public function setTransactionSaveDeleteData(&$model, $json, &$childModel, &$delete) {
        $manageStock = true;
        if ($manageStock) {
            $qty = !empty($model->received_quantity) ? $model->received_quantity : 0;
            if (!empty($qty) && !empty($model->masterCode)) {
                $productReceiptData = $model->masterCode;
                $fstockModel = new TblProductStock();
                $sale_type = strtoupper($productReceiptData->vendor_type);
                $sale_code = $productReceiptData->vendor_code;
                $fstockModel->setCodes($sale_type, $sale_code);
                $fstockModel->product_code = $model->product_code;
                $fstockModel->union_code = $productReceiptData->union_code;
                $txn_type = 'PRODUCT RECEIPT';
                $existfromStock = $fstockModel->getExistStock($sale_type);

                $f_stock = 0;
                if (!empty($existfromStock)) {
                    $historyModel = new TblProductStockHistory();
                    Yii::$app->operation->history($existfromStock, $historyModel, UPDATE);
                    $childModel[] = $historyModel;
                    $f_stock = $existfromStock->stock;
                    $existfromStock->stock = $f_stock + $qty;
                    $fstockModel = $existfromStock;
                } else {
                    $fstockModel->product_stock_code = $fstockModel->getCode($i);
                    $fstockModel->stock = $f_stock + $qty;
                    $fstockModel->x_col1 = Yii::$app->general->getUuid();
                }


                $i = 1;
                $fstockTxnModel = new TblProductStockTransaction();
                $fstockTxnModel->attributes = $fstockModel->attributes;
                unset($stockTxnModel->created_at);
                unset($stockTxnModel->created_by);
                $fstockTxnModel->product_stock_transaction_code = $fstockTxnModel->getCode($i);
                $fstockTxnModel->old_value = $f_stock;
                $fstockTxnModel->new_value = $qty;
                $fstockTxnModel->final_value = $fstockModel->stock;
                $fstockTxnModel->transaction_type = $txn_type;
                $fstockTxnModel->transaction_date = date('Y-m-d');
                $fstockTxnModel->reference_code = $model->product_sale_transaction_code;


                $childModel[] = $fstockModel;
                $childModel[] = $fstockTxnModel;
                $i++;
            }
        }
    }

}
