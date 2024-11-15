<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblMonthlyCreditLimit;
use app\modules\payment\models\TblMonthlyCreditLimitSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\organisation\models\TblDcs;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\dcsoperation\models\TblMember;
use app\modules\payment\models\TblMonthlyCreditLimitHistory;

/**
 * TblMonthlyCreditLimitController implements the CRUD actions for TblMonthlyCreditLimit model.
 */
class TblMonthlyCreditLimitController extends \app\controllers\ChildController {

    /**
     * Lists all TblMonthlyCreditLimit models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMonthlyCreditLimitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMonthlyCreditLimit model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblMonthlyCreditLimitSearch();
        $searchModel->monthly_credit_limit_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblMonthlyCreditLimit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblMonthlyCreditLimit();
        $message = 'Monthly Credit Limit';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $processedData = $this->processMonthlyCreditLimit($model);
            $modelSave = $processedData['modelSave'];
            $type = $processedData['type'];

            if (empty($model->getErrors()) && $model->validate()) {
                $transaction = $this->generalModel->saveTransaction($modelSave, [$message, $type]);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            }
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionCreateMember() {
        $model = new TblMonthlyCreditLimit();
        $model->customer_type = 'MEMBER';
        $message = 'Monthly Credit Limit';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $processedData = $this->processMonthlyCreditLimit($model);
            $modelSave = $processedData['modelSave'];
            $type = $processedData['type'];

            if (empty($model->getErrors()) && $model->validate()) {
                $transaction = $this->generalModel->saveTransaction($modelSave, [$message, $type]);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            }
        }

        return $this->render('create', [
                    'model' => $model,
                    'type' => 'memberWiseCredit',
        ]);
    }

    protected function processMonthlyCreditLimit($model) {
        $modelSave = [];
        $type = 'create';
        $monthlyCreditLimitData = $model->find()->where(['customer_type' => $model->customer_type, 'customer_code' => $model->customer_code])->one();

        if (!empty($monthlyCreditLimitData)) {
            $type = 'edit';
            $monthlyCreditLimitData->scenario = 'update';
            $historyModel = new TblMonthlyCreditLimitHistory();
            Yii::$app->operation->history($monthlyCreditLimitData, $historyModel, 'UPDATE');
            $modelSave[] = $historyModel;
            $monthlyCreditLimitData->final_amount = $model->final_amount;
            $modelSave[] = $monthlyCreditLimitData;
        } else {
            $model->dcs_code = strtolower($model->customer_type) === 'dcs' ? $model->customer_code : $model->dcs_code;
            $modelSave[] = $model;
        }

        return ['modelSave' => $modelSave, 'type' => $type];
    }

    /**
     * Finds the TblMonthlyCreditLimit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMonthlyCreditLimit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMonthlyCreditLimit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionValidateCustomer() {
        $response = [];
        $response['status'] = 'error';
        $response['data'] = '';
        $dcs_code = Yii::$app->request->post('dcsCode');
        $type = Yii::$app->request->post('customer_type');
        $customer_code = Yii::$app->request->post('customer_code');
        $this->model = new TblMonthlyCreditLimit();
        $this->model->union_code = Yii::$app->request->post('union_code');
        $this->model->customer_type = $type;
        $this->model->plant_code = Yii::$app->request->post('plant');
        $this->model->mcc_plant_code = Yii::$app->request->post('mcc');
        $this->model->bmc_code = Yii::$app->request->post('bmc_code');
        $this->model->from_date = Yii::$app->request->post('date');
        if (!empty($type) && strtolower($type) == 'member') {
            $model = new TblMember();
            $memberCode = str_pad($customer_code, 4, '0', STR_PAD_LEFT);
            $data = $model->validateMember($dcs_code, $memberCode);
            $this->model->dcs_code = $dcs_code;
            $this->model->customer_code = !empty($data) ? $data->member_code : '';
            if (!empty($data)) {
                $detail = Yii::$app->general->validateDeactivateDcs($this->model, $this->model->from_date, '', TRUE, $this->model->customer_code);
                if ($detail === false) {
                    $data = '';
                }
            }
        } else if (!empty($type) && strtolower($type) != 'dcs') {
            $this->model->customer_code = $customer_code;
            $data = Yii::$app->general->validateCustomerCode($this->model);
            $this->model->customer_code = $data;
        } else {
            $model = new TblDcs();
            $data = $model->validDcs($customer_code, $this->model->bmc_code);
            $this->model->dcs_code = $data;
            $detail = Yii::$app->general->validateDeactivateDcs($this->model, $this->model->from_date);
            if ($detail === false) {
                $data = '';
            }
            $this->model->customer_code = $data;
        }
        if (!empty($data)) {
            $name = Yii::$app->general->getCustomer($this->model, $type);
            $response['status'] = 'success';
            $response['data'] = $name;
            $response['code'] = $this->model->customer_code;
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($response);
    }

}
