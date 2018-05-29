<?php

namespace app\modules\webservice\bmc\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use app\modules\organisation\models\TblDcs;
use Yii;

class SocietyController extends ChildController {

    public function actionSocietyData() {
        $model = new TblDcs();
        $model->setAttributes($this->post_data);
        $model->setAttributes($this->post_data['content']);
        $data = $model->getSocietyData();
        return $this->response['data'] = $model->getSocietyData();
    }
    
}
