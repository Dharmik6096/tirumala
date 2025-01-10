<?php

namespace app\modules\clienterp\nddb\controllers;

use Yii;

class PullRequestController extends PullMasterController {

    public function actionMemberCollection() {
        $request = Yii::$app->request->getRawBody();
        if (!empty($request) && !empty($request['union_code'])) {
            try {
                $sp_param = [];
                $sp_param[] = $request['union_code'];
                $sp_name = 'clienterp_nddb_pull_member_collection';
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

    public function actionDcsMilkDispatch() {
        $request = Yii::$app->request->getRawBody();
        if (!empty($request) && !empty($request['union_code'])) {
            try {
                $sp_param = [];
                $sp_param[] = $request['union_code'];
                $sp_name = 'clienterp_nddb_pull_dcs_milk_dispatch';
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
