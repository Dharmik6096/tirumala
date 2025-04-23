<?php

namespace app\modules\androiddpu\v5\controllers;

use Yii;

class DashboardController extends \app\modules\androiddpu\v4\controllers\DashboardController {

    public function actionTripStatusSummary() {
        $data = $this->post_data;
        $res_data = [];
        if (!empty($data['organization_code']) && !empty($data['organization_type']) && !empty($data['content']['transaction_date'])) {
            $res_data = Yii::$app->general->getSpData('sp_app_eipl_v1_trip_status_summary', [$data['content']['transaction_date'], $data['organization_code'], $data['organization_code'], $data['organization_type']]);
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

}

?>
