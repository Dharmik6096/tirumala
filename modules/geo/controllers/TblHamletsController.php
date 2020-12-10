<?php

namespace app\modules\geo\controllers;

use Yii;
use app\modules\geo\models\TblHamlets;
use app\modules\geo\models\TblHamletsSearch;
use app\modules\geo\models\TblHamletsHistory;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use yii\web\Response;

/**
 * TblHamletsController implements the CRUD actions for TblHamlets model.
 */
class TblHamletsController extends ChildController {

    /**
     * Lists all TblHamlets models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblHamletsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblHamlets model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblHamlets();
        $this->viewFile = 'create';
        $this->model->scenario = 'add';
        $validate = 1;

        if ($this->model->load(Yii::$app->request->post())) {            
            $this->model->hamlet_name = ucwords($this->model->hamlet_name);  
            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique($this->model, 'hamlet_name', $this->model->hamlet_name);

            if ($validate == 1) {
                $code = $this->model->getMaxVillageCode($this->model->village_code);
                $this->model->hamlet_code = $code;
                $transaction = $this->generalModel->saveTransaction([$this->model],['hamlet', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblHamlets model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $this->model->scenario = 'add';
        $validate = 1;
        // $this->model->state = $this->model->villageCode->subDistrictCode->districtCode->stateCode->state_code;
        $this->model->state = \Yii::$app->general->getmultiforeignkey($this->model->villageCode,['subDistrictCode','districtCode','stateCode'],'state_code');
        $this->model->district = \Yii::$app->general->getmultiforeignkey($this->model->villageCode,['subDistrictCode','districtCode'],'district_code');
        // $this->model->district = $this->model->villageCode->subDistrictCode->districtCode->district_code;
        // $this->model->sub_district = $this->model->villageCode->subDistrictCode->sub_district_code;
        $this->model->sub_district = \Yii::$app->general->getmultiforeignkey($this->model->villageCode,['subDistrictCode'],'sub_district_code');

        if (Yii::$app->request->post()) {

            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique($this->model, 'hamlet_name', $_POST['TblHamlets']['hamlet_name']);
            if ($validate == 1) {
                $historyModel = new TblHamletsHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $this->model->load(Yii::$app->request->post());
                $this->model->hamlet_name = ucwords($this->model->hamlet_name);
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel],['hamlet', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            } else {
                $this->model->load(Yii::$app->request->post());
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblHamlets model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_hamlets', Yii::$app->request->post('id'), 'hamlet_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $localHistory = new TblHamletsHistory();
            Yii::$app->operation->history($this->model, $localHistory, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $localHistory]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblHamlets model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblHamlets the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblHamlets::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetMaxHamletCode() {
        $id = $_POST['id'];
        $model = new TblHamlets();
        $code = $model->getMaxVillageCode($id);
        return Json::encode(['status' => 'success', 'code' =>$code]);
        return;
    }

}
