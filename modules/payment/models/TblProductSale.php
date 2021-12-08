<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use app\modules\dcsoperation\models\TblMember;
use app\modules\payment\models\TblMemberCreditLimit;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\payment\models\TblPaymentCycleApplicability;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\collection\models\TblMilkCollection;
use app\modules\collection\models\TblBmcCollection;
use app\modules\payment\models\TblSaleInstallments;
use app\modules\product\models\TblProductSaleRateApplicability;
use app\modules\dcsaccounting\models\TblTaxDetail;
use app\modules\dcsaccounting\models\TblTaxDepends;
use app\modules\configuration\models\TblDcsGeneralConfig;
use app\modules\payment\models\TblProductSaleTaxCalculatedHistory;
use app\modules\product\models\TblProductStock;
use app\modules\product\models\TblProductStockHistory;
use app\modules\product\models\TblProductStockTransaction;
use app\modules\syncutility\models\TblSentbox;

/**
 * This is the model class for table "tbl_product_sale".
 *
 * @property string $product_sale_code
 * @property string $dcs_code
 * @property string $union_code
 * @property string $invoice_date
 * @property string $amount
 * @property string $other_amount
 * @property string $discount
 * @property string $paid_amount
 * @property string $amount_due
 * @property integer $is_installment
 * @property integer $no_of_installment
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property TblDcs $dcsCode
 * @property TblUnions $unionCode
 * @property TblProductSaleTransaction[] $tblProductSaleTransactionails
 * @property TblSaleInstallments[] $tblSaleInstallments
 */
class TblProductSale extends \app\models\ChildModel {

    public $payment_cycle_code, $available_credit, $customer_name, $ex_code;
    public $is_sentbox = TRUE;
    public $saveChildRecords = TRUE;
    public $import_union_code, $import_eipl_code, $import_key_pattern, $product_code, $quantity, $member_code, $available_stock;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_product_sale';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['payment_mode'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'payment_mode');
                }, 'on' => ['productSaleImport', 'productSaleMemberImport']],
            [['product_sale_code', 'dcs_code', 'union_code'], 'required', 'except' => ['saleProduct', 'androidsync', 'productSaleImport', 'productSaleMemberImport']],
            [['bmc_code', 'customer_type', 'customer_code', 'invoice_date', 'payment_mode'], 'required', 'on' => ['saleProduct', 'productSaleImport']],
            [['union_code', 'ex_code', 'customer_name'], 'required', 'on' => ['saleProduct']],
            [['product_code'], 'required', 'on' => ['productSaleImport', 'productSaleMemberImport']],
            [['dcs_code', 'member_code', 'invoice_date', 'payment_mode'], 'required', 'on' => ['productSaleMemberImport']],
            [['product_sale_code', 'dcs_code', 'union_code', 'created_by', 'updated_by'], 'string', 'except' => ['productSaleImport']],
            [['invoice_date', 'created_at', 'updated_at', 'dcs_code', 'union_code', 'invoice_date', 'no_of_installment', 'is_installment', 'payment_cycle_code', 'available_credit', 'type', 'customer_type', 'customer_code', 'payment_mode', 'originating_org_code', 'originating_org_type', 'originating_type', 'bmc_code', 'deduction_start_date', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'plant_code', 'mcc_plant_code', 'product_code', 'quantity', 'discount', 'member_code', 'available_stock'], 'safe'],
            [['amount', 'other_amount', 'discount', 'paid_amount', 'amount_due', 'no_of_installment'], 'number'],
            [['other_amount', 'discount', 'paid_amount', 'amount_due', 'quantity'], 'number', 'min' => 0],
            [['discount'], 'validateDisccount', 'except' => ['productSaleImport', 'productSaleMemberImport']],
            [['amount_due'], 'checkAmount', 'on' => 'validate_credit'],
            [['paid_amount'], 'validatePaidAmount', 'except' => ['saleProduct', 'androidsync']],
            [['other_amount', 'discount', 'paid_amount', 'amount_due'], 'default', 'value' => 0],
//            [['is_installment'], 'integer'],
//            [['is_installment'], 'integer','min'=>1,'on'=>'payment','when'=>function(){
//                return ($this->amount_due>0);
//            },'tooSmall'=>'You must check pay in installment to pay the due'],
            [['no_of_installment'], 'required', 'on' => 'saleProduct', 'when' => function() {
                    return ($this->payment_mode == 1);
                }, 'whenClient' => "function (attribute, value) { 
              return $('#tblproductsale-payment_mode').val() == 1; 
          }"],
            [['no_of_installment'], 'integer', 'min' => 1, 'on' => 'saleProduct', 'when' => function() {
                    return ($this->payment_mode == 1);
                }, 'whenClient' => "function (attribute, value) {
              return $('#tblproductsale-payment_mode').val() == 1; 
          }", 'tooSmall' => 'You must have atleast 1 installment to pay the due'],
            [['dcs_code'], 'required', 'on' => 'saleProduct', 'when' => function() {
                    return ($this->customer_type == 'Member');
                }, 'whenClient' => "function (attribute, value) { 
              return $('#tblproductsale-customer_type').val() == 'Member'; 
          }"],
//            [['no_of_installment'], 'required','on'=>'payment','when'=>function(){
//                return ($this->is_installment==1);
//            },'message'=>'You must have atleast 1 installment to pay the due'],
            //[['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            //[['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
            [['bmc_code'], function ($attribute, $params) {
                    Yii::$app->general->validateBMC($this, $attribute, 'bmc_code');
                }, 'on' => ['productSaleImport']],
            [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code'], 'on' => ['productSaleImport']],
            [['customer_type'], function ($attribute, $params) {
                    $this->union_code = Yii::$app->general->getforeignkey($this->bmcCode, 'union_code');
                    Yii::$app->general->validateGlobalData($this, $attribute, 'customer_type', FALSE, TRUE, ['union_code' => $this->union_code]);
                }, 'on' => ['productSaleImport']],
            [['customer_type'], 'exist', 'skipOnError' => true, 'targetClass' => TblCustomerType::className(), 'targetAttribute' => ['customer_type' => 'customer_type'], 'on' => ['productSaleImport']],
            [['invoice_date'], 'convertDateDot', 'on' => ['productSaleImport', 'productSaleMemberImport']],
            [['invoice_date'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['productSaleImport', 'productSaleMemberImport']],
            [['invoice_date'], 'convertDate', 'on' => ['productSaleImport', 'productSaleMemberImport']],
            [['invoice_date'], 'setImport', 'on' => ['productSaleImport', 'productSaleMemberImport']],
            [['invoice_date'], 'validatePaymentCycle', 'skipOnError' => true, 'on' => ['saleProduct', 'productSaleImport', 'productSaleMemberImport']],
            [['quantity'], 'validateQty', 'on' => ['productSaleImport', 'productSaleMemberImport']]
        ];
    }

    public function validateDisccount($attribute, $param) {
        if (!empty($this->discount) && $this->discount > (int) $this->amount + (int) $this->other_amount) {
            $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' must be less then 100%.'));
        }
    }

    public function validatePaidAmount($attribute, $param) {
        if (!empty($this->paid_amount) && $this->paid_amount > (int) $this->amount + (int) $this->other_amount - (int) $this->discount) {
            $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' must be less then Amount due.'));
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'product_sale_code' => Yii::t('app', 'Product Sale Code'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'union_code' => Yii::t('app', 'Union'),
            'invoice_date' => Yii::t('app', 'Sale Date'),
            'amount' => Yii::t('app', 'Amount'),
            'other_amount' => Yii::t('app', 'Other Amount'),
            'discount' => Yii::t('app', 'Discount'),
            'paid_amount' => Yii::t('app', 'Paid Amount'),
            'amount_due' => Yii::t('app', 'Total Amount'),
            'is_installment' => Yii::t('app', 'Pay in Installment?'),
            'no_of_installment' => Yii::t('app', 'No. of Installment'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'available_credit' => Yii::t('app', ''),
            'plant_code' => Yii::t('app', 'PLANT'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'customer_code' => Yii::t('app', 'Customer Code'),
            'customer_type' => Yii::t('app', 'Type'),
            'payment_mode' => Yii::t('app', 'Payment Type'),
            'ex_code' => Yii::t('app', 'Code'),
            'customer_name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'customer_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

//    public function getMemberCredit() {
//        return $this->hasOne(TblMemberCreditLimit::className(), ['member_code' => 'member_code']);
//    }
//    public function getMemberCode() {
//        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
//    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblProductSaleDetails() {
        return $this->hasMany(TblProductSaleTransaction::className(), ['product_sale_code' => 'product_sale_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSaleInstallments() {
        return $this->hasMany(TblSaleInstallments::className(), ['sale_code' => 'product_sale_code']);
    }

    /**
     * @inheritdoc
     * @return TblProductSaleQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblProductSaleQuery(get_called_class());
    }

    public function getCode() {
        $data = $this->find()->select(["MAX(CAST(product_sale_code AS INT)) as product_sale_code"])->where(['union_code' => $this->union_code])->one();
        return $data['product_sale_code'] + 1;
    }

    public function addProductSaleData($jsonData) {
        $this->union_code = $jsonData['union_code'];
        $this->attributes = $jsonData;
        $this->product_sale_code = (string) Yii::$app->general->getCodeAutoIncrement($this);
        $this->dcs_code = $jsonData['dcs_code'];
//        $this->member_code = $jsonData['member_code'];
        $this->invoice_date = date('Y-m-d H:i:s');
        $this->other_amount = 0;
        $this->discount = 0;
        $this->paid_amount = 0;
        $this->is_installment = 0;
        $this->no_of_installment = 0;
        //$this->is_active = 1;
    }

    public function checkAmount($attribute, $params) {
        $amt = !empty($this->amount_due) ? $this->amount_due : 0;
        $noOfIn = !empty($this->no_of_installment) ? $this->no_of_installment : 0;
        $due = !empty($noOfIn) ? $amt / $noOfIn : $amt;
        $available = $this->available_credit;
        if ($available == '') {
            $available = 0;
        }
        if ($due > $available) {
            $this->addError($attribute, "Amount Due '" . $due . "' is more then Available Credit.");
        }
    }

    public function getData($data) {
        $from_date = $data['from_date'];
        $to_date = $data['to_date'];
        return $this->find()->select(['product_sale_code', 'amount', 'amount_due', 'invoice_date',
                            'no_of_installment', 'is_installment',
                            'dcs_code', 'union_code'])
//                        ->where(['member_code' => $this->member_code])
                        ->andWhere("invoice_date between '$from_date' and '$to_date' ")->asArray()->all();
    }

    public function getDcsBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'dcs_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type']);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code']);
    }

    public function getMemberDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'member_code']);
    }

    public function validatePaymentCycle($attribute, $params) {
        if (empty($this->getErrors())) {
            if (!empty($this->invoice_date) && $this->payment_mode == 1) {
                $model = new TblPaymentCycleApplicability();
                $model->applicable_type = strtolower($this->customer_type) == 'member' ? 'DCS' : $this->customer_type;
                $model->applicable_code = $this->bmc_code;
                $model->applicable_for = 'BMC';
                $modelData = $model->getApplicablePaymentCycle(date('Y-m-d', strtotime($this->invoice_date)));
                if (!empty($modelData)) {
                    if ($modelData->data_lock_bmc == 1) {
                        $this->addError($attribute, "Payment Cycle is locked for Sale Date.");
                    }
                    $fromDate = date('Y-m-d', strtotime($modelData->from_date));
                    $toDate = date('Y-m-d', strtotime($modelData->to_date));
                    $saledAmount = 0;
                    $where = [];
                    $where = ['payment_mode' => $this->payment_mode, 'customer_type' => $this->customer_type, 'customer_code' => $this->customer_code];
//                if (strtolower($this->sale_type) == 'member') {
//                    $where['member_code'] = $this->member_code;
//                }
                    $data = $this->find()
                            ->select(['amount_due' => 'ISNULL(SUM(ISNULL(amount_due, 0)),0)'])
                            ->where(['between', 'cast(invoice_date as date)', $fromDate, $toDate])
                            ->andWhere($where)
                            ->one();
                    if (!empty($data->amount_due)) {
                        $saledAmount = $data->amount_due;
                    }
                    $model = new TblBmcCollection();
                    $collWhere = [];
                    $collWhere = ['customer_type' => $this->customer_type, 'customer_code' => $this->customer_code];
                    if (strtolower($this->customer_type) == 'member') {
                        $model = new TblMilkCollection();
                        $collWhere = ['member_code' => $this->customer_code];
                    }
                    $modelData = $model->find()
                            ->select(['amount' => 'ISNULL(SUM(ISNULL(amount, 0)), 0)'])
                            ->where(['between', 'date_time_of_collection', $modelData->from_date, $modelData->to_date])
                            ->andWhere($collWhere)
                            ->one();
                    $creditAmount = 0;
                    if (!empty($modelData->amount)) {
                        $creditAmount = $modelData->amount;
                    }
                    $availableCredit = $creditAmount - $saledAmount;
                    $amt = !empty($this->amount_due) ? $this->amount_due : 0;
                    $noOfIn = !empty($this->no_of_installment) ? $this->no_of_installment : 0;
                    $due = !empty($noOfIn) ? $amt / $noOfIn : $amt;
                    $config = isset(Yii::$app->session->get('unionConfig')[$this->union_code]['check_credit_limit']) ? Yii::$app->session->get('unionConfig')[$this->union_code]['check_credit_limit'] : '';
                    if ($config == 1 && $due > $availableCredit) {
                        $this->addError('amount_due', "Available Credit Limit is " . $availableCredit);
                        return false;
                    }
                } else {
                    $this->addError($attribute, "Payment Cycle aplicability not available for Sale Date.");
                    return false;
                }
            }
        }
    }

    public function getCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_type' => 'customer_type'])->andwhere(['union_code' => $this->union_code, 'customer_code_ex' => $this->ex_code]);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'customer_code']);
    }

    public function setTransactionData($model, $json, &$childModel) {
        $saleDate = date('Y-m-d', strtotime($model->invoice_date));
        $appCycleAppModel = new TblPaymentCycleApplicability();
        $appCycleAppModel->applicable_type = $model->customer_type;
        $appCycleAppModel->applicable_code = $model->bmc_code;
        $appCycleAppModel->applicable_for = 'BMC';
        $appCycleAppModelData = $appCycleAppModel->getApplicablePaymentCycle($saleDate);
        if (!empty($model->payment_mode)) {
            $no = !empty($model->no_of_installment) ? ($model->no_of_installment) : 1;
//            $cycle = !empty($appCycleAppModelData->payment_cycle_code) ? $appCycleAppModelData->payment_cycle_code : NULL;
//            $appCode = !empty($appCycleAppModelData->payment_cycle_applicabilty_code) ? $appCycleAppModelData->payment_cycle_applicabilty_code : NULL;
            $model->amount_due = !empty($model->amount_due) ? $model->amount_due : 0;
            $instAmount = floatval($model->amount_due / $no);
            $ai = 1;
            for ($i = 0; $i < $no; $i++) {
                $installmentModel = new TblSaleInstallments();
                $installmentModel->product_sale_code = $model->product_sale_code;
                $installmentModel->customer_code = $model->customer_code;
                $installmentModel->customer_type = $model->customer_type;
                $installmentModel->dcs_code = $model->dcs_code;
                $installmentModel->union_code = $model->union_code;
                $installmentModel->plant_code = $model->plant_code;
                $installmentModel->mcc_plant_code = $model->mcc_plant_code;
                $installmentModel->bmc_code = $model->bmc_code;
                $installmentModel->main_amount = $model->amount_due;
                $installmentModel->installment_amount = $instAmount;
                $installmentModel->installment_status = 0;
                $installmentModel->payment_cycle_applicability_code = NULL; //$appCode;
                $installmentModel->payment_cycle_code = NULL; //$cycle;
                $installmentModel->product_sale_installment_code = Yii::$app->general->getTransactionCode($installmentModel, $model->product_sale_code, $ai);
                $paymentCycleDate = Yii::$app->general->getforeignkey($installmentModel->tblPaymentCycleCode, 'from_date');
                $paymentCycleDate = !empty($paymentCycleDate) && $paymentCycleDate != 'N/A' ? date('Y-m-d', strtotime($paymentCycleDate)) : NULL;
                $installmentModel->installment_date = NULL; //$paymentCycleDate;
                $childModel[] = $installmentModel;
//                if (!empty($installmentModel->payment_cycle_code)) {
//                    $appCycleAppModel = new TblPaymentCycle();
//                    $cycle = $appCycleAppModel->getNextCycleCode($installmentModel->payment_cycle_code, $model->bmc_code, $model->customer_type, 'BMC', $appCode);
//                }
                $ai++;
            }
        }
    }

    public function setImport($attribute, $params) {
        if (empty($this->getErrors())) {
            if (!empty($this->member_code)) {
                $dcs = new TblDcs();
                $this->dcs_code = $dcs->getValidDcs($this->dcs_code);
                if (empty($this->dcs_code)) {
                    $this->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'DCS') . ' is invalid'));
                } else {
                    $dcs_data = $this->mainDcsCode;
                    $this->bmc_code = Yii::$app->general->getforeignkey($this->mainDcsCode, 'bmc_code');
                    $this->union_code = $dcs_data->union_code;
                    if ($this->union_code != $this->import_union_code) {
                        $this->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'DCS') . 'Code is invalid for Mapped Union.'));
                    }
                }
                $memberModel = new TblMember();
                $memberCode = str_pad($this->member_code, 4, '0', STR_PAD_LEFT);
                $data = $memberModel->validateMember($this->dcs_code, $memberCode);
                $this->customer_type = 'Member';
                if (empty($data)) {
                    $this->addError('member_code', Yii::t('app/validation', 'Member Code Is Invalid.'));
                } else {
                    $this->customer_code = $data->member_code;
                    $detail = Yii::$app->general->validateDeactivateDcs($this, $this->invoice_date, '', TRUE, $this->customer_code);
                    if ($detail === false) {
                        $this->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'DCS') . ' Or Member is Deactivated.'));
                    }
                }
            } else {
                Yii::$app->general->validateCustomer($this);
                if (!empty($this->customer_type) && strtolower($this->customer_type) == 'dcs') {
                    $detail = Yii::$app->general->validateDeactivateDcs($this, $this->invoice_date);
                    if ($detail === false) {
                        $this->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'DCS') . ' is Deactivated.'));
                    }
                }
            }
            $this->no_of_installment = $this->payment_mode == 1 ? (isset($this->no_of_installment) ? $this->no_of_installment : 1) : 0;
            $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'mcc_plant_code');
            $this->plant_code = Yii::$app->general->getforeignkey($this->bmcCode, 'plant_code');
        }
    }

    public function setChildTable(&$model, &$modelSave, &$errors) {
        $model->product_sale_code = Yii::$app->general->getUuid();

        $detailModel = new TblProductSaleTransaction();
        $detailModel->attributes = $model->attributes;
        $detailModel->product_sale_transaction_code = Yii::$app->general->getTransactionCode($detailModel, $detailModel->product_sale_code);
        $detailModel->product_code = $model->product_code;
        $detailModel->quantity = $model->quantity;
        $detailModel->discount = $model->discount;
        $this->loadRate($model, $detailModel);
        $detailModel->tax_code = Yii::$app->general->getforeignkey($detailModel->productCode, 'tax_code');
        $this->getCalculation($model, $detailModel);
        $this->getCalculation($model, $detailModel, 'check');
        $detailModel->amount = $model->amount;
        $model->other_amount = 0;
        $model->paid_amount = $model->payment_mode == 1 ? 0 : $model->amount_due;
        $model->is_installment = $model->payment_mode == 1 ? 1 : 0;
        $model->no_of_installment = $model->payment_mode == 1 ? $model->no_of_installment : 0;
        $this->createProductSaleData($model, $detailModel, $modelSave);
        if (!$detailModel->validate()) {
            $errors[] = $detailModel->getErrors();
        }
        if (!empty($model->discount) && $model->discount > (int) $model->amount + (int) $model->other_amount) {
            $model->addError('discount', Yii::t('app/validation', $this->getAttributeLabel('discount') . ' must be less then 100%.'));
        }
        array_push($modelSave, $detailModel);
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->invoice_date = !empty($this->invoice_date) ? Yii::$app->controls->view_date($this->invoice_date, 'php:Y-m-d') : NULL;
        }
    }

    public function convertDateDot() {
        try {
            $this->invoice_date = Yii::$app->controls->view_date($this->invoice_date, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->invoice_date = '-';
        }
    }

    public function loadRate(&$model, $detailModel) {
        if (empty($model->getErrors())) {
            $is_member_rate = strtolower($model->customer_type) == 'member' ? 1 : 0;
            $type = strtolower($model->customer_type) == 'member' ? 'DCS' : $model->customer_type;
            $customer_code = strtolower($model->customer_type) == 'member' ? $model->dcs_code : $model->customer_code;
            $date = !empty($model->invoice_date) ? date('Y-m-d', strtotime($model->invoice_date)) : date('Y-m-d');
            $appQuery = TblProductSaleRateApplicability::find()->innerJoinWith(['productRateCode', 'productCode'])
                    ->select(['product_sale_rate_applicability_code', 'tbl_product.unit_code', 'tbl_product_sale_rate.sale_rate', 'tbl_product_sale_rate_applicability.wef_date as dt'])->groupBy(['product_sale_rate_applicability_code', 'tbl_product_sale_rate.sale_rate', 'tbl_product_sale_rate_applicability.wef_date', 'tbl_product.unit_code'])
                    ->having(['<=', '[tbl_product_sale_rate_applicability].[wef_date]', $date])
                    ->where(['tbl_product_sale_rate.product_code' => $model->product_code, 'tbl_product_sale_rate_applicability.applicable_for' => $type, 'tbl_product_sale_rate_applicability.is_member_rate' => (int) $is_member_rate, 'tbl_product_sale_rate_applicability.applicable_code' => $customer_code]);
            $app = $appQuery->orderBy(['tbl_product_sale_rate_applicability.wef_date' => SORT_DESC])->createCommand()->queryOne();
            if (!empty($app)) {
                $detailModel->product_sale_rate_applicability_code = $app['product_sale_rate_applicability_code'];
                $detailModel->rate = $app['sale_rate'];
                $detailModel->x_col1 = $app['sale_rate'];
                $detailModel->unit_code = $app['unit_code'];
                $model->amount = $detailModel->quantity * $detailModel->rate;
            }
        }
    }

    public function getCalculation(&$model, &$detailModel, $flag = '') {
        if (empty($model->getErrors())) {
            $tax = $detailModel->tax_code;
            $value = !empty($flag) ? $model->discount : $detailModel->rate;
            $flag = !empty($flag) ? true : false;
            $incTax = (isset($_POST['incTax']) && (!empty($_POST['incTax']) || $_POST['incTax'] == 0)) ? $_POST['incTax'] : '';
            $incTax = ($incTax == true || $incTax == 1) ? 1 : (($incTax == false || $incTax == 0) ? 0 : $incTax);
            $modeltax = new TblTaxDetail();
            $data = $modeltax->getDetail($tax);
            $dependModel = new TblTaxDepends();
            $records = [];
            $calculation = null;
            $configModel = new TblDcsGeneralConfig();
            $configModel->union_code = $model->union_code;
            $configModelData = $configModel->getData();

            $total = 0;
            $rateWithTax = 0;
            $per = 0;
            foreach ($data as $key => $d) {
                if ($incTax != '') {
                    $rateWithTax = $incTax;
                } else {
                    $rateWithTax = !empty($configModelData->sale_rate_with_tax) ? $configModelData->sale_rate_with_tax : 0;
                }
                $records[$key]['tax_code'] = $d->basic_tax_code;
                $records[$key]['tax_name'] = Yii::$app->general->getforeignkey($d->basicTaxCode, 'basic_tax_name');
                $records[$key]['tax_val'] = $d->percentage;
                $records[$key]['operation'] = ($d->type == 0) ? 'Addition' : 'Substraction';

                if ($key == 0) {
                    $calculation = $value * $d->percentage / 100;
                } else {
                    $sum = 0;
                    $depend_data = $dependModel->getDepends($d->tax_detail_code);
                    foreach ($depend_data as $depend) {
                        $sum += $value;
                    }
                    $calculation = $sum * $d->percentage / 100;
                }
                $records[$key]['amount'] = $calculation;
                if ($d->type == 0) {
                    if ($d->percentage != 100) {
                        $per += $d->percentage;
                    }
                    $total += $records[$key]['amount'];
                } else {
                    if ($d->percentage != 100) {
                        $per -= $d->percentage;
                    }
                    $total -= $records[$key]['amount'];
                }
            }
            if ($rateWithTax == 1 && !$flag) {
                $total = ($value * 100) / ($per + 100);
                $value = $total;
            }
            if (!$flag) {
                // var totalAmt = obj1.total;
                // var changed_amount = obj1 . changedAmount;
                (float) $taxAmt = ($detailModel->rate - $total) * $detailModel->quantity; //var taxAmt = (rateValue - totalAmt) * recQty;
                // var disc = obj1 . totalDiscount;
//            $chagnedRate = (float) $value; //var chagnedRate = obj1 . changedAmount;
                (float) $changeamount = $value * $detailModel->quantity; //var changeamount = (obj1 . changedAmount * recQty) . toFixed(2);
//            $taxAmount = $taxAmt; //var taxAmount = Math . abs(taxAmt . toFixed(2));
                $detailModel->tax_amount = (float) $value; //$('#tblproductsaletransaction-rate') . val(chagnedRate . toFixed(2));
                $model->amount = $changeamount; //$('#tblproductsale-amount') . val(changeamount);
                $detailModel->tax_amount = (float) $taxAmt; //$('#tblproductsaletransaction-tax_amount') . val(taxAmount);
                (float) $totalAmount = $changeamount + $taxAmt; // var totalAmount = parseFloat(changeamount) + parseFloat(taxAmount);
                $model->amount_due = $totalAmount; //$('#tblproductsale-amount_due').val(totalAmount.toFixed(0));
            } else {
//                $totalAmt = $total; //var totalAmt = obj1 . total;
                $taxAmt = $detailModel->tax_amount;
                $discountValue = $model->discount;
                $diffAmt = $total - $discountValue; //var diffAmt = totalAmt - discountValue;
                $taxAmt = $taxAmt - $diffAmt; //  taxAmt = taxAmt - diffAmt;    
                $existToralAmt = $model->amount_due; //var existToralAmt = $('#tblproductsale-amount_due').val();
                $chagneAmt = $existToralAmt - $diffAmt - $discountValue; //var chagneAmt = existToralAmt - diffAmt - discountValue;
                $model->amount_due = $chagneAmt; // $('#tblproductsale-amount_due').val(chagneAmt.toFixed(0));
                $detailModel->tax_amount = (float) $taxAmt; //$('#tblproductsaletransaction-tax_amount').val(Math.abs(taxAmt.toFixed(2)));
            }
        }
    }

    public function createProductSaleData($model, $detailModel, &$modelSave) {
        if (!empty($model->payment_mode)) {
            $no = !empty($model->no_of_installment) ? ($model->no_of_installment) : 1;
            $cycle = NULL; //$appCycleAppModelData->payment_cycle_code;
            $appCode = NULL; //$appCycleAppModelData->payment_cycle_applicabilty_code;
            $instAmount = floatval($model->amount_due / $no);
            $ai = 1;
            for ($i = 0; $i < $no; $i++) {
                $installmentModel = new TblSaleInstallments();
                $installmentModel->product_sale_code = $model->product_sale_code;
                $installmentModel->customer_code = $model->customer_code;
                $installmentModel->customer_type = $model->customer_type;
                $installmentModel->dcs_code = $model->dcs_code;
                $installmentModel->union_code = $model->union_code;
                $installmentModel->plant_code = $model->plant_code;
                $installmentModel->mcc_plant_code = $model->mcc_plant_code;
                $installmentModel->bmc_code = $model->bmc_code;
                $installmentModel->main_amount = $model->amount_due;
                $installmentModel->installment_amount = $instAmount;
                $installmentModel->installment_status = 0;
                $installmentModel->payment_cycle_applicability_code = NULL; //$appCode;
                $installmentModel->payment_cycle_code = NULL; //$cycle;
                $installmentModel->product_sale_installment_code = Yii::$app->general->getTransactionCode($installmentModel, $model->product_sale_code, $ai);
                $installmentModel->installment_date = NULL; //$paymentCycleDate;
                $modelSave[] = $installmentModel;
                $ai++;
            }
        }

        $discount_val = !empty($detailModel->discount) ? $detailModel->discount : 0;
        $totalAmount = 0;
        $totalAmount = $totalAmount + $detailModel->amount - $discount_val;
        $credit = $model->amount_due;
        $configModel = new TblDcsGeneralConfig();
        $configModel->union_code = $model->union_code;
        $configModelData = $configModel->getData();
        $rateWithTax = !empty($configModelData->sale_rate_with_tax) ? $configModelData->sale_rate_with_tax : 0;
        $taxCode = $detailModel->tax_code;
        $totalAmt = !empty($model->amount_due) ? $model->amount_due : 0;
        $discount = !empty($detailModel->discount) ? $detailModel->discount : 0;
        $quantity = !empty($detailModel->quantity) ? $detailModel->quantity : 1;
        $amount = !empty($detailModel->x_col1) ? $detailModel->x_col1 : $detailModel->rate;
        $taxModel = new TblTaxDetail();
        $taxdata = $taxModel->getDetail($taxCode);
        $j = 1;
        if (!empty($taxdata) && $detailModel->tax_amount > 0) {
            foreach ($taxdata as $key => $d) {
                if ($d->percentage != 100) {
                    $disc = 0;
                    $percent = 0;
                    $count = 0;
                    foreach ($taxdata as $keys => $per) {
                        if ($per->percentage != 100) {
                            $percent += $per->percentage;
                            $count++;
                        }
                    }
                    if ($rateWithTax == 1 || $rateWithTax == true) {
                        $val = ($amount * 100) / ($percent + 100);
                        $val = $amount - $val;
                        $disc = $discount * $percent / 100;
                    } else {
                        $val = $amount * $percent / 100;
                        $disc = $discount * $percent / 100;
                    }
                    $val = $val / $count;
                    $disc = $disc / $count;
                    $val = $val * $detailModel->quantity;
                    $val = $val - $disc;
                    $val = round($val, 2);
                    $totalAmount = $totalAmount + $val;
                    $taxModel = new TblProductSaleTaxCalculated();
                    $taxModel->product_sale_code = $detailModel->product_sale_code;
                    $taxModel->product_sale_transaction_code = $detailModel->product_sale_transaction_code;
                    $taxModel->tax_detail_code = $d->tax_detail_code;
                    $taxModelData = $taxModel->getRecords();
                    if (!empty($taxModelData)) {
                        $taxModel = $taxModelData;
                        $historyModel = new TblProductSaleTaxCalculatedHistory();
                        Yii::$app->operation->history($taxModel, $historyModel, UPDATE);
                        $modelSave[] = $historyModel;
                    } else {
                        $taxModel->product_sale_tax_calculated_code = Yii::$app->general->getTransactionCode($taxModel, $taxModel->product_sale_code, $j);
                        $j++;
                    }
                    $taxModel->value = $val;
                    $modelSave[] = $taxModel;
                }
            }
        }

        $fstockModel = new TblProductStock();
        $sale_type = strtoupper($model->customer_type) == 'MEMBER' ? 'DCS' : 'BMC';
        $sale_code = strtoupper($model->customer_type) == 'MEMBER' ? $model->dcs_code : $model->bmc_code;
        $fstockModel->setCodes($sale_type, $sale_code);
        $fstockModel->product_code = $detailModel->product_code;
        $fstockModel->union_code = $model->union_code;
        $txn_type = strtoupper($model->customer_type) == 'MEMBER' ? 'PRODUCT SALE TO MEMBER' : 'PRODUCT SALE';
        $existfromStock = $fstockModel->getExistStock($sale_type);

        $f_stock = 0;
        $qty = $detailModel->quantity;
        if (!empty($existfromStock)) {
            $historyModel = new TblProductStockHistory();
            Yii::$app->operation->history($existfromStock, $historyModel, UPDATE);
            $modelSave[] = $historyModel;
            $f_stock = $existfromStock->stock;
            $existfromStock->stock = $f_stock - $qty;
            $fstockModel = $existfromStock;
            $modelSave[] = $fstockModel;

            $i = 1;
            $fstockTxnModel = new TblProductStockTransaction();
            $fstockTxnModel->attributes = $fstockModel->attributes;
            $fstockTxnModel->product_stock_transaction_code = $fstockTxnModel->getCode($i);
            $fstockTxnModel->old_value = $f_stock;
            $fstockTxnModel->new_value = $qty;
            $fstockTxnModel->final_value = $fstockModel->stock;
            $fstockTxnModel->transaction_type = $txn_type;
            $fstockTxnModel->transaction_date = date('Y-m-d');
            $fstockTxnModel->reference_code = $detailModel->product_sale_transaction_code;
            $modelSave[] = $fstockTxnModel;
            $i++;
        }
    }

    public function getMainDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function checkPaymentCycleLock() {
        $date = Yii::$app->formatter->asDate($this->invoice_date, 'php:Y-m-d');
        $type = ($this->customer_type == 'Member' ? 'DCS' : $this->customer_type);
        $codeParam = $this->bmc_code;
        $for = 'BMC';
        $flagArray = ($this->customer_type == 'Member' ? ['data_lock_member', 'billing_lock_member'] : ['data_lock_bmc', 'billing_lock_bmc']);
        $payment_model = new TblPaymentCycleApplicability;
        $data = $payment_model->find()
                ->where(['applicable_code' => $codeParam, 'applicable_for' => $for, 'applicable_type' => $type])
                ->andWhere(['<=', 'CAST(from_date as date)', $date])
                ->andWhere(['>=', 'CAST(to_date as date)', $date])
                ->one();

        if (!empty($data)) {
            foreach ($flagArray as $flag) {
                if ($data->$flag == 1) {
                    return FALSE;
                } else {
                    return TRUE;
                }
            }
        } else {
            return TRUE;
        }
    }

    public function validateQty($attribute, $param) {
        $config = isset(Yii::$app->session->get('unionConfig')[$this->union_code]['stock_check_on_sale']) ? Yii::$app->session->get('unionConfig')[$this->union_code]['stock_check_on_sale'] : '';
        if ($config == 1) {
            $sale_type = strtoupper($this->customer_type) == 'MEMBER' ? 'DCS' : 'BMC';
            $sale_code = strtoupper($this->customer_type) == 'MEMBER' ? $this->dcs_code : $this->bmc_code;
            $stockModel = new TblProductStock();
            $stockModel->setCodes(strtoupper($sale_type), $sale_code);
            $stockModel->product_code = $this->product_code;
            $stockModel->union_code = $this->union_code;
            $existtoStock = $stockModel->getExistStock($sale_type);
            $available_stock = !empty($existtoStock) ? $existtoStock->stock : 0;
            if ($available_stock < $this->quantity) {
                $this->addError('quantity', Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' must be less than Available Stock ' . $available_stock));
            }
        }
    }

    public function afterSave($insert, $changedAttributes) {
        $sentboxArray = [];
        if (!empty($this->customer_code)) {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', '', '', $this->customer_code);
        } else if (!empty($this->bmc_code)) {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', '', $this->bmc_code, '', '');
        } else if (!empty($this->mcc_plant_code)) {
            $sentboxArray = Yii::$app->general->getSentBoxCodes('', $this->mcc_plant_code, '', '', '');
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

}
