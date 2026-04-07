<?php

namespace app\modules\syncutility\controllers;

use Yii;
use app\modules\syncutility\models\TblForceSyncRequest;
use app\modules\syncutility\models\TblForceSyncRequestSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblForceSyncRequestController implements the CRUD actions for TblForceSyncRequest model.
 */
class TblForceSyncRequestController extends \app\controllers\ChildController {

    /**
     * Lists all TblForceSyncRequest models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblForceSyncRequestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblForceSyncRequest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblForceSyncRequest();

        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->model->from_datetime = Yii::$app->formatter->asDate($this->model->from_datetime, DATE_FORMAT) . ' ' . Yii::$app->general->getshift($this->model->from_shift);
            $this->model->to_datetime = Yii::$app->formatter->asDate($this->model->to_datetime, DATE_FORMAT) . ' ' . Yii::$app->general->getshift($this->model->to_shift);
            if ($this->model->validate()) {
                $dcsDetails = $this->model->dcsCode;
                if (!empty($dcsDetails)) {
                    $this->model->plant_code = !empty($dcsDetails->plant_code) ? $dcsDetails->plant_code : $this->model->plant_code;
                    $this->model->mcc_plant_code = !empty($dcsDetails->mcc_plant_code) ? $dcsDetails->mcc_plant_code : $this->model->mcc_plant_code;
                    $this->model->bmc_code = !empty($dcsDetails->bmc_code) ? $dcsDetails->bmc_code : $this->model->bmc_code;
                }
                $modelSave = [];
                $modelSave[] = $this->model;
                $transaction = $this->generalModel->saveTransaction($modelSave, ['Request Data', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->{$transaction}();
                }
            }
        }

        return $this->render('create', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Finds the TblForceSyncRequest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblForceSyncRequest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblForceSyncRequest::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
