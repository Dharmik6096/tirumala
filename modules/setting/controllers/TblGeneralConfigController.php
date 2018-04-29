<?php

namespace app\modules\setting\controllers;

use Yii;
use app\modules\setting\models\TblGeneralConfig;
use app\modules\setting\models\TblGeneralConfigSearch;
use app\modules\setting\models\TblGeneralConfigHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Response;

/**
 * TblGeneralConfigController implements the CRUD actions for TblGeneralConfig model.
 */
class TblGeneralConfigController extends \app\controllers\ChildController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TblGeneralConfig models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblGeneralConfigSearch();
        $this->model = new TblGeneralConfig();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'model' => $this->model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblGeneralConfig model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblGeneralConfig model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    
    public function actionCreate() {
        $this->model = new TblGeneralConfig();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $transaction = $this->generalModel->saveTransaction([$this->model], ['General Configuration', 'create']);
            if ($transaction !== FALSE) {
                Yii::$app->session->set('makerChecker', $this->model->is_active);
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblGeneralConfig model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
                $historyModel = new TblGeneralConfigHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $this->model->load(Yii::$app->request->post());
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['General Configuration', 'edit']);
                if ($transaction !== FALSE) {
                    Yii::$app->session->set('makerChecker', $this->model->is_active);
                    return $this->{$transaction}();
                }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblGeneralConfig model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
//    public function actionDelete($id)
//    {
//        $this->findModel($id)->delete();
//
//        return $this->redirect(['index']);
//    }
    
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_general_config', Yii::$app->request->post('id'), 'config_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblGeneralConfigHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblGeneralConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblGeneralConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblGeneralConfig::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
