<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblDcsSubcenterMisc;
use app\modules\organisation\models\TblDcsSubcenterMiscSearch;
use app\modules\organisation\models\TblDcsSubcenterMiscHistory;
use app\modules\globalmaster\models\TblMiscellaneous;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblDcsSubcenterMiscController implements the CRUD actions for TblDcsSubcenterMisc model.
 */
class TblDcsSubcenterMiscController extends \app\controllers\ChildController {

    /**
     * Lists all TblDcsSubcenterMisc models.
     * @return mixed
     */
    public function actionIndex($id, $name, $type) {
        $model = new TblDcsSubcenterMisc(['scenario' => 'rrrr']);
        $searchModel = new TblDcsSubcenterMiscSearch();
        if($type=='dcs')
            $searchModel->dcs_code = $id;
        else
             $searchModel->subcenter_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblDcsSubcenterMisc model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblDcsSubcenterMisc model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id, $name, $type) {
        $this->viewFile = 'create';

        $this->model = new TblDcsSubcenterMisc();
        if ($this->model->load(Yii::$app->request->post())) {

            $this->model->dcs_miscellaneous_code = $this->model->getCode(Yii::$app->getRequest()->getQueryParam('id'));
            $this->model->description = ucfirst($this->model->description);
            if (Yii::$app->getRequest()->getQueryParam('type') == 'dcs')
                $this->model->dcs_code = Yii::$app->getRequest()->getQueryParam('id');
            else
                $this->model->subcenter_code = Yii::$app->getRequest()->getQueryParam('id');


           $transaction = $this->generalModel->saveTransaction([$this->model], ['society miscellaneous', 'create']);
           if ($transaction !== FALSE) {
                return $this->$transaction();
           }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblDcsSubcenterMisc model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id, $name, $type, $pk) {
        $this->viewFile = 'update';
        $this->model = $this->findModel($pk);

        if ($this->model->load(Yii::$app->request->post())) {

            $historyModel = new TblDcsSubcenterMiscHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->description = ucfirst($this->model->description);

            $transaction =$this->generalModel->saveTransaction([$this->model, $historyModel],['Society miscellaneous', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblDcsSubcenterMisc model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_org', ['tbl_dcs_subcenter_misc','', '', Yii::$app->request->post('id'), 'dcs_miscellaneous_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblDcsSubcenterMiscHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblDcsSubcenterMisc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblDcsSubcenterMisc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDcsSubcenterMisc::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function customRedirect() {
        return $this->redirect(Url::previous());
    }

    protected function customRender() {
        $miscellaneousModel = new TblMiscellaneous();
        $miscellaneousData = $miscellaneousModel->getActiveMiscellaneous();
        return $this->render($this->viewFile, ['model' => $this->model, 'miscellaneous' => $miscellaneousData]);
    }

}
