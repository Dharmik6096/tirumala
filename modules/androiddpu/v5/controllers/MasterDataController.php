<?php

namespace app\modules\androiddpu\v5\controllers;

use app\modules\androiddpu\jobs\InboxJob;
use Yii;
use app\modules\syncutility\models\TblInbox;
use app\modules\androiddpu\components\HttpRequest;
use app\modules\syncutility\models\TblSentboxDesktop;

class MasterDataController extends \app\modules\androiddpu\v4\controllers\MasterDataController {

    public function actionInbox() {
        $content = $this->post_data['content'] ?? [];
        $success_id = [];
        $error_id = [];
        $messages = [];

        if (empty($content) || !is_array($content)) {
            $this->response['message'] = 'No content provided';
            $this->response['data'] = ['success_id' => '', 'error_id' => ''];
            return $this->response;
        }

        foreach ($content as $transaction_data) {
            if (empty($transaction_data)) continue;

            $uuid = $transaction_data['uuid'] ?? null;
            if (!isset($transaction_data['json_text']) || !is_string($transaction_data['json_text'])){
                $messages[] = "Unable to save!";
                if ($uuid) {
                    $error_id[] = $uuid;
                    continue;
                }
            }

            if (empty($uuid)) {
                $error_id[] = 'missing_uuid';
                continue;
            }

            $exists = TblInbox::find()->where(['uuid' => $uuid])->exists();
            if ($exists) {
                $messages[] = "Unable to save!";
                $success_id[] = $uuid;
                continue;
            }

            try {
                Yii::$app->queue->push(new InboxJob([
                    'transaction_data' => $transaction_data
                ]));
                $success_id[] = $uuid;
            } catch (\Exception $e) {
                Yii::error("Queue Push Failed: " . $e->getMessage());
                $error_id[] = $uuid;
            }
        }

        if (empty($error_id)) {
            $this->response['message'] = 'Successfully Queued!';
        } elseif (empty($success_id)) {
            $this->response['message'] = 'Queue Failed: ' . implode('; ', $messages);
        } else {
            $this->response['message'] = 'Partially Queued. Some errors occurred.';
        }

        $this->response['data'] = [
            'success_id' => implode(',', $success_id),
            'error_id' => implode(',', $error_id)
        ];

        return $this->response;
    }

    public function actionSentboxDesktop() {
        $res_data = [];
        $data = $this->post_data;
        $code = !empty($data['organization_code']) ? $data['organization_code'] : '';
        $type = !empty($data['organization_type']) ? $data['organization_type'] : '';
        $device_id = !empty($data['device_id']) ? $data['device_id'] : '';
        $model = new TblSentboxDesktop();
        $model->dest_org_id = $code;
        $model->dest_org_type = $type;
        $model->device_id = $device_id;
        $res_data = $model->getData();
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionSentboxCountDesktop() {
        $response = [];
        $data = $this->post_data;
        $code = !empty($data['organization_code']) ? $data['organization_code'] : '';
        $type = !empty($data['organization_type']) ? $data['organization_type'] : '';
        $device_id = !empty($data['device_id']) ? $data['device_id'] : '';
        $model = new TblSentboxDesktop();
        $model->dest_org_id = $code;
        $model->dest_org_type = $type;
        $model->device_id = $device_id;
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