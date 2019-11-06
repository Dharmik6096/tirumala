<?php

namespace app\modules\embededdpu\v1\controllers;

use yii\web\Controller;
//use app\modules\vendorapi\controllers\RestController;
use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\embededdpu\controllers\RestController;
use app\modules\organisation\models\TblDcsHistory;
use app\modules\collection\models\TblMilkCollection;

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

    public function actionAcknowledgement() {
        $req_data = Yii::$app->request->getRawBody();
        $stationId = !empty($req_data['station_id']) ? $req_data['station_id'] : '0';
        $message = 'Unable to save!';
        $model = new TblDcs();
        $modelData = $model->findOne($stationId);
        if (!empty($modelData)) {
            $model = $modelData;
            $model->scenario = 'customImportUpdate';
            if (!empty($req_data['ack_type']) && $req_data['ack_type'] == 'rate') {
                $model->rate_flag = 0;
            } else if (!empty($req_data['ack_type']) && $req_data['ack_type'] == 'name') {
                $model->is_name_request = 0;
            }
            $transaction = $this->generalModel->saveTransaction([$model], [], ['transactional data', 'create']);
            if ($transaction == 'customRedirect') {
                $message = 'Successfully Saved!';
            }
        }
        $this->response['message'] = [$message];
        return $this->response;
    }

    public function actionMemberList() {
        $req_data = Yii::$app->request->getRawBody();
        $stationId = !empty($req_data['station_id']) ? $req_data['station_id'] : '0';
        $sp_name = 'sp_app_embeded_dpu_v1_member_list';
        $sp_param = [];
        $sp_param[] = $stationId;
        $response = \Yii::$app->general->getSpData($sp_name, $sp_param);
        $this->response['data'] = $response;
        return $this->response;
    }

    public function actionSaveCollectionData() {
        $req_data = Yii::$app->request->getRawBody();
        $message = 'Unable to save!';
        $model = new TblMilkCollection();
        $model->setAttributes($req_data);
        $model->milk_quality_type_code = 1;
        $transaction = $this->generalModel->saveTransaction([$model], [], ['transactional data', 'create']);
        if ($transaction == 'customRedirect') {
            $message = 'Successfully Saved!';
        }
        $this->response['message'] = [$message];
        return $this->response;
    }

}
