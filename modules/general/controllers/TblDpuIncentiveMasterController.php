<?php

namespace app\modules\general\controllers;

use Yii;
use app\modules\general\models\TblDpuIncentiveMaster;
use app\modules\general\models\TblDpuIncentiveMasterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\general\models\TblDpuIncentiveMasterHistory;
/**
 * TblDpuIncentiveMasterController implements the CRUD actions for TblDpuIncentiveMaster model.
 */
class TblDpuIncentiveMasterController extends \app\controllers\ChildController {

    /**
     * Lists all TblDpuIncentiveMaster models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDpuIncentiveMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblDpuIncentiveMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblDpuIncentiveMaster();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
//            $this->model->asset_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Dpu Incentive Master', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblDpuIncentiveMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $this->model->bmc_code = Yii::$app->general->getforeignkey($this->model->dcsCode, 'bmc_code');
        $this->model->mcc_plant_code = Yii::$app->general->getforeignkey($this->model->dcsCode, 'mcc_plant_code');
        $this->model->plant_code = Yii::$app->general->getforeignkey($this->model->dcsCode, 'plant_code');

        if (Yii::$app->request->post()) {
            $historyModel = new TblDpuIncentiveMasterHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Dpu Incentive Master', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Finds the TblDpuIncentiveMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblDpuIncentiveMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDpuIncentiveMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
