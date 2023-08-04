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
        $selectedArray = $this->model->getWidgets();
        $mandateselectedArray = [];
        $mandateselectedArray = $this->model->getExistingMappingIsmandate();
        if (Yii::$app->request->post()) {
            $postArray = [];
            $postArray = Yii::$app->request->post('selection');
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
            $delete = [];
            if (!empty($toRevoke)) {
                foreach ($toRevoke as $revoke_widget) {
                    $model = new TblDocumentMapping();
                    $model->doc_id = (string) $revoke_widget;
                    $model->master_type = $this->model->master_type;
                    $record = $model->getExistMappedWidgets();
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
                    $model->master_type = $this->model->master_type;
                    $master[] = $model;
                    $auto_inc++;
                }
            }

            $transaction = $this->generalModel->saveDeleteTransaction($master, [], $delete, ['Document Mapping', 'edit']);
            $selectedArray = !empty($postArray) ? $postArray : [];
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
