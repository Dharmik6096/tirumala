<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblBmcSilosInfo;
use app\modules\organisation\models\TblBmcSilosInfoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;
use app\modules\organisation\models\TblBmcSilosInfoHistory;

/**
 * TblBmcSilosInfoController implements the CRUD actions for TblBmcSilosInfo model.
 */
class TblBmcSilosInfoController extends \app\controllers\ChildController {

    /**
     * Lists all TblBmcSilosInfo models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBmcSilosInfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBmcSilosInfo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblBmcSilosInfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($module, $id) {
        $this->model = new TblBmcSilosInfo();
        $this->viewFile = 'create';
        $modelSave = [];
        if ($this->model->load(Yii::$app->request->post())) {
            $update = FALSE;
            if (!empty(Yii::$app->request->post()['TblBmcSilosInfo']['bmc_silos_info_code'])) {
                $this->model = $this->findModel(Yii::$app->request->post()['TblBmcSilosInfo']['bmc_silos_info_code']);
                $historyModel = new TblBmcSilosInfoHistory();
                Yii::$app->operation->history($this->model, $historyModel, 'UPDATE');
                $modelSave[] = $historyModel;
                $this->model->load(Yii::$app->request->post());
                $update = TRUE;
            }
            if (!$update) {
                $this->model->setModel($module, $id, 0);
            }
            $modelSave[] = $this->model;
            $transaction = $this->generalModel->saveTransaction($modelSave, ['Silos Information', ($update) ? 'edit' : 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    protected function customRender() {
        $request = Yii::$app->request->queryParams;
        $searchModel = new TblBmcSilosInfoSearch();
        $searchModel->module_name = $request['module'];
        $searchModel->module_code = $request['id'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $isaction = TRUE;
        return $this->render($this->viewFile, ['model' => $this->model, 'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider, 'module' => $request['module'], 'id' => $request['id'], 'dist' => '', 'isaction' => $isaction]);
    }

    protected function customRedirect() {
        return $this->redirect(Url::previous());
    }

    /**
     * Deletes an existing TblBmcSilosInfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblBmcSilosInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblBmcSilosInfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblBmcSilosInfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUpdateSilos() {
        $data = [];
        $data['status'] = 'error';
        $data['message'] = '';
        $modelData = [];
        if (!empty($_POST['bmc_silos_info_code'])) {
            $modelData = $this->findModel($_POST['bmc_silos_info_code']);
            if (!empty($modelData)) {
                $data['status'] = 'success';
            }
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return ['data' => $data, 'modelData' => $modelData];
    }

}
