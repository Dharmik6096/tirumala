<?php

namespace app\modules\vsp\controllers;

use Yii;
use app\modules\vsp\models\TblBillHead;
use app\modules\vsp\models\TblBillHeadSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\vsp\models\TblBillHeadHistory;
use app\modules\vsp\models\TblBillHeadApplicability;
use app\modules\vsp\models\TblBillHeadApplicabilityHistory;
use app\modules\vsp\models\TblCriteriaKeywordMapping;
use yii\web\Response;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

/**
 * TblBillHeadController implements the CRUD actions for TblBillHead model.
 */
class TblBillHeadController extends \app\controllers\ChildController {

    /**
     * Lists all TblBillHead models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBillHeadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBillHead model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblBillHead model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {

        $this->model = new TblBillHead();
        $this->viewFile = 'create';

        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());

            $this->model->bill_head_code = (string) Yii::$app->general->getCodeAutoIncrement($this->model);
            $this->model->general_formula_comma = $this->AddCommaFormula($this->model->general_formula);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Bill Head', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Updates an existing TblBillHead model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if (Yii::$app->request->post()) {
            $master = [];
            $historyModel = new TblBillHeadHistory();
            Yii::$app->operation->history($model, $historyModel, 'UPDATE');
            $master[] = $historyModel;
            $model->load(Yii::$app->request->post());
            $model->general_formula_comma = $this->AddCommaFormula($model->general_formula);
            $master[] = $model;
            $transaction = $this->generalModel->saveTransaction($master, ['Bill Head', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TblBillHead model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblBillHeadHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblBillHead model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblBillHead the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblBillHead::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function AddCommaFormula($formula) {
        $operator = ['+', '-', '*', '/', '(', ')'];
        foreach ($operator as $key => $val) {
            $formula = str_replace($val, ',' . $val . ',', $formula);
        }
        return $formula;
    }

    public function actionBillHeadApplicability($id) {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblBillHeadApplicability();
        $appModel->model->wef_date = date('Y-m-d');
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'bill_head_code';
        $appModel->field_value = $id;
        $appModel->trans_label = Yii::t('app', 'bill head applicabilities');
        $appModel->fields = ['wef_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->wef_date);
                }],
            'dcs_code' => ['view' => ['grid'], 'value' => 'dcsCode.dcs_name'],
        ];
        $appModel->actions = ['delete' => ['option' => 'bill_head_applicabilty_code,bill_head_applicabilty_code,tbl-bill-head/delete-applicability']];
        $appModel->dcs_filters = ['society' => 'Society', 'routes' => 'Routes', 'mcc' => 'MCC'];

        return $appModel->createApp();
    }

    public function actionDeleteApplicability() {
        $model = TblBillHeadApplicability::find()->where(['bill_head_applicabilty_code' => Yii::$app->request->post('id')])->one();
        $localHistory = new TblBillHeadApplicabilityHistory();
        Yii::$app->operation->history($model, $localHistory, DELETE);
        $record = $this->generalModel->deleteTransaction([$model, $localHistory]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionListDcswise() {
        $out = null;
        if (isset($_POST['depdrop_parents'])) {

            $value = $_POST['depdrop_parents'];
            $dcsCode = $value[0];
            $model = new TblBillHead();
            $list = $model->billHeadData($dcsCode);
            $list = array_unique($list);
            foreach ($list as $key => $r) {
                $out[] = array('id' => $key,
                    'name' => $r);
            }
            echo Json::encode(['output' => $out, 'selected' => '']);
            return;
        }
        echo Json::encode(['output' => '', 'selected' => $selected]);
    }

    public function actionListUnionwise() {
        $out = null;
        if (isset($_POST['depdrop_parents'])) {

            $value = $_POST['depdrop_parents'];
            $unionCode = $value[0];
            $model = new TblBillHead();
            $list = $model->billHeadData('', $unionCode);
            $list = array_unique($list);
            foreach ($list as $key => $r) {
                $out[] = array('id' => $key,
                    'name' => $r);
            }
            echo Json::encode(['output' => $out]);
            return;
        }
        echo Json::encode(['output' => '', 'selected' => $selected]);
    }

    public function actionKeyword() {
        $keyword_model = new TblCriteriaKeywordMapping();
        $keyword_data = $keyword_model->getKeywordData();
        $data = ArrayHelper::getColumn($keyword_data, function($array) {
                    return $array["keyword"];
                });
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($data);
    }

}
