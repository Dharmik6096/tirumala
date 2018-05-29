<?php

namespace app\modules\webservice\ho\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use Yii;

class SocietyController extends ChildController {

    public function actionSocietyData() {
        $content = $this->post_data['content'];
        $data = $this->getOrgCodes($this->post_data);
        $union = !empty($data['union']) ? ',' . implode(',', $data['union']) . ',' : 0;
        $plant = !empty($data['plant']) ? ',' . implode(',', $data['plant']) . ',' : 0;
        $mcc = !empty($data['mcc']) ? ',' . implode(',', $data['mcc']) . ',' : 0;
        $bmc = !empty($content['bmc_code']) ? ',' . $content['bmc_code'] . ',' : 0;
        $dcs = !empty($data['dcs']) ? ',' . implode(',', $data['dcs']) . ',' : 0;
        $sp = !empty($content['bmc_code']) ? 'sp_app_ho_bmc_dcs_data' : 'sp_app_ho_dcs_data';
        return $this->response['data'] = $this->getSpData($sp, $union, $plant, $mcc, $bmc, $dcs, $content['from_datetime'], $content['to_datetime']);
    }

}
