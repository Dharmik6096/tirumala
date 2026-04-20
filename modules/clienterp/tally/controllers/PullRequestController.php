<?php

namespace app\modules\clienterp\tally\controllers;

use Yii;

class PullRequestController extends PullMasterController {

    public function actionMemberCollection() {
        $request = Yii::$app->request->getRawBody();
        $rateKey = 'limit_tally_member_collection_' . Yii::$app->request->userIP;
        $isCache = false;
        $count = 0;
        try {
            if (!empty(Yii::$app) && Yii::$app->has('redis')) {
                $count = (int)Yii::$app->redis->get($rateKey) ?: 0;
                $isCache = true;
            }
        } catch (\Exception $e) {
            Yii::error("Redis connection failed: " . $e->getMessage());
            $isCache = false;
        }
        if ($isCache) {
            if ($count >= 2) {
                $this->response->setStatusCode(429);
                $this->response->setMessage(['Too many requests.']);
                return $this->response;
            }
            try {
                Yii::$app->redis->incr($rateKey);
                Yii::$app->redis->expire($rateKey, 60);
            } catch (\Exception $e) {
                // ignore redis failures and fallback to DB
            }
        }
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
        $isCacheAvailable = false;
        $count = 0;
        try {
            if (!empty(Yii::$app) && Yii::$app->has('redis')) {
                $count = (int)Yii::$app->redis->get($rateKey) ?: 0;
                $isCacheAvailable = true;
            }
        } catch (\Exception $e) {
            Yii::error("Redis connection failed: " . $e->getMessage());
            $isCacheAvailable = false;
        }

        if ($isCacheAvailable) {
            if ($count >= 2) {
                $this->response->setStatusCode(429);
                $this->response->setMessage(['Too many requests.']);
                return $this->response;
            }
            try {
                Yii::$app->redis->incr($rateKey);
                Yii::$app->redis->expire($rateKey, 60);
            } catch (\Exception $e) {
                // ignore redis failures and fallback to DB
            }
        }
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
