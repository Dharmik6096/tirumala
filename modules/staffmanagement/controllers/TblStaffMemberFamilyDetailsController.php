<?php

namespace app\modules\staffmanagement\controllers;

use Yii;
use app\modules\staffmanagement\models\TblStaffMemberFamilyDetails;
use app\modules\staffmanagement\models\TblStaffMemberFamilyDetailsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\modules\staffmanagement\models\TblStaffMemberFamilyDetailsHistory;

/**
 * TblStaffMemberFamilyDetailsController implements the CRUD actions for TblStaffMemberFamilyDetails model.
 */
class TblStaffMemberFamilyDetailsController extends \app\controllers\ChildController {

    /**
     * Lists all TblStaffMemberFamilyDetails models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblStaffMemberFamilyDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblStaffMemberFamilyDetails model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblStaffMemberFamilyDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id) {
        $this->model = new TblStaffMemberFamilyDetails();
        $this->viewFile = 'create';
        $modelSave = [];
        if ($this->model->load(Yii::$app->request->post())) {
            $update = FALSE;
            if (!empty(Yii::$app->request->post()['TblStaffMemberFamilyDetails']['staff_family_details_code'])) {
                $this->model = $this->findModel(Yii::$app->request->post()['TblStaffMemberFamilyDetails']['staff_family_details_code']);
                $historyModel = new TblStaffMemberFamilyDetailsHistory();
                Yii::$app->operation->history($this->model, $historyModel, 'UPDATE');
                $modelSave[] = $historyModel;
                $this->model->load(Yii::$app->request->post());
                $update = TRUE;
            }
            if (!$update) {
                $this->model->staff_member_code = $id;
                $this->model->union_code = Yii::$app->general->getforeignkey($this->model->staffMemberCode, 'union_code');
            }
            $this->model->birth_date = empty($this->model->birth_date) ? NULL : $this->model->birth_date;
            $modelSave[] = $this->model;
            $transaction = $this->generalModel->saveTransaction($modelSave, ['Family Detail', ($update) ? 'edit' : 'create']);

            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    protected function customRender() {
        $request = Yii::$app->request->queryParams;
        $searchModel = new TblStaffMemberFamilyDetailsSearch();
        $searchModel->staff_member_code = $request['id'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $isaction = TRUE;
        return $this->render($this->viewFile, ['model' => $this->model, 'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider, 'id' => $request['id'], 'dist' => '', 'isaction' => $isaction]);
    }

    protected function customRedirect() {
        return $this->redirect(Url::previous());
    }

    /**
     * Updates an existing TblStaffMemberFamilyDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->staff_family_details_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblStaffMemberFamilyDetails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblStaffMemberFamilyDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblStaffMemberFamilyDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblStaffMemberFamilyDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUpdateFamily() {
        $data = [];
        $data['status'] = 'error';
        $data['message'] = '';
        $modelData = [];
        if (!empty($_POST['staff_family_details_code'])) {
            $modelData = $this->findModel($_POST['staff_family_details_code']);
            if (!empty($modelData)) {
                $data['status'] = 'success';
            }
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return ['data' => $data, 'modelData' => $modelData];
    }

}
