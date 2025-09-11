<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblMilkCollectionSummary;
use app\modules\collection\models\TblMilkCollectionSummarySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\collection\models\TblMilkCollectionSearch;
use app\modules\collection\models\TblMilkCollection;
use app\modules\collection\models\TblMilkCollectionHistory;
use app\modules\collection\models\TblMilkCollectionSummaryHistory;
use yii\helpers\Url;

/**
 * TblMilkCollectionSummaryController implements the CRUD actions for TblMilkCollectionSummary model.
 */
class TblMilkCollectionSummaryController extends \app\controllers\ChildController {

    /**
     * Lists all TblMilkCollectionSummary models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMilkCollectionSummarySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMilkCollectionSummary model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblMilkCollectionSummary model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblMilkCollectionSummary();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->milk_collection_summary_code = Yii::$app->general->getPrimaryCode($this->model);
            $this->model->date_time_of_collection = ($this->model->date_time_of_collection) ? Yii::$app->formatter->asDate($this->model->date_time_of_collection, DATE_FORMAT) : '';
            $this->model->date_time_of_collection = $this->model->date_time_of_collection . ' ' . \Yii::$app->general->getshift($this->model->shift_code);
            $this->model->data_inserted_from = 'PORTAL';
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Milk Collection Summary', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Updates an existing TblMilkCollectionSummary model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblMilkCollectionSummaryHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Milk Collection Summary', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Finds the TblMilkCollectionSummary model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMilkCollectionSummary the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMilkCollectionSummary::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionRepushDataView($id) {
        $model = $this->findModel($id);
        $searchModel = new TblMilkCollectionSearch();
        $searchModel->dcs_code = $model->dcs_code;
        $searchModel->date_time_of_collection = $model->date_time_of_collection;
        $searchModel->shift_code = $model->shift_code;
        $dataProvider = $searchModel->repushsearch(Yii::$app->request->queryParams);
        $searchModel->scenario = 'deleteMilkCollection';
        if (Yii::$app->request->post()) {

            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $message = 'Data Repush';
                $type = 'edit';
                $selectedcodes = empty(Yii::$app->request->post('selection')) ? [] : Yii::$app->request->post('selection');
                $where = [];
                foreach ($selectedcodes as $code) {
                    $where['milk_collection_code'] = $code;
                    $existData = TblMilkCollection::find()->where($where)->one();
                    if (!empty($existData)) {
                        $historyModel = new TblMilkCollectionHistory();
                        Yii::$app->operation->history($existData, $historyModel, 'REPUSH');
                        $saveModel[] = $historyModel;
                        $existData->data_post_status = 0;
                        $saveModel[] = $existData;
                        $message = 'Data Repush';
                        $type = 'edit';
                    }
                }
                $transaction = $this->generalModel->saveTransaction($saveModel, [$message, $type]);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['repush-bulk-data']);
                }
            }
        }

        return $this->render('repush_view', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRepushBulkData() {
        $searchModel = new TblMilkCollectionSummarySearch();
        $searchModel->scenario = 'listSearch';
        $searchModel->load(Yii::$app->request->post());
        $dataProvider = $searchModel->searchrepush(Yii::$app->request->queryParams);
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $selectedcodes = empty(Yii::$app->request->post('selection')) ? [] : Yii::$app->request->post('selection');
                $matchCount = 0;
                foreach ($selectedcodes as $code) {
                    $model = $this->findModel($code);
                    $historyModel = new TblMilkCollectionSummaryHistory();
                    Yii::$app->operation->history($model, $historyModel, UPDATE);
                    $model->data_post_status = 0;
                    $model->resp_desc = null;
                    $model->resp_status = null;
                    $model->response_datetime = null;
                    $model->pick_datetime = null;
                    $milkCollModel = new TblMilkCollection();
                    $collectionData = $milkCollModel->getCollectionSummaryData($model->dcs_code, $model->shift_code, $model->date_time_of_collection);
                    if (!empty($collectionData)) {
                        if (count($collectionData) == $model->sample_count) {
                            $matchCount++;
                            $saveModel[] = $model;
                            $saveModel[] = $historyModel;
                            foreach ($collectionData as $data) {
                                $historyModelMilkColl = new TblMilkCollectionHistory();
                                Yii::$app->operation->history($data, $historyModelMilkColl, 'REPUSH');
                                $saveModel[] = $historyModelMilkColl;
                                $data->data_post_status = 0;
                                $data->resp_desc = null;
                                $data->resp_status = null;
                                $data->response_datetime = null;
                                $data->picked_datetime = null;
                                $saveModel[] = $data;
                            }
                        }
                    }
                }
                $transaction = $this->generalModel->saveTransaction($saveModel, ['Milk Collection Summary Re-Push', 'edit']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['repush-bulk-data']);
                }
            }
        }
        return $this->render('_repush_data', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
