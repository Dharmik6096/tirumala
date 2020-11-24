<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblProductPurchaseRate;
use app\modules\product\models\TblProductPurchaseRateSearch;
use app\modules\product\models\TblProductPurchaseRateHistory;
use app\modules\product\models\TblProductPurchaseRateApplicability;
use app\modules\organisation\models\TblDcs;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\product\models\TblProductPurchaseRateApplicabilitySearch;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\product\models\TblProductPurchaseRateApplicabilityHistory;

/**
 * TblProductPurchaseRateController implements the CRUD actions for TblProductPurchaseRate model.
 */
class TblProductPurchaseRateController extends \app\controllers\ChildController {

    /**
     * Lists all TblProductPurchaseRate models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblProductPurchaseRateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblProductPurchaseRate model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblProductPurchaseRateSearch();
        $query = TblProductPurchaseRate::find()->select(['product_code', 'union_code'])->where(['product_purchase_rate_code' => $id])->one();
        $product_code = $query['product_code'];
        $searchModel->product_code = $product_code;
        $searchModel->product_purchase_rate_code = $id;
        $searchModel->union_code = $query['union_code'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblProductPurchaseRate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblProductPurchaseRate();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->product_purchase_rate_code = Yii::$app->general->getPrimaryCode($this->model);
            $this->model->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Product Purchase Rate', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblProductPurchaseRate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblProductPurchaseRateHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Product Purchase Rate', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblProductPurchaseRate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_product_purchase_rate', Yii::$app->request->post('id'), 'product_purchase_rate_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblProductPurchaseRateHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblProductPurchaseRate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblProductPurchaseRate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblProductPurchaseRate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionProductPurchaseRateApplicability($id) {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblProductPurchaseRateApplicability();
        $customerType = new TblCustomerType();
        $customerType->union_code = $model->union_code;
        $value = $customerType->getCustomerType(['tbl_customer_type.is_applicability' => 1]);
        $appModel->model->wef_date = $model->wef_date;
        $appModel->is_union = false;
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'product_purchase_rate_code';
        $appModel->field_value = $id;
        $appModel->trans_label = 'product purchase rate applicability';
        $appModel->mcc_field_name = 'applicable_code';
        $appModel->options = ['tanker_rate'];
        $appModel->assignStaticData = [
            'product_code' => $model->product_code,
            'purchase_rate' => $model->purchase_rate
        ];
        $appModel->header_title = ' [Product: ' . Yii::$app->general->getforeignkey($model->productCode, 'product_name') . ', Purchase Rate: ' . $model->purchase_rate . '] ';
        $appModel->fields = [
            'bmc_code' => ['view' => ['grid'], 'label' => Yii::t('app', 'BMC Code'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for, FALSE, TRUE);
                }],
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
        $appModel->actions = ['delete' => ['option' => 'product_purchase_rate_applicability_code,product_purchase_rate_applicability_code,tbl-product-purchase-rate/delete-applicability,allowDelete()']];

        return $appModel->createApp();
    }

    public function actionGetMinDate() {

        $model = new TblProductPurchaseRate();
        if (!empty(Yii::$app->request->post('code'))) {
            $model->product_rate_code = Yii::$app->request->post('code');
            $model->union_code = Yii::$app->request->post('union');
        }
        $model->product_code = Yii::$app->request->post('id');
        return Json::encode(['status' => 'success', 'date' => $model->getMinDate()]);
        return;
    }

    public function setAppModel($appModel) {
        $dcsList = TblDcs::find()->select('dcs_code')->where(['is_active' => 1, 'union_code' => $this->model->union_code])->all();
//        echo $dcsList->createCommand()->getRawSql();die;
        $dcsList = \yii\helpers\ArrayHelper::map($dcsList, 'dcs_code', 'dcs_code');
        $save_mode = [];
        foreach ($dcsList as $dl) {
            $appModel->wef_date = $this->model->wef_date;
            $appModel->product_purchase_rate_code = $this->model->product_purchase_rate_code;
            $appModel->union_code = $this->model->union_code;
            $appModel->dcs_code = $dl;
            array_push($save_mode, $appModel);
        }
    }

    public function actionDeleteApplicability() {
        $model = new TblProductPurchaseRateApplicability();
        $model = $model->findOne(Yii::$app->request->post('id'));
        $historyModel = new TblProductPurchaseRateApplicabilityHistory();
        Yii::$app->operation->history($model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

}
