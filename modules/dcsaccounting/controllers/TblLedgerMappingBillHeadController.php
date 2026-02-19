<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblLedgerMappingBillHead;
use app\modules\dcsaccounting\models\TblLedgerMappingBillHeadSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use app\modules\vsp\models\TblBillHead;
use app\modules\dcsaccounting\models\TblLedgers;
use yii\helpers\ArrayHelper;
use app\modules\vsp\models\TblBillHeadSearch;
use app\modules\dcsaccounting\models\TblLedgerMappingBillHeadHistory;

/**
 * TblLedgerMappingBillHeadController implements the CRUD actions for TblLedgerMappingBillHead model.
 */
class TblLedgerMappingBillHeadController extends ChildController {

    /**
     * Lists all TblLedgerMappingBillHead models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblLedgerMappingBillHeadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $searchModel = new TblLedgerMappingBillHeadSearch();
        $model = new TblLedgerMappingBillHead();
        $dataProvider = $searchModel->mappingSearch(Yii::$app->request->queryParams);

        if (Yii::$app->request->post()) {
            $incCount = 0;
            $save_model = [];
            $historyModel = [];

            $postData = Yii::$app->request->post()['TblBillHead'];
            foreach ($postData as $billHeadCode => $data) {
                $mapping_model = TblLedgerMappingBillHead::find()->where(['bill_head_code' => $billHeadCode])->one();
                $isNew = false;

                if (!empty($mapping_model)) {
                    $isChanged = (
                            $mapping_model->ledger_code != $data['ledger_code'] ||
                            $mapping_model->has_sub_ledger != (!empty($data['has_sub_ledger']) ? 1 : 0) ||
                            $mapping_model->credit_debit != $data['credit_debit']
                            );

                    if ($isChanged) {
                        $mapingHistory = new TblLedgerMappingBillHeadHistory();
                        Yii::$app->operation->history($mapping_model, $mapingHistory, 'UPDATE');
                        $historyModel[] = $mapingHistory;
                    }
                } else {
                    $mapping_model = new TblLedgerMappingBillHead();
                    $mapping_model->bill_head_code = $billHeadCode;
                    $incCount++;
                    $mapping_model->ledger_mapping_bill_head_code = Yii::$app->general->getCodeAutoIncrement($mapping_model, $incCount);
                    $mapping_model->union_code = $data['union_code'];
                    $isNew = true;
                }
                $mapping_model->has_sub_ledger = !empty($data['has_sub_ledger']) ? 1 : 0;
                $mapping_model->credit_debit = $data['credit_debit'];
                $mapping_model->ledger_code = $data['ledger_code'];

                $save_model[] = $mapping_model;
            }

            if (!empty($save_model)) {
                $transaction = $this->generalModel->saveTransaction($save_model, $historyModel, ['Ledger Mapping Bill Head', 'edit']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('mapping', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $model
        ]);
    }

    /**
     * Finds the TblLedgerMappingBillHead model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblLedgerMappingBillHead the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblLedgerMappingBillHead::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
