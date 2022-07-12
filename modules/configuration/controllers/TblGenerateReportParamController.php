<?php

namespace app\modules\configuration\controllers;

use Yii;
use app\modules\configuration\models\TblGenerateReportParam;
use app\modules\configuration\models\TblGenerateReportParamSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblGenerateReportParamController implements the CRUD actions for TblGenerateReportParam model.
 */
class TblGenerateReportParamController extends \app\controllers\ChildController {

    /**
     * Lists all TblGenerateReportParam models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblGenerateReportParamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblGenerateReportParam model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblGenerateReportParam model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblGenerateReportParam();
        $this->viewFile = 'create';
        $this->model->from_date = date('d-m-Y');
        $this->model->to_date = date('d-m-Y');
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->model->scenario = 'createFront';
            if ($this->model->validate()) {
                $this->model->from_date = Yii::$app->formatter->asDate($this->model->from_date, DATE_FORMAT) . ' ' . Yii::$app->general->getshift($this->model->from_shift);
                $this->model->to_date = Yii::$app->formatter->asDate($this->model->to_date, DATE_FORMAT) . ' ' . Yii::$app->general->getshift($this->model->to_shift);
                if ($this->model->report_type == 0) {
                    $this->model->report_key = 'MemberPassbook';
                } elseif ($this->model->report_type == 1) {
                    $this->model->report_key = 'MemberDailyCollection';
                } elseif ($this->model->report_type == 2) {
                    $this->model->report_key = 'MemberConsolidated';
                } else {
                    $this->model->report_key = 'MilkCollectionData';
                }
                if (empty($this->model->union_code)) {
                    $this->model->union_code = !empty(Yii::$app->session->get('organizations_code')) ? ',' . Yii::$app->session->get('organizations_code') . ',' : 0;
                }
                if (empty($this->model->plant_code)) {
                    $this->model->plant_code = !empty(Yii::$app->session->get('Plant')) ? ',' . Yii::$app->session->get('Plant') . ',' : 0;
                }
                if (empty($this->model->mcc_code)) {
                    $this->model->mcc_code = !empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : 0;
                }
                if (empty($this->model->bmc_code)) {
                    $this->model->bmc_code = !empty(Yii::$app->session->get('BMC')) ? ',' . Yii::$app->session->get('BMC') . ',' : 0;
                }
                if (empty($this->model->dcs_code)) {
                    $this->model->dcs_code = !empty(Yii::$app->session->get('Dcs')) ? ',' . Yii::$app->session->get('Dcs') . ',' : 0;
                }
                if (empty($this->model->member_code)) {
                    $this->model->member_code = 0;
                }
                $exist = $this->model->getExistData();
                if (!empty($exist)) {
                    $this->model->ref_code = $exist->report_param_code;
                }
                $transaction = $this->generalModel->saveTransaction([$this->model], ['Report Param', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Updates an existing TblGenerateReportParam model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->report_param_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblGenerateReportParam model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblGenerateReportParam model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblGenerateReportParam the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblGenerateReportParam::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
