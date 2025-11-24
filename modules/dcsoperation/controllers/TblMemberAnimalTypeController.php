<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblMemberAnimalType;
use app\modules\dcsoperation\models\TblMemberAnimalTypeHistory;
use app\modules\dcsoperation\models\TblMemberAnimalTypeSearch;
use yii\web\NotFoundHttpException;
use app\controllers\ChildController;
use yii\helpers\Url;

/**
 * TblMemberAnimalTypeController implements the CRUD actions for TblMemberAnimalType model.
 */
class TblMemberAnimalTypeController extends ChildController {

    /**
     * Lists all TblMemberAnimalType models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMemberAnimalTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMemberAnimalType model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblMemberAnimalType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblMemberAnimalType();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Member Animal Type', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblMemberAnimalType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            $historyModel = new TblMemberAnimalTypeHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Member Animal Type', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Finds the TblMemberAnimalType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMemberAnimalType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMemberAnimalType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionDeactivate($id) {
        $this->model = $this->findModel($id);
        $historyModel = new TblMemberAnimalTypeHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $this->model->is_active = 0;
        if ($this->model->validate()) {
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Member Animal Type', 'edit']);
            if ($transaction !== FALSE) {
                Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                    'message' => 'Member Animal Type deactivated successfully.']);
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
