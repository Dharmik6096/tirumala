<?php

namespace app\modules\clienterp\tally\controllers;

use Yii;
use app\modules\collection\models\TblMilkCollection;
use app\modules\collection\models\TblDcsMilkDispatchTxn;

class PushRequestController extends PushMasterController {

    public function actionDataAcknowledgement() {
        $request = Yii::$app->request->getRawBody();
        if (!empty($request) && !empty($request['ack_type'])) {
            $timestamp = date('Y-m-d H:i:s');
            $success_update = ['resp_desc' => 'SUCCESS', 'resp_status' => 2, 'data_post_status' => 2, 'response_datetime' => $timestamp];
            $error_update = ['resp_desc' => 'ERROR', 'resp_status' => 3, 'data_post_status' => 3, 'response_datetime' => $timestamp];
            $success_where = ['x_col1' => $request['success_id'], 'data_post_status' => 1];
            $error_where = ['x_col1' => $request['error_id'], 'data_post_status' => 1];
            try {
                if ($request['ack_type'] == 'MILK_COLLECTION') {
                    $model = new TblMilkCollection();
                } else if ($request['ack_type'] == 'DCS_MILK_DISPATCH') {
                    $model = new TblDcsMilkDispatchTxn();
                }
                if (isset($model)) {
                    if (!empty($request['success_id'])) {
                        $model->updateAll($success_update, $success_where);
                    }
                    if (!empty($request['error_id'])) {
                        $model->updateAll($error_update, $error_where);
                    }
                    unset($model);
                }
                $this->response->setMessage(['Acknowledgement Saved Success.']);
            } catch (\Throwable $ex) {
                $this->response->setStatusCode($this->eiplResponseCode->statusError);
                $this->response->setMessage(['Error While Process Request.']);
            }
        } else {
            $this->response->setStatusCode($this->eiplResponseCode->validationFail);
            $this->response->setMessage(['Required Parameter Missing.']);
        }
        return $this->response;
    }

}
