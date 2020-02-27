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

/**
 * This is the model class for table "tbl_product_sale".
 *
 * @property string $product_sale_code
 * @property string $dcs_code
 * @property string $union_code
 * @property string $member_code
 * @property string $sale_date_time
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
 * @property TblProductSaleDetails[] $tblProductSaleDetails
 * @property TblSaleInstallments[] $tblSaleInstallments
 */
class TblProductSale extends \app\models\ChildModel {

    public $payment_cycle_code, $available_credit, $plant_code, $mcc_plant_code, $customer_name;

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
                [['product_sale_code', 'dcs_code', 'union_code', 'member_code'], 'required', 'except' => ['saleProduct']],
                [['bmc_code', 'union_code', 'customer_type', 'customer_code', 'sale_date_time', 'sale_mode'], 'required', 'on' => ['saleProduct']],
                [['product_sale_code', 'dcs_code', 'union_code', 'member_code', 'created_by', 'updated_by'], 'string'],
                [['sale_date_time', 'created_at', 'updated_at', 'dcs_code', 'union_code', 'sale_date_time', 'no_of_installment', 'is_installment', 'payment_cycle_code', 'available_credit', 'type', 'sale_type', 'customer_type', 'customer_code', 'sale_mode', 'originating_org_code', 'originating_org_type', 'originating_type', 'bmc_code'], 'safe'],
                [['amount', 'other_amount', 'discount', 'paid_amount', 'amount_due'], 'number'],
                [['other_amount', 'discount', 'paid_amount', 'amount_due'], 'number', 'min' => 0],
                [['discount'], 'validateDisccount'],
                [['amount_due'], 'checkAmount', 'on' => 'validate_credit'],
                [['paid_amount'], 'validatePaidAmount', 'except' => ['saleProduct']],
                [['sale_date_time'], 'validatePaymentCycle', 'on' => ['saleProduct']],
                [['other_amount', 'discount', 'paid_amount', 'amount_due'], 'default', 'value' => 0],
//            [['is_installment'], 'integer'],
//            [['is_installment'], 'integer','min'=>1,'on'=>'payment','when'=>function(){
//                return ($this->amount_due>0);
//            },'tooSmall'=>'You must check pay in installment to pay the due'],
//            [['no_of_installment'], 'integer','min'=>1,'on'=>'payment','when'=>function(){
//                return ($this->is_installment==1);
//            },'tooSmall'=>'You must have atleast 1 installment to pay the due'],
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
            'dcs_code' => Yii::t('app', 'Society Name'),
            'union_code' => Yii::t('app', 'Union'),
            'member_code' => Yii::t('app', 'Member'),
            'sale_date_time' => Yii::t('app', 'Sale Date'),
            'amount' => Yii::t('app', 'Amount'),
            'other_amount' => Yii::t('app', 'Other Amount'),
            'discount' => Yii::t('app', 'Discount'),
            'paid_amount' => Yii::t('app', 'Paid Amount'),
            'amount_due' => Yii::t('app', 'Amount Due'),
            'is_installment' => Yii::t('app', 'Pay in Installment?'),
            'no_of_installment' => Yii::t('app', 'No Of Installments'),
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
            'sale_mode' => Yii::t('app', 'Sale Type'),
            'customer_code' => Yii::t('app', 'Name'),
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

    public function getMemberCredit() {
        return $this->hasOne(TblMemberCreditLimit::className(), ['member_code' => 'member_code']);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblProductSaleDetails() {
        return $this->hasMany(TblProductSaleDetails::className(), ['product_sale_code' => 'product_sale_code']);
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
        $data = $this->find()->select(["MAX(product_sale_code) as product_sale_code"])->where(['union_code' => $this->union_code])->one();
        return $data['product_sale_code'] + 1;
    }

    public function addProductSaleData($jsonData) {
        $this->union_code = $jsonData['union_code'];
        $this->attributes = $jsonData;
        $this->product_sale_code = (string) Yii::$app->general->getCodeAutoIncrement($this);
        $this->dcs_code = $jsonData['dcs_code'];
        $this->member_code = $jsonData['member_code'];
        $this->sale_date_time = date('Y-m-d H:i:s');
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
        return $this->find()->select(['product_sale_code', 'amount', 'amount_due', 'sale_date_time',
                            'no_of_installment', 'is_installment',
                            'dcs_code', 'union_code', 'member_code'])
                        ->where(['member_code' => $this->member_code])->andWhere("sale_date_time between '$from_date' and '$to_date' ")->asArray()->all();
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
        if (!empty($this->sale_date_time) && $this->sale_mode == 1) {
            $model = new TblPaymentCycleApplicability();
            $model->applicable_type = $this->customer_type;
            $model->applicable_code = $this->bmc_code;
            $model->applicable_for = 'BMC';
            $modelData = $model->getApplicablePaymentCycle(date('Y-m-d', strtotime($this->sale_date_time)));
            if (!empty($modelData)) {
                if ($modelData->data_lock_bmc == 1) {
                    $this->addError($attribute, "Payment Cycle is locked for Sale Date.");
                }
                $fromDate = date('Y-m-d', strtotime($modelData->from_date));
                $toDate = date('Y-m-d', strtotime($modelData->to_date));
                $saledAmount = 0;
                $where = [];
                $where = ['sale_type' => $this->sale_type, 'sale_mode' => $this->sale_mode, 'customer_type' => $this->customer_type, 'customer_code' => $this->customer_code];
                if (strtolower($this->sale_type) == 'member') {
                    $where['member_code'] = $this->member_code;
                }
                $data = $this->find()
                        ->select(['amount_due' => 'ISNULL(SUM(ISNULL(amount_due, 0)),0)'])
                        ->where(['between', 'cast(sale_date_time as date)', $fromDate, $toDate])
                        ->andWhere($where)
                        ->one();
                if (!empty($data->amount_due)) {
                    $saledAmount = $data->amount_due;
                }
                $model = new TblBmcCollection();
                $collWhere = [];
                $collWhere = ['customer_type' => $this->customer_type, 'customer_code' => $this->customer_code];
                if (strtolower($this->sale_type) == 'member') {
                    $model = new TblMilkCollection();
                    $collWhere = ['member_code' => $this->member_code];
                }
                $modelData = $model->find()
                        ->select(['amount' => 'ISNULL(SUM(ISNULL(amount, 0)), 0)'])
                        ->where(['between', 'date_time_of_collection', date('Y-m-d H:i:s', strtotime($modelData->from_date)), date('Y-m-d H:i:s', strtotime($modelData->to_date))])
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

}
