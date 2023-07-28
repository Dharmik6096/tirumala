<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblMccPayment;
use app\modules\payment\models\TblMccPaymentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\vsp\models\TblMccBillHead;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\payment\models\TblMccPaymentHistory;
use yii\helpers\Url;
use app\modules\payment\models\TblMccPaymentTransactionSearch;

/**
 * TblMccPaymentController implements the CRUD actions for TblMccPayment model.
 */
class TblMccPaymentController extends \app\controllers\ChildController {

    /**
     * Lists all TblMccPayment models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMccPaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMccPayment model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblMccPaymentTransactionSearch();
        $searchModel->mcc_payment_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblMccPayment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblMccPayment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->mcc_payment_code]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblMccPayment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->mcc_payment_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblMccPayment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblMccPayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMccPayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMccPayment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPaymentAdjust() {
        $this->layout = "@app/themes/pcdf/layouts/paymentLayout.php";
        $model = new TblMccPayment();
        $model->load(Yii::$app->request->get());

        if (Yii::$app->request->post()) {
            $adjust_id = Yii::$app->request->post('TblMccPayment')['mcc_payment_code'];
            $adjust_amt = Yii::$app->request->post('TblMccPayment')['adjust_amount'];
            $adjust_remark = Yii::$app->request->post('TblMccPayment')['adjust_remark'];
            $hold_amt = Yii::$app->request->post('TblMccPayment')['hold_amount'];
            $save_model = [];
            $cnt = 0;
            foreach ($adjust_id as $key => $value) {
                if (($adjust_amt[$key] != 0 && $adjust_amt[$key] != '') || ($hold_amt[$key] != 0 && $hold_amt[$key] != '')) {
                    $data = TblMccPayment::findOne($adjust_id[$key]);
                    $updateData = false;
                    $oldData = $data->oldAttributes;
                    $historyModel = new TblMccPaymentHistory();
                    Yii::$app->operation->history($data, $historyModel, UPDATE);
                    $data->adjust_amount = $adjust_amt[$key];
                    $data->adjust_remark = $adjust_remark[$key];
                    $data->hold_amount = $hold_amt[$key];
                    $data->final_pay = (float) $data->net_payable + (float) $adjust_amt[$key] - (float) $hold_amt[$key];

                    if ($model->billing_type == 'mcc_remuneration') {
                        $data->scenario = 'mccremuneration';
                    }
                    if (!empty($oldData) && ($oldData['hold_amount'] != $data->hold_amount || $oldData['adjust_amount'] != $data->adjust_amount || $oldData['adjust_remark'] != $data->adjust_remark)) {
                        $updateData = true;
                    }
                    if ($updateData) {
                        $save_model[] = $historyModel;
                        $save_model[] = $data;
                        $cnt++;
                    }
                }
            }
            $transaction = $this->generalModel->saveTransaction($save_model, ['Payment of ' . $cnt . ' ' . Yii::$app->general->getforeignkey($model->bmcCode, 'bmc_name') . '  adjusted succesfully', 'info']);

            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            $msg = '';
            $url = Url::to(['index']);
            if ($transaction == 'customRedirect') {
                $result = 'success';
            } else {
                $result = 'error';
                $msgData = Yii::$app->session->getFlash('success');
                $msg = !empty($msg['message']) ? $msg['message'] : '';
            }
            return ['status' => $result, 'url' => $url, 'msg' => $msg];
        }
        if ($model->billing_type == 'mcc_remuneration') {
            $query = $model->getRemunerationRecords();
            $title = 'MCC Payment Process : Step 2';
        } else {
            $query = $model->getRecords();
            $title = 'MCC Payment Process : Step 2';
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

        return $this->render('payment-adjust', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'title' => $title,
        ]);
    }

    public function actionBillHead() {
        $searchModel = new TblMccPaymentTransactionSearch();
        $searchModel->mcc_payment_code = Yii::$app->request->get()['code'];
        $model = $this->findModel($searchModel->mcc_payment_code);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->renderAjax('bill-head-view', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model
        ]);
    }

    public function actionPaymentDisburse() {
        $this->layout = "@app/themes/pcdf/layouts/paymentLayout.php";
        $model = new TblMccPayment();
        $model->load(Yii::$app->request->get());
        $model->bmc_code = $model->bmc_code;
        $model->payment_cycle_code = $model->p_payment_cycle_code;
        $query = $model->find()->where(['payment_cycle_code' => $model->payment_cycle_code,
            'bmc_code' => $model->bmc_code,
            'status' => ['processed', 'rejected']]);
//  ->andWhere(['>', 'final_pay', 0]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);
        return $this->render('disburse_payment', [
                    'searchModel' => $model,
                    'dataProvider' => $dataProvider,
                    'title' => Yii::t('app', 'MCC') . ' Payment Disburse : Step 1'
        ]);
    }

    public function actionBankPayment() {
        $model = new TblMccPayment();
        $model->load(Yii::$app->request->post());
        // $model->dcs_code = Yii::$app->request->post('selection');
        $this->LockBilling($model);

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        $msg = '';
        $url = Url::to(['index']);
        $result = 'success';
        return ['status' => $result, 'url' => $url, 'msg' => $msg];
//        return $this->redirect(['index']);
    }

    protected function LockBilling($model) {
        $param = [];
        $payment_cycle_code = explode('#', $model->payment_cycle_code);
        $param['mcc_plant_code'] = is_array($model->mcc_plant_code) ? ',' . implode(',', $model->mcc_plant_code) . ',' : $model->mcc_plant_code;
        $param['bmc_code'] = is_array($model->bmc_code) ? ',' . implode(',', $model->bmc_code) . ',' : $model->bmc_code;
        $param['applicable_for'] = 'BMC';
        $param['from_datetime'] = $payment_cycle_code[0];
        $param['to_datetime'] = $payment_cycle_code[1];
        $param['user_code'] = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
        Yii::$app->ClientPaymentConfig->processPayment('mcc_payment_disburse', $param);

//        $save_model = [];
//        $newModel = new TblVspPayment();
//        $query = $newModel->find()->where([
//                    'payment_cycle_code' => $model->payment_cycle_code,
//                    'tbl_vsp_payment.bmc_code' => $model->bmc_code,
//                    'tbl_vsp_payment.customer_type' => $model->customer_type,
//                    'status' => ['processed', 'rejected'],
//                ])
//                ->all();
//        $PaymentApp = TblPaymentCycleApplicability::find()
//                ->where(['payment_cycle_code' => $model->payment_cycle_code,
//                    'applicable_code' => $model->bmc_code,
//                    'applicable_for' => 'BMC',
//                    'applicable_type' => $model->customer_type,
//                ])
//                ->one();
//        if (!empty($PaymentApp)) {
//            $model->from_datetime = $PaymentApp->from_date;
//            $PaymentApp->billing_lock_bmc = 1;
//            $save_model[] = $PaymentApp;
//        }
//        foreach ($query as $data) {
//            $outstanding = TblVspOutstanding::find()->where([
//                        'customer_type' => $data->customer_type,
//                        'customer_code' => $data->customer_code
//                    ])->one();
//            if (empty($outstanding)) {
//                $outstanding = new TblVspOutstanding();
//                $outstanding->attributes = $data->attributes;
//            } else {
//                $oshistoryModel = new TblVspOutstandingHistory();
//                Yii::$app->operation->history($outstanding, $oshistoryModel, UPDATE);
//                $save_model[] = $oshistoryModel;
//            }
//            // $outstanding->scenario = 'payment';
//            $outstanding->payment_cycle_code = $data->payment_cycle_code;
//            $outstanding->hold_amount = $data->hold_amount;
//            $outstanding->due_amount = $data->adjust_amount;
//            $save_model[] = $outstanding;
//            $data->status = 'sent';
//            $save_model[] = $data;
//            $transaction = $this->generalModel->saveTransaction($save_model, ['Payment Locked Successfully', 'info']);
//            if ($transaction == 'customRedirect') {
//                $param = [];
//                $param['from_datetime'] = $model->from_datetime;
//                $param['customer_type'] = $model->customer_type;
//                $param['bmc_code'] = $model->bmc_code;
//                Yii::$app->ClientPaymentConfig->processPayment('payment_installment_status', $param);
//            }
//        }
    }

}
