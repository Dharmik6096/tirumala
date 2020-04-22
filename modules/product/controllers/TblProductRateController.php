<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblProductRate;
use app\modules\product\models\TblProductRateSearch;
use app\modules\product\models\TblProductRateHistory;
use app\modules\product\models\TblProductRateApplicability;
use app\modules\organisation\models\TblDcs;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\product\models\TblProductRateApplicabilitySearch;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\product\models\TblProductRateApplicabilityHistory;

/**
 * TblProductRateController implements the CRUD actions for TblProductRate model.
 */
class TblProductRateController extends \app\controllers\ChildController {

    /**
     * Lists all TblProductRate models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblProductRateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblProductRate model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $is_member_rate) {
//        return $this->render('view', [
//                    'model' => $this->findModel($id),
//        ]);

        $searchModel = new TblProductRateSearch();
        $query = TblProductRate::find()->select(['product_code', 'union_code'])->where(['product_rate_code' => $id])->one();
        $product_code = $query['product_code'];
        //$query_rate = TblProductRate::find()->select('*')->where(['product_code'=>$product_code])->andWhere(['<>', 'product_rate_code', $id])->orderBy(['wef_date' => SORT_DESC]);
        $searchModel->product_code = $product_code;
        $searchModel->product_rate_code = $id;
        $searchModel->is_member_rate = $is_member_rate;
        $searchModel->union_code = $query['union_code'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$dataProvider = $searchModel->getHistoryRate($id);

        return $this->render('view', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblProductRate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblProductRate();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->product_rate_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            $this->model->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Product Rate', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblProductRate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblProductRateHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Product Rate', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblProductRate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_product_rate', Yii::$app->request->post('id'), 'product_rate_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblProductRateHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblProductRate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblProductRate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblProductRate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionProductRateApplicability($id) {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblProductRateApplicability();
        $customerType = new TblCustomerType();
        if ($model->is_member_rate == 1) {
            $value = ['DCS' => Yii::t('app', 'DCS')];
        } else {
            $customerType->union_code = $model->union_code;
            $value = $customerType->getCustomerType();
        }
//        $appModel->model->shift_code = $model->shift_id;
        $appModel->model->wef_date = $model->wef_date;
        $appModel->is_union = false;
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'product_rate_code';
        $appModel->field_value = $id;
        $appModel->trans_label = 'product rate applicability';
        $appModel->mcc_field_name = 'applicable_code';
        $appModel->options = ['tanker_rate'];
        $appModel->assignStaticData = [
            'product_code' => $model->product_code,
            'is_member_rate' => $model->is_member_rate
        ];
        $appModel->header_title = ' [Product: ' . Yii::$app->general->getforeignkey($model->productCode, 'product_name') . ', Rate: ' . $model->rate . '] ';
        $appModel->fields = ['wef_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->wef_date);
                }],
            'applicable_for' => ['view' => ['grid'], 'value' => function($model) {
                    return Yii::$app->general->getforeignkey($model->customerTypeFor, 'customer_desc');
                }],
            'applicable_code' => ['view' => ['grid'], 'value' => 'applicable_code'],
            'name' => ['view' => ['grid'], 'value' => function($model) {
                    return $model->getName($model->applicable_for);
                }],
        ];
        $appModel->dcs_filters = $value;
        $appModel->actions = ['delete' => ['option' => 'product_rate_applicability_code,product_rate_applicability_code,tbl-product-rate/delete-applicability,allowDelete()']];

        return $appModel->createApp();
    }

//
//    public function actionProductRateApplicability($id) {
//        $cmodel = $this->findModel($id);
//        $appModel = Yii::$app->getModule('applicability');
//        $appModel->model = new TblProductRateApplicability();
//        $appModel->model->product_rate_code = $id;
//        $appModel->model->wef_date = Yii::$app->controls->view_date($cmodel->wef_date);
//        $appModel->searchModel = new TblProductRateApplicabilitySearch();
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

        $model = new TblProductRate();
        if (!empty(Yii::$app->request->post('code'))) {
            $model->product_rate_code = Yii::$app->request->post('code');
            $model->union_code = Yii::$app->request->post('union');
        }
        $model->product_code = Yii::$app->request->post('id');
        echo Json::encode(['status' => 'success', 'date' => $model->getMinDate()]);
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
        $model = new TblProductRateApplicability();
        $model = $model->findOne(Yii::$app->request->post('id'));
        $historyModel = new TblProductRateApplicabilityHistory();
        Yii::$app->operation->history($model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

}
