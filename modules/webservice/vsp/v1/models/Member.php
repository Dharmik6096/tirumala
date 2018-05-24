<?php

namespace app\modules\webservice\vsp\v1\models;

use Yii;
use app\modules\dcsoperation\models\TblMember;
use app\modules\payment\models\TblMemberCreditLimit;
class Member extends TblMember {

    public function memberList() {
        return $this->find()->select(['member_code', 'CONCAT(member_name,\' \',father_name,\' \',surname) as member_name', 'mobile_no','gender_code','caste_category_code','bank_code','branch_code','bank_account_no','ifsc','email','pan_no','adhar_no','dob'])->where(['dcs_code' => $this->dcs_code])->all();
    }
    
    public function memberCount($dcs_code) {
        return $this->find()->where(['dcs_code' => $dcs_code])->count();
    }
    
    public function memberCredit($member_code){
        $credit_model = new TblMemberCreditLimit();
        return $credit_model->find()->select(['member_code','balance'])->where(['member_code' => $member_code])->one();
    }
}
