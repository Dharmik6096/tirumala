<?php

namespace app\modules\product\models;

use Yii;
use app\modules\product\models\TblProductRequisition;
use app\modules\product\models\TblProduct;
use app\modules\globalmaster\models\TblUnits;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;
use app\modules\syncutility\models\TblSentbox;
use app\modules\product\models\TblProductDispatch;

/**
 * This is the model class for table "tbl_product_dispatch_transaction".
 *
 * @property string $dispatch_transaction_code
 * @property string $vendor_type
 * @property string $vendor_code
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $challan_no
 * @property string $dispatch_date
 * @property string $product_requisition_code
 * @property string $requisition_transaction_code
 * @property string $product_code
 * @property string $status
 * @property string $rate
 * @property string $amount
 * @property string $discount_amount
 * @property string $dispatch_qty
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
class TblProductDispatchTransaction extends \app\models\ChildModel {

    public $is_close;
    public $net_amount;
    public $uom, $product_name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_dispatch_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['dispatch_transaction_code'], 'safe'],
                [['so_no', 'delivery_no', 'bill_no', 'remarks'], 'safe'],
                [['dispatch_transaction_code', 'vendor_type', 'vendor_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'challan_no', 'product_requisition_code', 'requisition_transaction_code', 'product_code', 'status', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string'],
                [['dispatch_date', 'created_at', 'updated_at', 'is_close'], 'safe'],
                [['rate', 'amount', 'discount_amount', 'dispatch_qty'], 'number'],
                [['originating_type'], 'integer'],
                [['dispatch_qty'], 'required', 'on' => 'dispatchUpdate'],
                [['dispatch_qty'], 'ValidateQty', 'on' => 'dispatchUpdate'],
                [['discount_amount'], 'ValidateDiscount', 'on' => 'dispatchUpdate'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dispatch_transaction_code' => Yii::t('app', 'Dispatch Transaction Code'),
            'vendor_type' => Yii::t('app', 'Type'),
            'vendor_code' => Yii::t('app', 'Code'),
            'union_code' => Yii::t('app', 'Union'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'challan_no' => Yii::t('app', 'Challan No.'),
            'dispatch_date' => Yii::t('app', 'Dispatch Date'),
            'product_requisition_code' => Yii::t('app', 'Product Requisition Code'),
            'requisition_transaction_code' => Yii::t('app', 'Requisition Transaction Code'),
            'product_code' => Yii::t('app', 'Product'),
            'status' => Yii::t('app', 'Status'),
            'rate' => Yii::t('app', 'Rate'),
            'amount' => Yii::t('app', 'Amount'),
            'discount_amount' => Yii::t('app', 'Discount Amount'),
            'dispatch_qty' => Yii::t('app', 'Dispatch Quantity'),
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

    public function getPreviousDispQty($reqCode) {
        $data = $this->find()->where(['requisition_transaction_code' => $reqCode])->sum('dispatch_qty');
        return ($data) ? $data : 0;
    }

    public function getUom($product_code) {
        $query = TblUnits::find()->select(['unit_name'])->leftJoin('tbl_product', 'tbl_product.unit_code = tbl_units.unit_code')->where(['tbl_product.product_code' => $product_code])->one();
        return $query['unit_name'];
    }

    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    public function getProductRequisitionCode() {
        return $this->hasOne(TblProductRequisition::className(), ['product_requisition_code' => 'product_requisition_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'vendor_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'vendor_type']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'vendor_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'vendor_code']);
    }

    public function getEntityName() {
        $type = Yii::$app->general->getforeignkey($this->productDispatchCode, 'vendor_type');
        $name = '';
        if ($type == 'BMC') {
            $name = Yii::$app->general->getmultiforeignkey($this->productDispatchCode, ['bmcCode'], 'bmc_name');
        } else if ($type == 'DCS') {
            $name = Yii::$app->general->getmultiforeignkey($this->productDispatchCode, ['dcsCode'], 'dcs_name');
        }
        return $name;
    }

    public function getMccCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getProductRequisitionTransactionCode() {
        return $this->hasOne(TblProductRequisitionTransaction::className(), ['requisition_transaction_code' => 'requisition_transaction_code']);
    }

    public function ValidateQty($attribute, $params) {
        if (!empty($this->requisition_transaction_code)) {
            $acceptedQty = $this->productRequisitionTransactionCode->approved_quantity;
            $data = $this->find()->where([
                        'requisition_transaction_code' => $this->requisition_transaction_code,
                    ])->andWhere(['<>', 'dispatch_transaction_code', $this->dispatch_transaction_code])->sum('dispatch_qty');
            $dispatchedQty = ($data) ? $data : 0;

            $Qty = $acceptedQty - $dispatchedQty;
            if ($this->dispatch_qty > $Qty) {
                $this->addError($attribute, Yii::t('app/validation', 'Dispatch Qty can not be grater than the accepted qty.'));
            }
        }
    }

    public function ValidateDiscount($attribute, $params) {
        if ($this->discount_amount > $this->amount) {
            $this->addError($attribute, Yii::t('app/validation', 'Discount amount can not be grater than amount.'));
        }
    }

    public function checkProductAvailabel($productCode, $date) {

        $model = new TblProduct();
        $array = $model->getProduct(trim($productCode), $date);

        return $array;
    }

    public function getProductDispatchCode() {
        return $this->hasOne(TblProductDispatch::className(), ['challan_no' => 'challan_no']);
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        $type = Yii::$app->general->getforeignkey($this->productDispatchCode, 'vendor_type');
        if (!empty($type)) {
            $type = $type == 'DCS' ? 'VLC' : $type;
            $sentboxArray[] = [
                'code' => Yii::$app->general->getforeignkey($this->productDispatchCode, 'vendor_code'),
                'type' => $type
            ];
            $this->union_code = Yii::$app->general->getforeignkey($this->productDispatchCode, 'union_code');
        }
        foreach ($sentboxArray as $sent) {
            $flag = (isset($this->operation) && $this->operation == true) ? $this->operation : (($insert) ? 'INSERT' : 'UPDATE');
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
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function getEntityRefCode() {
        $type = Yii::$app->general->getforeignkey($this->productRequisitionCode, 'vendor_type');
        $name = '';
        if ($type == 'BMC') {
            $name = Yii::$app->general->getmultiforeignkey($this->productRequisitionCode, ['bmcCode'], 'ref_code');
        } else if ($type == 'DCS') {
            $name = Yii::$app->general->getmultiforeignkey($this->productRequisitionCode, ['dcsCode'], 'ref_code');
        }
        return $name;
    }

    public function getEntityExCode() {
        $type = Yii::$app->general->getforeignkey($this->productRequisitionCode, 'vendor_type');
        $name = '';
        if ($type == 'BMC') {
            $name = Yii::$app->general->getmultiforeignkey($this->productRequisitionCode, ['bmcCode'], 'bmc_code_ex');
        } else if ($type == 'DCS') {
            $name = Yii::$app->general->getmultiforeignkey($this->productRequisitionCode, ['dcsCode'], 'dcs_code_ex');
        }
        return $name;
    }

}
