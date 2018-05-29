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
        $data_type = !empty($content['data_type']) ? $content['data_type'] : 'P';        
        $query = \Yii::$app->db->createCommand("{CALL sp_app_ho_bmc_data(:date_from,:date_to,:union_code,:plant_code,:mcc_code,:bmc_code,:dcs_code,:data_type)}")
                ->bindValue(':date_from', $content['from_datetime'])
                ->bindValue(':date_to', $content['to_datetime'])
                ->bindValue(':union_code', $union)
                ->bindValue(':plant_code', $plant)
                ->bindValue(':mcc_code', $mcc)
                ->bindValue(':bmc_code', $bmc)
                ->bindValue(':dcs_code', $dcs)
                ->bindValue(':data_type', $data_type);
        return $this->response['data'] = $query->queryAll();
    }

}
