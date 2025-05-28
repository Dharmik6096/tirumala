<?php

namespace app\modules\product\models;

use Yii;
use app\modules\product\models\TblProductRequisition;
use app\modules\product\models\TblProduct;
use app\modules\globalmaster\models\TblUnits;
use yii\helpers\Json;
use webvimark\modules\UserManagement\models\User;
use app\modules\syncutility\models\TblSentbox;
use app\modules\product\models\TblDispatchCenter;

/**
 * This is the model class for table "tbl_product_requisition_transaction".
 *
 * @property string $requisition_transaction_code
 * @property string $product_requisition_code
 * @property string $requisition_on_date
 * @property string $quantity
 * @property string $provisional_rate
 * @property string $provisional_amount
 * @property string $discount_amount
 * @property string $product_code
 * @property string $status
 * @property integer $is_approved
 * @property string $approved_by
 * @property string $approved_quantity
 * @property string $approved_date
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
class TblProductRequisitionTransaction extends \app\models\ChildModel {

    public $product_name, $union_code;
    public $check_record;
    public $req_action;
    public $operation = TRUE;
    public $uom;
    public $is_sentbox = TRUE;
    public $remark;

//    public $scheme_type;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_requisition_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_code', 'quantity'], 'required', 'except' => ['submit', 'androidsync', 'dispatchWithReq']],
                [['requisition_transaction_code', 'check_record', 'req_action', 'union_code', 'remark'], 'safe'],
                [['requisition_transaction_code', 'requisition_on_date', 'product_requisition_code', 'product_code', 'status', 'approved_by', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['quantity'], 'number', 'min' => 1, 'message' => Yii::t('app/validation', '{attribute} must be a digit. e.g. "01".'), 'tooBig' => '{attribute} Should be less than 999', 'tooSmall' => '{attribute} Should be greater than 1', 'except' => ['addSchemeProduct', 'dispatchWithReq']],
                [['quantity', 'provisional_rate', 'provisional_amount', 'discount_amount', 'approved_quantity'], 'number'],
                [['product_code'], 'validateProduct', 'on' => ['addProduct']],
                [['requisition_on_date'], 'validateDeliveryDate', 'except' => ['androidsync', 'dispatchWithReq']],
                [['approved_quantity'], 'validateApprovedQty', 'except' => ['androidsync', 'dispatchWithReq']],
                [['is_approved', 'originating_type'], 'integer'],
                [['approved_date', 'created_at', 'updated_at'], 'safe'],
                ['discount_amount', 'default', 'value' => 0],
                ['is_approved', 'default', 'value' => 0],
                ['provisional_rate', 'default', 'value' => 0],
                ['provisional_amount', 'default', 'value' => 0],
                [['dispatch_center_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'requisition_transaction_code' => Yii::t('app', 'Requisition Transaction Code'),
            'product_requisition_code' => Yii::t('app', 'Product Requisition Code'),
            'quantity' => Yii::t('app', 'Quantity'),
            'provisional_rate' => Yii::t('app', 'Rate'),
            'provisional_amount' => Yii::t('app', 'Amount (Rs.)'),
            'discount_amount' => Yii::t('app', 'Discount Amount (Rs.)'),
            'product_code' => Yii::t('app', 'Product'),
            'status' => Yii::t('app', 'Status'),
            'is_approved' => Yii::t('app', 'Is Approved'),
            'approved_by' => Yii::t('app', 'Approved By'),
            'approved_quantity' => Yii::t('app', 'Approved Quantity'),
            'approved_date' => Yii::t('app', 'Approved Date'),
            'requisition_on_date' => Yii::t('app', 'Order Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'Remarks'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'uom' => Yii::t('app', 'UOM'),
        ];
    }

    public function validateProduct($attribute, $params) {
        if (!empty($this->$attribute)) {
            $check = $this->find()->where(['product_code' => $this->product_code, 'product_requisition_code' => $this->product_requisition_code])->andWhere(['<>', 'requisition_transaction_code', $this->requisition_transaction_code])->count();
            if ($check != 0) {
                $this->addError($attribute, Yii::t('app/validation', 'Product is already added.'));
                return false;
            }
        }
    }

    public function validateDeliveryDate($attribute, $params) {
        $chek_date = TRUE;
        if (Yii::$app->getRequest()->getQueryParam('id') == -1) {
            $reqModel = new TblProductRequisition();
            $jsonData = Json::decode($_POST['product_req']);
            $list = [];
            $reqModel->addProductRequisition($jsonData);

            $delivery_date = $reqModel->req_date;
        } else {
            $product_requisition_code = Yii::$app->getRequest()->getQueryParam('id');
            if (!empty($product_requisition_code)) {
                $reqModel = TblProductRequisition::find()->select('req_date')->where(['tbl_product_requisition.product_requisition_code' => $this->product_requisition_code])->one();
                $delivery_date = !empty($reqModel['req_date']) ? $reqModel['req_date'] : '';
            } else {
                $chek_date = FALSE;
            }
        }
        if ($chek_date) {
            if (date('Y-m-d', strtotime($this->requisition_on_date)) < date('Y-m-d', strtotime($delivery_date))) {
                $date = date_create($delivery_date);
                $date = date_format($date, 'd-m-Y');
                $this->addError($attribute, Yii::t('app/validation', 'Delivery date must be greater than or equal to Requisition Date - ' . $date));
                return false;
            }
        }
    }

    public function validateApprovedQty($attribute, $params) {
        if (!empty($this->$attribute)) {
            if (($this->$attribute > $this->quantity)) {
                $this->addError($attribute, Yii::t('app/validation', 'Approved quantity cannot be higher then quantity.'));
                return false;
            }
        }
    }

    public function checkProductAvailabel($productCode, $productReqCode, $date) {

        $reqModel = new TblProductRequisition();
        $reqModel = $reqModel->getRecord($productReqCode);

        $model = new TblProduct();
        $array = $model->getProduct(trim($productCode), $date);

        return $array;
    }

    public function getUom($product_code) {
        $query = TblUnits::find()->select(['unit_name'])->leftJoin('tbl_product', 'tbl_product.unit_code = tbl_units.unit_code')->where(['tbl_product.product_code' => $product_code])->one();
        return $query['unit_name'];
    }

    public function getProductCode() {
        return $this->hasOne(TblProduct::className(), ['product_code' => 'product_code']);
    }

    public function getApproveByCode() {
        return $this->hasOne(User::className(), ['user_code' => 'approved_by']);
    }

    public function getProductRequisitionCode() {
        return $this->hasOne(TblProductRequisition::className(), ['product_requisition_code' => 'product_requisition_code']);
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        $type = Yii::$app->general->getforeignkey($this->productRequisitionCode, 'vendor_type');
        if (!empty($type)) {
            $type = $type == 'DCS' ? 'VLC' : $type;
            $sentboxArray[] = [
                'code' => Yii::$app->general->getforeignkey($this->productRequisitionCode, 'vendor_code'),
                'type' => $type
            ];
            $this->union_code = Yii::$app->general->getforeignkey($this->productRequisitionCode, 'union_code');
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
        $sentbox->source_org_id = $this->union_code;
        $sentbox->dest_org_type = $type;
        return $sentbox;
    }

    public function getRecord($reqCode) {
        $records = $this->find()->where(['requisition_transaction_code' => $reqCode])->one();
        return $records;
    }

    public function getEntityName() {
        $type = Yii::$app->general->getforeignkey($this->productRequisitionCode, 'vendor_type');
        $name = '';
        if ($type == 'BMC') {
            $name = Yii::$app->general->getmultiforeignkey($this->productRequisitionCode, ['bmcCode'], 'bmc_name');
        } else if ($type == 'DCS') {
            $name = Yii::$app->general->getmultiforeignkey($this->productRequisitionCode, ['dcsCode'], 'dcs_name');
        }
        return $name;
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

    public function getDispatchCenter() {
        return $this->hasOne(TblDispatchCenter::className(), ['dispatch_center_code' => 'dispatch_center_code']);
    }

}
