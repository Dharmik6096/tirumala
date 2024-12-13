<?php

namespace app\modules\organisation\controllers;

use app\controllers\ChildController;
use app\modules\organisation\models\TblOrganizationLatLongApplicabilityHistory;
use Yii;
use app\modules\organisation\models\TblOrganizationLatlong;
use app\modules\organisation\models\TblOrganizationLatlongHistory;
use app\modules\organisation\models\TblOrganizationLatlongSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Response;
use app\modules\organisation\models\TblOrganizationLatLongApplicability;
use app\modules\organisation\models\TblOrganizationLatLongApplicabilitySearch;

/**
 * TblOrganizationLatlongController implements the CRUD actions for TblOrganizationLatlong model.
 */
class TblOrganizationLatlongController extends ChildController
{
    /**
     * Lists all TblOrganizationLatlong models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblOrganizationLatlongSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblOrganizationLatlong model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblOrganizationLatlong model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblOrganizationLatlong();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->scenario = 'create';
            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model], ['Organization lat long', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblOrganizationLatlong model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblOrganizationLatlongHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Organization lat long', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblOrganizationLatlong model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $historyModel = new TblOrganizationLatlongHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionChangeStatus($id) {
        $message = 'Activated';
        $this->model = $this->findModel($id);
        $historyModel = new TblOrganizationLatlongHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        if($this->model->is_active == 0){
            $this->model->is_active = 1;
        } else {
            $message = 'Deactivated';
            $this->model->is_active = 0;
        }
        $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Organization lat long', 'edit']);
        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'Organization lat long '.$message.' Successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'Organization lat long Not '.$message];
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblOrganizationLatlong model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblOrganizationLatlong the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblOrganizationLatlong::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionMapRouteSource($id) {
        $model = new TblOrganizationLatlong();
        $model -> user_code = $id;
        $userOrgData = $model->getUserOrgLatLong();
        $dest = [];
        $modelApplicability = new TblOrganizationLatLongApplicability();
        $modelApplicability -> user_code = $id;
        if(!empty($userOrgData['userDataOrg'])){
            $modelApplicability -> union_code = $userOrgData['userDataOrg'][0] -> union_code;
            foreach($userOrgData['userDataOrg'] as $key => $val){             
                $name =  Yii::$app->general->getField($val, $val->customer_type);
                $dest[$val->customer_code . '-'. $val->organization_latlong_code .'-' . $val->customer_type] = $val->customer_code . ' - ' . $name . ' - ' . Yii::t('app', $val->customer_type);
        }
        }
        $searchModel = new TblOrganizationLatLongApplicabilitySearch();
        $searchModel->user_code = $modelApplicability -> user_code;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
  
        if (Yii::$app->request->post()) {
            $applicable_code = Yii::$app->request->post('TblOrganizationLatLongApplicability')['applicable_code'];
            $user_code = Yii::$app->request->post('TblOrganizationLatLongApplicability')['user_code'];
            if (empty($applicable_code)) {
                $model->addError('applicable_code', 'Please select at least one Source.');
            } else {
                $postData = array_filter($applicable_code);
                $mapping = [];
                $saveModel = [];
                $error_msg = [];
                $validatefalse = 0;
                foreach ($postData as $data) {
                    $modelnew = new TblOrganizationLatLongApplicability();
                    $model->scenario = 'saveLatlongApplicability';
                    $d = explode('-', $data);
                    $modelnew->applicable_code = $d[0];
                    $modelnew->organization_latlong_code = $d[1];
                    $modelnew->applicable_for = $d[2];
                    $modelnew->user_code = $user_code;
                    $modelnew->union_code = $modelApplicability->union_code;
                    if ($modelnew->validate()) {
                        $saveModel[] = $modelnew;
                        
                    }else{
                        $validatefalse++;
                    }
                    
                }
                if($validatefalse == 0){
                    $transaction = $this->generalModel->saveTransaction($saveModel, ['Org Latlong Mapping', 'create']);
                    if ($transaction) {
                        Yii::$app->display->message(true, 'Organization Latlong Mapping', 'create');
                        return $this->redirect(['map-route-source','id' => $id]);
                    }
                }
                
            }
        }

        return $this->render('_map_route_source', [
                    'model' => $modelApplicability, 
                    'destinations' => $dest,
                   'selected' => $userOrgData['selected'],
                    'defaultValue' => '', 
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
        ]);
    }

    public function actionDeleteSource() {
        $saveModel = [];
        $deleteModel = [];
        $this->model = TblOrganizationLatLongApplicability::findOne(Yii::$app->request->post('id'));
        if(!empty($this->model)){
            $historyModel = new TblOrganizationLatLongApplicabilityHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $saveModel[] = $historyModel;
            $deleteModel[] = $this->model;
            $transaction = $this->generalModel->saveDeleteTransaction([], $saveModel, $deleteModel, ['Mapped Orgniation Latlong', 'delete']);
            if ($transaction == 'customRedirect') {
                $this->redirect(['map-route-source', 'id' => $this->model->user_code]);
                // $record = ['status' => 'success', 'msg' => 'Record Deleted Successfully.'];
            }
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        }
       
    }

    
}
