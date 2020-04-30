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

    public function actionMemberCollectionSummary() {
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
//        $org_codes = $this->getOrgCodes();
        foreach ($param as $value) {
            $array_val = explode(':', $value);
            $param_val = !empty($array_val[1]) ? $array_val[1] : (isset($req_data[$value]) ? $req_data[$value] : NULL);
            $param_val = empty($param_val) && isset($org_codes[$value]) ? (!empty($org_codes[$value]) ? (is_array($org_codes[$value]) ? (',' . implode(',', $org_codes[$value]) . ',') : $org_codes[$value]) : '0' ) : ((is_array($param_val) ? (',' . implode(',', $param_val) . ',') : $param_val));
            $sp_param[] = $param_val;
        }
//        echo "<pre>";
//        print_r($sp_name);
//        echo "</pre>";
//        echo "<pre>";
//        print_r($sp_param);
//        echo "</pre>";
//        die;
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

    public function actionMemberCollectionShift() {
        return $this->prepareResposne();
    }

    public function actionMemberCollectionDateShiftSummary() {
        return $this->prepareResposne();
    }

    public function actionMemberCollectionDateWiseSummary() {
        return $this->prepareResposne();
    }

    public function actionMemberCollectionConsolidated() {
        return $this->prepareResposne();
    }

    public function actionSocietyCollectionDateShiftSummary() {
        return $this->prepareResposne();
    }

    public function actionSocietyCollectionDateWiseSummary() {
        return $this->prepareResposne();
    }

    public function actionSocietyCollectionConsolidated() {
        return $this->prepareResposne();
    }

    public function actionManualMilkEntrySocietyDateShiftWise() {
        return $this->prepareResposne();
    }

    public function actionManualMilkEntryMemberDateShiftWise() {
        return $this->prepareResposne();
    }

    public function actionManualMilkEntrySocietyDateWise() {
        return $this->prepareResposne();
    }

    public function actionManualMilkEntryMemberDateWise() {
        return $this->prepareResposne();
    }

    public function actionManualMilkEntrySocietyConsolidated() {
        return $this->prepareResposne();
    }

    public function actionManualMilkEntryMemberConsolidated() {
        return $this->prepareResposne();
    }

    public function actionBmcCollectionShiftReport() {
        return $this->prepareResposne();
    }

    public function actionBmcCollectionDateShiftSummary() {
        return $this->prepareResposne();
    }

    public function actionBmcCollectionDateWiseSummary() {
        return $this->prepareResposne();
    }

    public function actionBmcCollectionConsolidated() {
        return $this->prepareResposne();
    }

    public function actionUnionCollectionDateShiftSummary() {
        return $this->prepareResposne();
    }

    public function actionUnionCollectionDateSummary() {
        return $this->prepareResposne();
    }

    public function actionUnionCollectionConsolidated() {
        return $this->prepareResposne();
    }

}
