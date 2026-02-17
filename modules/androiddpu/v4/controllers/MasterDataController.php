<?php

namespace app\modules\androiddpu\v4\controllers;

use yii\web\Controller;
//use app\modules\vendorapi\controllers\RestController;
use Yii;
use ReflectionClass;
use DateTime;
use app\modules\androiddpu\controllers\RestController;
use app\modules\syncutility\models\TblSentbox;
use app\modules\syncutility\models\TblInbox;
use app\modules\syncutility\models\TblSyncLog;
use app\modules\androiddpu\components\HttpRequest;
use app\modules\installation\models\TblAndroidInstallationDetails;

/**
 * Default controller for the `vendorapi` module
 */
class MasterDataController extends \app\modules\androiddpu\v3\controllers\MasterDataController {

    public function actionSentbox() {
        $res_data = [];
        $data = $this->post_data;
//        $sync_active_model = $this->syncActiveRecord($data);
//        if (!empty($sync_active_model)) {
        if (true) {
            $code = !empty($data['organization_code']) ? $data['organization_code'] : '';
            $type = !empty($data['organization_type']) ? $data['organization_type'] : '';
            $device_id = !empty($data['device_id']) ? $data['device_id'] : '';
            $sync_key = !empty($data['sync_key']) ? $data['sync_key'] : '';
            $model = new TblSentbox();
            $model->dest_org_id = $code;
            $model->dest_org_type = $type;
            $model->device_id = $device_id;
            $notInTables = ['tbl_purchase_rate', 'tbl_purchase_rate_based', 'tbl_purchase_rate_details'];
            $res_data = $model->getData($notInTables);
            $this->response['data'] = $res_data;
        }
        return $this->response;
    }

    public function actionAcknowledgement() {
        $res_data = [];
        $res_data['message'] = 'Sentbox Not Updated.';
        $data = $this->post_data;
        $sync_active_model = $this->syncActiveRecord($data);
        if (!empty($sync_active_model)) {
            $content = $data['content'];
            $ids = $content['uuid'];
            $record = $this->generalModel->deleteMapping(['TblSentbox', 'TblSentboxClone'], 'uuid', $ids);
            if (!in_array(FALSE, $record)) {
                $res_data['message'] = 'Sentbox Updated Successfully.';
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionInbox() {
        $res_data = [];
        $message = 'Unable to save!';
        $success_id = [];
        $error_id = [];
        $data = $this->post_data;
        if (!empty($data['content'])) {
            foreach ($data['content'] as $transaction_data) {
                if (!empty($transaction_data['uuid'])) {
                    $request = Yii::$app->get('androidHttpRequest');
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

    public function syncActiveRecord($data) {
        $data['sync_key'] = !empty($data['sync_key']) ? $data['sync_key'] : '';
        $model = new TblAndroidInstallationDetails();
        $sync_active_model = $model->getSyncActiveData($data);
        return $sync_active_model;
    }

    public function actionSentboxCount() {
        $response = [];
        $data = $this->post_data;
        $code = !empty($data['organization_code']) ? $data['organization_code'] : '';
        $type = !empty($data['organization_type']) ? $data['organization_type'] : '';
        $device_id = !empty($data['device_id']) ? $data['device_id'] : '';
        $model = new TblSentbox();
        $model->dest_org_id = $code;
        $model->dest_org_type = $type;
        $model->device_id = $device_id;
        $notInTables = ['tbl_purchase_rate', 'tbl_purchase_rate_based', 'tbl_purchase_rate_details'];
        $response['count'] = $model->getDataCount($notInTables);
        $this->response['data'] = $response;
        return $this->response;
    }

}
