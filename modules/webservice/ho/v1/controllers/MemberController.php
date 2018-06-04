<?php

namespace app\modules\webservice\ho\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use Yii;

class MemberController extends ChildController {

    public function actionMemberData() {
        $param = [];
        $content = $this->post_data['content'];
        $data = $this->getOrgCodes($this->post_data);
        $param['date_from'] = !empty($content['from_datetime']) ? $content['from_datetime'] : '';
        $param['date_to'] = !empty($content['to_datetime']) ? $content['to_datetime'] : '';
        $param['union_code'] = !empty($data['union']) ? ',' . implode(',', $data['union']) . ',' : 0;
        $param['plant_code'] = !empty($data['plant']) ? ',' . implode(',', $data['plant']) . ',' : 0;
        $param['mcc_code'] = !empty($data['mcc']) ? ',' . implode(',', $data['mcc']) . ',' : 0;
        $param['bmc_code'] = !empty($data['bmc']) ? ',' . implode(',', $data['bmc']) . ',' : 0;
        $param['dcs_code'] = !empty($content['dcs_code']) ? ',' . $content['dcs_code'] . ',' : 0;
        $param['data_type'] = !empty($content['data_type']) ? $content['data_type'] : 'P';
        $sp = 'sp_app_ho_member_data';
        if ($param['data_type'] == 'A') {
            $this->apply_camel_case = FALSE;
        }
        return $this->response['data'] = $this->getSpData($sp, $param);
    }

    public function actionMemberCollectionDetails() {
        $param = [];
        $content = $this->post_data['content'];
        $data = $this->getOrgCodes($this->post_data);
        $param['date_from'] = !empty($content['from_datetime']) ? $content['from_datetime'] : '';
        $param['date_to'] = !empty($content['to_datetime']) ? $content['to_datetime'] : '';
        $param['union_code'] = !empty($data['union']) ? ',' . implode(',', $data['union']) . ',' : 0;
        $param['plant_code'] = !empty($data['plant']) ? ',' . implode(',', $data['plant']) . ',' : 0;
        $param['mcc_code'] = !empty($data['mcc']) ? ',' . implode(',', $data['mcc']) . ',' : 0;
        $param['bmc_code'] = !empty($data['bmc']) ? ',' . implode(',', $data['bmc']) . ',' : 0;
        $param['dcs_code'] = !empty($content['dcs_code']) ? ',' . $content['dcs_code'] . ',' : 0;
        $param['member_code'] = !empty($content['member_code']) ? ',' . $content['dcs_code'] . $content['member_code'] . ',' : 0;
        $sp = 'sp_app_ho_member_collection_details';
        
        return $this->response['data'] = $this->getSpData($sp, $param);
    }

}
