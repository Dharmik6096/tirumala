<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblLedgerMappingProductGroup;
use app\modules\dcsaccounting\models\TblLedgerMappingProductGroupHistory;
use app\modules\dcsaccounting\models\TblLedgerMappingProductGroupSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;

/**
 * TblLedgerMappingProductGroupController implements the CRUD actions for TblLedgerMappingProductGroup model.
 */
class TblLedgerMappingProductGroupController extends ChildController {

    /**
     * Lists all TblLedgerMappingProductGroup models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblLedgerMappingProductGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates or updates TblLedgerMappingProductGroup models.
     * @return mixed
     */
    public function actionCreate() {
        $searchModel = new TblLedgerMappingProductGroupSearch();
        $model = new TblLedgerMappingProductGroup();
        $dataProvider = $searchModel->mappingSearch(Yii::$app->request->queryParams);

        if (Yii::$app->request->post()) {
            $incCount = 0;
            $save_model = [];
            $historyModel = [];
        
            $post = Yii::$app->request->post();
            $postData = isset($post['TblProductGroup']['tblLedgerMappingProductGroup']) ? $post['TblProductGroup']['tblLedgerMappingProductGroup'] : [];
            if (!empty($postData)) {
                foreach ($postData as $productGroupCode => $data) {
                    $query = TblLedgerMappingProductGroup::find()->where(['product_group_code' => $productGroupCode]);
                    Yii::$app->general->filterByOrg($query, $model);
                    $mapping_model = $query->one();
                    
                    if (!empty($mapping_model)) {
                        $isChanged = ($mapping_model->ledger_sale_code != $data['ledger_sale_code'] || $mapping_model->ledger_purchase_code != $data['ledger_purchase_code']);

                        if ($isChanged) {
                            $mapingHistory = new TblLedgerMappingProductGroupHistory();
                            Yii::$app->operation->history($mapping_model, $mapingHistory, 'UPDATE');
                            $historyModel[] = $mapingHistory;
                        }
                    } else {
                        $mapping_model = new TblLedgerMappingProductGroup();
                        $mapping_model->product_group_code = $productGroupCode;
                        $incCount++;
                        $mapping_model->ledger_mapping_product_group_code = Yii::$app->general->getCodeAutoIncrement($mapping_model, $incCount);
                        $mapping_model->union_code = $data['union_code'];
                    }

                    $mapping_model->ledger_sale_code = $data['ledger_sale_code'];
                    $mapping_model->ledger_purchase_code = $data['ledger_purchase_code'];

                    $save_model[] = $mapping_model;
                }

                if (!empty($save_model)) {
                    $transaction = $this->generalModel->saveTransaction($save_model, $historyModel, ['Ledger Mapping Product Group', 'edit']);
                    if ($transaction == 'customRedirect') {
                        return $this->redirect(['index']);
                    }
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
     * Finds the TblLedgerMappingProductGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblLedgerMappingProductGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblLedgerMappingProductGroup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
