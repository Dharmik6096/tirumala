<?php

namespace app\modules\androiddpu\v5\controllers;

use app\modules\androiddpu\jobs\InboxJob;
use Exception;
use Yii;
use app\modules\syncutility\models\TblInbox;
use app\modules\androiddpu\components\HttpRequest;
use app\modules\syncutility\models\TblSentboxDesktop;

class MasterDataController extends \app\modules\androiddpu\v4\controllers\MasterDataController
{

    public function actionInbox()
    {
        $res_data = [];
        $message = 'Unable to save!';
        $success_id = [];
        $error_id = [];
        $data = $this->post_data;

        $hasQueue = false;
        if (Yii::$app->has('queueInbox')) {
            try {
                $queue = Yii::$app->queueInbox;
                $method = new \ReflectionMethod(get_class($queue), 'open');
                $method->setAccessible(true);
                $method->invoke($queue);
                $hasQueue = true;
            } catch (\Exception $e) {
                $hasQueue = false;
            }
        }
        if (!empty($data['content'])) {
            foreach ($data['content'] as $transaction_data) {
                if (!empty($transaction_data['uuid'])) {
                    $sync_timestamp = date('Y-m-d H:i:s');
                    if ($hasQueue) {
                        try {
                            $jobId = Yii::$app->queueInbox->push(new InboxJob([
                                'transaction_data' => $transaction_data,
                                'sync_timestamp' => $sync_timestamp,
                            ]));

                            if ($jobId) {
                                $message = 'Successfully Saved!';
                                $success_id[] = $transaction_data['uuid'];
                            } else {
                                $error_id[] = $transaction_data['uuid'];
                            }
                        } catch (Exception $e) {
                            $error_id[] = $transaction_data['uuid'];
                        }
                    } else {
                        $request = Yii::$app->get('androidHttpRequest');
                        $model = new TblInbox();
                        $model->setAttributes($transaction_data);
                        $model->sync_timestamp = date('Y-m-d H:i:s');
                        // $model->posting_timestamp = date('Y-m-d H:i:s');
                        $transaction = $this->generalModel->saveDeleteTransaction([$model], [], [], ['transactional data', 'create'], true);
                        if ($transaction == 'customRedirect') {
                            $message = 'Successfully Saved!';
                            $success_id[] = $transaction_data['uuid'];
                        } else {
                            $errorData = !empty($transaction) ? (string)$transaction : 'error_occured';
                            if (strstr(strtolower($errorData), 'cannot insert duplicate key')) {
                                $success_id[] = $transaction_data['uuid'];
                            } else {
                                $error_id[] = $transaction_data['uuid'];
                            }
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


    public function actionSentboxDesktop()
    {
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

    public function actionSentboxCountDesktop()
    {
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

    public function actionAcknowledgementDesktop()
    {
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