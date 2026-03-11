<?php

namespace app\modules\clienterp\tally\controllers;

use Yii;

class PullRequestController extends PullMasterController {

    public function actionMemberCollection() {
        $request = Yii::$app->request->getRawBody();
        $rateKey = 'limit_tally_member_collection_' . Yii::$app->request->userIP;
        $count = Yii::$app->cache->get($rateKey) ?: 0;
        if ($count >= 2) {
            $this->response->setStatusCode(429);
            $this->response->setMessage(['Too many requests.']);
            return $this->response;
        }
        Yii::$app->cache->set($rateKey, $count + 1, 60);
        if (!empty($request) && !empty($request['union_code']) && !empty($request['dcs_code']) && !empty($request['from_date']) && !empty($request['to_date'])) {
            try {
                $sp_param = [];
                $sp_param[] = $request['union_code'];
                $sp_param[] = $request['dcs_code'];
                $sp_param[] = $request['from_date'];
                $sp_param[] = $request['to_date'];
                $sp_name = 'clienterp_tally_pull_member_collection';
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
        $rateKey = 'limit_tally_dcs_milk_dispatch_' . Yii::$app->request->userIP;
        $count = Yii::$app->cache->get($rateKey) ?: 0;
        if ($count >= 2) {
            $this->response->setStatusCode(429);
            $this->response->setMessage(['Too many requests.']);
            return $this->response;
        }
        Yii::$app->cache->set($rateKey, $count + 1, 60);
        if (!empty($request) && !empty($request['union_code']) && !empty($request['dcs_code']) && !empty($request['from_date']) && !empty($request['to_date'])) {
            try {
                $sp_param = [];
                $sp_param[] = $request['union_code'];
                $sp_param[] = $request['dcs_code'];
                $sp_param[] = $request['from_date'];
                $sp_param[] = $request['to_date'];
                $sp_name = 'clienterp_tally_pull_dcs_milk_dispatch';
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
