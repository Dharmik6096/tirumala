<?php

namespace app\modules\webservice\bmc\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use app\modules\collection\models\TblMilkCollection;
use Yii;

class MemberCollectionController extends ChildController {

    public function actionCollectionData() {
        $model = new TblMilkCollection();
        $model->setAttributes($this->post_data);
        $content = $this->post_data['content'];
        $from_date = $content['from_date'] . ' ' . \Yii::$app->general->getshift($content['from_shift']);
        $to_date = $content['to_date'] . ' ' . \Yii::$app->general->getshift($content['to_shift']);
        $society_code = $content['dcs_code'];
        return $this->response['data'] = $model->memberCollectionData($from_date, $to_date, $society_code);
    }
    
}
