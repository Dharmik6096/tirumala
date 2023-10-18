<?php

namespace app\modules\payment\controllers;

use Yii;
use app\controllers\ChildController;
use app\modules\payment\models\TblPartyPaymentSearch;
use app\modules\payment\models\TblPartyPaymentDetailSearch;
use app\modules\payment\models\TblPartyPaymentDetail;
use app\modules\payment\models\TblPartyPayment;
use yii\web\Response;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblPartyPaymentHeadDetailSearch;
use app\modules\payment\models\TblPartyPaymentHistory;
use app\modules\payment\models\TblPartyPaymentHeadDetail;

class TblPartyPaymentController extends ChildController {

    public $freeAccessActions = [];

    public function actionIndex() {
        $searchModel = new TblPartyPaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        $searchModel = new TblPartyPaymentSearch();
        $searchModel->party_payment_code = $id;
        $searchModel->grid_filter = FALSE;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $headDataProvider = new ActiveDataProvider([
            'query' => TblPartyPaymentHeadDetail::find()->where(['party_payment_code' => $id]),
            'pagination' => FALSE,
        ]);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'headDataProvider' => $headDataProvider
        ]);
    }

    public function actionCreate() {
        $model = new TblPartyPayment();
        $model->scenario = 'process';
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->from_date = date('Y-m-d', strtotime($model->from_date));
                $model->to_date = date('Y-m-d', strtotime($model->to_date));
                $is_valid = \Yii::$app->general->getSpData('validate_party_payment', [$model->payment_type, $model->party_master_code, $model->from_date, $model->to_date]);
                //   $is_valid[] = ['msg' => 'Payment is already generated for Selected Payment Cycle. Do You want to Regenerate?', 'allow_process' => 1];
                $msg = $is_valid[0]['msg'];
                $allow_process = $is_valid[0]['allow_process'];
                $queryParam = [];
                $queryParam[] = 'process-payment';
                $queryParamRegenerate = [];
                $queryParam['TblPartyPayment'] = ['from_date' => $model->from_date, 'to_date' => $model->to_date, 'payment_type' => $model->payment_type, 'party_master_code' => $model->party_master_code];
                $queryParamRegenerate = $queryParam;
                $queryParamRegenerate['reGenerate'] = 0;
                if ($allow_process) {
                    if (!empty($msg)) {
                        $result = 'displayConfirmPopup';
                        $queryParamRegenerate['reGenerate'] = 1;
                        $msg = Yii::t('app', $msg);
                    } else {
                        $result = 'success';
//                        $queryParam['reGenerate'] = 1;
                    }
                } else {
                    $result = 'displayPopup';
                    $msg = Yii::t('app', $msg);
                }
                $url = Url::to($queryParam);
                $url_regenerate = Url::to($queryParamRegenerate);
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                return ['status' => $result, 'url' => $url, 'url_regenerate' => $url_regenerate, 'msg' => $msg];
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionProcessPayment($reGenerate = 0) {
        if (Yii::$app->request->get()) {
            $model = new TblPartyPayment();
            $model->load(Yii::$app->request->get());
            if ($reGenerate == 1) {
                $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
                $data = [];
                $data['payment_type'] = $model->payment_type;
                $data['party_master_code'] = $model->party_master_code;
                $data['from_date'] = $model->from_date;
                $data['to_date'] = $model->to_date;
                Yii::$app->ClientPaymentConfig->processPayment('party_payment', $data);
            }
            return $this->redirect(['payment-adjust', 'TblPartyPayment' => ['from_date' => $model->from_date, 'to_date' => $model->to_date, 'payment_type' => $model->payment_type, 'party_master_code' => $model->party_master_code, 'union_code' => $model->union_code]]);
        }
    }

    public function actionPaymentAdjust() {
        $this->layout = "@app/themes/pcdf/layouts/paymentLayout.php";
        $model = new TblPartyPayment();
        $model->load(Yii::$app->request->get());
        $model = $model->find()->where(['from_date' => $model->from_date, 'to_date' => $model->to_date, 'party_master_code' => $model->party_master_code])->one();
        if (!empty($model)) {
            if (Yii::$app->request->post()) {
                if (!empty(Yii::$app->request->post()['TblPartyPayment']['adjust_amount'])) {
                    $historyModel = new TblPartyPaymentHistory();
                    Yii::$app->operation->history($model, $historyModel, UPDATE);
                    $final_amount = $model->net_amount;
                    $model->load(Yii::$app->request->post());
                    $model->final_amount = $final_amount + $model->adjust_amount;
                    $transaction = $this->generalModel->saveTransaction([$model, $historyModel], ['Payment of ' . $model->partyMaster->party_name . '(' . $model->party_master_code . ')' . ' adjusted succesfully', 'info']);
                    if ($transaction == 'customRedirect') {
                        return $this->redirect(['index']);
                    }
                } else {
                    Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                        'message' => 'Payment of ' . $model->partyMaster->party_name . '(' . $model->party_master_code . ')' . ' adjusted succesfully']);
                    return $this->redirect(['index']);
                }
            }
            $searchModel = new TblPartyPaymentDetailSearch();
            $searchModel->party_payment_code = $model->party_payment_code;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $searchModelHead = new TblPartyPaymentHeadDetailSearch();
            $searchModelHead->party_payment_code = $model->party_payment_code;
            $dataProviderHead = $searchModelHead->search([]);
            return $this->render('payment_adjust', [
                        'model' => $model,
                        'vehicleDetail' => $dataProvider,
                        'headDetail' => $dataProviderHead,
                        'searchModel' => $searchModel,
                        'searchModelHead' => $searchModelHead
            ]);
        } else {
            Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                'message' => 'Receipt Detail not found.']);
            return $this->redirect(['create']);
        }
    }

    public function actionPaymentDisburse() {
        $model = new TblPartyPayment();
        $model->scenario = 'disburse';
        $model->load(Yii::$app->request->get());
        $searchModel = new TblPartyPaymentSearch();
        $searchModel->scenario = 'disburse';        
        $searchModel->attributes = $model->attributes;
        $searchModel->status = 'locked';
        $dataProvider = $searchModel->disbursesearch();
        return $this->render('payment_disburse', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'title' => 'Party Payment Disburse',
        ]);
    }

    public function actionProcessPaymentDisburse() {
        if (Yii::$app->request->post()) {
            $model = new TblPartyPayment();
            $model->load(Yii::$app->request->post());
            if (!empty($model->payment_cycle_code)) {
                $date_time = explode('#', $model->payment_cycle_code);
                $model->from_datetime = $date_time[0];
                $model->to_datetime = $date_time[1];
                if (Yii::$app->request->post('flag') == 'disburse') {
                    $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
                    $data = [];
                    $data['union_code'] = $model->union_code;
                    $data['bmc_code'] = ',' . implode(',', $model->bmc_code) . ',';
                    $data['payment_type'] = $model->payment_type;
                    $data['customer_type'] = $model->customer_type;
                    $data['from_datetime'] = $model->from_datetime;
                    $data['to_datetime'] = $model->to_datetime;
                    $data['user_code'] = $user;
                    Yii::$app->ClientPaymentConfig->processPayment('bonus_payment_disburse', $data);
                    $msg_content = Yii::t('app', 'Bonus Payment Successfully Disbursed.');
                    Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                        'message' => $msg_content]);
                    $this->redirect(['index']);
                } else {
                    if ($this->exportBonusCSV($model)) {
                        return $this->redirect(\yii\helpers\Url::previous());
                    }
                }
            }
        }
    }
    
    public function actionBillHead() {
        $code = Yii::$app->request->post()['party_payment_code'];
        $model = TblPartyPayment::findOne($code);
        $model->grid_filter = FALSE;
        $dataProvider = new ActiveDataProvider([
            'query' => TblPartyPaymentHeadDetail::find()->where(['party_payment_code' => $code]),
            'pagination' => FALSE,
        ]);
        return $this->renderAjax('bill_head_view', [
                    'dataProvider' => $dataProvider,
                    'model' => $model
        ]);
    }
    
    public function actionPartyBillHeadDetail() {
        $code = Yii::$app->request->get()['code'];
        $model = $this->findModel($code);
        $model->grid_filter = FALSE;
        $dataProvider = new ActiveDataProvider([
            'query' => TblPartyPaymentHeadDetail::find()->where(['party_payment_code' => $code]),
            'pagination' => FALSE,
        ]);
        return $this->renderAjax('bill_head_view', [
                    'dataProvider' => $dataProvider,
                    'model' => $model
        ]);
    }
    
    public function actionPaymentDetail($id) {
        $searchModel = new TblPartyPaymentDetailSearch();
        $searchModel->party_payment_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $summary_data = $searchModel->partyPaymentCode;
        $title = $summary_data->payment_type . ' Payment';
        $title .= Yii::$app->general->getforeignkey($summary_data->partyMaster, 'party_name') . ' > ' .
                (Yii::$app->controls->view_date($summary_data->from_date) . ' to ' . Yii::$app->controls->view_date($summary_data->to_date) ) . ')';
        return $this->render('payment_detail', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'title' => $title
        ]);
    }
    
    protected function findModel($id) {
        if (($model = TblPartyPayment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
