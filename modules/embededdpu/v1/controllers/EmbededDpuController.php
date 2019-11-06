<?php

namespace app\modules\embededdpu\v1\controllers;

use yii\web\Controller;
//use app\modules\vendorapi\controllers\RestController;
use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\embededdpu\controllers\RestController;
use app\modules\organisation\models\TblDcsHistory;

/**
 * Default controller for the `vendorapi` module
 */
class EmbededDpuController extends RestController {

    public function actionStartUp() {
        $req_data = Yii::$app->request->getRawBody();
        $stationId = !empty($req_data['station_id']) ? $req_data['station_id'] : '0';
        $sp_name = 'sp_app_embeded_dpu_v1_start_up';
        $sp_param = [];
        $sp_param[] = $stationId;
        $response = \Yii::$app->general->getSpData($sp_name, $sp_param);
        $data = [];
        if (!empty($response)) {
            $data = $response[0];
        }
        $this->response['data'] = $data;
        return $this->response;
    }

    public function actionAcknowledgeNameRequest() {
        $req_data = Yii::$app->request->getRawBody();
        $stationId = !empty($req_data['station_id']) ? $req_data['station_id'] : '0';
        $message = 'Unable to save!';
        $model = new TblDcs();
        $modelData = $model->findOne($stationId);
        if (!empty($modelData)) {
            $model = $modelData;
            $model->scenario = 'customImportUpdate';
            $model->is_name_request = 0;
            $transaction = $this->generalModel->saveTransaction([$model], [], ['transactional data', 'create']);
            if ($transaction == 'customRedirect') {
                $message = 'Successfully Saved!';
            }
        }
        $this->response['message'] = [$message];
        return $this->response;
    }

}
