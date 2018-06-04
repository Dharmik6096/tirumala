<?php

namespace app\modules\webservice\ho\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use Yii;

class SocietyController extends ChildController {

    public function actionSocietyData() {
        $param = [];
        $content = $this->post_data['content'];
        $data = $this->getOrgCodes($this->post_data);
        $param['date_from'] = !empty($content['from_datetime']) ? $content['from_datetime'] : '';
        $param['date_to'] = !empty($content['to_datetime']) ? $content['to_datetime'] : '';
        $param['union_code'] = !empty($data['union']) ? ',' . implode(',', $data['union']) . ',' : 0;
        $param['plant_code'] = !empty($data['plant']) ? ',' . implode(',', $data['plant']) . ',' : 0;
        $param['mcc_code'] = !empty($data['mcc']) ? ',' . implode(',', $data['mcc']) . ',' : 0;
        $param['bmc_code'] = !empty($content['bmc_code']) ? ',' . $content['bmc_code'] . ',' : 0;
        $param['dcs_code'] = !empty($data['dcs']) ? ',' . implode(',', $data['dcs']) . ',' : 0;
        $param['data_type'] = !empty($content['data_type']) ? $content['data_type'] : 'P';
        if ($content['data_type'] == 'P')
            $sp = 'sp_app_ho_bmc_dcs_data';
        else
            $sp = 'sp_app_ho_dcs_data';
        if ($param['data_type'] == 'A') {
            $this->apply_camel_case = FALSE;
        }
        return $this->response['data'] = $this->getSpData($sp, $param);
    }

}
