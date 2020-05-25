<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblMemberPaymentRestrict;
use app\modules\payment\models\TblMemberPaymentRestrictSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\organisation\models\TblDcs;
use yii\base\Model;
use app\modules\payment\models\TblMemberPaymentRestrictHistory;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblMemberPaymentRestrictController implements the CRUD actions for TblMemberPaymentRestrict model.
 */
class TblMemberPaymentRestrictController extends \app\controllers\ChildController {

    /**
     * Lists all TblMemberPaymentRestrict models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMemberPaymentRestrictSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMemberPaymentRestrict model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblMemberPaymentRestrict model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblMemberPaymentRestrict();
        $this->model->scenario = 'searchModel';
        $this->model->load(Yii::$app->request->get());
        $existData = $this->model->getExistData();
        $dcsModel = new TblDcs();
        $dcsModel->attributes = $this->model->attributes;
        $dcsModelData = $dcsModel->getRecords($existData);
        $saveModel = [];
        foreach ($dcsModelData as $dcs) {
            $m = new TblMemberPaymentRestrict();
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
                    $m->wef_date = !empty($m->wef_date) ? date('Y-m-d', strtotime($m->wef_date)) : '';
                    $master[] = $m;
                }
            }
            $transaction = $this->generalModel->saveTransaction($master, [], ['Member Payment Restrict', 'create']);
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
     * Deletes an existing TblMemberPaymentRestrict model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblMemberPaymentRestrictHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblMemberPaymentRestrict model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMemberPaymentRestrict the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMemberPaymentRestrict::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
