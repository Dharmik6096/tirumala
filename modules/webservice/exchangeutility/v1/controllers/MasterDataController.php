<?php

namespace app\modules\webservice\exchangeutility\v1\controllers;

use app\modules\androiddpu\controllers\RestController;
use app\modules\syncutility\models\TblSentbox;
use app\modules\installation\models\TblAndroidInstallationDetails;

/**
 * Default controller for the `vendorapi` module
 */
class MasterDataController extends RestController {

    public function actionSentbox() {
        $res_data = [];
        $data = $this->post_data;
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
            if ($record == true) {
                $res_data['message'] = 'Sentbox Updated Successfully.';
            }
        }
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
