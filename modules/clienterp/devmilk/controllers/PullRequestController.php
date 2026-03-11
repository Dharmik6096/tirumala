<?php

namespace app\modules\clienterp\devmilk\controllers;

use Yii;

class PullRequestController extends PullMasterController {

    public function actionBmcCollection() {
        $request = Yii::$app->request->getRawBody();
        if (!empty($request) && !empty($request['bmc_code']) && !empty($request['date']) && !empty(!empty($request['shift']))) {
            try {
                $sp_param = [];
                $sp_param[] = $request['bmc_code'];
                $sp_param[] = $request['date'];
                $sp_param[] = $request['shift'];
                $sp_name = 'clienterp_devmilk_pull_bmc_collection';

                $response = \Yii::$app->general->getSpData($sp_name, $sp_param);
                $this->response->setData($response, FALSE);
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

    public function actionVspPayment() {

        $request = Yii::$app->request->getRawBody();
        if (!empty($request) && !empty($request['bmc_code']) && !empty($request['from_date']) && !empty(!empty($request['to_date']))) {
            try {
                $sp_param = [];
                $sp_param[] = $request['bmc_code'];
                $sp_param[] = $request['from_date'];
                $sp_param[] = $request['to_date'];
                $sp_name = 'clienterp_devmilk_pull_vsp_payment';
                $response = \Yii::$app->general->getSpData($sp_name, $sp_param);
                $this->response->setData($response, FALSE);
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
