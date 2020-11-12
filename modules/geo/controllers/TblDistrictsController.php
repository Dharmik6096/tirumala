<?php

namespace app\modules\geo\controllers;

use Yii;
use app\controllers\ChildController;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblDistrictsSearch;
use app\modules\geo\models\TblDistrictsHistory;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblDistrictsController implements the CRUD actions for TblDistricts model.
 */
class TblDistrictsController extends ChildController {

    public $freeAccessActions = ['district-list'];

    /**
     * Lists all TblDistricts models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDistrictsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblDistricts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblDistricts();
        $this->viewFile = 'create';
        $validate = 1;
        if ($this->model->load(Yii::$app->request->post())) {

            $this->model->district_name = ucwords($this->model->district_name);
            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique($this->model, 'district_name', $this->model->district_name);
            if ($validate == 1) {
                $transaction = $this->generalModel->saveTransaction([$this->model], ['district', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblDistricts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $validate = 1;
        if (Yii::$app->request->post()) {

            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique($this->model, 'district_name', $_POST['TblDistricts']['district_name']);
            if ($validate == 1) {
                $historyModel = new TblDistrictsHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $this->model->load(Yii::$app->request->post());
                $this->model->district_name = ucwords($this->model->district_name);
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['district', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblDistricts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_districts', Yii::$app->request->post('id'), 'district_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblDistrictsHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblDistricts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblDistricts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDistricts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * Description: return district array
     * By: Dhara
     * DAte: 8-11-2016
     * @return type
     */
    public function actionDistrictList() {
        $out = NULL;
        if (isset($_POST['depdrop_parents'])) {

            $cnt = 0;
            foreach ($_POST as $key => $val) {
                if ($cnt == 0) {
                    $cnt++;
                    continue;
                }
                $data = explode(',', $key);
            }
            $code = str_replace("'", '', $key);
            $value = $_POST['depdrop_parents'];
            $district = new TblDistricts();
            $list = $district->getDistrict($value[0], $code);
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
