<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblBmcDispatchInspection;
use app\modules\tankermovement\models\TblBmcDispatchInspectionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\configuration\models\TblConfig;
use app\modules\tankermovement\models\TblConfigTxnResult;

/**
 * TblBmcDispatchInspectionController implements the CRUD actions for TblBmcDispatchInspection model.
 */
class TblBmcDispatchInspectionController extends \app\controllers\ChildController {

    /**
     * Lists all TblBmcDispatchInspection models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBmcDispatchInspectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $config = new TblConfig();
        $config->config_for = 'BMC';
        $config->process_name = 'BMC_DISPATCH';
        $config->config_type = 'CONTROL';
        $config_list = $config->getControlConfigList();
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'config_list' => $config_list
        ]);
    }

    /**
     * Creates a new TblBmcDispatchInspection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($trip_code, $vehicle_trip_detail_code) {
        $model = new TblBmcDispatchInspection();
        $model->trip_code = $trip_code;
        $model->vehicle_trip_detail_code = $vehicle_trip_detail_code;
        $trip_model = $model->tripCode;
        $trip_detail = $model->tripDetailCode;

        if (!empty($trip_model) && !empty($trip_detail)) {
            $model->inspection_date = $trip_model->transaction_date;
            $model->trip_code = $trip_model->trip_code;
            $model->vehicle_code = $trip_model->vehicle_code;
            $model->bmc_code = $trip_detail->destination_code;
            $org_detail = $model->bmcCode;
            $model->union_code = $org_detail->union_code;
            $model->plant_code = $org_detail->plant_code;
            $model->mcc_plant_code = $org_detail->mcc_plant_code;
            $config = new TblConfig();
            $config->config_for = 'BMC';
            $config->process_name = 'BMC_DISPATCH';
            $config->config_type = 'CONTROL';
            $config_mapping = new TblConfigTxnResult();
            $config_list = $config->getOrgConfigList($config->config_for, $model->bmc_code);
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->inspection_date = $trip_model->transaction_date;
                $model->bmc_dispatch_inspection_code = Yii::$app->general->getPrimaryCode($model);
                $saveModel = [];
                $saveModel[] = $model;
                $config_data = !empty(Yii::$app->request->post()['TblConfigTxnResult']) ? Yii::$app->request->post()['TblConfigTxnResult'] : [];
                $cnt = 1;
                foreach ($config_data as $data) {
                    $config_model = new TblConfigTxnResult();
                    $config_model->attributes = $model->attributes;
                    $config_model->attributes = $data;
                    $config_model->config_for = 'BMC_DISPATCH_INSPECTION';
                    $config_model->config_txn_result_code = Yii::$app->general->getPrimaryCode($config_model, $cnt);
                    $config_model->ref_code = $model->bmc_dispatch_inspection_code;
                    $config_detail = $config_model->configCode;
                    $saveModel[] = $config_model;
                    $cnt++;
                }
                $transaction = $this->generalModel->saveTransaction($saveModel, ['Inspection', 'create']);
                if ($transaction == 'customRedirect') {
                    $this->redirect(['index']);
                }
            }
            return $this->render('create', [
                        'model' => $model,
                        'config' => $config_mapping,
                        'config_list' => $config_list
            ]);
        } else {
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the TblBmcDispatchInspection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblBmcDispatchInspection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblBmcDispatchInspection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
