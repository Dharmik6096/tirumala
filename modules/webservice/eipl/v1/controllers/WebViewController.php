<?php

namespace app\modules\webservice\eipl\v1\controllers;

use Yii;
use app\modules\webservice\eipl\controllers\MasterController;

class WebViewController extends MasterController {

    protected function verbs() {
        return [
            '*' => ['GET'],
        ];
    }

    public function actionPrintChallan($id) {
        $controls = [];
        $controls['p_challan_no'] = $id;
        $controls['p_report_name'] = 'Delivery Challan';
        $url = Yii::$app->general->printDocument($controls, 'milkcollection/DeliveryChallan', 'BMCDispatchChallan', 'pdf', true);
        $data['url'] = !empty($url) ? Yii::$app->request->getHostInfo() . Yii::$app->request->baseUrl . $url : $url;
        $this->response->setData($data);
        return $this->response;
    }

}
