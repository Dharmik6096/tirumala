<?php

namespace app\modules\welfarescheme\controllers;

use Yii;
use app\modules\welfarescheme\models\TblSchemeDocumentMaster;
use app\modules\welfarescheme\models\TblSchemeDocumentMasterHistory;
use app\modules\welfarescheme\models\TblSchemeDocumentMasterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblSchemeDocumentMasterController implements the CRUD actions for TblSchemeDocumentMaster model.
 */
class TblSchemeDocumentMasterController extends \app\controllers\ChildController {

    public function actionIndex() {
        $searchModel = new TblSchemeDocumentMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblSchemeDocumentMaster model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblSchemeDocumentMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblSchemeDocumentMaster();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Scheme Document Master', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblSchemeDocumentMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblSchemeDocumentMasterHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Scheme Document Master', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->render($this->viewFile, ['model' => $this->model,
        ]);
    }

    /**
     * Finds the TblSchemeDocumentMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblSchemeDocumentMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblSchemeDocumentMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
