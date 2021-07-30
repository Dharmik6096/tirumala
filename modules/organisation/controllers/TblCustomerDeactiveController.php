<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblCustomerDeactive;
use app\modules\organisation\models\TblCustomerDeactiveSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\organisation\models\TblCustomerMaster;
use yii\web\Response;
use yii\helpers\Json;
use kartik\widgets\ActiveForm;

/**
 * TblCustomerDeactiveController implements the CRUD actions for TblCustomerDeactive model.
 */
class TblCustomerDeactiveController extends \app\controllers\ChildController {

    /**
     * Lists all TblCustomerDeactive models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblCustomerDeactiveSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblCustomerDeactive model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblCustomerDeactiveSearch();
        $searchModel->customer_code = $id;
        $dataProvider = $searchModel->viewsearch(Yii::$app->request->queryParams);

        $model = new TblCustomerMaster();
        return $this->render('view', [
                    'model' => $model->findOne($id),
                    'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblCustomerDeactive model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblCustomerDeactive();
        $this->viewFile = 'create';
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->model->customer_deactive_code = (string) Yii::$app->general->getCodeAutoIncrement($this->model);
            $this->model->from_date = !empty($this->model->from_date) ? date('Y-m-d H:i:s', strtotime($this->model->from_date)) : NULL;

            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model], ['Customer Deactivated', 'create']);
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
     * Updates an existing TblCustomerDeactive model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->customer_deactive_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblCustomerDeactive model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblCustomerDeactive model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblCustomerDeactive the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblCustomerDeactive::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionActivateCustomer($customer_deactive_code = '') {
        $model = new TblCustomerDeactive();
        $model->scenario = 'activeCustomer';
        $model->customer_deactive_code = $customer_deactive_code;
        $ActiveModel = $this->findModel($model->customer_deactive_code);
        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
//            $ActiveModel->to_date = !empty($model->to_date) ? date('Y-m-d H:i:s', strtotime($model->to_date)) : NULL;
            $ActiveModel->to_date = !empty($model->to_date) ? date('Y-m-d', strtotime('-1 day', strtotime($model->to_date))) : NULL;
            $ActiveModel->scenario = 'activeCustomer';
            if ($ActiveModel->validate()) {
                $transaction = $this->generalModel->saveTransaction([$ActiveModel], ['Customer Activated', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($ActiveModel));
            }
        }
        return $this->renderAjax('_search', [
                    'model' => $model,
                    'ActiveModel' => $ActiveModel,
                    'customer_deactive_code' => $customer_deactive_code
        ]);
    }

}
