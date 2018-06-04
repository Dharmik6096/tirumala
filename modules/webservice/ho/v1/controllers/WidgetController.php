<?php

namespace app\modules\webservice\ho\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use Yii;

class WidgetController extends ChildController {

    
    public function actionSocietyCollection() {
        $param = [];
        $content = $this->post_data['content'];
        $data = $this->getOrgCodes($this->post_data);
        $param['date_from'] = !empty($content['from_datetime']) ? $content['from_datetime'] : '';
        $param['date_to'] = !empty($content['to_datetime']) ? $content['to_datetime'] : '';
        $param['union_code'] = !empty($data['union']) ? ',' . implode(',', $data['union']) . ',' : 0;
        $param['plant_code'] = !empty($data['plant']) ? ',' . implode(',', $data['plant']) . ',' : 0;
        $param['mcc_code'] = !empty($data['mcc']) ? ',' . implode(',', $data['mcc']) . ',' : 0;
        $param['bmc_code'] = !empty($data['bmc']) ? ',' . implode(',', $data['bmc']) . ',' : 0;
        $param['dcs_code'] = !empty($data['dcs']) ? ',' . implode(',', $data['dcs']) . ',' : 0;
        $sp = 'sp_app_ho_widget_society_collection';
        return $this->response['data'] = $this->getSpData($sp, $param);
    }
    
    public function actionBmcCollection() {
        $param = [];
        $content = $this->post_data['content'];
        $data = $this->getOrgCodes($this->post_data);
        $param['date_from'] = !empty($content['from_datetime']) ? $content['from_datetime'] : '';
        $param['date_to'] = !empty($content['to_datetime']) ? $content['to_datetime'] : '';
        $param['union_code'] = !empty($data['union']) ? ',' . implode(',', $data['union']) . ',' : 0;
        $param['plant_code'] = !empty($data['plant']) ? ',' . implode(',', $data['plant']) . ',' : 0;
        $param['mcc_code'] = !empty($data['mcc']) ? ',' . implode(',', $data['mcc']) . ',' : 0;
        $param['bmc_code'] = !empty($data['bmc']) ? ',' . implode(',', $data['bmc']) . ',' : 0;
        $param['dcs_code'] = !empty($data['dcs']) ? ',' . implode(',', $data['dcs']) . ',' : 0;
        $sp = 'sp_app_ho_widget_bmc_collection';
        return $this->response['data'] = $this->getSpData($sp, $param);
    }
    
    public function actionMemberDiff() {
        $param = [];
        $content = $this->post_data['content'];
        $data = $this->getOrgCodes($this->post_data);
        $param['date_from'] = !empty($content['from_datetime']) ? $content['from_datetime'] : '';
        $param['date_to'] = !empty($content['to_datetime']) ? $content['to_datetime'] : '';
        $param['union_code'] = !empty($data['union']) ? ',' . implode(',', $data['union']) . ',' : 0;
        $param['plant_code'] = !empty($data['plant']) ? ',' . implode(',', $data['plant']) . ',' : 0;
        $param['mcc_code'] = !empty($data['mcc']) ? ',' . implode(',', $data['mcc']) . ',' : 0;
        $param['bmc_code'] = !empty($data['bmc']) ? ',' . implode(',', $data['bmc']) . ',' : 0;
        $param['dcs_code'] = !empty($data['dcs']) ? ',' . implode(',', $data['dcs']) . ',' : 0;
        $sp = 'sp_app_ho_widget_member_diff';
        return $this->response['data'] = $this->getSpData($sp, $param);
    }
       
    public function actionSocietyDiff() {
        $param = [];
        $content = $this->post_data['content'];
        $data = $this->getOrgCodes($this->post_data);
        $param['date_from'] = !empty($content['from_datetime']) ? $content['from_datetime'] : '';
        $param['date_to'] = !empty($content['to_datetime']) ? $content['to_datetime'] : '';
        $param['union_code'] = !empty($data['union']) ? ',' . implode(',', $data['union']) . ',' : 0;
        $param['plant_code'] = !empty($data['plant']) ? ',' . implode(',', $data['plant']) . ',' : 0;
        $param['mcc_code'] = !empty($data['mcc']) ? ',' . implode(',', $data['mcc']) . ',' : 0;
        $param['bmc_code'] = !empty($data['bmc']) ? ',' . implode(',', $data['bmc']) . ',' : 0;
        $param['dcs_code'] = !empty($data['dcs']) ? ',' . implode(',', $data['dcs']) . ',' : 0;
        $sp = 'sp_app_ho_widget_society_diff';
        return $this->response['data'] = $this->getSpData($sp, $param);
    }

}
