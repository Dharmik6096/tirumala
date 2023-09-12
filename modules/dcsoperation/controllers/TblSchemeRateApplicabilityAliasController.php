<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblSchemeRateApplicabilityAlias;
use app\modules\dcsoperation\models\TblSchemeRateApplicabilityAliasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\dcsoperation\models\TblSchemeRateApplicabilityHistory;
use app\modules\dcsoperation\models\TblSchemeRateApplicability;
use app\modules\dcsoperation\models\TblSchemeRateApplicabilityAliasHistory;

/**
 * TblSchemeRateApplicabilityAliasController implements the CRUD actions for TblSchemeRateApplicabilityAlias model.
 */
class TblSchemeRateApplicabilityAliasController extends \app\controllers\ChildController {

    public function actionApplicabiltyApprove() {
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $succCount = 0;
                $errorCount = 0;
                $deletedata = Yii::$app->request->post('selection');
                $operation = Yii::$app->request->post()['operation'];
                foreach ($deletedata as $key => $value) {
                    $saveModel = [];
                    $deleteModel = [];
                    if ($operation == 'approve') {
                        $existData = $this->findModel($value);
                        $MainModel = new TblSchemeRateApplicability();
                        $MainModel->attributes = $existData->attributes;
                        $MainModel->scheme_rate_code = $existData->scheme_rate_code;
                        $MainModel->approved_at = date('Y-m-d H:i:s');
                        $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : null;
                        $MainModel->approved_by = $user;
                        $MainModel->scenario = 'approval';
                        $historyModel = new TblSchemeRateApplicabilityAliasHistory();
                        Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                        $existData->status = 1;
                    } else {
                        $existData = $this->findModel($value);
                        $historyModel = new TblSchemeRateApplicabilityAliasHistory();
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

                    $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Applicability Approve', 'create']);
                }
                $msg = 'Scheme Rate Applicability ' . strtolower($operation) . ' successfully. <br />' . strtolower($operation) . ' count : ' . $succCount . '<br />Not ' . strtolower($operation) . ' count : ' . $errorCount;
                Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                    'message' => $msg]);
                $getData = Yii::$app->request->queryParams;
                if (!empty($getData['TblSchemeRateApplicabilityAliasSearch'])) {
                    return $this->redirect(['applicabilty-approve', 'TblSchemeRateApplicabilityAliasSearch' => $getData['TblSchemeRateApplicabilityAliasSearch']]);
                } else {
                    return $this->redirect(['applicabilty-approve']);
                }
            }
        }
        $searchModel = new TblSchemeRateApplicabilityAliasSearch();
        $dataProvider = $searchModel->approvalsearch(Yii::$app->request->queryParams);
        $searchModel->scenario = 'approvalApplicability';
        return $this->render('_approval', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblSchemeRateApplicabilityAlias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblSchemeRateApplicabilityAlias the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblSchemeRateApplicabilityAlias::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
