<?php

namespace app\modules\clienterp\comfed\controllers;

use Yii;
use app\modules\clienterp\controllers\PushMasterController;

class PushRequestController extends PushMasterController {

    public function actionFarmerFrnoUpdate() {
        $rawBody = Yii::$app->request->getRawBody();
        $request = is_array($rawBody) ? $rawBody : json_decode($rawBody, true);

        if (empty($request) || !is_array($request)) {
            $this->response->setStatusCode(201);
            $this->response->setMessage(['Invalid request data format.']);
            return $this->response;
        }

        $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
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
                'updated_by'  => !empty($user) ? trim($user) : ''
            ];
        }

        try {
            $sp_name = 'sp_clienterp_comfed_farmer_frno_update';
            $sp_param = [json_encode($updatedRequest)];

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
