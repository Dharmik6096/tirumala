<?php

namespace app\modules\webservice\emilkprolite\v1\controllers;

use app\modules\webservice\emilkprolite\controllers\MasterController;
use Yii;
use app\modules\webservice\supervisor\v1\V1;
use app\modules\webservice\components\EmilkProLiteRequest;
use app\modules\syncutility\models\TblInbox;
use app\modules\syncutility\models\TblSyncLog;
use app\modules\webservice\eipl\models\TblDpuCollectionHoData;

//use app\modules\webservice\models\TblAppMasterCall;
//use app\modules\webservice\models\TblAppMasterCallHistory;

class RequestMasterController extends MasterController {

    public function actionInbox() {
        $res_data = [];
        $message = 'Unable to save!';
        $success_id = [];
        $error_id = [];
        $data = $this->post_data;
        if (!empty($data['content'])) {
            foreach ($data['content'] as $transaction_data) {
                $request = new EmilkProLiteRequest();
                $transaction_data = $request->camelCaseToUnderscore($transaction_data);
                $model = new TblInbox();
                $model->setAttributes($transaction_data);
                $modelData = $model->findOne($model->uuid);
                $syncModel = new TblSyncLog();
                $syncModel->uuid = $model->uuid;
                $syncModelData = $syncModel->findOne($syncModel->uuid);
                if (!empty($modelData) || !empty($syncModelData)) {
                    $message = 'Successfully Saved!';
                    $success_id[] = $transaction_data['uuid'];
                } else {
                    $transaction = $this->generalModel->saveTransaction([$model], ['transactional data', 'create']);
                    if ($transaction == 'customRedirect') {
                        $message = 'Successfully Saved!';
                        $success_id[] = $transaction_data['uuid'];
                    } else {
                        $error_id[] = $transaction_data['uuid'];
                    }
                }
            }
        }
        $res_data['success_id'] = implode(',', $success_id);
        $res_data['error_id'] = implode(',', $error_id);
//        $this->response->setMessage([$message]);
//        $this->response->setData($res_data);
        $this->response['message'] = [$message];
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionSaveDpuCollectionData() {
        $records = $this->post_data;
        $successId = [];
        $errorId = [];
        $setData = [];
        $currentDateTime = date('Y-m-d H:i:s');
        $identityRecord = !empty(Yii::$app->eiplapp->identity) ? Yii::$app->eiplapp->identity : [];
        $setData['access_token'] = !empty($records['eipl_token']) ? $records['eipl_token'] : NULL;
        $setData['identity_type'] = NULL; //!empty($identityRecord['login_type']) ? $identityRecord['login_type'] : NULL;
        $setData['mobile_no'] = NULL; //!empty($identityRecord['mobile_no']) ? $identityRecord['mobile_no'] : NULL;
        $setData['device_id'] = !empty($records['device_id']) ? $records['device_id'] : NULL;
        $setData['entry_type'] = 'HTTP';
        $setData['status'] = 0;
        $setData['entry_datetime'] = $currentDateTime;
        $request = new EmilkProLiteRequest();
        if (!empty($records['content'])) {
            foreach ($records['content'] as $record) {
                $transaction_data = $request->camelCaseToUnderscore($record);
                $model = new TblDpuCollectionHoData();
                $model->setAttributes($transaction_data);
                $model->setAttributes($setData);
                if ($model->save()) {
                    $successId[] = $model->uuid;
                } else {
                    $errorId[] = $model->uuid;
                }
            }
        }
        $response = [];
        $response['success_id'] = $successId;
        $response['error_id'] = $errorId;
//        $this->response->setData($response);
        $message = 'Successfully Saved!';
//        $this->response->setMessage([$message]);
        $this->response['message'] = [$message];
        $this->response['data'] = $response;
        return $this->response;
    }

}
