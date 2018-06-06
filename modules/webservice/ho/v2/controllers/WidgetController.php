<?php

namespace app\modules\webservice\ho\v2\controllers;

use app\modules\webservice\controllers\ChildController;
use Yii;

class WidgetController extends ChildController {

    public function actionSocietyWidgets() {
        $param = [];
        $response = [];
        $content = $this->post_data['content'];
        $data = $this->getOrgCodes($this->post_data);
        $param[] = !empty($content['from_datetime']) ? $content['from_datetime'] : '';
        $param[] = !empty($content['to_datetime']) ? $content['to_datetime'] : '';
        $param[] = !empty($data['union']) ? ',' . implode(',', $data['union']) . ',' : 0;
        $param[] = !empty($data['plant']) ? ',' . implode(',', $data['plant']) . ',' : 0;
        $param[] = !empty($data['mcc']) ? ',' . implode(',', $data['mcc']) . ',' : 0;
        $param[] = !empty($data['bmc']) ? ',' . implode(',', $data['bmc']) . ',' : 0;
        $param[] = !empty($data['dcs']) ? ',' . implode(',', $data['dcs']) . ',' : 0;
        $sp = 'sp_app_ho_widget_society_collection';
        $response['society_collection'] = $this->getSpData($sp, $param);
        $sp = 'sp_app_ho_widget_member_diff';
        $response['member_diff'] = $this->getSpData($sp, $param);
        $sp = 'sp_app_ho_collection_vs_completed';
        $param[0] = (date('H:i:s') <= '16:00:00') ? date('Y-m-d 06:00:00') : date('Y-m-d 18:00:00');
        unset($param[1]);
        $response['collection_completed'] = $this->getSpData($sp, $param);
        return $this->response['data'] = $response;
    }

    public function actionBmcWidgets() {
        $param = [];
        $response = [];
        $content = $this->post_data['content'];
        $data = $this->getOrgCodes($this->post_data);
        $param[] = !empty($content['from_datetime']) ? $content['from_datetime'] : '';
        $param[] = !empty($content['to_datetime']) ? $content['to_datetime'] : '';
        $param[] = !empty($data['union']) ? ',' . implode(',', $data['union']) . ',' : 0;
        $param[] = !empty($data['plant']) ? ',' . implode(',', $data['plant']) . ',' : 0;
        $param[] = !empty($data['mcc']) ? ',' . implode(',', $data['mcc']) . ',' : 0;
        $param[] = !empty($data['bmc']) ? ',' . implode(',', $data['bmc']) . ',' : 0;
        $param[] = !empty($data['dcs']) ? ',' . implode(',', $data['dcs']) . ',' : 0;
        $sp = 'sp_app_ho_widget_bmc_collection';
        $response['bmc_collection'] = $this->getSpData($sp, $param);
        $sp = 'sp_app_ho_widget_society_diff';
        $response['society_diff'] = $this->getSpData($sp, $param);
        return $this->response['data'] = $response;
    }

}
