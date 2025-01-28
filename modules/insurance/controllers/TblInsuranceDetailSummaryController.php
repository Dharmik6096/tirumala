<?php

namespace app\modules\insurance\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\insurance\models\TblInsuranceDetailSummary;
use app\modules\insurance\models\TblInsuranceDetailSummaryHistory;
use yii\helpers\Json;

/**
 * TblInsuranceDetailSummaryController implements the CRUD actions for TblInsuranceDetailSummary model.
 */
class TblInsuranceDetailSummaryController extends ChildController {

    public $freeAccessActions = ['get-from-to-date', 'dcs-list'];

    /**
     * Creates a new TblInsuranceDetailSummary model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionExtendDate() {
        $this->model = new TblInsuranceDetailSummary();
        $this->viewFile = 'extend_date';
        $this->model->scenario = 'extend_date';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model = TblInsuranceDetailSummary::find()->where(['insurance_master_code' => $this->model->insurance_master_code, 'dcs_code' => $this->model->dcs_code, 'status' => 'PUBLISH'])->one();
            if (!empty($this->model)) {
                $master = [];
                $historyModel = new TblInsuranceDetailSummaryHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $master[] = $historyModel;
                $this->model->scenario = 'extend_date';
                $this->model->load(Yii::$app->request->post());
                if ($this->model->validate()) {
                    $master[] = $this->model;
                    $transaction = $this->generalModel->saveTransaction($master, ['Insurance Detail Summary', 'edit']);
                    if ($transaction !== FALSE) {
                        return $this->redirect(['/insurance/tbl-insurance-detail/index']);
                    }
                }
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => 'Selected Society insurance record not avaliable.']);
                return $this->redirect(['extend-date']);
            }
        }
        return $this->customRender();
    }

    public function actionGetFromToDate() {
        $insurance_master_code = Yii::$app->request->post('insurance_master_code');
        $dcs_code = Yii::$app->request->post('dcs_code');

        $insuranceDetailSummaryModel = new TblInsuranceDetailSummary();
        $data = $insuranceDetailSummaryModel->getInsuranceDetailSummary($insurance_master_code, $dcs_code, 'PUBLISH');
        if (!empty($data)) {
            $toDate = Yii::$app->controls->view_date($data->to_date);
            $fromDate = Yii::$app->controls->view_date($data->from_date);
            return Json::encode(['status' => 'success', 'to_date' => $toDate, 'from_date' => $fromDate]);
        } else {
            return Json::encode(['status' => 'error']);
        }
    }

    public function actionDcsList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $date = (isset($parents[2]) && !empty($parents[2])) ? $parents[2] : FALSE;
            if (!empty($parents[0]) && !empty($parents[1])) {
                $insuranceSummary = new TblInsuranceDetailSummary();
                $data = $insuranceSummary->getBMCDCSList($parents[0], $parents[1], $date);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

}
