<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblWeightCollection;
use app\modules\collection\models\TblWeightCollectionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\collection\models\TblQualityCollection;
use app\modules\collection\models\TblQualityCollectionHistory;
use app\modules\collection\models\TblWeightCollectionHistory;
use app\modules\collection\models\TblBmcCollection;
use app\modules\collection\models\TblBmcCollectionHistory;

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
            $this->model->date_time_of_collection = date('Y-m-d', strtotime($this->model->date_time_of_collection)) . ' ' . \Yii::$app->general->getshift($this->model->shift_code);
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

    public function actionDeleteBulkCollection() {
        $searchModel = new TblWeightCollectionSearch();
        $dataProvider = $searchModel->deletesearch(Yii::$app->request->queryParams);
        $searchModel->scenario = 'delete';
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $deleteModel = [];
                $deletedata = Yii::$app->request->post('selection');
                $codes = empty(Yii::$app->request->post('selection')) ? [] : Yii::$app->request->post('selection');
                $where = [];
                $wheres = [];
                foreach ($codes as $code) {
                    $where['uuid'] = $code;
                    $existData = TblWeightCollection::find()->where($where)->one();
                    $historyModel = new TblWeightCollectionHistory();
                    Yii::$app->operation->history($existData, $historyModel, DELETE);
                    $saveModel[] = $historyModel;
                    $deleteModel[] = $existData;

                    $wheres['mcc_plant_code'] = $existData->mcc_plant_code;
                    $wheres['bmc_code'] = $existData->bmc_code;
                    $wheres['date_time_of_collection'] = date('Y-m-d', strtotime($existData->date_time_of_collection)) . ' ' . \Yii::$app->general->getshift($existData->shift_code);
                    $wheres['shift_code'] = $existData->shift_code;
                    $wheres['sample_no'] = $existData->sample_no;
                    $wheres['doc_no'] = $existData->doc_no;

                    $existTesting = TblQualityCollection::find()->where($wheres)->one();
                    if (!empty($existTesting)) {
                        $testinghistoryModel = new TblQualityCollectionHistory();
                        Yii::$app->operation->history($existTesting, $testinghistoryModel, DELETE);
                        $saveModel[] = $testinghistoryModel;
                        $deleteModel[] = $existTesting;
                    }
                    $existColl = TblBmcCollection::find()->where($wheres)->one();
                    if (!empty($existColl)) {
                        $collhistoryModel = new TblBmcCollectionHistory();
                        Yii::$app->operation->history($existColl, $collhistoryModel, DELETE);
                        $saveModel[] = $collhistoryModel;
                        $deleteModel[] = $existColl;
                    }
                    $message = 'BMC Weight Data';
                    $type = 'delete';
                }

                $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, [$message, $type]);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('delete_bulk', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
