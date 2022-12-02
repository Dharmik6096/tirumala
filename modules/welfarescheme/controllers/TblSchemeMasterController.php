<?php

namespace app\modules\welfarescheme\controllers;

use Yii;
use app\modules\welfarescheme\models\TblSchemeMaster;
use app\modules\welfarescheme\models\TblSchemeMasterHistory;
use app\modules\welfarescheme\models\TblSchemeMasterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblSchemeMasterController implements the CRUD actions for TblSchemeMaster model.
 */
class TblSchemeMasterController extends \app\controllers\ChildController {

    public function actionIndex() {
        $searchModel = new TblSchemeMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblSchemeMaster model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblSchemeMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblSchemeMaster();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->start_date = !empty($this->model->start_date) ? date('Y-m-d', strtotime($this->model->start_date)) : '';
            $this->model->end_date = !empty($this->model->end_date) ? date('Y-m-d', strtotime($this->model->end_date)) : '';
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Scheme Master', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblSchemeMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblSchemeMasterHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->start_date = !empty($this->model->start_date) ? date('Y-m-d', strtotime($this->model->start_date)) : '';
            $this->model->end_date = !empty($this->model->end_date) ? date('Y-m-d', strtotime($this->model->end_date)) : '';
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Scheme Master', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->render($this->viewFile, ['model' => $this->model,
        ]);
    }

    /**
     * Finds the TblSchemeMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblSchemeMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblSchemeMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
