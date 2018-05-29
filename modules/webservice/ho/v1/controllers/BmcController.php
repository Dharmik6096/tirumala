<?php

namespace app\modules\webservice\ho\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use Yii;

class BmcController extends ChildController {

    public function actionHomeData() {
        $content = $this->post_data['content'];
        $data = $this->getOrgCodes($this->post_data);
        $union = !empty($data['union']) ? ',' . implode(',', $data['union']) . ',' : 0;
        $plant = !empty($data['plant']) ? ',' . implode(',', $data['plant']) . ',' : 0;
        $mcc = !empty($data['mcc']) ? ',' . implode(',', $data['mcc']) . ',' : 0;
        $bmc = !empty($data['bmc']) ? ',' . implode(',', $data['bmc']) . ',' : 0;
        $dcs = !empty($data['dcs']) ? ',' . implode(',', $data['dcs']) . ',' : 0;
        $sp = 'sp_app_ho_dashboard';
        return $this->response['data'] = $this->getSpData($sp, $union, $plant, $mcc, $bmc, $dcs, $content['from_datetime'], $content['to_datetime']);
    }

    public function actionBmcData() {
        $content = $this->post_data['content'];
        $data = $this->getOrgCodes($this->post_data);
        $union = !empty($data['union']) ? ',' . implode(',', $data['union']) . ',' : 0;
        $plant = !empty($data['plant']) ? ',' . implode(',', $data['plant']) . ',' : 0;
        $mcc = !empty($data['mcc']) ? ',' . implode(',', $data['mcc']) . ',' : 0;
        $bmc = !empty($data['bmc']) ? ',' . implode(',', $data['bmc']) . ',' : 0;
        $dcs = !empty($data['dcs']) ? ',' . implode(',', $data['dcs']) . ',' : 0;
        $sp = 'sp_app_ho_bmc_data';
        return $this->response['data'] = $this->getSpData($sp, $union, $plant, $mcc, $bmc, $dcs, $content['from_datetime'], $content['to_datetime']);
    }

}
