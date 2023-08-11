<?php

namespace app\modules\payment\controllers;

use Yii;
use yii\web\Controller;
use app\modules\payment\models\TblVspPayment;
use app\modules\payment\models\TblVspPaymentSearch;
use app\modules\payment\models\TblMemberPaymentSummary;
use app\modules\payment\models\TblMemberPaymentSummarySearch;
use app\modules\payment\models\TblPaymentHoldReason;

class TblUpdateUnrealizePaymentController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new TblVspPayment();
        $model->load(Yii::$app->request->get());
        
        $data = Yii::$app->request->get('TblVspPayment');

        if (isset($data['payment_type']) && $data['payment_type'] == 'VENDOR') {
            $model->scenario = 'paymenttypevendor';
            $searchModel = new TblVspPaymentSearch();
            $dataProvider = $searchModel->unreleasePaymentSearch($data);
            
        } else{
            $model->scenario = 'unreleasepaymentsearch';
            $searchModel = new TblMemberPaymentSummarySearch();
            $searchModel->attributes=$data;
            $dataProvider = $searchModel->unreleasePaymentSearch($data);
        }

        return $this->render('index', [
            'model' => $model,
            'title' => 'Update Unrelease Payment',
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdateUnreleasePayment()
    {
        echo '<pre>'; 
        print_r(Yii::$app->request->post());
        echo '</pre>';
        die();
    }

}
