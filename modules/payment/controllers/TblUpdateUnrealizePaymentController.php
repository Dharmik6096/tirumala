<?php

namespace app\modules\payment\controllers;

use Yii;
use yii\web\Controller;
use app\modules\payment\models\TblVspPayment;
use app\modules\payment\models\TblVspPaymentSearch;
use app\modules\payment\models\TblMemberPayment;
use app\modules\payment\models\TblMemberPaymentSearch;

class TblUpdateUnrealizePaymentController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new TblVspPayment();
        $model->load(Yii::$app->request->get());
        
        $data = Yii::$app->request->get('TblVspPayment');
        
        if (isset($data['payment_type']) && $data['payment_type'] == 'VENDOR') {
            $searchModel = new TblVspPaymentSearch();
            $dataProvider = $searchModel->search($data);
        } 
        $searchModel = new TblMemberPaymentSearch();
        $dataProvider = $searchModel->unrealizePaymentSearch($data);

        return $this->render('index', [
            'model' => $model,
            'title' => 'Update Unrealize Payment',
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}
