<?php

namespace app\modules\webservice\vsp\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use app\modules\webservice\vsp\v1\models\Member;
use app\modules\webservice\models\TblAppActivation;
use Yii;

class MemberController extends ChildController {

    public function actionMemberData() {
        $code = $this->post_data['content'];
        $app_model = new TblAppActivation();
        $app_model->hash_key = $this->post_data['token'];
        $app_model->imei_no = $this->post_data['imei'];
        $app_model->code = $this->post_data['dcs_code'];
        $app_model->type = $this->post_data['type'];
        $app_data = $app_model->activationDetail();
        if($app_data->m_id == $app_data->m_id_a){
            return $this->response['data'] = ['member'=>'No changes occured'];
        }else{
            $model = new Member();
            $model->setAttributes($this->post_data);
            $member_data = $model->memberList();
            $member_data[] = ['m_id' => $app_data->m_id];
            $this->response['data'] = $member_data;
            return $this->response;
        }
    }
    
    public function actionMemberUpdate(){
        $model = new Member();
        $model->setAttributes($this->post_data);
        $member_info = $this->post_data['content'];
        $id = $member_info['member_code'];
        $member_details = $model->findOne($id);
        $member_details->member_name = $member_info['member_name'];
        $member_details->gender_code = $member_info['gender_code'];
        $member_details->bank_code = $member_info['bank_code'];
        $member_details->branch_code = $member_info['branch_code'];
        $member_details->bank_account_no = $member_info['bank_account_no'];
        $member_details->ifsc = $member_info['ifsc'];
        $member_details->adhar_no = $member_info['adhar_no'];
        $member_details->pan_no = $member_info['pan_no'];
//        $encryptedmobile = Yii::$app->general->encryptData($member_info['mobile_no']);
        $member_details->mobile_no = $member_info['mobile_no'];
        $member_details->email = $member_info['email'];
        if($member_details->save()){
            $this->response['data'] = ['message' => 'Member Updated Successfully'];
        }else{
            $errors = $member_details->getErrors();
            $error = [];
            foreach($errors as $key => $value){
                $error[] = $value[0];
            }
            $this->response['data'] = ['message' => $error];
        }
    }
    
    public function actionMemberCreditLimit(){
        $model = new Member();
        $model->setAttributes($this->post_data);
        $member_info = $this->post_data['content'];
        $member_code = $member_info['member_code'];
        $member_data = $model->memberCredit($member_code);
        if(!empty($member_data)){
            $this->response['data'] = $member_data;
        }else{
            $this->response['data'] = ['message'=>'No credit available'];
        }
        return $this->response;
    }
    
}
