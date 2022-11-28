<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblMilkReject;
use app\modules\collection\models\TblMilkRejectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\collection\models\TblMilkRejectHistory;
use app\modules\collection\models\TblBmcCollection;
use app\modules\collection\models\TblBmcCollectionHistory;

/**
 * TblMilkRejectController implements the CRUD actions for TblMilkReject model.
 */
class TblMilkRejectController extends \app\controllers\ChildController {

    /**
     * Lists all TblMilkReject models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMilkRejectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMilkReject model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblMilkReject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblMilkReject();
        if ($this->model->load(Yii::$app->request->post())) {
            $this->setModel($this->model);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Milk Reject', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('create', [
                    'model' => $this->model
        ]);
    }

    /**
     * Updates an existing TblMilkReject model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        if (Yii::$app->request->post()) {
            $master = [];
            $historyModel = new TblMilkRejectHistory();
            Yii::$app->operation->history($this->model, $historyModel, 'UPDATE');
            $master[] = $historyModel;
            $this->model->load(Yii::$app->request->post());
            $this->setModel($this->model);
            $master[] = $this->model;
            $transaction = $this->generalModel->saveTransaction($master, ['Milk Reject', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        if ($this->model->source_org_type == 'bmc') {
            $this->model->customer_code = $this->model->dest_org_code;
            $this->model->customer_type = $this->model->dest_org_type;
        }
        return $this->render('update', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Deletes an existing TblMilkReject model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblMilkReject model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMilkReject the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMilkReject::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function setModel(&$model) {
        if ($model->source_org_type == 'bmc') {
            $model->source_org_code = $model->bmc_code;
            $model->dest_org_code = $model->customer_code;
            $model->dest_org_type = $model->customer_type;
        } else {
            $model->source_org_code = $model->plant_code;
            $model->dest_org_code = $model->bmc_code;
            $model->dest_org_type = 'bmc';
        }
        $model->sample_no = $this->model->getSampleNo();
        (float) $fat = $model->fat;
        (float) $snf = $model->snf;
        $model->qty_mode = Yii::$app->general->getUnionConfiguration($model->union_code, 'collection_qty_mode', 'BMC');
        (float) $lr1 = Yii::$app->general->getUnionConfiguration($model->union_code, 'clr_constant1', 'BMC');
        (float) $lr2 = Yii::$app->general->getUnionConfiguration($model->union_code, 'clr_constant2', 'BMC');
        $model->clr = ($snf - ($fat * $lr1) - $lr2) * 4;
        $model->date_time_of_collection = !empty($model->date_time_of_collection) ? date('Y-m-d', strtotime($model->date_time_of_collection)) : '';
        $model->date_time_of_collection = $model->date_time_of_collection . ' ' . \Yii::$app->general->getshift($model->shift_code);
    }

    public function actionResponsibilityMapping() {
        $searchModel = new TblMilkRejectSearch();
        $searchModel->scenario = 'responsibilityMapping';
        if (Yii::$app->request->post()) {
            if (!empty(Yii::$app->request->post()['TblMilkRejectSearch'])) {
                $post_data = Yii::$app->request->post()['TblMilkRejectSearch'];
                $saveModel = [];
                foreach ($post_data as $d) {
                    if (!empty($d['rejection_responsibility_code'])) {
                        if ($d['collection_type'] == 'bmc_rejection') {
                            $model = TblMilkReject::findOne($d['collection_code']);
                            $historyModel = new TblMilkRejectHistory();
                        } else {
                            $model = TblBmcCollection::findOne($d['collection_code']);
                            $historyModel = new TblBmcCollectionHistory();
                        }
                        if (!empty($model)) {
                            $old_resp = $model->rejection_responsibility_code;
                            $new_resp = $d['rejection_responsibility_code'];
                            if ($old_resp != $new_resp) {
                                Yii::$app->operation->history($model, $historyModel, 'UPDATE');
                                $saveModel[] = $historyModel;
                                $model->rejection_responsibility_code = $new_resp;
                                $model->scenario = 'rejectRespMap';
                                $saveModel[] = $model;
                            }
                        }
                    }
                }
                if (!empty($saveModel)) {
                    $transaction = $this->generalModel->saveTransaction($saveModel, ['Rejection Responsibility Mapping', 'edit']);
                }
            }
        }
        $dataProvider = $searchModel->responsibilityMapping(Yii::$app->request->queryParams);
        return $this->render('responsibility_mapping', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
