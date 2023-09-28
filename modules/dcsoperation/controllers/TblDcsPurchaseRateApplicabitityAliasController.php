<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabitityAlias;
use app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabitityAliasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabilityAliasHistory;
use app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabitity;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use app\modules\dcsoperation\models\TblPurchaseRate;

/**
 * TblDcsPurchaseRateApplicabitityAliasController implements the CRUD actions for TblDcsPurchaseRateApplicabitityAlias model.
 */
class TblDcsPurchaseRateApplicabitityAliasController extends \app\controllers\ChildController {

    public function actionApplicabiltyApprove() {
        if (Yii::$app->request->post()) {

            if (isset($_REQUEST['selection'])) {
                $succCount = 0;
                $errorCount = 0;
                $operation = Yii::$app->request->post()['operation'];
                $deletedata = Yii::$app->request->post('selection');
                foreach ($deletedata as $key => $value) {
                    $saveModel = [];
                    $deleteModel = [];
                    if ($operation == 'approve') {
                        $existData = $this->findModel($value);
                        $MainModel = new TblDcsPurchaseRateApplicabitity();
                        $MainModel->attributes = $existData->attributes;
                        $MainModel->purchase_rate_code = $existData->purchase_rate_code;
                        $MainModel->scenario = 'approval';
                        $historyModel = new TblDcsPurchaseRateApplicabilityAliasHistory();
                        Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                        $existData->status = 1;

                        $dcsRateModel = TblPurchaseRate::find()->where(['dcs_purchase_rate_code' => $MainModel->purchase_rate_code])->one();
                        if (!empty($dcsRateModel) && strtoupper($MainModel->applicable_for) == 'DCS') {
                            $dcsAppModel = new TblPurchaseRateApplicability();
                            $dcsAppModel->attributes = $MainModel->attributes;
                            $dcsAppModel->purchase_rate_code = $dcsRateModel->purchase_rate_code;
                            $dcsAppModel->dcs_code = $MainModel->applicable_code;
                            $dcsAppModel->union_code = $MainModel->union_code;
                            $dcsAppModel->applicable_for = 'DCS';
                        }
                    } else {
                        $existData = $this->findModel($value);
                        $historyModel = new TblDcsPurchaseRateApplicabilityAliasHistory();
                        Yii::$app->operation->history($existData, $historyModel, 'DELETE');
                    }

                    if ($operation == 'approve' && $MainModel->validate()) {
                        $succCount++;
                        $saveModel[] = $existData;
                        $saveModel[] = $MainModel;
                        $saveModel[] = $historyModel;
                        if (!empty($dcsAppModel)) {
                            $saveModel[] = $dcsAppModel;
                        }
                    } elseif ($operation == 'reject') {
                        $succCount++;
                        $deleteModel[] = $existData;
                        $saveModel[] = $historyModel;
                    } else {

                        $errorCount++;
                        $errorMsg = [];
                        foreach ($MainModel->getErrors() as $err) {
                            if (!empty($err[0])) {
                                $errorMsg[] = $err[0];
                            }
                        }
                        $existData->status = 0;
                        $existData->error_desc = implode(', ', $errorMsg);
                        $saveModel[] = $existData;
                    }

                    $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Applicability Approve', 'create']);
                }
                $msg = 'Price Chart Applicability  ' . strtolower($operation) . ' successfully. <br />' . strtolower($operation) . ' count : ' . $succCount . '<br />Not  ' . strtolower($operation) . ' count : ' . $errorCount;
                Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                    'message' => $msg]);
                $getData = Yii::$app->request->queryParams;
                if (!empty($getData['TblDcsPurchaseRateApplicabitityAliasSearch'])) {
                    return $this->redirect(['applicabilty-approve', 'TblDcsPurchaseRateApplicabitityAliasSearch' => $getData['TblDcsPurchaseRateApplicabitityAliasSearch']]);
                } else {
                    return $this->redirect(['applicabilty-approve']);
                }
            }
        }
        $searchModel = new TblDcsPurchaseRateApplicabitityAliasSearch();
        $dataProvider = $searchModel->approvalsearch(Yii::$app->request->queryParams);
        $searchModel->scenario = 'approvalApplicability';
        return $this->render('_approval', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblDcsPurchaseRateApplicabitityAlias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblDcsPurchaseRateApplicabitityAlias the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDcsPurchaseRateApplicabitityAlias::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
