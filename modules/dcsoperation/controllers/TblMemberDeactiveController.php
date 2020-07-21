<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblMemberDeactive;
use app\modules\dcsoperation\models\TblMemberDeactiveSearch;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;
use kartik\widgets\ActiveForm;
use app\modules\dcsoperation\models\TblMember;

/**
 * TblMemberDeactiveController implements the CRUD actions for TblMemberDeactive model.
 */
class TblMemberDeactiveController extends \app\controllers\ChildController {

    /**
     * Lists all TblMemberDeactive models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMemberDeactiveSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMemberDeactive model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblMemberDeactiveSearch();
        $searchModel->member_code = $id;
        $dataProvider = $searchModel->viewsearch(Yii::$app->request->queryParams);

        $model = new TblMember();
        return $this->render('view', [
                    'model' => $model->findOne($id),
                    'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblMemberDeactive model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblMemberDeactive();
        $this->viewFile = 'create';
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->model->member_deactive_code = (string) Yii::$app->general->getCodeAutoIncrement($this->model);
            $this->model->from_date = !empty($this->model->from_date) ? date('Y-m-d H:i:s', strtotime($this->model->from_date)) : NULL;

            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model], ['Member Deactivated', 'create']);
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
     * Updates an existing TblMemberDeactive model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->member_deactive_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblMemberDeactive model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblMemberDeactive model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblMemberDeactive the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMemberDeactive::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionActivateMember($member_deactive_code = '') {
        $model = new TblMemberDeactive();
        $model->scenario = 'activeMember';
        $model->member_deactive_code = $member_deactive_code;
        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $ActiveModel = $this->findModel($model->member_deactive_code);
            $ActiveModel->to_date = !empty($model->to_date) ? date('Y-m-d H:i:s', strtotime($model->to_date)) : NULL;
            $ActiveModel->scenario = 'activeMember';
            if ($ActiveModel->validate()) {
                $transaction = $this->generalModel->saveTransaction([$ActiveModel], ['Member Activated', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'msg' => $msg];
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($ActiveModel));
            }
        }
        return $this->renderAjax('_search', [
                    'model' => $model,
        ]);
    }

}
