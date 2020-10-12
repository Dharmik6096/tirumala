<?php

namespace app\modules\geo\controllers;

use Yii;
use app\modules\geo\models\TblBlocks;
use app\modules\geo\models\TblBlocksSearch;
use app\modules\geo\models\TblBlocksHistory;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\controllers\ChildController;

/**
 * TblBlocksController implements the CRUD actions for TblBlocks model.
 */
class TblBlocksController extends ChildController
{
    
    /**
     * Lists all TblBlocks models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblBlocksSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBlocks model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblBlocks model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->model = new TblBlocks();
        $this->model->scenario = 'add';
        $this->viewFile = 'create';
        $validate = 1;
        
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->block_code = $this->model->getCode();
            $this->model->block_name = ucwords($this->model->block_name);
            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique($this->model, 'block_name', $this->model->block_name);
            if ($validate == 1) {
                $transaction = $this->generalModel->saveTransaction([$this->model], ['block', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblBlocks model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $this->model->scenario = 'add';
        $this->model->state = $this->model->subDistrictCode->districtCode->stateCode->state_code;
        $this->model->district = $this->model->subDistrictCode->districtCode->district_code;
        $validate = 1;

        if (Yii::$app->request->post()) {
            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique($this->model, 'block_name', $_POST['TblBlocks']['block_name']);
            if ($validate == 1) {
                $historyModel = new TblBlocksHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $this->model->load(Yii::$app->request->post());
                $this->model->block_name = ucwords($this->model->block_name);
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['block', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblBlocks model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_block', Yii::$app->request->post('id'), 'block_code']);
        if ($valueOut == 1) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblBlocksHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblBlocks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblBlocks the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblBlocks::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
