<?php

namespace app\modules\document\controllers;

use Yii;
use app\modules\document\models\TblDocumentMapping;
use app\modules\document\models\TblDocumentMappingSearch;
use app\modules\welfarescheme\models\TblDocumentMasterInfoSearch;
use app\modules\document\models\TblDocumentMappingHistory;

/**
 * TblDocumentMappingController implements the CRUD actions for TblDocumentMapping model.
 */
class TblDocumentMappingController extends \app\controllers\ChildController {

    /**
     * Lists all TblDocumentMapping models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDocumentMappingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDocumentMapping() {
        $this->model = new TblDocumentMapping();
        $this->model->load(Yii::$app->request->queryParams);
        $searchModel = new TblDocumentMasterInfoSearch();
        $searchModel->load(Yii::$app->request->queryParams);
        $searchModel->is_active = 1;
        $dataProvider = $searchModel->mappingsearch(Yii::$app->request->queryParams);
        $selectedArray = [];
        $selectedArray = $this->model->getExistingMapping();
        $mandateselectedArray = [];
        $mandateselectedArray = $this->model->getExistingMappingIsmandate();
        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            $postMasterArray = $this->model['master_type'] ? $this->model['master_type'] : [];
            $postArray = !empty($data['docId']) ? $data['docId'] : [];
            $postMendateArray = !empty($data['isMandate']) ? $data['isMandate'] : [];
            $master = [];
            $auto_inc = 1;
            $newAssignments = [];
            if (!empty($postArray)) {
                $newAssignments = $postArray;
            }
            $oldAssignments = [];
            if (!empty($selectedArray)) {
                $oldAssignments = array_keys($selectedArray);
            }
            $toAssign = array_diff($newAssignments, $oldAssignments);
            $toRevoke = array_values(array_diff($oldAssignments, $newAssignments));
            $toUpdate = array_diff($newAssignments, array_merge($toAssign, $toRevoke));

            $delete = [];
            if (!empty($toRevoke)) {
                foreach ($toRevoke as $revoke_doc) {
                    $model = new TblDocumentMapping();
                    $model->doc_id = $revoke_doc;
                    $model->union_code = $model->docId->union_code;
                    $record = $model->getExistMappedControl($postMasterArray);
                    $historyModel = new TblDocumentMappingHistory();
                    Yii::$app->operation->history($record, $historyModel, 'DELETE');
                    $master[] = $historyModel;
                    if (!empty($record)) {
                        $delete[] = $record;
                    }
                }
            }
            if (!empty($toAssign)) {
                foreach ($toAssign as $Assign_doc) {
                    $model = new TblDocumentMapping();
                    $model->doc_id = $Assign_doc;
                    $model->is_mandate = !empty($postMendateArray) && in_array($Assign_doc, $postMendateArray) ? 1 : 0;
                    $model->master_type = $postMasterArray;
                    $model->union_code = $model->docId->union_code;
                    $master[] = $model;
                    $auto_inc++;
                }
            }

            if (!empty($toUpdate)) {
                foreach ($toUpdate as $update_doc) {
                    $model = new TblDocumentMapping();
                    $model->doc_id = $update_doc;
                    $model->is_mandate = !empty($postMendateArray) && in_array($update_doc, $postMendateArray) ? 1 : 0;
                    $record = $model->getExistMappedControl($postMasterArray);
                    if (!empty($record) && ($model->is_mandate != $record->is_mandate)) {
                        $historyModel = new TblDocumentMappingHistory();
                        Yii::$app->operation->history($record, $historyModel, 'UPDATE');
                        $master[] = $historyModel;
                        $model->master_type = $postMasterArray;
                        $record->is_mandate = $model->is_mandate;
                        $master[] = $record;
                    }
                }
            }
            $transaction = $this->generalModel->saveDeleteTransaction($master, [], $delete, ['Document Mapping', 'edit']);
            $selectedArray = !empty($postArray) ? $postArray : [];
            $mandateselectedArray = !empty($postMendateArray) ? $postMendateArray : [];
            if ($transaction == 'customRedirect') {
                return $this->redirect(['index']);
            }
        }
        return $this->render('document_mapping', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'selectedArray' => $selectedArray,
                    'mandateselectedArray' => $mandateselectedArray,
        ]);
    }

}
