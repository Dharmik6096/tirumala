<?php

namespace app\modules\webservice\vsp\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use Yii;

class DispatchController extends ChildController {

    public function actionDispatchData() {
        $content = $this->post_data['content'];
        $query = \Yii::$app->db->createCommand("{CALL sp_app_vsp_difference_collection_dispatch(:dcs_code,:from_date,:to_date)}")
                ->bindValue(':dcs_code', $this->post_data['dcs_code'])
                ->bindValue(':from_date', $content['from_date'])
                ->bindValue(':to_date', $content['to_date']);
        $results = $query->queryAll();
        return $this->response['data'] = $results;
    }

}
