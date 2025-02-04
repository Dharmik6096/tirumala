<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblPermanentHoldAmountSearch;
use app\modules\payment\models\TblPermanentHoldAmount;
use app\modules\payment\models\TblPaymentCycleApplicability;
use app\modules\payment\models\TblPaymentCycle;
use app\modules\payment\models\TblPermanentHoldAmountHistory;
use app\modules\payment\models\TblPermanentHoldAmountTransaction;

/**
 * TblMemberPaymentController implements the CRUD actions for TblMemberPayment model.
 */
class TblPermanentHoldAmountController extends \app\controllers\ChildController {

    public function actionIndex() {
        $searchModel = new TblPermanentHoldAmountSearch();     
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReleasePayment() {
        if(Yii::$app->request->post()) {
            if (Yii::$app->request->post()['release_date']) {
                $saveModel = [];
                $postData = Yii::$app->request->post()['TblPermanentHoldAmount'];
                // $payment_cycle = TblPaymentCycle::findOne(Yii::$app->request->post()['payment_cycle_code']);
                if (Yii::$app->request->post()['selection']) {
                    foreach (Yii::$app->request->post()['selection'] as $key => $permanent_hold_amount_code) {
                        $holdPaymentModel = TblPermanentHoldAmount::findOne($permanent_hold_amount_code);
                        if (!empty($holdPaymentModel)) {
                            $historyModel = new TblPermanentHoldAmountHistory();
                            Yii::$app->operation->history($holdPaymentModel, $historyModel, UPDATE);
                            $holdPaymentModel->hold_amount = (float)$holdPaymentModel->hold_amount - (float)$postData[$key]['release_amount'];
                            $holdPaymentModel->release_amount = (float)$holdPaymentModel->release_amount + (float)$postData[$key]['release_amount'];
                            $holdPaymentModel->release_date = date('Y-m-d',strtotime(Yii::$app->request->post()['release_date']));
                            $holdPaymentModel->release_by = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
                            $transaction = new TblPermanentHoldAmountTransaction();
                            $transaction->attributes = $holdPaymentModel->attributes;
                            $saveModel[] = $historyModel;
                            $saveModel[] = $holdPaymentModel;
                            $saveModel[] = $transaction;
                        }
                    }
                    // $transaction = $this->generalModel->saveTransaction($saveModel, ['Member payment released.', 'info']);
                    // if ($transaction == 'customRedirect') {
                    //     return $this->redirect(['index']);
                    // }
                }
            }
        }
        $searchModel = new TblPermanentHoldAmountSearch();
        $searchModel->scenario = 'releasepayment';
        $searchModel->load(Yii::$app->request->get());        
        $dataProvider = $searchModel->search([], true);
        $searchModel->grid_filter = false;
        $dataProvider->pagination = false;
        return $this->render('payment_release', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }
}
