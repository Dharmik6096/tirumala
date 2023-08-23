<?php

namespace app\modules\welfarescheme\controllers;

use Yii;
use app\modules\welfarescheme\models\TblSchemeDocumentMapping;
use app\modules\welfarescheme\models\TblSchemeDocumentMappingSearch;
use app\modules\welfarescheme\models\TblSchemeDocumentMappingHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblSchemeDocumentMappingController implements the CRUD actions for TblSchemeDocumentMapping model.
 */
class TblSchemeDocumentMappingController extends \app\controllers\ChildController {

    /**
     * Lists all TblSchemeDocumentMapping models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblSchemeDocumentMappingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblSchemeDocumentMapping model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblSchemeDocumentMapping model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblSchemeDocumentMapping();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->union_code = Yii::$app->general->getforeignkey($this->model->schemeId, 'union_code');
            $this->model->doc_id = Yii::$app->general->getforeignkey($this->model->docId, 'doc_name');
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Scheme Document Mapping', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblSchemeDocumentMapping model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblSchemeDocumentMappingHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->union_code = Yii::$app->general->getforeignkey($this->model->schemeId, 'union_code');
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Scheme Document Mapping', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->render($this->viewFile, ['model' => $this->model,
        ]);
    }

    /**
     * Deletes an existing TblSchemeDocumentMapping model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblSchemeDocumentMapping model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblSchemeDocumentMapping the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblSchemeDocumentMapping::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
