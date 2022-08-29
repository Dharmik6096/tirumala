<?php

namespace app\modules\globalmaster\controllers;

use Yii;
use app\modules\globalmaster\models\TblDeviceMasterMapping;
use app\modules\globalmaster\models\TblDeviceMasterMappingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\globalmaster\models\TblDeviceMasterMappingHistory;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblDeviceMasterMappingController implements the CRUD actions for TblDeviceMasterMapping model.
 */
class TblDeviceMasterMappingController extends \app\controllers\ChildController {

    /**
     * Lists all TblDeviceMasterMapping models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDeviceMasterMappingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblDeviceMasterMapping model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id) {
        $this->model = new TblDeviceMasterMapping();
        $this->viewFile = 'create';
        $this->model->scenario = 'create';
        $saveModel = [];
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->model->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $this->model->device_mapping_code = (string) Yii::$app->general->getCodeAutoIncrement($this->model);
            $this->model->device_master_code = $id;
            if ($this->model->validate()) {
                $saveModel[] = $this->model;
//                $this->model->setChildTable($this->model, $saveModel);
                $transaction = $this->generalModel->saveTransaction($saveModel, ['Device Master Mapping', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['tbl-device-master/index']);
                }
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Deletes an existing TblDeviceMasterMapping model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblDeviceMasterMappingHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblDeviceMasterMapping model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblDeviceMasterMapping the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDeviceMasterMapping::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
