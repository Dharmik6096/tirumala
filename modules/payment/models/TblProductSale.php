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
                [['product_sale_code', 'dcs_code', 'union_code'], 'required', 'except' => ['saleProduct', 'androidsync']],
                [['bmc_code', 'union_code', 'customer_type', 'customer_code', 'invoice_date', 'payment_mode', 'ex_code', 'customer_name'], 'required', 'on' => ['saleProduct']],
                [['product_sale_code', 'dcs_code', 'union_code', 'created_by', 'updated_by'], 'string'],
                [['invoice_date', 'created_at', 'updated_at', 'dcs_code', 'union_code', 'invoice_date', 'no_of_installment', 'is_installment', 'payment_cycle_code', 'available_credit', 'type', 'customer_type', 'customer_code', 'payment_mode', 'originating_org_code', 'originating_org_type', 'originating_type', 'bmc_code', 'deduction_start_date', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'plant_code', 'mcc_plant_code'], 'safe'],
                [['amount', 'other_amount', 'discount', 'paid_amount', 'amount_due'], 'number'],
                [['other_amount', 'discount', 'paid_amount', 'amount_due'], 'number', 'min' => 0],
                [['discount'], 'validateDisccount'],
                [['amount_due'], 'checkAmount', 'on' => 'validate_credit'],
                [['paid_amount'], 'validatePaidAmount', 'except' => ['saleProduct', 'androidsync']],
                [['invoice_date'], 'validatePaymentCycle', 'on' => ['saleProduct']],
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
            'customer_code' => Yii::t('app', 'Name'),
            'customer_type' => Yii::t('app', 'Type'),
            'payment_mode' => Yii::t('app', 'Payment Type'),
            'customer_code' => Yii::t('app', 'Name'),
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
        $due = $this->amount_due;
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
                if ($this->amount_due > $availableCredit) {
                    $this->addError('amount_due', "Available Credit Limit is " . $availableCredit);
                    return false;
                }
            } else {
                $this->addError($attribute, "Payment Cycle aplicability not available for Sale Date.");
                return false;
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

}
