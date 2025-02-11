<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblProductRequisition;
use app\modules\product\models\TblProductRequisitionHistory;
use app\modules\product\models\TblProductRequisitionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\product\models\TblProductRequisitionTransaction;
use app\modules\product\models\TblProductRequisitionTransactionHistory;
use app\modules\product\models\TblProductRequisitionTransactionSearch;
use yii\widgets\ActiveForm;
use yii\web\Response;

/**
 * TblProductRequisitionController implements the CRUD actions for TblProductRequisition model.
 */
class TblProductRequisitionController extends \app\controllers\ChildController {

    /**
     * Lists all TblProductRequisition models.
     * @return mixed
     */
//    public function actionIndex() {
//        $searchModel = new TblProductRequisitionSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//
//        return $this->render('index', [
//                    'searchModel' => $searchModel,
//                    'dataProvider' => $dataProvider,
//        ]);
//    }
    public function actionIndex() {
        $searchModel = new TblProductRequisitionTransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblProductRequisition model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblProductRequisitionTransactionSearch();
        $searchModel->product_requisition_code = Yii::$app->getRequest()->getQueryParam('id');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id), 'searchModel' => $searchModel, 'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new TblProductRequisition model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblProductRequisition();
        $this->viewFile = 'create';
        $this->model->scenario = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->vendor_code = $this->model->bmc_code;
            if ($this->model->vendor_type == 'DCS') {
                $this->model->vendor_code = $this->model->dcs_code;
            }
            $this->model->product_requisition_code = Yii::$app->general->getPrimaryCode($this->model);
            if (!empty($this->model->date)) {
                $this->model->date = Yii::$app->formatter->asDate($this->model->date, DATE_FORMAT);
            }
            $this->model->status = 0;
//            $this->model->union_code = Yii::$app->session->get('organizations_code');
//            $this->model->union_code = '001';
            if ($this->model->validate()) {
                $result = 'success';
                //$this->model->save();
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                $attribute_data = $this->model->attributes;
                $attribute_data['req_date'] = $attribute_data['req_date'].' '.Yii::$app->request->post()['TblProductRequisition']['req_time'];
                $attribute_data['req_time'] = Yii::$app->request->post()['TblProductRequisition']['req_time'];
                $url = \yii\helpers\Url::to(['tbl-product-requisition-transaction/create', 'id' => -1, 'date' => $this->model->req_date]);
                $a = ['status' => $result, 'url' => $url, 'object' => $attribute_data];
                return $a;
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($this->model);
            }
            //return $this->{$this->generalModel->saveTransaction([$this->model], ['product requisition', 'create'])}();
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblProductRequisition model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblProductRequisitionHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            return $this->{$this->generalModel->saveTransaction([$this->model, $historyModel], ['product requisition', 'edit'])}();
        }
        return $this->customRender();
    }

    /**
     * Finds the TblProductRequisition model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblProductRequisition the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblProductRequisition::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
