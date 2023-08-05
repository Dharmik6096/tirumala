<?php

namespace app\modules\document\controllers;

use Yii;
use app\modules\document\models\TblDocumentMapping;
use app\modules\document\models\TblDocumentMappingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\ChildModel;
use app\modules\welfarescheme\models\TblDocumentMasterInfoSearch;
use app\modules\document\models\TblDocumentMappingHistory;
use app\modules\welfarescheme\models\TblDocumentMasterInfo;

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

    /**
     * Displays a single TblDocumentMapping model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblDocumentMapping model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblDocumentMapping();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->mapping_id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
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
                foreach ($toRevoke as $revoke_widget) {
                    $model = new TblDocumentMapping();
                    $model->doc_id = $revoke_widget;
                    $model->union_code = $model->docId->union_code;
                    $record = $model->getExistMappedControl();
                    $historyModel = new TblDocumentMappingHistory();
                    Yii::$app->operation->history($record, $historyModel, 'DELETE');
                    $master[] = $historyModel;
                    if (!empty($record)) {
                        $delete[] = $record;
                    }
                }
            }
            if (!empty($toAssign)) {
                foreach ($toAssign as $Assign_widget) {
                    $model = new TblDocumentMapping();
                    $model->doc_id = $Assign_widget;
                    $model->is_mandate = !empty($postMendateArray) && in_array($Assign_widget, $postMendateArray) ? 1 : 0;
                    $model->master_type = $postMasterArray;
                    $model->union_code = $model->docId->union_code;
                    $master[] = $model;
                    $auto_inc++;
                }
            }

            if (!empty($toUpdate)) {
                foreach ($toUpdate as $update_widget) {
                    $model = new TblDocumentMapping();
                    $model->doc_id = $update_widget;
                    $model->is_mandate = !empty($postMendateArray) && in_array($update_widget, $postMendateArray) ? 1 : 0;
                    $record = $model->getExistMappedControl();
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

    /**
     * Updates an existing TblDocumentMapping model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->mapping_id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblDocumentMapping model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblDocumentMapping model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblDocumentMapping the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDocumentMapping::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
