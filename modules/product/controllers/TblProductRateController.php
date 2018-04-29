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
    public function actionView($id) {
//        return $this->render('view', [
//                    'model' => $this->findModel($id),
//        ]);

        $searchModel = new TblProductRateSearch();
        $query = TblProductRate::find()->select(['product_code', 'union_code'])->where(['product_rate_code' => $id])->one();
        $product_code = $query['product_code'];
        //$query_rate = TblProductRate::find()->select('*')->where(['product_code'=>$product_code])->andWhere(['<>', 'product_rate_code', $id])->orderBy(['wef_date' => SORT_DESC]);
        $searchModel->product_code = $product_code;
        $searchModel->product_rate_code = $id;
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
            $transaction = $this->generalModel->saveTransaction([$this->model],['Product Rate', 'create']);
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
        $appModel->is_union = false;
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'product_rate_code';
        $appModel->field_value = $id;
        $appModel->trans_label = 'product rate applicability';
        $appModel->fields = ['wef_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function($model) {
            return Yii::$app->controls->view_date($model->wef_date);
        }],
            'dcs_code' => ['view' => ['grid'], 'value' => 'dcsCode.dcs_name'],
        ];
        return $appModel->createApp();
    }

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

}
