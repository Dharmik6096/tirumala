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
    
    public function actionCollectionCompleted() {
        $param = [];
        $response = [];
        $data = $this->getOrgCodes($this->post_data);
        $param[] = (date('H:i:s') <= '16:00:00') ? date('Y-m-d 06:00:00') : date('Y-m-d 18:00:00');
        $param[] = !empty($data['union']) ? ',' . implode(',', $data['union']) . ',' : 0;
        $param[] = !empty($data['plant']) ? ',' . implode(',', $data['plant']) . ',' : 0;
        $param[] = !empty($data['mcc']) ? ',' . implode(',', $data['mcc']) . ',' : 0;
        $param[] = !empty($data['bmc']) ? ',' . implode(',', $data['bmc']) . ',' : 0;
        $param[] = !empty($data['dcs']) ? ',' . implode(',', $data['dcs']) . ',' : 0;
        $sp = 'sp_app_ho_collection_vs_completed';
        return $this->response['data'] = $this->getSpData($sp, $param);
    }
    
    public function actionSocietyComparision(){
        $param = [];
        $response = [];
        $data = $this->getOrgCodes($this->post_data);
        $to_date1 = date('Y-m-d');
        $from_date1 = date('Y-m-d', strtotime("-6 day", strtotime($to_date1)));
        $to_date2 = date('Y-m-d', strtotime("-1 day", strtotime($from_date1)));
        $from_date2 = date('Y-m-d', strtotime("-6 day", strtotime($to_date2)));
        $param[] = $from_date1;
        $param[] = $to_date1;
        $param[] = $from_date2;
        $param[] = $to_date2;
        $param[] = !empty($data['union']) ? ',' . implode(',', $data['union']) . ',' : 0;
        $param[] = !empty($data['plant']) ? ',' . implode(',', $data['plant']) . ',' : 0;
        $param[] = !empty($data['mcc']) ? ',' . implode(',', $data['mcc']) . ',' : 0;
        $param[] = !empty($data['bmc']) ? ',' . implode(',', $data['bmc']) . ',' : 0;
        $param[] = !empty($data['dcs']) ? ',' . implode(',', $data['dcs']) . ',' : 0;
        $sp = 'sp_app_ho_widget_society_comparison';
        return $this->response['data'] = $this->getSpData($sp, $param);
    }
    
    public function actionBmcComparision(){
        $param = [];
        $response = [];
        $data = $this->getOrgCodes($this->post_data);
        $to_date1 = date('Y-m-d');
        $from_date1 = date('Y-m-d', strtotime("-6 day", strtotime($to_date1)));
        $to_date2 = date('Y-m-d', strtotime("-1 day", strtotime($from_date1)));
        $from_date2 = date('Y-m-d', strtotime("-6 day", strtotime($to_date2)));
        $param[] = $from_date1;
        $param[] = $to_date1;
        $param[] = $from_date2;
        $param[] = $to_date2;
        $param[] = !empty($data['union']) ? ',' . implode(',', $data['union']) . ',' : 0;
        $param[] = !empty($data['plant']) ? ',' . implode(',', $data['plant']) . ',' : 0;
        $param[] = !empty($data['mcc']) ? ',' . implode(',', $data['mcc']) . ',' : 0;
        $param[] = !empty($data['bmc']) ? ',' . implode(',', $data['bmc']) . ',' : 0;
        $param[] = !empty($data['dcs']) ? ',' . implode(',', $data['dcs']) . ',' : 0;
        $sp = 'sp_app_ho_widget_bmc_comparison';
        return $this->response['data'] = $this->getSpData($sp, $param);
    }
    
    public function actionSocietyWeeklyCollection(){
        $param = [];
        $response = [];
        $data = $this->getOrgCodes($this->post_data);
        $to_date = date('Y-m-d');
        $from_date = date('Y-m-d', strtotime("-6 day", strtotime($to_date)));
        $param[] = $from_date;
        $param[] = $to_date;
        $param[] = !empty($data['union']) ? ',' . implode(',', $data['union']) . ',' : 0;
        $param[] = !empty($data['plant']) ? ',' . implode(',', $data['plant']) . ',' : 0;
        $param[] = !empty($data['mcc']) ? ',' . implode(',', $data['mcc']) . ',' : 0;
        $param[] = !empty($data['bmc']) ? ',' . implode(',', $data['bmc']) . ',' : 0;
        $param[] = !empty($data['dcs']) ? ',' . implode(',', $data['dcs']) . ',' : 0;
        $sp = 'sp_app_ho_widget_society_weekly_collection';
        return $this->response['data'] = $this->getSpData($sp, $param);
    }
    
    public function actionBmcWeeklyCollection(){
        $param = [];
        $response = [];
        $data = $this->getOrgCodes($this->post_data);
        $to_date = date('Y-m-d');
        $from_date = date('Y-m-d', strtotime("-6 day", strtotime($to_date)));
        $param[] = $from_date;
        $param[] = $to_date;
        $param[] = !empty($data['union']) ? ',' . implode(',', $data['union']) . ',' : 0;
        $param[] = !empty($data['plant']) ? ',' . implode(',', $data['plant']) . ',' : 0;
        $param[] = !empty($data['mcc']) ? ',' . implode(',', $data['mcc']) . ',' : 0;
        $param[] = !empty($data['bmc']) ? ',' . implode(',', $data['bmc']) . ',' : 0;
        $param[] = !empty($data['dcs']) ? ',' . implode(',', $data['dcs']) . ',' : 0;
        $sp = 'sp_app_ho_widget_bmc_weekly_collection';
        return $this->response['data'] = $this->getSpData($sp, $param);
    }

}
