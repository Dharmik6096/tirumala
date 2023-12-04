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
        $detailDataProvider = new ActiveDataProvider([
            'query' => TblPartyPaymentDetail::find()->where(['party_payment_code' => $id]),
            'pagination' => FALSE,
        ]);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'detailDataProvider' => $detailDataProvider,
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
                        $queryParam['reGenerate'] = 1;
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
        $req = Yii::$app->request;
        if ($req->isAjax) {
            $requestData = Yii::$app->request->post();
        } else {
            $requestData = Yii::$app->request->get();
        }
        $model->load($requestData);
        $model = $model->find()->where(['from_date' => $model->from_date, 'to_date' => $model->to_date, 'party_master_code' => $model->party_master_code, 'payment_type' => $model->payment_type]);
        $status = ['generated', 'processed'];
        if ($req->isAjax) {
            $model = $model->andFilterWhere(['status' => $status]);
        }
        $model = $model->one();
        if (!empty($model)) {
            if (Yii::$app->request->post()) {
                if (!empty(Yii::$app->request->post()['TblPartyPayment']['adjust_amount'])) {
                    $historyModel = new TblPartyPaymentHistory();
                    Yii::$app->operation->history($model, $historyModel, UPDATE);
                    $model->load($requestData);
                    $processFlag = !empty($requestData['process_lock_flag']) ? $requestData['process_lock_flag'] : 'processed';
                    $model->status = $processFlag;
                    $final_amount = $model->net_amount;
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
                        'dataProvider' => $dataProvider,
                        'headDetail' => $dataProviderHead,
                        'searchModel' => $searchModel,
                        'searchModelHead' => $searchModelHead,
                        'type' => 'adjust'
            ]);
        } else {
            Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                'message' => 'Receipt Detail not found.']);
            return $this->redirect(['create']);
        }
    }

//    public function actionPaymentDisburse() {
//        $model = new TblPartyPayment();
//        $searchModel = new TblPartyPaymentSearch();
//        $searchModel->scenario = 'disburse';
//            $searchModel->load(Yii::$app->request->queryParams);
//            $model->attributes = $searchModel->attributes;
//        $searchModel->status = 'locked';
//        $dataProvider = $searchModel->disbursesearch();
//        return $this->render('payment_disburse', [
//                    'model' => $model,
//                    'searchModel' => $searchModel,
//                    'dataProvider' => $dataProvider,
//                    'title' => 'Party Payment Disburse',
//        ]);
//    }

    public function actionPaymentDisburse() {
        $model = new TblPartyPayment();
        $searchModel = new TblPartyPaymentSearch();
        $searchModel->scenario = 'disburse';
        $searchModel->status = 'locked';

        $searchModel->load(Yii::$app->request->queryParams);
        $dataProvider = $searchModel->disbursesearch();
        $model->attributes = $searchModel->attributes;
        if (!empty($searchModel->payment_cycle_code)) {
            $date_time = explode('#', $searchModel->payment_cycle_code);
            $model->from_date = $date_time[0];
            $model->to_date = $date_time[1];
            $model = $model->find()->where(['from_date' => $model->from_date, 'to_date' => $model->to_date, 'party_master_code' => $model->party_master_code, 'payment_type' => $model->payment_type])->one();
        }
        $id = !empty($model->party_payment_code) ? $model->party_payment_code : '';
        $dataProviderHead = new ActiveDataProvider([
            'query' => TblPartyPaymentHeadDetail::find()->where(['party_payment_code' => $id]),
            'pagination' => FALSE,
        ]);
        $dataProviderDetail = new ActiveDataProvider([
            'query' => TblPartyPaymentDetail::find()->where(['party_payment_code' => $id]),
            'pagination' => FALSE,
        ]);
        $searchModelDetail = new TblPartyPaymentDetailSearch();
        $searchModelHead = new TblPartyPaymentHeadDetailSearch();

        return $this->render('payment_disburse', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dataProviderDetail' => $dataProviderDetail,
                    'headDetail' => $dataProviderHead,
                    'searchModelDetail' => $searchModelDetail,
                    'title' => 'Party Payment Disburse',
                    'searchModelHead' => $searchModelHead,
                    'type' => 'disburse'
        ]);
    }

    public function actionProcessPaymentDisburse() {
        if (Yii::$app->request->post()) {
            $model = new TblPartyPayment();
            $model->load(Yii::$app->request->post());
            if (Yii::$app->request->post('flag') == 'disburse') {
                $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
                $data = [];
                $data['payment_type'] = $model->payment_type;
                $data['party_master_code'] = $model->party_master_code;
                $data['from_date'] = !empty($model->from_date) ? date('Y-m-d', strtotime($model->from_date)) : date('Y-m-d');
                $data['to_date'] = !empty($model->to_date) ? date('Y-m-d', strtotime($model->to_date)) : date('Y-m-d');
                Yii::$app->ClientPaymentConfig->processPayment('party_payment_disburse', $data);
                $msg_content = Yii::t('app', 'Party Payment Successfully Disbursed.');
                Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                    'message' => $msg_content]);
                $this->redirect(['index']);
            } else {
                if ($this->exportPartyPaymentCSV($model)) {
                    return $this->redirect(['payment-disburse']);
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

    public function actionPaymentCycleList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[1])) {
                $model = new TblPartyPayment();
                $data = $model->PaymentCycleList($parents[0], $parents[1], $parents[2]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    protected function exportPartyPaymentCSV($model) {
        //Export bonus File from Disburse Screen
        $newModel = new TblPartyPayment();
        $query = $newModel->find()
                        // ->joinWith(['tbl_party_payment_detail','on','party_payment_code'])
                        ->where(['from_date' => $model->from_date,
                            'to_date' => $model->to_date,
                            'status' => ['locked'],
                            'payment_type' => $model->payment_type,
                        ])->all();

        $extention = 'xls';
        $header = [
            'mime' => 'application/ms-excel',
            'extension' => $extention,
            'writer' => 'Excel2007',
        ];

        $fileName = "party_payment_disburse." . $header['extension'] .
                header('Content-Type: ' . $header['mime']);
        header('Content-Disposition: attachment;filename=' . $fileName);
        header('Cache-Control: max-age=0');
        echo "<table border='1'>";
        echo "<tr>";
        echo "<td>" . Yii::t('app', 'Party Payment Code') . "</td>";
        echo "<td>" . Yii::t('app', 'From Date') . "</td>";
        echo "<td>" . Yii::t('app', 'To Date') . "</td>";
        echo "<td>" . Yii::t('app', 'Qty') . "</td>";
        echo "<td>" . Yii::t('app', 'Avg Fat') . "</td>";
        echo "<td>" . Yii::t('app', 'Avg SNF') . "</td>";
        echo "<td>" . Yii::t('app', 'RTPL') . "</td>";
        echo "<td>" . Yii::t('app', 'Net Amount') . "</td>";
        echo "<td>" . Yii::t('app', 'Total Addition') . "</td>";
        echo "<td>" . Yii::t('app', 'Total Deduction') . "</td>";
        echo "<td>" . Yii::t('app', 'Adjust Amount') . "</td>";
        echo "<td>" . Yii::t('app', 'Final Amount') . "</td>";
        echo "<td>" . Yii::t('app', 'Bank Ac No.') . "</td>";
        echo "<td>" . Yii::t('app', 'Bank Name') . "</td>";
        echo "<td>" . Yii::t('app', 'Branch Name') . "</td>";
        echo "<td>" . Yii::t('app', 'IFSC') . "</td>";
        echo "</tr>";

        foreach ($query as $row) {
            echo "<tr>";
            $this->setVal($row->party_payment_code);
            $this->setVal($row->from_date);
            $this->setVal($row->to_date);
            $this->setVal($row->total_qty);
            $this->setVal($row->avg_fat);
            $this->setVal($row->avg_snf);
            $this->setVal($row->avg_rate);
            $this->setVal($row->net_amount);
            $this->setVal($row->total_addition);
            $this->setVal($row->total_deduction);
            $this->setVal($row->adjust_amount);
            $this->setVal($row->final_amount);
            $this->setVal($row->bank_account_no);
            $this->setVal($row->bank_name);
            $this->setVal($row->branch_name);
            $this->setVal($row->ifsc);

            echo "</tr>";
        }
        echo "</table>";
        exit();
    }

    public function setVal($value) {
        if (!empty($value) && is_numeric($value) && (float) $value <= 100000000 && substr($value, 0, 1) != 0) {
            echo "<td>" . $value . "</td>";
        } else {
            echo "<td style=\"mso-number-format:'\@'\">" . $value . "</td>";
        }
    }
}
