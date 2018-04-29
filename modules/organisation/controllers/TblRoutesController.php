<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblRoutes;
use app\modules\organisation\models\TblRoutesSearch;
use app\modules\organisation\models\TblRoutesHistory;
use yii\web\NotFoundHttpException;
use app\controllers\ChildController;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblRoutesController implements the CRUD actions for TblRoutes model.
 */
class TblRoutesController extends ChildController {
    /**
     * @inheritdoc
     */

    /**
     * Lists all TblRoutes models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new TblRoutes();
        $searchModel = new TblRoutesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblRoutes model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblRoutes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblRoutes();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->federation_code = $this->model->unionCode->federationCode->federation_code;
            $this->model->route_name = ucwords($this->model->route_name);
            $this->model->route_code = $this->model->getCode();

            $transaction = $this->generalModel->saveTransaction([$this->model], ['route', 'create']);
            
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblRoutes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $this->model->federation_code = $this->model->unionCode->federationCode->federation_code;

        if (Yii::$app->request->post()) {
            $historyModel = new TblRoutesHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->route_name = ucwords($this->model->route_name);

            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['route', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblRoutes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_routes', Yii::$app->request->post('id'), 'route_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblRoutesHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblRoutes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblRoutes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblRoutes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function customRedirect() {
        return $this->redirect(['view', 'id' => $this->model->route_code]);
    }
}
