<?php

namespace app\modules\configuration\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\configuration\models\TblShiftTimeAndroid;
use app\modules\configuration\models\TblShiftTimeAndroidSearch;
use app\modules\configuration\models\TblShiftTimeAndroidHistory;

/**
 * TblGenerateReportParamController implements the CRUD actions for TblShiftTimeAndroid model.
 */
class TblShiftTimeAndroidController extends \app\controllers\ChildController {

    /**
     * Lists all TblShiftTimeAndroid models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblShiftTimeAndroidSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblShiftTimeAndroid model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblShiftTimeAndroid();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            if ($this->model->org_type == 'BMC') {
                $this->model->org_code = $this->model->bmc_code;
            } elseif ($this->model->org_type == 'MCC') {
                $this->model->org_code = $this->model->mcc_plant_code;
            }
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Asset Group', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblShiftTimeAndroid model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            $historyModel = new TblShiftTimeAndroidHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Shift Time Android', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Finds the TblShiftTimeAndroid model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblShiftTimeAndroid the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblShiftTimeAndroid::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
