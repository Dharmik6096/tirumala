<?php

namespace app\modules\webservice\ho\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use Yii;

class MemberController extends ChildController {

    public function actionMemberData() {
        $content = $this->post_data['content'];
        $data = $this->getOrgCodes($this->post_data);
        $union = !empty($data['union']) ? ',' . implode(',', $data['union']) . ',' : 0;
        $plant = !empty($data['plant']) ? ',' . implode(',', $data['plant']) . ',' : 0;
        $mcc = !empty($data['mcc']) ? ',' . implode(',', $data['mcc']) . ',' : 0;
        $bmc = !empty($data['bmc']) ? ',' . implode(',', $data['bmc']) . ',' : 0;
        $dcs = !empty($content['dcs_code']) ? ',' . $content['dcs_code'] . ',' : 0;
        $sp = 'sp_app_ho_member_data';
        return $this->response['data'] = $this->getSpData($sp, $union, $plant, $mcc, $bmc, $dcs, $content['from_datetime'], $content['to_datetime']);
    }

}
