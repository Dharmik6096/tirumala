<?php

namespace app\modules\webservice\ho\v2\controllers;

use Yii;

class SocietyController extends \app\modules\webservice\ho\v1\controllers\SocietyController {

    public $apply_camel_case = FALSE;

    public function actionSocietyData() {
        $param = [];
        $content = $this->post_data['content'];
        $data = $this->getOrgCodes($this->post_data);
        $param[] = !empty($content['from_datetime']) ? $content['from_datetime'] : '';
        $param[] = !empty($content['to_datetime']) ? $content['to_datetime'] : '';
        $param[] = !empty($data['union']) ? ',' . implode(',', $data['union']) . ',' : 0;
        $param[] = !empty($data['plant']) ? ',' . implode(',', $data['plant']) . ',' : 0;
        $param[] = !empty($data['mcc']) ? ',' . implode(',', $data['mcc']) . ',' : 0;
        $param[] = !empty($content['bmc_code']) ? ',' . $content['bmc_code'] . ',' : 0;
        $param[] = !empty($data['dcs']) ? ',' . implode(',', $data['dcs']) . ',' : 0;
        $param[] = !empty($content['data_type']) ? $content['data_type'] : 'P';
        if (!empty($content['data_type']) && $content['data_type'] == 'P')
            $sp = 'sp_app_ho_bmc_dcs_data';
        else
            $sp = 'sp_app_ho_dcs_data';

        return $this->response['data'] = $this->getSpData($sp, $param);
    }

}
