<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblVoucherTypes;
use app\modules\dcsaccounting\models\TblVoucherTypesSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use app\modules\dcsaccounting\models\TblVoucherTypesHistory;
use yii\web\Response;
use yii\helpers\Json;

class TblVoucherTypesController extends ChildController {

    public function actionIndex() {
        $searchModel = new TblVoucherTypesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $this->model = new TblVoucherTypes();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            if ($this->model->validate()) {
                $this->model->voucher_type_code = Yii::$app->general->getCodeAutoIncrement($this->model);
                $transaction = $this->generalModel->saveTransaction([$this->model], ['Voucher Type', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post() && $this->model->load(Yii::$app->request->post())) {
            $historyModel = new TblVoucherTypesHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Voucher Type', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblVoucherTypesHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    protected function findModel($id) {
        if (($model = TblVoucherTypes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
