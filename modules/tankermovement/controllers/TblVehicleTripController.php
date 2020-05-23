<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblVehicleTrip;
use app\modules\tankermovement\models\TblVehicleTripSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\tankermovement\models\TblVehicleTripDetailSearch;

/**
 * TblVehicleTripController implements the CRUD actions for TblVehicleTrip model.
 */
class TblVehicleTripController extends \app\controllers\ChildController {

    /**
     * Lists all TblVehicleTrip models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVehicleTripSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblVehicleTrip model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblVehicleTripDetailSearch();
        $searchModel->vehicle_trip_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblVehicleTrip model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblVehicleTrip();
        $this->model->transaction_date = date('Y-m-d');
        $this->viewFile = 'create';

        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $this->model->trip_mode = 'offline';
            $result = $this->model->setModel();
            if ($result[0]) {
                $transaction = $this->generalModel->saveTransaction($result[1], ['Vehicle Trip with Trip No. ' . $result[2]['trip_code'], 'create']);
                if ($transaction == 'customRedirect') {
                    if ($result[2]['inspection_require']) {
                        return $this->redirect(['/tankermovement/tbl-bmc-dispatch-inspection/create',
                                    'trip_code' => $result[2]['trip_code'],
                                    'vehicle_trip_detail_code' => $result[2]['vehicle_trip_detail_code']
                        ]);
                    } else {
                        return $this->{$transaction}();
                    }
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Finds the TblVehicleTrip model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblVehicleTrip the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVehicleTrip::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
