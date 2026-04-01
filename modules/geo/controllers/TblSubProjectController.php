<?php

namespace app\modules\geo\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\geo\models\TblSubProject;
use app\modules\geo\models\TblSubProjectApplicability;
use app\modules\geo\models\TblSubProjectApplicabilityHistory;
use app\modules\geo\models\TblSubProjectHistory;
use app\modules\geo\models\TblSubProjectSearch;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use yii\web\Response;

/**
 * TblSubProjectController implements the CRUD actions for TblSubProject model.
 */
class TblSubProjectController extends ChildController {

    /**
     * Lists all TblSubProject models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblSubProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblSubProject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {

        $this->model = new TblSubProject();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $this->model->sub_project_name = ucwords($this->model->sub_project_name);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Sub Project', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('create', ['model' => $this->model]);
    }

    /**
     * Updates an existing TblSubProject model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $historyModel = new TblSubProjectHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->sub_project_name = ucwords($this->model->sub_project_name);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Sub Project', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', ['model' => $this->model]);
    }

    /**
     * Deletes an existing TblSubProject model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblSubProjectHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblSubProject model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblSubProject the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblSubProject::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSubProjectApplicability($id) {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblSubProjectApplicability();
        $appModel->field_name = 'sub_project_code';
        $appModel->field_value = $id;
        $appModel->trans_label = Yii::t('app', 'sub project applicability');
        $appModel->mcc_field_name = 'applicable_code';
        $appModel->options = ['tanker_rate'];
        $appModel->assignStaticData = [
            'sub_project_code' => $model->sub_project_code
        ];
        $appModel->check_wef_date = true;
        $appModel->check_applicability_with_field_name = false;
        $appModel->select_from_all = true;
        $appModel->header_title = ' [Project: ' . $model->projectCode->project_name . ' > Sub Project: ' . $model->sub_project_name . '] ';
        $appModel->fields = [
            'bmc_code' => ['view' => ['grid'], 'label' => Yii::t('app', 'BMC') . ' Ref Code', 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->bmcCode, 'ref_code');
                }],
            'bmc_name' => ['view' => ['grid'], 'label' => Yii::t('app', 'BMC Name'), 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name');
                }],
            'wef_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->wef_date);
                }],
            'applicable_for' => ['view' => ['grid', 'create'], 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->customerTypeFor, 'customer_desc');
                }],
            'applicable_code' => ['view' => ['grid', 'create'], 'value' => 'applicable_code'],
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
        $appModel->actions = ['delete' => ['option' => 'sub_project_applicability_code,sub_project_applicability_code,tbl-sub-project/delete-applicability,allowDelete()']];
        $appModel->dcs_filters = ['DCS' => Yii::t('app', 'DCS')];
        return $appModel->createApp();
    }

    public function actionDeleteApplicability() {
        $model = new TblSubProjectApplicability();
        $model = $model->findOne(Yii::$app->request->post('id'));
        $historyModel = new TblSubProjectApplicabilityHistory();
        Yii::$app->operation->history($model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

}
