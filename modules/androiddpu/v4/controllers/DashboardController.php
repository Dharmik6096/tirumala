<?php

namespace app\modules\androiddpu\v4\controllers;

use app\modules\androiddpu\controllers\RestController;
use Yii;

class DashboardController extends RestController {

    public function actionRmrdSummary() {
        $data = $this->post_data;

        $res_data = [];
        if (!empty($data['organization_code']) && !empty($data['organization_type']) && in_array($data['organization_type'], ['BMC']) && !empty($data['content']['from_date']) && !empty($data['content']['to_date'])) {
            $raw_data = Yii::$app->general->getSpData('sp_app_amcs_v4_dashboard_rmrd_summary', [$data['organization_type'], $data['organization_code'], $data['content']['from_date'], $data['content']['to_date']]);

            foreach ($raw_data as $entry) {
                $dataType = $entry['data_type'];
                unset($entry['data_type']);
                $res_data[$dataType][] = ($dataType === 'bmcCollection') ?
                        $entry :
                        ['collectionDatetime' => $entry['collectionDatetime'],'shiftCode' =>$entry['shiftCode'], 'totalSample' => $entry['totalSample']] +
                        (($dataType !== 'qualityCollection') ? ['totalQuantity' => $entry['totalQuantity']] : []);
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

}

?>
