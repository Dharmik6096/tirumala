<?php

namespace app\modules\androiddpu\v2\controllers;

use app\modules\androiddpu\controllers\RestController;
use Yii;
use app\modules\dcsoperation\models\TblPurchaseRate;
use app\modules\dcsoperation\models\TblPurchaseRateDetails;
use app\modules\dcsoperation\models\TblMember;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use app\modules\organisation\models\TblDcs;

class RealtimeServicesController extends RestController {

    public function actionPurchaseRate() {
        $res_data = [];
        $res_data['purchaseRate'] = '';
        $res_data['purchaseRateBased'] = [];
        $res_data['purchaseRateApplicability'] = '';
        $data = $this->post_data;
        $org_code = $data['organization_code'];
        $org_type = $data['organization_type'];
        $model = new TblPurchaseRate();
        $model->purchase_rate_code = $data['content']['purchase_rate_code'];
        $rate = $model->getRateRecord();
        if (!empty($rate)) {
            $res_data['purchaseRate'] = $rate->attributes;
            $based_date = [];
            $base_record = $rate->purchaseRateBased;
            foreach ($base_record as $b) {
                $based_date[] = $b->attributes;
            }
            $res_data['purchaseRateBased'] = $based_date;
            $rate->app_org_code = $org_code;
            $res_data['purchaseRateApplicability'] = !empty($rate->purchaseRateApplicability) ? $rate->purchaseRateApplicability[0]->attributes : "";
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionPurchaseRateDetail() {
        $data = $this->post_data;
        $model = new TblPurchaseRateDetails();
        $model->attributes = $data['content'];
        $res_data = Yii::$app->general->getSpData('sp_app_amcs_v2_purchase_rate_detail', [$model->purchase_rate_code, $model->milk_quality_type_code, $model->milk_type_code]);
        if (!empty($res_data)) {
            $res_data = array_column($res_data, 'detail');
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionMemberDownload() {
        $data = $this->post_data;
        $model = new TblMember();
        $model->attributes = $data['content'];
        $res_data = Yii::$app->general->getSpData('sp_app_amcs_v2_member_detail', [$model->dcs_code]);
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionDownloadAcknowledgement() {
        $data = $this->post_data['content'];
        $res_data = [];
        $res_data['message'] = 'Acknowledgement Updated.';
        if (!empty($data['ack_type'])) {
            if ($data['ack_type'] == 'RATE') {
                $model = new TblPurchaseRateApplicability();
                $model->attributes = $data;
                $model->updateAll(['updated_at' => date('Y-m-d H:i:s'), 'download_date_time' => date('Y-m-d H:i:s'), 'is_download' => 0], ['purchase_rate_code' => $model->purchase_rate_code, 'dcs_code' => $model->dcs_code]);
                $model = new TblDcs();
                $model->attributes = $data;
                $model->updateAll(['updated_at' => date('Y-m-d H:i:s'), 'rate_flag' => 0], ['dcs_code' => $model->dcs_code]);
            } else if ($data['ack_type'] == 'MEMBER') {
                $model = new TblDcs();
                $model->attributes = $data;
                $model->updateAll(['updated_at' => date('Y-m-d H:i:s'), 'is_name_request' => 0], ['dcs_code' => $model->dcs_code]);
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

}
