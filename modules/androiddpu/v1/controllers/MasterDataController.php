<?php

namespace app\modules\androiddpu\v1\controllers;

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

/**
 * Default controller for the `vendorapi` module
 */
class MasterDataController extends RestController {

    public function actionSentbox() {
        $res_data = [];
        $data = $this->post_data;
        $code = !empty($data['organization_code']) ? $data['organization_code'] : '';
        $type = !empty($data['organization_type']) ? $data['organization_type'] : '';
        $device_id = !empty($data['device_id']) ? $data['device_id'] : '';
        $model = new TblSentbox();
        $model->dest_org_id = $code;
        $model->dest_org_type = $type;
        $model->device_id = $device_id;
        $notInTables = ['tbl_purchase_rate_applicability', 'tbl_purchase_rate', 'tbl_purchase_rate_based', 'tbl_purchase_rate_details'];
        $res_data = $model->getData($notInTables);
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionAcknowledgement() {
        $res_data = [];
        $res_data['message'] = 'Sentbox Not Updated.';
        $data = $this->post_data;
        $content = $data['content'];
        $ids = $content['uuid'];
        $record = $this->generalModel->deleteMapping(['TblSentbox', 'TblSentboxClone'], 'uuid', $ids);
        if ($record == true) {
            $res_data['message'] = 'Sentbox Updated Successfully.';
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
                $request = Yii::$app->get('androidHttpRequest');
                $transaction_data = $request->camelCaseToUnderscore($transaction_data);
                $model = new TblInbox();
                $model->setAttributes($transaction_data);
                $model->posting_timestamp = date('Y-m-d H:i:s');
                $modelData = $model->findOne($model->uuid);
                $syncModel = new TblSyncLog();
                $syncModel->uuid = $model->uuid;
                $syncModelData = $syncModel->findOne($syncModel->uuid);
                if (!empty($modelData) || !empty($syncModelData)) {
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
        $this->response['message'] = [$message];
        $this->response['data'] = $res_data;
        return $this->response;
    }

}
