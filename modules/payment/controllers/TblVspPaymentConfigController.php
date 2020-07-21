<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblVspPaymentConfig;
use app\modules\payment\models\TblVspPaymentConfigSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\payment\models\TblVspPaymentConfigHistory;
use app\modules\organisation\models\TblDcs;
use yii\web\Response;
use yii\helpers\Json;
use yii\base\Model;

/**
 * TblVspPaymentConfigController implements the CRUD actions for TblVspPaymentConfig model.
 */
class TblVspPaymentConfigController extends \app\controllers\ChildController {

    /**
     * Lists all TblVspPaymentConfig models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVspPaymentConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblVspPaymentConfig model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblVspPaymentConfig();
        $this->model->scenario = 'searchModel';
        $this->model->load(Yii::$app->request->get());
        $dcs = $this->model->dcs_code;
        $existData = $this->model->getExistData();
        $dcsModel = new TblDcs();
        $dcsModel->attributes = $this->model->attributes;
        $dcsModelData = $dcsModel->getRecords($existData, $dcs);
        $saveModel = [];
        foreach ($dcsModelData as $dcs) {
            $m = new TblVspPaymentConfig();
            $m->union_code = $dcs->union_code;
            $m->plant_code = $dcs->plant_code;
            $m->mcc_plant_code = $dcs->mcc_plant_code;
            $m->bmc_code = $dcs->bmc_code;
            $saveModel[] = $m;
        }
        if ($this->model->load(Yii::$app->request->post()) && Model::loadMultiple($saveModel, Yii::$app->request->post()) && Model::validateMultiple($saveModel)) {
            $master = [];
            foreach ($saveModel as $m) {
                if (!empty($m->dcs_code)) {
                    $m->transaction_date = date('Y-m-d');
                    $m->billing_based_on = $this->model->billing_based_on;
                    $master[] = $m;
                }
            }
            $transaction = $this->generalModel->saveTransaction($master, [], ['DCS Wise Billing Mapping', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
                    'searchModel' => $this->model,
                    'model' => $dcsModelData,
                    'saveModel' => $saveModel,
                    'dcsModelData' => $dcsModelData
        ]);
    }

    /**
     * Deletes an existing TblVspPaymentConfig model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblVspPaymentConfigHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblVspPaymentConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVspPaymentConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVspPaymentConfig::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
