<?php

namespace app\modules\transporter\controllers;

use Yii;
use app\modules\transporter\models\TblVehicleKmInfo;
use app\modules\transporter\models\TblVehicleKmInfoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\transporter\models\TblVehicleMaster;
use app\modules\transporter\models\TblVehicleKmInfoHistory;
use app\components\Model;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Json;

/**
 * TblVehicleKmInfoController implements the CRUD actions for TblVehicleKmInfo model.
 */
class TblVehicleKmInfoController extends \app\controllers\ChildController {

    public $freeAccessActions = ['route-list', 'route-vehicle-detail'];

    /**
     * Lists all TblVehicleKmInfo models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVehicleKmInfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblVehicleKmInfo model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblVehicleKmInfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblVehicleKmInfo();
        $this->viewFile = 'create';

        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->wef_date = !empty($this->model->wef_date) ? date('Y-m-d', strtotime($this->model->wef_date)) : '';
            $this->model->wef_date = $this->model->wef_date . ' ' . \Yii::$app->general->getshift($this->model->shift_code);

            $transaction = $this->generalModel->saveTransaction([$this->model], ['Vehicle Km Information', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblVehicleKmInfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblVehicleKmInfoHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->wef_date = !empty($this->model->wef_date) ? date('Y-m-d', strtotime($this->model->wef_date)) : '';
            $this->model->wef_date = $this->model->wef_date . ' ' . \Yii::$app->general->getshift($this->model->shift_code);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Km Wise Rate', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', ['model' => $this->model]);
    }

    /**
     * Deletes an existing TblVehicleKmInfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblVehicleKmInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblVehicleKmInfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVehicleKmInfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionRouteList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[1])) {
                $mccs = new TblVehicleKmInfo();
                $data = $mccs->getdateWiseVehicleRouteList($parents[0], $parents[1]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                echo Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionRouteVehicleDetail() {
        var_dump(Yii::$app->request->get('TblGateEntry'));die;
        $route_code = '';
        $shift_code = '';
        $datetime = '';
        $model = new TblVehicleKmInfo();
        $data = $model->getRouteVehicleDetail($route_code, $datetime);
        $vehicle_code = $parsing_no = $arrival_time = $grace_time = '';
        if (!empty($data)) {
            $vehicle_code = $data->vehicle_code;
            $parsing_no = $data->vehicle->parsing_no;
            $arrival_time = ($shift_code == '1') ? $data->morning_arrival_time : $data->evening_arrival_time;
            $grace_time = ($shift_code == '1') ? $data->morning_grace_time : $data->evening_grace_time;
        }
        return Json::encode(['vehicle_code' => $vehicle_code, 'parsing_no' => $parsing_no, 'arrival_time' => $arrival_time, 'grace_time' => $grace_time]);
    }

}
