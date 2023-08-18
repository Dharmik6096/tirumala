<?php

namespace app\modules\payment\controllers;

use Yii;
use yii\web\Controller;
use app\modules\payment\models\TblVspPayment;
use app\modules\payment\models\TblVspPaymentSearch;
use app\modules\payment\models\TblMemberPaymentSummary;
use app\modules\payment\models\TblMemberPaymentSummarySearch;
use app\modules\payment\models\TblMemberPaymentSummaryHistory;
use app\modules\payment\models\TblVspPaymentHistory;

class TblUpdateUnreleasePaymentController extends \app\controllers\ChildController {

    public function actionIndex() {
        $model = new TblVspPayment();
        $model->load(Yii::$app->request->get());
        $data = Yii::$app->request->get('TblVspPayment');
        if ($model->payment_type == 'VENDOR') {
            $model->scenario = 'paymenttypevendor';
            $searchModel = new TblVspPaymentSearch();
            $searchModel->attributes = $data;
            $dataProvider = $searchModel->unreleasePaymentSearch($data);
        } else {
            $model->scenario = 'unreleasepaymentsearch';
            $searchModel = new TblMemberPaymentSummarySearch();
            $searchModel->attributes = $data;
            $dataProvider = $searchModel->unreleasePaymentSearch($data);
        }

        return $this->render('index', [
                    'model' => $model,
                    'title' => 'Update Disburse W/O Release Payment',
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdateUnreleasePayment() {
        $postData = Yii::$app->request->post();
        $paymentType = $postData['payment_type'];
        $selectedRecords = $postData['selection'];
        $success = 0;
        $error = 0;
        if ($paymentType === 'MEMBER') {
            $data = $postData['TblMemberPaymentSummary'];
            foreach ($selectedRecords as $tblId) {
                $model = TblMemberPaymentSummary::findOne($tblId);
                if (!empty($model)) {
                    $historyModel = new TblMemberPaymentSummaryHistory();
                    Yii::$app->operation->history($model, $historyModel, 'UPDATE');
                    $model->scenario = 'unreleasePaymentUpdate'; 
                    $model->hold_reason = $data[$tblId]['hold_reason'];
                    $model->release_date = date('Y-m-d', strtotime($data[$tblId]['release_date']));
                    $model->is_release = 1;
                    if ($model->validate() && $historyModel->save() && $model->save()) {
                        $success++;
                    } else {
                        $error++;
                    }
                }
            }
        } elseif ($paymentType === 'VENDOR') {
            $data = $postData['TblVspPayment'];
            foreach ($selectedRecords as $tblId) {
                $model = TblVspPayment::findOne($tblId);
                if (!empty($model)) {
                    $historyModel = new TblVspPaymentHistory();
                    Yii::$app->operation->history($model, $historyModel, 'UPDATE');
                    $model->scenario = 'unreleasePaymentUpdate'; 
                    $model->hold_reason = $data[$tblId]['hold_reason'];
                    $model->release_date = date('Y-m-d', strtotime($data[$tblId]['release_date']));
                    $model->is_release = 1;
                    if ($model->validate() && $historyModel->save() && $model->save()) {
                        $success++;
                    } else {
                        $error++;
                    }
                }
            }
        }
        $msg = Yii::t('app', $paymentType . ' payment data updated.<br/>Success Count : ' . $success . '<br/>Error Count : ' . $error);
        Yii::$app->getSession()->setFlash('success', [
            'type' => 'success',
            'message' => $msg,
        ]);
        return $this->redirect(['index']);
    }
}