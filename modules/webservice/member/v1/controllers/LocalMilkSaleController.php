<?php

namespace app\modules\webservice\member\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use app\modules\dcsoperation\models\TblLocalMilkSale;
use Yii;

class LocalMilkSaleController extends ChildController {

    public function actionLocalSaleData() {
        $model = new TblLocalMilkSale();
        $model->setAttributes($this->post_data);
        $result = $model->getLocalData($this->post_data['member_code'], $this->post_data['content']);
        $data = [];
        foreach ($result as $value) {
            $array_temp = [];
            $array_temp = $value->oldAttributes;
            $array_temp['milk_class_name'] = $value->milkClass->class_name;
            $array_temp['milk_type_name'] = $value->milkType->animal_type_name;
            $array_temp['shift'] = $value->shift->shift;
            $data[] = $array_temp;
        }
        $this->response['data'] = $data;
        return $this->response;
    }

}
