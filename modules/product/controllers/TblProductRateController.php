<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblProductSaleRate;
use app\modules\product\models\TblProductSaleRateSearch;
use app\modules\product\models\TblProductSaleRateHistory;
use app\modules\product\models\TblProductSaleRateApplicability;
use app\modules\organisation\models\TblDcs;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\product\models\TblProductSaleRateApplicabilitySearch;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\product\models\TblProductSaleRateApplicabilityHistory;

/**
 * TblProductRateController implements the CRUD actions for TblProductSaleRate model.
 */
class TblProductRateController extends \app\controllers\ChildController {

    /**
     * Lists all TblProductSaleRate models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblProductSaleRateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblProductSaleRate model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $is_member_rate) {
        $searchModel = new TblProductSaleRateSearch();
        $query = TblProductSaleRate::find()->select(['product_code', 'union_code'])->where(['product_sale_rate_code' => $id])->one();
        $product_code = $query['product_code'];
        $searchModel->product_code = $product_code;
        $searchModel->product_sale_rate_code = $id;
        $searchModel->is_member_rate = $is_member_rate;
        $searchModel->union_code = $query['union_code'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblProductSaleRate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblProductSaleRate();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->product_sale_rate_code = Yii::$app->general->getPrimaryCode($this->model);
            $this->model->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Product Sale Rate', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblProductSaleRate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblProductSaleRateHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Product Sale Rate', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblProductSaleRate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_product_rate', Yii::$app->request->post('id'), 'product_rate_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblProductSaleRateHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblProductSaleRate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblProductSaleRate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblProductSaleRate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionProductRateApplicability($id) {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblProductSaleRateApplicability();
        $customerType = new TblCustomerType();
        if ($model->is_member_rate == 1) {
            $value = ['DCS' => Yii::t('app', 'DCS')];
        } else {
            $customerType->union_code = $model->union_code;
            $value = $customerType->getCustomerType(['tbl_customer_type.is_applicability' => 1]);
        }
//        $appModel->model->shift_code = $model->shift_id;
        $appModel->model->wef_date = $model->wef_date;
        $appModel->is_union = false;
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'product_sale_rate_code';
        $appModel->field_value = $id;
        $appModel->trans_label = 'product rate applicability';
        $appModel->mcc_field_name = 'applicable_code';
        $appModel->options = ['tanker_rate'];
        $appModel->assignStaticData = [
            'product_code' => $model->product_code,
            'is_member_rate' => $model->is_member_rate,
            'sale_rate' => $model->sale_rate,
            'commission' => $model->commission,
            'x_col1' => $model->x_col1
        ];
        $appModel->header_title = ' [Product: ' . Yii::$app->general->getforeignkey($model->productCode, 'product_name') . ', Sale Rate: ' . $model->sale_rate . '] ';
        $appModel->fields = [
            'bmc_code' => ['view' => ['grid'], 'label' => Yii::t('app', 'BMC Code'), 'value' => function($model) {
                    return Yii::$app->general->getCustomer($model, $model->applicable_for, FALSE, TRUE, FALSE);
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
        $appModel->dcs_filters = $value;
        $appModel->actions = ['delete' => ['option' => 'product_sale_rate_applicability_code,product_sale_rate_applicability_code,tbl-product-rate/delete-applicability,allowDelete()']];

        return $appModel->createApp();
    }

//
//    public function actionProductRateApplicability($id) {
//        $cmodel = $this->findModel($id);
//        $appModel = Yii::$app->getModule('applicability');
//        $appModel->model = new TblProductSaleRateApplicability();
//        $appModel->model->product_rate_code = $id;
//        $appModel->model->wef_date = Yii::$app->controls->view_date($cmodel->wef_date);
//        $appModel->searchModel = new TblProductSaleRateApplicabilitySearch();
//        $appModel->field_name = 'product_rate_code';
//        $appModel->field_value = $id;
//        $appModel->trans_label = 'product rate applicability';
//        $appModel->top_section = FALSE;
//        $appModel->is_union = FALSE;
//        $appModel->union_code = $cmodel->union_code;
//        $appModel->bmc_field_name = 'applicable_code';
//
//        $appModel->options = ['bmc'];
//        $appModel->payment = true;
//        $appModel->customer_type_wise_entry = true;
//        $appModel->assignMultiData = true;
//        $appModel->setModelFields = true;
//        $appModel->assignMultiDataKey = 'applicable_type';
//        $appModel->assignDataKey = 'applicable_code';
//        $appModel->assignStaticData = [
//            'applicable_for' => 'BMC',
//            'product_code' => $cmodel->product_code
//        ];
//
//        $appModel->title = Yii::$app->controls->view_date($cmodel->wef_date);
//        $appModel->fields = [
//            'wef_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function($model) {
//                    return Yii::$app->controls->view_date($model->wef_date);
//                }],
//            'product' => ['view' => ['grid'], 'value' => function($model) {
//                    return Yii::$app->general->getforeignkey($model->productRate, 'product_name');
//                }],
//            'applicable_type' => ['view' => ['grid'], 'value' => function($model) {
//                    return Yii::$app->general->getforeignkey($model->customerType, 'customer_desc');
//                }],
//            'applicable_for' => ['view' => ['grid'], 'value' => 'applicable_for'],
//            'applicable_code' => ['view' => ['grid'], 'value' => 'applicable_code'],
//            'applicable_name' => ['view' => ['grid'], 'value' => function($model) {
//                    return $model->getName($model->applicable_for);
//                }],
//        ];
////        $appModel->dcs_filters = ['society' => 'Society', 'routes' => 'Routes', 'mcc' => 'MCC'];
//        return $appModel->customerTypeWiseApplicability();
//    }

    public function actionGetMinDate() {

        $model = new TblProductSaleRate();
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
            $appModel->product_rate_code = $this->model->product_rate_code;
            $appModel->union_code = $this->model->union_code;
            $appModel->dcs_code = $dl;
            array_push($save_mode, $appModel);
        }
    }

    public function actionDeleteApplicability() {
        $model = new TblProductSaleRateApplicability();
        $model = $model->findOne(Yii::$app->request->post('id'));
        $historyModel = new TblProductSaleRateApplicabilityHistory();
        Yii::$app->operation->history($model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionDeleteBulkApplicability() {
        $searchModel = new TblProductSaleRateApplicabilitySearch();
        $dataProvider = $searchModel->deletesearch(Yii::$app->request->queryParams);
        $searchModel->scenario = 'deleteApplicability';
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $deleteModel = [];
                $deletedata = Yii::$app->request->post('selection');
                $codes = empty(Yii::$app->request->post('selection')) ? [] : Yii::$app->request->post('selection');
                $where = [];
                foreach ($deletedata as $code) {
                    $where['product_sale_rate_applicability_code'] = $code;
                    $existData = TblProductSaleRateApplicability::find()->where($where)->one();
                    $historyModel = new TblProductSaleRateApplicabilityHistory();
                    Yii::$app->operation->history($existData, $historyModel, DELETE);
                    $saveModel[] = $historyModel;
                    $deleteModel[] = $existData;
                    $message = 'Sale Rate Applicability';
                    $type = 'delete';
                }
                $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, [$message, $type]);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('delete_bulk_applicability', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
