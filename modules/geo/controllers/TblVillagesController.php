<?php

namespace app\modules\geo\controllers;

use Yii;
use app\modules\geo\models\TblVillages;
use app\modules\geo\models\TblVillagesSearch;
use app\modules\geo\models\TblVillagesHistory;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use yii\web\Response;
use app\controllers\ChildController;

/**
 * TblVillagesController implements the CRUD actions for TblVillages model.
 */
class TblVillagesController extends ChildController {

    /**
     * Lists all TblVillages models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVillagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblVillages model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblVillages();
        $this->model->scenario = 'add';
        $this->viewFile = 'create';
        $validate = 1;
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->village_name = ucwords($this->model->village_name);
            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique($this->model, 'village_name', $this->model->village_name);
            if ($validate == 1) {
                $transaction = $this->generalModel->saveTransaction([$this->model], ['village', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblVillages model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $this->model->scenario = 'add';
        $this->model->state = \Yii::$app->general->getmultiforeignkey($this->model->subDistrictCode,['districtCode','stateCode'],'state_code');
        $this->model->district = \Yii::$app->general->getmultiforeignkey($this->model->subDistrictCode,['districtCode'],'district_code');
        $validate = 1;

        if (Yii::$app->request->post()) {
            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique($this->model, 'village_name', $_POST['TblVillages']['village_name']);
            if ($validate == 1) {
                $historyModel = new TblVillagesHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $this->model->load(Yii::$app->request->post());
                $this->model->village_name = ucwords($this->model->village_name);
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['village', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblVillages model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_villages', Yii::$app->request->post('id'), 'village_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblVillagesHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblVillages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblVillages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVillages::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
