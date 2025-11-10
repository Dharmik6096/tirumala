<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblAadeshMaster;
use app\modules\product\models\TblAadeshMasterSearch;
use app\modules\product\models\TblAadeshMasterApplicability;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\product\models\TblAadeshMasterApplicabilitySearch;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\product\models\TblAadeshMasterApplicabilityHistory;

/**
 * TblProductRateController implements the CRUD actions for TblAadeshMaster model.
 */
class TblAadeshMasterController extends \app\controllers\ChildController {

    /**
     * Lists all TblAadeshMaster models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblAadeshMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblAadeshMaster model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $is_member_rate) {
        $searchModel = new TblAadeshMasterSearch();
        $query = TblAadeshMaster::find()->select(['product_code', 'union_code'])->where(['aadesh_master_code' => $id])->one();
        $product_code = $query['product_code'];
        $searchModel->product_code = $product_code;
        $searchModel->aadesh_master_code = $id;
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
     * Creates a new TblAadeshMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblAadeshMaster();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->aadesh_master_code = Yii::$app->general->getPrimaryCode($this->model);
            $this->model->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Aadesh Master', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Finds the TblAadeshMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblAadeshMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblAadeshMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionProductRateApplicability($id) {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblAadeshMasterApplicability();
        $customerType = new TblCustomerType();
        if ($model->is_member_rate == 1) {
            $value = ['DCS' => Yii::t('app', 'DCS')];
        } else {
            $customerType->union_code = $model->union_code;
            $value = $customerType->getCustomerType(['tbl_customer_type.is_product_sale_applicability' => 1]);
        }
        $appModel->model->wef_date = $model->wef_date;
        $appModel->is_union = false;
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'aadesh_master_code';
        $appModel->field_value = $id;
        $appModel->trans_label = 'aadesh master applicability';
        $appModel->mcc_field_name = 'applicable_code';
        $appModel->options = ['tanker_rate'];
        $appModel->assignStaticData = [
            'product_code' => $model->product_code,
            'is_member_rate' => $model->is_member_rate,
            'sale_rate' => $model->sale_rate,
            'commission' => $model->commission,
            'product_mrp' => $model->product_mrp,
            'distributor_landing_rate' => $model->distributor_landing_rate,
            'sachiv_price' => $model->sachiv_price,
            'member_price' => $model->member_price,
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
        $appModel->actions = ['delete' => ['option' => 'aadesh_master_applicability_code,aadesh_master_applicability_code,tbl-aadesh-master/delete-applicability,allowDelete()']];

        return $appModel->createApp();
    }

    public function actionGetMinDate() {

        $model = new TblAadeshMaster();
        if (!empty(Yii::$app->request->post('code'))) {
            $model->product_rate_code = Yii::$app->request->post('code');
            $model->union_code = Yii::$app->request->post('union');
        }
        $model->product_code = Yii::$app->request->post('id');
        return Json::encode(['status' => 'success', 'date' => $model->getMinDate()]);
        return;
    }

    public function actionDeleteApplicability() {
        $model = new TblAadeshMasterApplicability();
        $model = $model->findOne(Yii::$app->request->post('id'));
        $historyModel = new TblAadeshMasterApplicabilityHistory();
        Yii::$app->operation->history($model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionDeleteBulkApplicability() {
        $searchModel = new TblAadeshMasterApplicabilitySearch();
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
                    $where['aadesh_master_applicability_code'] = $code;
                    $existData = TblAadeshMasterApplicability::find()->where($where)->one();
                    $historyModel = new TblAadeshMasterApplicabilityHistory();
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
