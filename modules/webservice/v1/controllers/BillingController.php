<?php

namespace app\modules\webservice\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use app\modules\webservice\v1\models\Billing;
use Yii;

class BillingController extends ChildController {

    public function actionBillingData() {
        $model = new Billing();
        $model->setAttributes($this->post_data);
        $result = $model->getBillingData($this->post_data['content']);
//        var_dump($result);die;
        $data = [];
        if (!empty($result)) {
            foreach ($result as $value) {
                $array_temp = [];
                $from_date = $value->paymentCycleCode->from_date;
                $to_date = $value->paymentCycleCode->to_date;
                $array_temp = $value->oldAttributes;
                $array_temp['from_date'] = $from_date; 
                $array_temp['to_date'] = $to_date;               
                $data[] = $array_temp;
            }
            $this->response['data'] = $data;
        }
        return $this->response;
    }
    
}
