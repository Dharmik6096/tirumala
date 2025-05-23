<?php

namespace app\modules\insurance\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\insurance\models\TblInsuranceMaster;
use app\modules\insurance\models\TblInsuranceMasterHistory;
use app\modules\insurance\models\TblInsuranceMasterSearch;
use yii\web\NotFoundHttpException;

/**
 * TblInsuranceMasterController implements the CRUD actions for TblInsuranceMaster model.
 */
class TblInsuranceMasterController extends ChildController {

    /**
     * Lists all TblInsuranceMaster models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblInsuranceMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblInsuranceMaster model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblInsuranceMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblInsuranceMaster();
        $this->viewFile = 'create';
        if (Yii::$app->request->post()) {
            if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
                $this->setModel();
                $transaction = $this->generalModel->saveTransaction([$this->model], ['Insurance Master', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblInsuranceMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            $historyModel = new TblInsuranceMasterHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->scenario = 'update';
            if ($this->model->validate()) {
                $this->setModel();
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Insurance Master', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblInsuranceMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblInsuranceMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblInsuranceMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblInsuranceMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function setModel() {
        $this->model->insurance_start_date = ($this->model->insurance_start_date == '') ? null : Yii::$app->formatter->asDate($this->model->insurance_start_date, DATE_FORMAT);
        $this->model->insurance_end_date = ($this->model->insurance_end_date == '') ? null : Yii::$app->formatter->asDate($this->model->insurance_end_date, DATE_FORMAT);
        $this->model->dcs_edit_start_date = ($this->model->dcs_edit_start_date == '') ? null : Yii::$app->formatter->asDate($this->model->dcs_edit_start_date, DATE_FORMAT);
        $this->model->dcs_edit_end_date = ($this->model->dcs_edit_end_date == '') ? null : Yii::$app->formatter->asDate($this->model->dcs_edit_end_date, DATE_FORMAT);
        $this->model->insurance_final_date = ($this->model->insurance_final_date == '') ? null : Yii::$app->formatter->asDate($this->model->insurance_final_date, DATE_FORMAT);
    }

}
