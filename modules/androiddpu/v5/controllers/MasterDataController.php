<?php

namespace app\modules\androiddpu\v5\controllers;

use Yii;
use app\modules\syncutility\models\TblInbox;
use app\modules\androiddpu\components\HttpRequest;

class MasterDataController extends \app\modules\androiddpu\v4\controllers\MasterDataController {

    public function actionInbox() {
        $res_data = [];
        $message = 'Unable to save!';
        $success_id = [];
        $error_id = [];
        $data = $this->post_data;
        if (!empty($data['content'])) {
            foreach ($data['content'] as $transaction_data) {
                if (!empty($transaction_data['uuid'])) {
                    $request = new HttpRequest();
                    $transaction_data = $request->camelCaseToUnderscore($transaction_data);
                    $model = new TblInbox();
                    $model->setAttributes($transaction_data);
                    $model->sync_timestamp = date('Y-m-d H:i:s');
                    // $model->posting_timestamp = date('Y-m-d H:i:s');
                    $transaction = $this->generalModel->saveTransaction([$model], ['transactional data', 'create']);
                    if ($transaction == 'customRedirect') {
                        $message = 'Successfully Saved!';
                        $success_id[] = $transaction_data['uuid'];
                    } else {
                        $errorData = !empty($transaction) ? (string) $transaction : 'error_occured';
                        if (strstr(strtolower($errorData), 'cannot insert duplicate key')) {
                            $success_id[] = $transaction_data['uuid'];
                        } else {
                            $error_id[] = $transaction_data['uuid'];
                        }
                    }
                }
            }
        }

        $res_data['success_id'] = implode(',', $success_id);
        $res_data['error_id'] = implode(',', $error_id);
        $this->response['message'] = [$message];
        $this->response['data'] = $res_data;
        return $this->response;
    }

}
