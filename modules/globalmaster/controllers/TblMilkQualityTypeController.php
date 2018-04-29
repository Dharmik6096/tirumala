<?php
namespace app\modules\globalmaster\controllers;
use Yii;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\globalmaster\models\TblMilkQualityTypeSearch;
use app\modules\globalmaster\models\TblMilkQualityTypeHistory;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;
/**
 * TblMilkQualityTypeController implements the CRUD actions for TblMilkQualityType model.
 */
class TblMilkQualityTypeController extends ChildController {
    /**
     * @inheritdoc
     */
    /**
     * Lists all TblMilkQualityType models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMilkQualityTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }
     /**
     * Creates a new TblMilkQualityType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblMilkQualityType();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->milk_quality_type_name = ucwords($this->model->milk_quality_type_name);
            $this->model->milk_quality_type_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            $transaction =$this->generalModel->saveTransaction([$this->model], ['Milk Quality Type', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }
    /**
     * Updates an existing TblMilkQualityType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel=new TblMilkQualityTypeHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->milk_quality_type_name = ucwords($this->model->milk_quality_type_name);
            $transaction = $this->generalModel->saveTransaction([$this->model,$historyModel], ['Milk Quality Type', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }
    /**
     * Deletes an existing TblMilkQualityType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {

        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_milk_quality_type', Yii::$app->request->post('id'), 'milk_quality_type_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
             $historyModel=new TblMilkQualityTypeHistory();
             Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $this->model->save();
            $record = $this->generalModel->deleteTransaction([$this->model,$historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);

    }
    /**
     * Finds the TblMilkQualityType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMilkQualityType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMilkQualityType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
