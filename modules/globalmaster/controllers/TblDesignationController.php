<?php
namespace app\modules\globalmaster\controllers;
use Yii;
use app\modules\globalmaster\models\TblDesignation;
use app\modules\globalmaster\models\TblDesignationSearch;
use app\modules\globalmaster\models\TblDesignationHistory;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;
/**
 * TblDesignationController implements the CRUD actions for TblDesignation model.
 */
class TblDesignationController extends ChildController {
    /**
     * @inheritdoc
     */
    /**
     * Lists all TblDesignation models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDesignationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }
    /**
     * Creates a new TblDesignation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblDesignation();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->designation_name = ucwords($this->model->designation_name);
            $this->model->designation_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Designation', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }
    /**
     * Updates an existing TblDesignation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel=new TblDesignationHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->designation_name = ucfirst($this->model->designation_name);
            $transaction = $this->generalModel->saveTransaction([$this->model,$historyModel], ['Designation', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
            return $this->customRender();
    }
    /**
     * Deletes an existing TblDesignation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {

        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_designation', 'tbl_designation_local', 'tbl_designation_local_history', Yii::$app->request->post('id'), 'designation_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel=new TblDesignationHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model,$historyModel], ['TblDesignationLocal','TblDesignationLocalHistory'], 'designation_code');
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);

    }
    /**
     * Finds the TblDesignation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblDesignation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDesignation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
