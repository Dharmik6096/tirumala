<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblVehicleMaster;
use app\modules\organisation\models\TblVehicleMasterSearch;
use app\modules\organisation\models\TblVehicleMasterHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Response;

/**
 * TblVehicleMasterController implements the CRUD actions for TblVehicleMaster model.
 */
class TblVehicleMasterController extends \app\controllers\ChildController
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
     * Lists all TblVehicleMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblVehicleMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblVehicleMaster model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblVehicleMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblVehicleMaster();
        $this->viewFile = 'create';
        
        if ($this->model->load(Yii::$app->request->post())) {
            $this->setModel($this->model);
            $this->model->vehicle_code = Yii::$app->general->getCodeAutoIncrement($this->model);
//            var_dump($this->model);die;
            $transaction = $this->generalModel->saveTransaction([$this->model],['Vehicle', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblVehicleMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
                $historyModel = new TblVehicleMasterHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $this->model->load(Yii::$app->request->post());
                $this->setModel($this->model);
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Vehicle', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblVehicleMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_vehicle_master', Yii::$app->request->post('id'), 'vehicle_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblVehicleMasterHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblVehicleMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblVehicleMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblVehicleMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function customRedirect() {
        return $this->redirect(['view', 'id' => $this->model->vehicle_code]);
    }
    
    private function setModel() {
        $this->model->driver_name = ucwords($this->model->driver_name);
        $this->model->wef_date = ($this->model->wef_date == '') ? null : Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);        
        $this->model->expiry_date = ($this->model->expiry_date == '') ? null : Yii::$app->formatter->asDate($this->model->expiry_date, DATE_FORMAT);        
    }

    public function actionVehicleOpenList() {
        $out = null;
        if (isset($_POST['depdrop_parents'])) {
            $value = $_POST['depdrop_parents'];
            $unionCode = $value[0];
            $vehicleMasterModel = new TblVehicleMaster();
            $list = $vehicleMasterModel->getVehicleMaster($unionCode);
            foreach ($list as $key => $r) {
                $out[] = array('id' => $key,
                    'name' => $r);
            }
            return Json::encode(['output' => $out]);
            return;
        }
        return Json::encode(['output' => '', 'selected' => $selected]);
    }

}
