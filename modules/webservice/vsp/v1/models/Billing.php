<?php

namespace app\modules\webservice\vsp\v1\models;

use Yii;
//use app\modules\dcsoperation\models\TblDcsPaymentCycle;
//use app\modules\payment\models\TblDcsPaymentCycle;
//use app\modules\dcsoperation\models\TblFarmerBill;
use yii\helpers\ArrayHelper;
use app\modules\payment\models\TblDcsPaymentCycleApplicability;
use app\modules\payment\models\TblMemberPayment;

class Billing extends TblDcsPaymentCycleApplicability {

    public function getBillingData($data) {
        $paymentCycle = new TblDcsPaymentCycleApplicability();
        $paymentCycleCodes = $paymentCycle->find()
                        ->select(['payment_cycle_applicabilty_code'])
                        ->where(['<=','from_date' , $data['date']])
                        ->andwhere(['dcs_code'=>$this->dcs_code])
                        ->orderBy('from_date DESC')
                        ->limit($data['count'] + 1)->all();
        $CycleCode = ArrayHelper::getColumn($paymentCycleCodes, 'payment_cycle_applicabilty_code');
       
        $model = new TblMemberPayment();
        $result = $model->find()->select(['dcs_payment_cycle_code','total_amount','total_deduction','final_amount','disburse_amount','member_code','payment_date','status','qty','avg_fat','avg_snf','kg_fat','kg_snf','avg_rate','bank_status'])->where(['dcs_payment_cycle_applicabilty_code' => $CycleCode, 'dcs_code' => $this->dcs_code])->orderBy('member_payment_code DESC')->all();
        return $result;
    }

}


