<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblLedgerMappingEvent;
use app\modules\dcsaccounting\models\TblLedgerMappingEventSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use app\modules\dcsaccounting\models\TblLedgerMappingEventHistory;

/**
 * TblLedgerMappingEventController implements the CRUD actions for TblLedgerMappingEvent model.
 */
class TblLedgerMappingEventController extends ChildController {

    /**
     * Lists all TblLedgerMappingEvent models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblLedgerMappingEventSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblLedgerMappingEvent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblLedgerMappingEvent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblLedgerMappingEvent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCreate() {
        $searchModel = new TblLedgerMappingEventSearch();
        $model = new TblLedgerMappingEvent();
        $dataProvider = $searchModel->mappingSearch(Yii::$app->request->queryParams);

        if (Yii::$app->request->post()) {

            $incCount = 0;
            $save_model = [];
            $historyModel = [];
            $postData = Yii::$app->request->post()['TblEvent'];

            foreach ($postData as $eventCode => $data) {
                $mapping_model = TblLedgerMappingEvent::find()->where(['event_code' => $eventCode])->one();
                $isChanged = false;

                $newCreditLedger = $data['credit_ledger_code'] ?? '';
                $newCreditSub = !empty($data['credit_sub_ledger']) ? 1 : 0;
                $newDebitLedger = $data['debit_ledger_code'] ?? '';
                $newDebitSub = !empty($data['debit_sub_ledger']) ? 1 : 0;
                $newVoucherType = $data['voucher_type_code'] ?? '';

                if (!empty($mapping_model)) {
                    if ($mapping_model->credit_ledger_code != $newCreditLedger || $mapping_model->credit_sub_ledger != $newCreditSub || $mapping_model->debit_ledger_code != $newDebitLedger || $mapping_model->debit_sub_ledger != $newDebitSub || $mapping_model->voucher_type_code != $newVoucherType) {
                        $isChanged = true;
                        $mapingHistory = new TblLedgerMappingEventHistory();
                        Yii::$app->operation->history($mapping_model, $mapingHistory, 'UPDATE');
                        $historyModel[] = $mapingHistory;
                    }
                } else {
                    if ((isset($data['credit_ledger_code']) && $data['credit_ledger_code'] != '') || (isset($data['debit_ledger_code']) && $data['debit_ledger_code'] != '') || $newCreditSub == 1 || $newDebitSub == 1 || (isset($data['voucher_type_code']) && $data['voucher_type_code'] != '')) {
                        $mapping_model = new TblLedgerMappingEvent();
                        $mapping_model->event_code = $eventCode;
                        $mapping_model->event_code_default = !empty($data['event_code_default']) ? $data['event_code_default'] : '';
                        $incCount++;
                        $mapping_model->ledger_mapping_event_code = Yii::$app->general->getCodeAutoIncrement($mapping_model, $incCount);
                        $mapping_model->union_code = $data['union_code'];
                    }
                }

                if ($mapping_model && ($mapping_model->isNewRecord || $isChanged)) {
                    $mapping_model->credit_ledger_code = $newCreditLedger;
                    $mapping_model->credit_sub_ledger = $newCreditSub;
                    $mapping_model->debit_ledger_code = $newDebitLedger;
                    $mapping_model->debit_sub_ledger = $newDebitSub;
                    $mapping_model->voucher_type_code = $newVoucherType;
                    $save_model[] = $mapping_model;
                }
            }
            if (!empty($save_model)) {
                $transaction = $this->generalModel->saveTransaction($save_model, $historyModel, ['Ledger Mapping Event', 'edit']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            } else {
                Yii::$app->session->setFlash('success', ['type' => 'error', 'message' => 'No changes detected. Please update at least one mapping before saving.']);
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        return $this->render('mapping', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model
        ]);
    }

}
