<?php

namespace app\modules\clienterp\comfed\controllers;

use Yii;
use app\modules\clienterp\controllers\PushMasterController;

class PushRequestController extends PushMasterController {

    public function actionFarmerFrnoUpdate() {
        $rawBody = Yii::$app->request->getRawBody();
        $request = is_array($rawBody) ? $rawBody : json_decode($rawBody, true);
        $rateKey = 'limit_comfed_farmer_frno_update_' . Yii::$app->request->userIP;
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

        if (empty($request) || !is_array($request)) {
            $this->response->setStatusCode(201);
            $this->response->setMessage(['Invalid request data format.']);
            return $this->response;
        }

        $updatedRequest = [];
        foreach ($request as $item) {
            if (empty($item['fr_no']) || empty($item['dcs_no']) ||
                empty($item['fr_name']) || empty($item['fr_phone_no'])) {

                $this->response->setStatusCode(201);
                $this->response->setMessage(['frNo, dcsNo, frName, frPhoneNo are required.']);
                return $this->response;
            }

            $updatedRequest[] = [
                'fr_name'     => trim($item['fr_name']),
                'fr_dob'      => !empty($item['fr_dob']) ? trim($item['fr_dob']) : null,
                'fr_phone_no' => trim($item['fr_phone_no']),
                'fr_no'       => trim($item['fr_no']),
                'dcs_no'      => trim($item['dcs_no']),
                'fr_aadhar'         => !empty($item['fr_aadhar']) ? trim($item['fr_aadhar']) : null,
                'registration_code' => !empty($item['registration_code']) ? trim($item['registration_code']) : null
            ];
        }

        try {
            $sp_name = 'sp_clienterp_comfed_farmer_frno_update';
            $sp_param = [json_encode($updatedRequest), 'FARMER_FRNO_UPDATE_API'];

            Yii::$app->general->getSpData($sp_name, $sp_param, true);

            $this->response->setStatusCode(200);
            $this->response->setMessage(['Data Captured Successfully.']);
        } catch (\Throwable $ex) {
            $this->response->setStatusCode(501);
            $this->response->setMessage(['Error While Process Request.']);
            Yii::info('Comfed push-request farmer-frno-update error: ' . $ex->getMessage());
        }

        return $this->response;
    }
}
