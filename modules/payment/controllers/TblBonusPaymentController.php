<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblBonusPayment;
use app\modules\payment\models\TblBonusPaymentSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use app\modules\payment\models\TblBonusPaymentSummary;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblBonusPaymentSummarySearch;
use app\modules\payment\models\TblBonusPaymentSummaryHead;
use app\modules\payment\models\TblBonusPaymentHead;

class TblBonusPaymentController extends ChildController {

    public function actionIndex() {
        $searchModel = new TblBonusPaymentSummarySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        $searchModel = new TblBonusPaymentSearch();
        $searchModel->bonus_payment_summary_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        // Farmer Bonus Payment Process : Step 1
        $model = new TblBonusPaymentSummary();
        $model->scenario = 'process';
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->from_datetime = date('Y-m-d 06:00:00', strtotime($model->from_datetime));
                $model->to_datetime = date('Y-m-d 18:00:00', strtotime($model->to_datetime));
                // $is_valid = \Yii::$app->general->getSpData('sp_bonus_payment_validate', [$model->union_code, ',' . implode(',', $model->bmc_code) . ',', 'MEMBER', 'DCS', $model->from_datetime, $model->to_datetime]);
                $is_valid[] = ['msg' => 'Payment is already generated for Selected Payment Cycle. Do You want to Regenerate?', 'allow_process' => 1];
                $msg = $is_valid[0]['msg'];
                $allow_process = $is_valid[0]['allow_process'];
                $queryParam = [];
                $queryParam[] = 'process-payment';
                $queryParamRegenerate = [];
                $queryParam['TblBonusPaymentSummary'] = ['from_datetime' => $model->from_datetime, 'to_datetime' => $model->to_datetime, 'bmc_code' => $model->bmc_code, 'union_code' => $model->union_code, 'payment_type' => 'MEMBER', 'customer_type' => 'DCS', 'mcc_plant_code' => $model->mcc_plant_code];
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
            $model = new TblBonusPaymentSummary();
            $model->load(Yii::$app->request->get());
            if ($reGenerate == 1) {
                $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
                $data = [];
                $data['union_code'] = $model->union_code;
                $data['bmc_code'] = ',' . implode(',', $model->bmc_code) . ',';
                $data['payment_type'] = $model->payment_type;
                $data['customer_type'] = $model->customer_type;
                $data['from_datetime'] = $model->from_datetime;
                $data['to_datetime'] = $model->to_datetime;
                $data['user_code'] = $user;
                return Yii::$app->ClientPaymentConfig->processPayment('bonus_payment', $data);
            }
            return $this->redirect(['payment-adjust', 'TblBonusPaymentSummary' => ['mcc_plant_code' => $model->mcc_plant_code, 'from_datetime' => $model->from_datetime, 'to_datetime' => $model->to_datetime, 'bmc_code' => $model->bmc_code, 'customer_type' => $model->customer_type, 'payment_type' => $model->payment_type, 'union_code' => $model->union_code]]);
        }
    }

    public function actionPaymentAdjust() {
        $this->layout = "@app/themes/pcdf/layouts/paymentLayout.php";
        $model = new TblBonusPaymentSummary();
        $model->load(Yii::$app->request->get());
        if (Yii::$app->request->post()) {
            $postData = Yii::$app->request->post();
            var_dump($postData);
            die;
        }
        $query = $model->getRecords();
        $title = $model->payment_type . ' Bonus Payment Process : Step 2';
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

    public function actionSummaryBillHead() {
        $code = Yii::$app->request->get()['code'];
        $model = $this->findModel($code);
        $dataProvider = new ActiveDataProvider([
            'query' => TblBonusPaymentSummaryHead::find()->where(['bonus_payment_summary_code' => $code]),
            'pagination' => FALSE,
        ]);
        return $this->renderAjax('bill-head-view', [
                    'dataProvider' => $dataProvider,
                    'model' => $model
        ]);
    }

    protected function findModel($id) {
        if (($model = TblBonusPaymentSummary::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
