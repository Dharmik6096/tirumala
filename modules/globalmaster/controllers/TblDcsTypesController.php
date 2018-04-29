<?php
namespace app\modules\globalmaster\controllers;
use Yii;
use app\modules\globalmaster\models\TblDcsTypes;
use app\modules\globalmaster\models\TblDcsTypesSearch;
use app\modules\globalmaster\models\TblDcsTypesHistory;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use yii\web\Response;
/**
 * TblDcsTypesController implements the CRUD actions for TblDcsTypes model.
 */
class TblDcsTypesController extends ChildController {
    /**
     * @inheritdoc
     */
    /**
     * Lists all TblDcsTypes models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDcsTypesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }
    /**
     * Creates a new TblDcsTypes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblDcsTypes();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->dcs_type_name = ucwords($this->model->dcs_type_name);
            $this->model->dcs_type_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Society Type', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }
    /**
     * Updates an existing TblDcsTypes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel=new TblDcsTypesHistory();
            Yii::$app->operation->history($this->model, $historyModel,UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->dcs_type_name = ucwords($this->model->dcs_type_name);
            $transaction = $this->generalModel->saveTransaction([$this->model,$historyModel], ['Society Type', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
            return $this->customRender();
    }
    /**
     * Deletes an existing TblDcsTypes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {

        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_dcs_types', Yii::$app->request->post('id'), 'dcs_type_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel=new TblDcsTypesHistory();
            Yii::$app->operation->history($this->model,$historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model,$historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);

    }
    /**
     * Finds the TblDcsTypes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblDcsTypes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDcsTypes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
