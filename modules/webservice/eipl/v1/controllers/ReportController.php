<?php

namespace app\modules\webservice\eipl\v1\controllers;

use app\modules\webservice\eipl\controllers\MasterController;
use Yii;
use app\modules\webservice\eipl\v1\V1;
use app\modules\webservice\components\EiplRequest;
use yii\helpers\Json;

class ReportController extends MasterController {

    public function actionMemberCollection() {
        return $this->prepareResposne();
    }

    public function actionMemberCollectionPassbook() {
        return $this->prepareResposne();
    }

    public function prepareResposne() {
        $req_data = Yii::$app->request->getRawBody();
        $endpoint = !empty($req_data['endpoint']) ? $req_data['endpoint'] : NULL;
        $data = V1::getLabels($endpoint);
        $response = [];
        $sp_name = $data['sp'];
        $sp_param = [];
        $param = !empty($data['param']) ? explode('#', $data['param']) : [];
        $org_codes = $this->getOrgCodes();
        foreach ($param as $value) {
            $array_val = explode(':', $value);
            $param_val = !empty($array_val[1]) ? $array_val[1] : (isset($req_data[$value]) ? $req_data[$value] : NULL);
            $param_val = empty($param_val) && isset($org_codes[$value]) ? (!empty($org_codes[$value]) ? (is_array($org_codes[$value]) ? (',' . implode(',', $org_codes[$value]) . ',') : $org_codes[$value]) : '0' ) : ((is_array($param_val) ? (',' . implode(',', $param_val) . ',') : $param_val));
            $sp_param[] = $param_val;
        }
        $result = \Yii::$app->general->getSpData($sp_name, $sp_param);
        $response['main_data'] = [];
        $response['summary_line'] = '';
        if (!empty($result)) {
            $response['summary_line'] = $result[count($result) - 1];
            unset($result[count($result) - 1]);
            $response['main_data'] = $result;
        }
        $this->response->setData($response);
        return $this->response;
    }

}
