<?php

namespace app\modules\androiddpu\v4\controllers;

use Yii;
use app\modules\androiddpu\controllers\RestController;

class ProductSaleLiveController extends RestController {

    public function actionProductDetail() {
        $data = $this->post_data;
        $res_data = [];
        if (!empty($data['organization_code']) && !empty($data['organization_type']) && in_array($data['organization_type'], ['VLC'])) {
            $res_data = Yii::$app->general->getSpData('sp_app_amcs_v4_product_detail', [$data['organization_type'], $data['organization_code']]);
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionMemberCreditDetail() {
        $data = $this->post_data;
        $content = $data['content'];
        $res_data = [];
        if (!empty($data['organization_code']) && !empty($data['organization_type']) && in_array($data['organization_type'], ['VLC']) && !empty($content['bmc_code']) && !empty($content['member_code']) && !empty($content['sale_date'])) {
            $res_data = Yii::$app->general->getSpData('sp_app_amcs_v4_member_credit_detail', [$content['bmc_code'], $content['member_code'], $content['sale_date']]);
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

}
