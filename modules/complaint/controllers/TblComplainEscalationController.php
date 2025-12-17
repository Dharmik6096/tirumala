<?php

namespace app\modules\complaint\controllers;

use Yii;
use app\modules\complaint\models\TblComplainEscalation;
use app\modules\complaint\models\TblComplainEscalationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\complaint\models\TblComplainEscalationTxn;
use app\modules\complaint\models\TblComplainEscalationTxnSearch;
use app\modules\complaint\models\TblComplainEscalationHistory;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblComplainEscalationController implements the CRUD actions for TblComplainEscalation model.
 */
class TblComplainEscalationController extends \app\controllers\ChildController {

    /**
     * Lists all TblComplainEscalation models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblComplainEscalationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblComplainEscalation model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblComplainEscalationTxnSearch();
        $searchModel->complain_escalation_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblComplainEscalation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblComplainEscalation();
        $txnModel = new TblComplainEscalationTxn();
        $saveModel = [];
        if (Yii::$app->request->post()) {

            $this->model->load(Yii::$app->request->post());

            $txnData = Yii::$app->request->post()['TblComplainEscalationTxn'];
            unset($txnData['user_type']);
            unset($txnData['department']);
            unset($txnData['escalation_time']);
            unset($txnData['level']);
            $saveModel[] = $this->model;
            foreach ($txnData as $key => $value) {
                $txnModel = new TblComplainEscalationTxn();
                $txnModel->user_type = $value['user_type'];
                $txnModel->department = $value['department'];
                $txnModel->escalation_time = $value['escalation_time'];
                $txnModel->level = $value['level'];
                $saveModel[] = $txnModel;
            }

            if ($this->model->validate()) {
                $auto_key_config['TblComplainEscalationTxn'][] = ['self_key' => 'complain_escalation_code', 'parent_key' => 'complain_escalation_code', 'parent_index' => 0];
                $transaction = $this->generalModel->saveTransactionAutoIncForeignKey($saveModel, ['Complain Escalation', 'create'], $auto_key_config);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return Json::encode($record);
                }
            } else {
                $msg = '';
                foreach ($this->model->getErrors() as $errorkey => $value) {
                    $msg .= $value[0] . '<br/>';
                }
                $record = ['status' => 'success', 'msg' => $msg];
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
                    'txnModel' => $txnModel,
        ]);
    }

    /**
     * Updates an existing TblComplainEscalation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->complain_escalation_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblComplainEscalation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblComplainEscalation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblComplainEscalation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblComplainEscalation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDeactivateEscalation($id) {
        $this->model = $this->findModel($id);
        $historyModel = new TblComplainEscalationHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $this->model->is_active = 0;
        $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Complain Escalation', 'edit']);

        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'Complain Escalation Deactivated Successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'Complain Escalation Not Deactivated.'];
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionActivateEscalation($id) {
        $this->model = $this->findModel($id);
        $historyModel = new TblComplainEscalationHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $this->model->is_active = 1;
        $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Complain Escalation', 'edit']);

        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'Complain Escalation Activated Successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'Complain Escalation Not Activated.'];
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

}
