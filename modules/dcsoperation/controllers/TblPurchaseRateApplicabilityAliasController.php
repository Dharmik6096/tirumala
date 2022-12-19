<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblPurchaseRateApplicabilityAlias;
use app\modules\dcsoperation\models\TblPurchaseRateApplicabilityAliasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use app\modules\dcsoperation\models\TblPurchaseRateApplicabilityAliasHistory;

/**
 * TblPurchaseRateApplicabilityAliasController implements the CRUD actions for TblPurchaseRateApplicabilityAlias model.
 */
class TblPurchaseRateApplicabilityAliasController extends \app\controllers\ChildController {

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
                        $MainModel = new TblPurchaseRateApplicability();
                        $MainModel->attributes = $existData->attributes;
                        $MainModel->purchase_rate_code = $existData->purchase_rate_code;
                        $MainModel->scenario = 'approval';
                        $historyModel = new TblPurchaseRateApplicabilityAliasHistory();
                        Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                        $existData->status = 1;
                    } else {
                        $existData = $this->findModel($value);
                        $historyModel = new TblPurchaseRateApplicabilityAliasHistory();
                        Yii::$app->operation->history($existData, $historyModel, 'DELETE');
                    }
                    if ($operation == 'approve' && $MainModel->validate()) {
                        $succCount++;
                        $saveModel[] = $existData;
                        $saveModel[] = $MainModel;
                        $saveModel[] = $historyModel;
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

                    $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Milk Collection Approval', 'edit']);
                }
                $msg = 'Purchase Rate Applicability ' . $operation . ' successfully. <br />' . strtolower($operation) . ' count : ' . $succCount . '<br />Not ' . strtolower($operation) . ' count : ' . $errorCount;
                Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                    'message' => $msg]);
                $getData = Yii::$app->request->queryParams;
                if (!empty($getData['TblPurchaseRateApplicabilityAliasSearch'])) {
                    return $this->redirect(['applicabilty-approve', 'TblPurchaseRateApplicabilityAliasSearch' => $getData['TblPurchaseRateApplicabilityAliasSearch']]);
                } else {
                    return $this->redirect(['applicabilty-approve']);
                }
            }
        }
        $searchModel = new TblPurchaseRateApplicabilityAliasSearch();
        $dataProvider = $searchModel->approvalsearch(Yii::$app->request->queryParams);
        $searchModel->scenario = 'approvalApplicability';
        return $this->render('_approval', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblPurchaseRateApplicabilityAlias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblPurchaseRateApplicabilityAlias the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblPurchaseRateApplicabilityAlias::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
