<?php

namespace app\modules\webservice\member\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use app\modules\dcsoperation\models\TblFarmerBill;
use Yii;

class BillingController extends ChildController {

    public function actionBillingData() {
        $model = new TblFarmerBill();
        $model->setAttributes($this->post_data);
        $result = $model->getBillingData($this->post_data['content']);
//        var_dump($result);die;
        $data = [];
        if (!empty($result)) {
            foreach ($result as $value) {
                $array_temp = [];
                $array_temp = $value->oldAttributes;
                $array_temp['from_date'] = Yii::$app->controls->view_date($value->dcsPaymentCode->from_date);
                $array_temp['to_date'] = Yii::$app->controls->view_date($value->dcsPaymentCode->to_date);
                $array_temp['from_shift'] = $value->dcsPaymentCode->fromShiftType->shift;
                $array_temp['to_shift'] = $value->dcsPaymentCode->toShiftType->shift;
                $data[] = $array_temp;
            }
            $this->response['data'] = $data;
        }
        return $this->response;
    }

}
