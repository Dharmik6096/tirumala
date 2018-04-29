<?php

namespace app\modules\geo\controllers;

use Yii;
use app\modules\geo\models\TblSubDistricts;
use app\modules\geo\models\TblSubDistrictsSearch;
use app\modules\geo\models\TblSubDistrictsHistory;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use yii\web\Response;
use app\controllers\ChildController;

/**
 * TblSubDistrictsController implements the CRUD actions for TblSubDistricts model.
 */
class TblSubDistrictsController extends ChildController {

    /**
     * Lists all TblSubDistricts models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblSubDistrictsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblSubDistricts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblSubDistricts();
        $this->model->scenario = 'add';
        $this->viewFile = 'create';
        $validate = 1;

        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->sub_district_name = ucwords($this->model->sub_district_name);
            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique($this->model, 'sub_district_name', $this->model->sub_district_name);

            if ($validate == 1) {
                $transaction = $this->generalModel->saveTransaction([$this->model], ['sub district', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblSubDistricts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->model->scenario = 'add';
        $this->viewFile = 'update';
        $this->model->state = $this->model->districtCode->stateCode->state_code;
        $validate = 1;
        if (Yii::$app->request->post()) {

            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique($this->model, 'sub_district_name', $_POST['TblSubDistricts']['sub_district_name']);
            if ($validate == 1) {
                $historyModel = new TblSubDistrictsHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $this->model->load(Yii::$app->request->post());
                $this->model->sub_district_name = ucwords($this->model->sub_district_name);
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['sub district', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblSubDistricts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {

        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_sub_districts', Yii::$app->request->post('id'), 'sub_district_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblSubDistrictsHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblSubDistricts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblSubDistricts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblSubDistricts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
