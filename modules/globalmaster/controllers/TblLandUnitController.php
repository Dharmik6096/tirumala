<?php
namespace app\modules\globalmaster\controllers;
use Yii;
use app\modules\globalmaster\models\TblLandUnit;
use app\modules\globalmaster\models\TblLandUnitSearch;
use app\modules\globalmaster\models\TblLandUnitHistory;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
/**
 * TblLandUnitController implements the CRUD actions for TblLandUnit model.
 */
class TblLandUnitController extends ChildController
{
    /**
     * @inheritdoc
     */
    /**
     * Lists all TblLandUnit models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new TblLandUnit();
        $searchModel=new TblLandUnitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $unit_data=  $model->getDefaultValues();
        return $this->render('index', ['searchModel' => $searchModel,'dataProvider' => $dataProvider,'unit_data'=>$unit_data]);
    }
    /**
     * Creates a new TblLandUnit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->model = new TblLandUnit();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            //$this->model->land_unit_name=  ucwords($this->model->land_unit_name);
            $this->model->is_default = 0;
            $this->model->land_unit_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Land Unit', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
         }
         return $this->customRender();
    }
    /**
     * Updates an existing TblLandUnit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            $historyModel=new TblLandUnitHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            //$this->model->land_unit_name = ucfirst($this->model->land_unit_name);
            $transaction = $this->generalModel->saveTransaction([$this->model,$historyModel], ['Land Unit', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
            return $this->customRender();
    }
    /**
     * Deletes an existing TblLandUnit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {

        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_land_unit',Yii::$app->request->post('id'), 'land_unit_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel=new TblLandUnitHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model,$historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);

    }
    /**
     * Finds the TblLandUnit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblLandUnit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblLandUnit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }   
}
