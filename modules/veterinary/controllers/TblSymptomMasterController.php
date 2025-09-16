<?php

namespace app\modules\veterinary\controllers;

use Yii;
use app\modules\veterinary\models\TblSymptomMaster;
use app\modules\veterinary\models\TblSymptomMasterHistory;
use app\modules\veterinary\models\TblSymptomMasterSearch;
use yii\web\NotFoundHttpException;
use app\controllers\ChildController;
use yii\helpers\Url;

/**
 * TblSymptomMasterController implements the CRUD actions for TblSymptomMaster model.
 */
class TblSymptomMasterController extends ChildController {

    /**
     * Lists all TblSymptomMaster models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblSymptomMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblSymptomMaster model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblSymptomMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    
    public function actionCreate() {
        $this->model = new TblSymptomMaster();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Symptom', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblSymptomMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            $historyModel = new TblSymptomMasterHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Symptoms', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Finds the TblSymptomMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblSymptomMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblSymptomMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionDeactivate($id) {
        $this->model = $this->findModel($id);
        $historyModel = new TblSymptomMasterHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $this->model->is_active = 0;
        if ($this->model->validate()) {
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Symptom', 'edit']);
            if ($transaction !== FALSE) {
                Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                    'message' => 'Symptom deactivated successfully.']);
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => 'Could not deactivate. Please try again.']);
            }
        } else {
            $msg = '';
            foreach ($this->model->getErrors() as $errorkey => $value) {
                $msg .= $value[0] . '<br/>';
            }
            Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => $msg]);
            return $this->redirect(\yii\helpers\Url::previous());
        }

        return $this->redirect(Url::previous());
    }

}
