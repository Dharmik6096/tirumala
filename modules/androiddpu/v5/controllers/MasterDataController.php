<?php

namespace app\modules\androiddpu\v5\controllers;

use Yii;
use app\modules\syncutility\models\TblInbox;
use app\modules\androiddpu\components\HttpRequest;
use app\modules\syncutility\models\TblSentboxDesktop;

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
                    $transaction = $this->generalModel->saveDeleteTransaction([$model], [], [], ['transactional data', 'create'], true);
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

    public function actionSentboxDesktop() {
        $res_data = [];
        $data = $this->post_data;
        if (true) {
            $code = !empty($data['organization_code']) ? $data['organization_code'] : '';
            $type = !empty($data['organization_type']) ? $data['organization_type'] : '';
            $model = new TblSentboxDesktop();
            $model->dest_org_id = $code;
            $model->dest_org_type = $type;
            $res_data = $model->getData();
            $this->response['data'] = $res_data;
        }
        return $this->response;
    }

    public function actionSentboxCountDesktop() {
        $response = [];
        $data = $this->post_data;
        $code = !empty($data['organization_code']) ? $data['organization_code'] : '';
        $type = !empty($data['organization_type']) ? $data['organization_type'] : '';
        $model = new TblSentboxDesktop();
        $model->dest_org_id = $code;
        $model->dest_org_type = $type;
        $response['count'] = $model->getDataCount();
        $this->response['data'] = $response;
        return $this->response;
    }

    public function actionAcknowledgementDesktop() {
        $res_data = [];
        $res_data['message'] = 'Sentbox Not Updated.';
        $data = $this->post_data;
        $content = $data['content'];
        $ids = $content['uuid'];
        $record = $this->generalModel->deleteMapping(['TblSentboxDesktop', 'TblSentboxDesktopClone'], 'uuid', $ids);
        if (!in_array(FALSE, $record)) {
            $res_data['message'] = 'Sentbox Updated Successfully.';
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }
}