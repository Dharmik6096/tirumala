<?php

namespace app\modules\webservice\vsp\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use Yii;
use app\modules\webservice\vsp\v1\models\RateChart;
use app\modules\webservice\models\TblAppActivation;
class RateChartController extends ChildController {

    public function actionRateChart(){
        $model = new RateChart();
        $model->setAttributes($this->post_data);
        
        
        $appModel = new TblAppActivation();
        $appModel->setAttributes($this->post_data);
        $appModel->imei_no = $this->post_data['imei'];
        $appModel->code = $this->post_data['dcs_code'];
        $appModel->hash_key = $this->post_data['token'];
        $act_detail = $appModel->activationDetail();
        
        $milk_type_info = $this->post_data['content'];
        $milk_type_code = $milk_type_info['milk_type_code'];
        
        if($milk_type_code == 1){
            $field = 'c_rate_code';
        }
        if($milk_type_code == 2){
            $field = 'b_rate_code';
        }
        if($milk_type_code == 3){
            $field = 'm_rate_code';
        }
        if($act_detail[$field] != $act_detail[$field.'_a']){
            $purchase_code = $act_detail[$field];
            $rate_chart = $model->rateChart($purchase_code,$milk_type_code);
            $this->response['data'] = $rate_chart;
        }else{
            $this->response['data'] = ['No Changes Occured'];
        }
        return $this->response;
    }
    
}
