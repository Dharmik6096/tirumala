<?php

namespace app\modules\vendorapi\controllers;

use Yii;
use app\modules\vendorapi\models\TblPreventCollectionData;
use app\modules\vendorapi\models\TblPreventCollectionDataSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblPreventCollectionDataController implements the CRUD actions for TblPreventCollectionData model.
 */
class TblPreventCollectionDataController extends \app\controllers\ChildController {

    /**
     * Lists all TblPreventCollectionData models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblPreventCollectionDataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblPreventCollectionData model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblPreventCollectionData model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblPreventCollectionData();
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $this->model->from_date = !empty($this->model->from_date) ? date('Y-m-d', strtotime($this->model->from_date)) : '';
            $this->model->from_date = $this->model->from_date . ' ' . \Yii::$app->general->getshift($this->model->from_shift);
            $this->model->to_date = !empty($this->model->to_date) ? date('Y-m-d', strtotime($this->model->to_date)) : '';
            $this->model->to_date = $this->model->to_date . ' ' . \Yii::$app->general->getshift($this->model->to_shift);
            $master = [];
            $data = $this->model->getAttributes();
            $mccCodes = $this->model->mcc_plant_code;
            foreach ($mccCodes as $mccCode) {
                if ($mccCode != 'multiselect-all') {
                    $model = new TblPreventCollectionData();
                    $model->setAttributes($data);
                    $model->mcc_plant_code = $mccCode;
                    $master[] = $model;
                }
            }
            $transaction = $this->generalModel->saveTransaction($master, ['Prevent BMC Collection Data', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }

        return $this->render('create', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Updates an existing TblPreventCollectionData model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->prevent_collection_data_id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblPreventCollectionData model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblPreventCollectionData model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblPreventCollectionData the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblPreventCollectionData::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
