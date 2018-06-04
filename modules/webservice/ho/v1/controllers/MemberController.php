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
        $data_type = !empty($content['data_type']) ? $content['data_type'] : 'P';
        $from_datetime = !empty($content['from_datetime']) ? $content['from_datetime'] : '';
        $to_datetime = !empty($content['to_datetime']) ? $content['to_datetime'] : '';
        $sp = 'sp_app_ho_member_data';
        return $this->response['data'] = $this->getSpData($sp, $union, $plant, $mcc, $bmc, $dcs, $from_datetime, $to_datetime, $data_type);
    }

    public function actionMemberCollectionDetails() {
        $content = $this->post_data['content'];
        $data = $this->getOrgCodes($this->post_data);
        $union = !empty($data['union']) ? ',' . implode(',', $data['union']) . ',' : 0;
        $plant = !empty($data['plant']) ? ',' . implode(',', $data['plant']) . ',' : 0;
        $mcc = !empty($data['mcc']) ? ',' . implode(',', $data['mcc']) . ',' : 0;
        $bmc = !empty($data['bmc']) ? ',' . implode(',', $data['bmc']) . ',' : 0;
        $dcs = !empty($content['dcs_code']) ? ',' . $content['dcs_code'] . ',' : 0;
        $member_code = !empty($content['member_code']) ? ',' . $content['dcs_code'] . $content['member_code'] . ',' : 0;
        $from_datetime = !empty($content['from_datetime']) ? $content['from_datetime'] : '';
        $to_datetime = !empty($content['to_datetime']) ? $content['to_datetime'] : '';
        $sp = 'sp_app_ho_member_collection_details';
        $query = \Yii::$app->db->createCommand("{CALL " . $sp . "(:date_from,:date_to,:union_code,:plant_code,:mcc_code,:bmc_code,:dcs_code,:member_code)}")
                ->bindValue(':date_from', $from_datetime)
                ->bindValue(':date_to', $to_datetime)
                ->bindValue(':union_code', $union)
                ->bindValue(':plant_code', $plant)
                ->bindValue(':mcc_code', $mcc)
                ->bindValue(':bmc_code', $bmc)
                ->bindValue(':dcs_code', $dcs)
                ->bindValue(':member_code', $member_code);
        return $this->response['data'] = $query->queryAll();
    }

}
