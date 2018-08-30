<?php

namespace app\modules\webservice\supervisor\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use Yii;
use app\modules\webservice\supervisor\v1\V1;

//use app\modules\webservice\models\TblAppMasterCall;
//use app\modules\webservice\models\TblAppMasterCallHistory;

class RequestMasterController extends ChildController {

    public function actionIndex() {
        $req_content = $this->post_data['content'];
        unset($this->post_data['content']);
        $req_data = array_merge($req_content, $this->post_data);
        $svc = $req_data['svc'];
        $data = V1::getLabels($svc);

        if (!empty($data) && !empty($data['sp'])) {
            $sp_name = $data['sp'];
            $sp_param = [];
            $param = !empty($data['param']) ? explode(',', $data['param']) : [];
            $org_codes = $this->getOrgCodes($this->post_data['code']);
            foreach ($param as $value) {
                $param_val = isset($req_data[$value]) ? $req_data[$value] : NULL;
                if ($value == 'project_code' && empty($param_val)) {
                    $param_val = $org_codes['PROJECT'];
                }
                if ($value == 'mcc_code' && empty($param_val)) {
                    $param_val = $org_codes['MCC'];
                }
                if ($value == 'plant_code' && empty($param_val)) {
                    $param_val = $org_codes['PLANT'];
                }
                $sp_param[] = $param_val;
            }
            if (isset($data['checkdate']) && $data['checkdate'] == TRUE) {
                $sp_param[] = NULL;
            }
            $data = \Yii::$app->general->getSpData($sp_name, $sp_param);
        } else {
            $svc_array = explode('/', $svc);
            $cntrlaction = 'webservice/supervisor/v1/' . $svc_array[0] . '/' . $svc_array[1];
            $data = Yii::$app->runAction($cntrlaction)['data'];
        }
        return $this->response['data'] = $data;
    }

}
