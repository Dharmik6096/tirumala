<?php

namespace app\modules\vsp\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use app\modules\vsp\models\TblMccBillHeadCriteriaSearch;
use app\modules\vsp\models\TblMccBillHeadCriteria;
use app\modules\vsp\models\TblMccBillHeadCriteriaSlabs;
use app\modules\vsp\models\TblMccBillHeadCriteriaSlabsSearch;

class TblMccBillHeadCriteriaController extends \app\controllers\ChildController {

    public function actionIndex() {
        $searchModel = new TblMccBillHeadCriteriaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $this->model = new TblMccBillHeadCriteria();
        $searchModel = new TblMccBillHeadCriteriaSlabsSearch();
        $dataProvider = $searchModel->createsearch([]);
        $dataProvider->sort = false;
        $txModel = new TblMccBillHeadCriteriaSlabs();
        $this->viewFile = 'create';
        $txModel->scenario = 'create';
        $modelSave = [];
        $message = 'Mcc Bill Head Criteria';
        $type = 'create';
       
        if (Yii::$app->request->post()) {
            echo '<pre>';
            print_r(Yii::$app->request->post());
            die();
            $this->model->load(Yii::$app->request->post());

            $txModel->load(Yii::$app->request->post());
            if ($txModel->validate()) {
                if (empty($this->model->criteria_code)) {
                    $this->model->scenario = 'create';
                    $this->model->criteria_code = Yii::$app->general->getPrimaryCode($this->model);
                    $modelSave[] = $this->model;
                }
                $txModel->criteria_code = $this->model->criteria_code;
                $txModel->criteria_slab_code = Yii::$app->general->getTransactionCode($txModel, $txModel->criteria_code);
                $txModel->union_code = $this->model->union_code;
                $txModel->general_formula_code = $this->model->general_formula_code;
                $txModel->mcc_bill_head_code = $this->model->mcc_bill_head_code;
                $modelSave[] = $txModel;
            }
            if (empty($this->model->getErrors()) && empty($txModel->getErrors()) && $this->model->validate() && $txModel->validate()) {
                $transaction = $this->generalModel->saveTransaction($modelSave, [$message, $type]);
                if ($transaction == 'customRedirect') {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg, 'pk_code' => $this->model->criteria_code];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $err = [];
                foreach ($this->model->getErrors() as $key => $value) {
                    $err[$key] = $value;
                }
                foreach ($txModel->getErrors() as $key => $value) {
                    $err[$key] = $value;
                }

                return Json::encode($err);
            }
        } else {
            return $this->render('create', [
                        'model' => $this->model,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'txModel' => $txModel,
            ]);
        }
        return $this->render('create', [
            'model' => $this->model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'txModel' => $txModel,
    ]);
}
}