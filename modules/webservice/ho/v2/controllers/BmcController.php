<?php

namespace app\modules\webservice\ho\v2\controllers;

use Yii;

class BmcController extends \app\modules\webservice\ho\v1\controllers\BmcController {

    public function actionBmcData() {
        $param = [];
        $content = $this->post_data['content'];
        $data = $this->getOrgCodes($this->post_data);
        $param[] = !empty($content['from_datetime']) ? $content['from_datetime'] : '';
        $param[] = !empty($content['to_datetime']) ? $content['to_datetime'] : '';
        $param[] = !empty($data['union']) ? ',' . implode(',', $data['union']) . ',' : 0;
        $param[] = !empty($data['plant']) ? ',' . implode(',', $data['plant']) . ',' : 0;
        $param[] = !empty($data['mcc']) ? ',' . implode(',', $data['mcc']) . ',' : 0;
        $param[] = !empty($data['bmc']) ? ',' . implode(',', $data['bmc']) . ',' : 0;
        $param[] = !empty($data['dcs']) ? ',' . implode(',', $data['dcs']) . ',' : 0;
        $param[] = !empty($content['data_type']) ? $content['data_type'] : 'P';
        $sp = 'sp_app_ho_v2_bmc_data';
        $this->apply_camel_case = FALSE;
        return $this->response['data'] = $this->getSpData($sp, $param);
    }

}
