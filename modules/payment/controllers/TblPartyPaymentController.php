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

class TblPartyPaymentController extends ChildController {
    public $freeAccessActions = [];
    
    public function actionIndex() {
        $searchModel = new TblPartyPaymentDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionView($id) {
        $searchModel = new TblBonusPaymentSearch();
        $searchModel->bonus_payment_summary_code = $id;
        $searchModel->grid_filter = FALSE;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $headDataProvider = new ActiveDataProvider([
            'query' => TblBonusPaymentSummaryHead::find()->where(['bonus_payment_summary_code' => $id]),
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
//            echo "<pre>";
//            print_r($model);
//            die;
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
//                $data['union_code'] = $model->union_code;
                $data['payment_type'] = $model->payment_type;
                $data['party_master_code'] = $model->party_master_code;
                $data['from_date'] = $model->from_date;
                $data['to_date'] = $model->to_date;
//                $data['user_code'] = $user;
                Yii::$app->ClientPaymentConfig->processPayment('party_payment', $data);
            }
            return $this->redirect(['payment-adjust', 'TblBonusPaymentSummary' => ['from_date' => $model->from_date, 'to_date' => $model->to_date, 'payment_type' => $model->payment_type, 'party_master_code' => $model->party_master_code, 'union_code' => $model->union_code]]);
        }
    }
    
    public function actionPaymentAdjust1() {
        $this->layout = "@app/themes/pcdf/layouts/paymentLayout.php";
        $model = new TblPartyPayment();
        $model->load(Yii::$app->request->get());
        if (Yii::$app->request->post()) {
            $postData = Yii::$app->request->post();
            $model->load($postData);
            $processFlag = !empty($postData['process_lock_flag']) ? $postData['process_lock_flag'] : 'processed';
            $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
            $updated_at = date('Y-m-d H:i:s');
            $update_data = ['status' => $processFlag, 'updated_at' => $updated_at, 'updated_by' => $user];
            $status = ['generated', 'processed'];
            $updated_on = [
                'from_date' => $model->from_date,
                'to_date' => $model->to_date,
                'payment_type' => $model->payment_type,
                'party_master_code' => $model->party_master_code,
                'union_code' => $model->union_code,
                'status' => $status
            ];
            TblBonusPaymentSummary::updateAll($update_data, $updated_on);
            $bmc_in = "'" . implode("','", $model->bmc_code) . "'";
            $status_in = "'" . implode("','", $status) . "'";
            Yii::$app->db->createCommand("update bp set bp.status = :status, bp.updated_at = :updated_at, bp.updated_by=:updated_by from tbl_party_payment bp
            inner join tbl_party_payment ps on ps.party_payment_code = bp.party_payment_code where 
            ps.from_date = :from_date and 
            ps.to_date = :to_date and
//            ps.bmc_code in ($bmc_in) and
            ps.payment_type = :payment_type and
            ps.party_master_code = :party_master_code and
            ps.union_code = :union_code and
            bp.status in ($status_in)")
                    ->bindValue(':status', $processFlag)
                    ->bindValue(':updated_at', $updated_at)
                    ->bindValue(':updated_by', $user)
                    ->bindValue(':from_date', $model->from_date)
                    ->bindValue(':to_date', $model->to_date)
                    ->bindValue(':payment_type', $model->payment_type)
                    ->bindValue(':party_master_code', $model->party_master_code)
                    ->bindValue(':union_code', $model->union_code)
                    ->execute();
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            $msg = $model->payment_type . ' Payment of ' . Yii::$app->general->getforeignkey($model->partyMaster, 'party_name') . ' ' . $processFlag . ' succesfully';
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'message' => $msg,
            ]);
            $url = Url::to(['index']);
            return ['status' => 'success', 'url' => $url, 'msg' => $msg];
        }
        $query = $model->getRecords();
        $title = $model->payment_type . ' Party Payment Process : Step 2';
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
        return $this->render('payment_adjust', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'title' => $title,
        ]);
    }
    public function actionPaymentAdjust() {
        $this->layout = "@app/themes/pcdf/layouts/paymentLayout.php";
        $model = new TblPartyPayment();
        $model->load(Yii::$app->request->get());
//        echo "<pre>";
//        print_r($model);
//        die;
        $model = $model->find()->where(['from_date' => $model->from_date, 'to_date' => $model->to_date, 'party_master_code' => $model->party_master_code])->one();
        if (!empty($model)) {
//            $model->vendor_code = $model->transporterCode->vendor_code;
            if (Yii::$app->request->post()) {
                if (!empty(Yii::$app->request->post()['TblTPartyPayment']['adjust_amount'])) {
                    $historyModel = new TblPartyPaymentHistory();
                    Yii::$app->operation->history($model, $historyModel, UPDATE);
                    $final_amount = $model->net_amount;
                    $model->load(Yii::$app->request->post());
                    $model->final_amount = $final_amount + $model->adjust_amount;
                    $transaction = $this->generalModel->saveTransaction([$model, $historyModel], ['Payment of ' . $model->partyMatser->party_name . '(' . $model->party_master_code . ')' . ' adjusted succesfully', 'info']);
                    if ($transaction == 'customRedirect') {
                        return $this->redirect(['index']);
                    }
                } else {
                    Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                        'message' => 'Payment of ' . $model->partyMatser->party_name . '(' . $model->party_master_code . ')' . ' adjusted succesfully']);
                    return $this->redirect(['index']);
                }
            }
            $searchModel = new TblPartyPaymentDetailSearch();
            $searchModel->party_payment_code = $model->party_payment_code;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $searchModelHead = new TblPartyPaymentHeadDetailSearch();
            $searchModelHead->party_payment_code = $model->party_payment_code;
            $dataProviderHead = $searchModelHead->search(Yii::$app->request->queryParams);
            return $this->render('payment-adjust-secondary', [
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
}