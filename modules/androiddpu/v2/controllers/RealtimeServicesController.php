<?php

namespace app\modules\androiddpu\v2\controllers;

use app\modules\androiddpu\controllers\RestController;
use Yii;
use app\modules\dcsoperation\models\TblPurchaseRate;
use app\modules\dcsoperation\models\TblPurchaseRateDetails;
use app\modules\dcsoperation\models\TblMember;

class RealtimeServicesController extends RestController {

    public function actionPurchaseRate() {
        $res_data = [];
        $data = $this->post_data;
        $org_code = $data['organization_code'];
        $org_type = $data['organization_type'];
        $model = new TblPurchaseRate();
        $model->purchase_rate_code = $data['content']['purchase_rate_code'];
        $rate = $model->getRateRecord();
        if (!empty($rate)) {
            $res_data = $rate->attributes;
            $based_date = [];
            $base_record = $rate->purchaseRateBased;
            foreach ($base_record as $b) {
                $based_date[] = $b->attributes;
            }
            $res_data['purchaseRateBased'] = $based_date;
            $rate->app_org_code = $org_code;
            $res_data['purchaseRateApplicability'] = $rate->purchaseRateApplicability;
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
        
    }

}
