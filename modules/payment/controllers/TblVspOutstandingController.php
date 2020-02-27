<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblVspOutstanding;
use app\modules\payment\models\TblVspOutstandingSearch;
use yii\web\NotFoundHttpException;
use app\modules\payment\models\TblVspOutstandingHistory;

/**
 * TblVspOutstandingController implements the CRUD actions for TblVspOutstanding model.
 */
class TblVspOutstandingController extends \app\controllers\ChildController {

    /**
     * Lists all TblVspOutstanding models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVspOutstandingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblVspOutstanding model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblVspOutstanding();
        $this->viewFile = 'create';
        $this->model->scenario = 'createPortal';
        if ($this->model->load(Yii::$app->request->post())) {
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Vsp Outstanding', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblVspOutstanding model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $this->model->scenario = 'createPortal';
        if (Yii::$app->request->post()) {
            $master = [];
            $historyModel = new TblVspOutstandingHistory();
            Yii::$app->operation->history($this->model, $historyModel, 'UPDATE');
            $master[] = $historyModel;
            $this->model->load(Yii::$app->request->post());
            $master[] = $this->model;
            $transaction = $this->generalModel->saveTransaction($master, ['Vsp Outstanding', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }

        return $this->customRender();
    }

    /**
     * Deletes an existing TblVspOutstanding model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblVspOutstanding model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVspOutstanding the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVspOutstanding::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
