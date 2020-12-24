<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblCollectionPenaltyRate;
use app\modules\collection\models\TblCollectionPenaltyRateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\collection\models\TblCollectionPenaltyRateHistory;
use app\modules\collection\models\TblCollectionPenaltyRateApplicability;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\collection\models\TblCollectionPenaltyRateApplicabilityHistory;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblCollectionPenaltyRateController implements the CRUD actions for TblCollectionPenaltyRate model.
 */
class TblCollectionPenaltyRateController extends \app\controllers\ChildController {

    /**
     * Lists all TblCollectionPenaltyRate models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblCollectionPenaltyRateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblCollectionPenaltyRate model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblCollectionPenaltyRate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblCollectionPenaltyRate();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->penalty_rate_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            $this->model->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Collection Penalty Rate', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblCollectionPenaltyRate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblCollectionPenaltyRateHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Collection Penalty Rate', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblCollectionPenaltyRate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblCollectionPenaltyRate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblCollectionPenaltyRate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblCollectionPenaltyRate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCollectionPenaltyRateApplicability($id) {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblCollectionPenaltyRateApplicability();
        $customerType = new TblCustomerType();
        $customerType->union_code = $model->union_code;
        $value = $customerType->getCustomerType(['tbl_customer_type.is_applicability' => 1]);
//        $appModel->model->shift_code = $model->shift_id;
        $appModel->model->wef_date = $model->wef_date;
//        $appModel->is_union = false;
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'penalty_rate_code';
        $appModel->field_value = $id;
        $appModel->trans_label = 'penalty rate applicability';
        $appModel->mcc_field_name = 'applicable_code';
        $appModel->options = ['tanker_rate'];
        $appModel->assignStaticData = [
            'penalty_rate' => $model->penalty_rate,
            'penalty_type' => $model->penalty_type
        ];
        $appModel->header_title = ' [Type: ' . Yii::$app->dropdown->getRecords('penalty_type')['data'][$model->penalty_type] . ', Rate: ' . $model->penalty_rate . '] ';
        $appModel->fields = [
            'wef_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->wef_date);
                }],
            'applicable_for' => ['view' => ['grid'], 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->customerTypeFor, 'customer_desc');
                }],
            'applicable_code' => ['view' => ['grid'], 'value' => 'applicable_code'],
            'ref_code' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for, false, FALSE, TRUE);
                }],
            'code_ex' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code Ex.'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for, true);
                }],
            'name' => ['view' => ['grid'], 'value' => function($model) {
                    return $model->getName($model->applicable_for);
                }],
        ];
        $appModel->dcs_filters = $value;
        $appModel->actions = ['delete' => ['option' => 'penalty_rate_applicability_code,penalty_rate_applicability_code,tbl-collection-penalty-rate/delete-applicability']];

        return $appModel->createApp();
    }

    public function actionDeleteApplicability() {
        $model = new TblCollectionPenaltyRateApplicability();
        $model = $model->findOne(Yii::$app->request->post('id'));
        $historyModel = new TblCollectionPenaltyRateApplicabilityHistory();
        Yii::$app->operation->history($model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

}
