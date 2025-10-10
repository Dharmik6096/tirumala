<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblMemberRateRepushLog;
use app\modules\organisation\models\TblMemberRateRepushLogSearch;
use yii\web\NotFoundHttpException;
use app\modules\bkgprocess\models\TblOrgFileCreator;
use app\modules\bkgprocess\models\TblOrgFileCreatorHistory;

class TblMemberRateRepushLogController extends \app\controllers\ChildController {

    public function actionIndex() {
        $searchModel = new TblMemberRateRepushLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $searchModel = new TblMemberRateRepushLogSearch();
        $searchModel->scenario = 'repush';
        Yii::$app->default->getDefaults($searchModel);

        $dataProvider = $searchModel->searchLogData(Yii::$app->request->queryParams);
        return $this->render('create', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

    public function actionRepush() {
        $postData = Yii::$app->request->post();
        $selectedDcsCodes = isset($postData['dcs_codes']) ? $postData['dcs_codes'] : [];
        $file_type = !empty($postData['file_type'] == 'MEMBER') ? 'MEMBER' : 'RATE';
        $successCount = 0;
        $errorCount = 0;
        $saveModel = [];

        foreach ($selectedDcsCodes as $dcsCode) {
            $logStatus = 3;
            $orgFileCreator = TblOrgFileCreator::find()->where(['module_name' => 'TblDcs', 'module_code' => $dcsCode, 'file_type' => $file_type, 'vendor_code' => 'BIPL'])->one();

            if ($orgFileCreator) {
                $historyModel = new TblOrgFileCreatorHistory();
                Yii::$app->operation->history($orgFileCreator, $historyModel, 'UPDATE');
                $saveModel[] = $historyModel;
                $orgFileCreator->status = 0;
                $orgFileCreator->file_status = 0;
                $saveModel[] = $orgFileCreator;
                $successCount++;
                $logStatus = 2;
            } else {
                $errorCount++;
                $logStatus = 3;
            }

            $logModel = new TblMemberRateRepushLog();
            $logModel->union_code = $postData['union_code'];
            $logModel->plant_code = $postData['plant_code'];
            $logModel->mcc_plant_code = $postData['mcc_plant_code'];
            $logModel->bmc_code = $postData['bmc_code'];
            $logModel->dcs_code = $dcsCode;
            $logModel->file_type = $file_type;
            $logModel->dpu_type = $postData['dpu_type'];
            $logModel->log_status = $logStatus;
            $logModel->purchase_rate_code = !empty($orgFileCreator->value1) ? $orgFileCreator->value1 : '';
            $saveModel[] = $logModel;
        }
        $transaction = $this->generalModel->saveTransaction($saveModel, ['Member / Rate Re-Push', 'create']);

        if ($transaction == 'customRedirect') {
            Yii::$app->session->setFlash('success', ['type' => 'error', 'message' => $file_type . ' Re-push process completed. <br/> Success Count : ' . $successCount . ' <br/> Error Count : ' . $errorCount]);
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('success ', ['type' => 'error', 'message' => $file_type . ' Failed to save re-push logs. <br/> Success Count :  ' . $successCount . ' <br/> Error Count : ' . $errorCount]);
            return $this->redirect(['create']);
        }
    }

    protected function findModel($id) {
        if (($model = TblMemberRateRepushLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
