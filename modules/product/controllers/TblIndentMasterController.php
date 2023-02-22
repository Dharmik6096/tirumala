<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblIndentMaster;
use app\modules\product\models\TblIndentMasterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

/**
 * TblIndentMasterController implements the CRUD actions for TblIndentMaster model.
 */
class TblIndentMasterController extends \app\controllers\ChildController {

    /**
     * Lists all TblIndentMaster models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblIndentMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblIndentMaster model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblIndentMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblIndentMaster();
        $searchModel = new TblIndentMasterSearch();
        $searchModel->attributes = Yii::$app->request->get('TblIndentMaster');
        $dataProvider = $searchModel->createsearch([]);

        $dataProvider->sort = false;
        $this->viewFile = 'create';
        $modelSave = [];
        $message = 'Indent Master';
        $type = 'create';
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->model->indent_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            $this->model->indent_date = Yii::$app->formatter->asDate($this->model->indent_date, DATE_FORMAT) . ' 00:00:00.000000';
            $this->model->customer_code = $this->model->member_code;
            $this->model->customer_type = 'Member';
            $this->model->status = 'pending';
            $this->model->status_by = $this->model->created_by;
            $this->model->status_date = $this->model->created_at;
            if ($this->model->validate()) {
                $modelSave[] = $this->model;
                $transaction = $this->generalModel->saveTransaction($modelSave, [$message, $type]);
                if ($transaction == 'customRedirect') {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'msg' => $msg];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($this->model));
            }
        } else {
            return $this->render('create', [
                        'model' => $this->model,
                        'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
            ]);
        }
        return $this->render('create', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionListGrid() {
        $searchModel = new TblIndentMasterSearch();
        $searchModel->attributes = Yii::$app->request->get('TblIndentMaster');
        $dataProvider = $searchModel->createsearch([]);
        return $this->renderAjax('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    /**
     * Updates an existing TblIndentMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->indent_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblIndentMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblIndentMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblIndentMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblIndentMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
