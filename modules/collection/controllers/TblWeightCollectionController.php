<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblWeightCollection;
use app\modules\collection\models\TblWeightCollectionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblWeightCollectionController implements the CRUD actions for TblWeightCollection model.
 */
class TblWeightCollectionController extends \app\controllers\ChildController {


    /**
     * Lists all TblWeightCollection models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblWeightCollectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblWeightCollection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblWeightCollection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblWeightCollection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCreate() {
        $this->model = new TblWeightCollection();
        $this->model->scenario = 'PortalCreate';
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $datetime = date('Y-m-d H:i:s');
            $this->model->uuid = Yii::$app->general->getUuid();
            $this->model->date_time_of_collection = ($this->model->date_time_of_collection) ? date('Y-m-d', strtotime($this->model->date_time_of_collection)) : '';
            $this->model->date_time_of_collection = $this->model->date_time_of_collection . ' ' . \Yii::$app->general->getshift($this->model->shift_code);
            $dcsData = $this->model->dcsCode;
            $this->model->plant_code = !empty($dcsData->plant_code) && $dcsData->plant_code != 'N/A' ? $dcsData->plant_code : '';
            $this->model->mcc_plant_code = !empty($dcsData->mcc_plant_code) && $dcsData->mcc_plant_code != 'N/A' ? $dcsData->mcc_plant_code : '';
            $this->model->bmc_code = !empty($dcsData->bmc_code) && $dcsData->bmc_code != 'N/A' ? $dcsData->bmc_code : '';
            $this->model->route_code = !empty($dcsData->route_code) && $dcsData->route_code != 'N/A' ? $dcsData->route_code : '';
//            $this->model->bmc_code = $this->model->mcc_code;
//            $this->model->quality_datetime = $datetime;
            $transaction = $this->generalModel->saveTransaction([$this->model], ['BMC Weight Data', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

}
