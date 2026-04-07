<?php

namespace app\modules\clienterp\eipl\controllers;

use app\modules\clienterp\models\TblDeviceAnalysisLog;
use Yii;

class PushRequestController extends PushMasterController {

    public function actionDeviceData() {
        $request = Yii::$app->request->getRawBody();
        try {
            $errors = [];
            $save_model = [];
            $is_valid_data = true;
            $model = new TblDeviceAnalysisLog();
            $model->setAttributes($request);
            if ($model->validate()) {
                $save_model[] = $model;
            } else {
                $is_valid_data = false;
                $errors = $model->getErrors();
            }
            if (!empty($errors)) {
                $error_list = [];
                foreach ($errors as $e) {
                    $error_list = array_merge($error_list, $e);
                }
                $this->response->setStatusCode($this->eiplResponseCode->validationFail);
                $this->response->setMessage($error_list);
            } else if ($is_valid_data && !empty($save_model)) {
                $transaction = $this->generalModel->saveTransaction($save_model, ['Device Analysis Log', 'create']);
                $msg = Yii::$app->getSession()->getFlash('success')['message'] ?? 'Device data saved successfully.';
                if ($transaction == 'customRedirect') {
                    $this->response->setData(['id' => $model->id], false);
                } else {
                    $this->response->setStatusCode($this->eiplResponseCode->statusError);
                }
                $this->response->setMessage([$msg]);
            }
        } catch (\Throwable $ex) {
            $this->response->setStatusCode($this->eiplResponseCode->statusError);
            $this->response->setMessage(['Error While Processing Request.']);
        }
        return $this->response;
    }


}
