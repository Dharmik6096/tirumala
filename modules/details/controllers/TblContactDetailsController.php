<?php

namespace app\modules\details\controllers;

use Yii;
use app\modules\details\models\TblContactDetails;
use app\modules\details\models\TblContactDetailsSearch;
use app\modules\details\models\TblContactDetailsHistory;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\usermanagement\models\User;

/**
 * TblContactDetailsController implements the CRUD actions for TblContactDetails model.
 */
class TblContactDetailsController extends \app\controllers\ChildController {

    public $freeAccessActions = ['get-contact-details', 'contact-details-list'];

    /**
     * Lists all TblContactDetails models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblContactDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblContactDetails model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblContactDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($module, $id, $form_validation_type = 'default') {
        $this->model = new TblContactDetails();
        $this->viewFile = 'create';
        $this->model->form_validation_type = $form_validation_type;
        $modelSave = [];
        if ($this->model->load(Yii::$app->request->post())) {
            $update = FALSE;
            if (!empty(Yii::$app->request->post()['TblContactDetails']['detail_code']) && !empty($existingModel = TblContactDetails::findOne(Yii::$app->request->post()['TblContactDetails']['detail_code']))) {
                $this->model = $existingModel;
                $historyModel = new TblContactDetailsHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $modelSave[] = $historyModel;
                $this->model->load(Yii::$app->request->post());
                $this->model->is_contact_verified = 0;
                $update = TRUE;
            }
            if (!$update) {
                $this->model->setModel($module, $id, 0);
            }
            $this->model->from_date = !empty($this->model->from_date) ? date('Y-m-d', strtotime($this->model->from_date)) : NULL;
            $this->model->to_date = !empty($this->model->to_date) ? date('Y-m-d', strtotime($this->model->to_date)) : NULL;
            $modelSave[] = $this->model;
            $transaction = $this->generalModel->saveTransaction($modelSave, ['Contact Details', ($update) ? 'edit' : 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender($form_validation_type);
    }

    protected function customRender($form_validation_type = 'default') {
        $request = Yii::$app->request->queryParams;
        $searchModel = new TblContactDetailsSearch();
        $searchModel->module_name = $request['module'];
        $searchModel->module_code = $request['id'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render($this->viewFile, ['model' => $this->model, 'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider, 'module' => $request['module'], 'id' => $request['id'], 'dist' => '', 'form_validation_type' => $form_validation_type]);
    }

    protected function customRedirect() {
        return $this->redirect(Url::previous());
    }

    /**
     * Updates an existing TblContactDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblContactDetailsHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Contact Details', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblContactDetails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete() {
        $valueOut = $this->generalModel->callSp('sp_delete_master_geo', ['tbl_contact_details', Yii::$app->request->post('id'), 'detail_code']);
        if ($valueOut == 0) {
            $this->model = $this->findModel(Yii::$app->request->post('id'));
            $historyModel = new TblContactDetailsHistory();
            Yii::$app->operation->history($this->model, $historyModel, DELETE);
            $record = $this->generalModel->deleteTransaction([$this->model, $historyModel]);
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted since it is in use by the system.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    /**
     * Finds the TblContactDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblContactDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblContactDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDeactivate($id) {
        $this->model = $this->findModel($id);
        $contactModel = new TblContactDetailsHistory();
        $userModel = new user();
        $userModel->mobile_no = $this->model->mobile_no;
        $UserRecords = $userModel->getLoginDetails();
        $allow_app_login = !empty($UserRecords) ? $UserRecords->allow_app_login : '';
        if ($allow_app_login == 1) {
            $UserRecords->username = Yii::$app->general->getUserName($UserRecords->username);
            $UserRecords->login_type = isset($UserRecords->login_type) ? (!empty(Yii::$app->dropdown->getRecords('user_login_type')['data'][$UserRecords->login_type]) ? Yii::$app->dropdown->getRecords('user_login_type')['data'][$UserRecords->login_type] : '') : '';
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'error',
                'message' => "Could Not Deactivate {$this->model->mobile_no} <br> Active User Available: {$UserRecords->username} - {$UserRecords->user_code} - {$UserRecords->login_type}",
            ]);
        } else {
            Yii::$app->operation->history($this->model, $contactModel, UPDATE);
            $this->model->is_active = 0;
            $this->model->is_default = 0;
            $this->model->is_contact_verified = 0;
            $transaction = $this->generalModel->saveTransaction([$this->model, $contactModel], ['Contact Details', 'edit']);
            if ($transaction !== FALSE) {
                Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                    'message' => 'Contact deactivated successfully.']);
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => 'Could not deactivate. Please try again.']);
            }
        }
        $this->redirect(Url::previous());
    }

    public function actionActivate($id) {
        $this->model = $this->findModel($id);
        $contactModel = new TblContactDetailsHistory();
        Yii::$app->operation->history($this->model, $contactModel, UPDATE);
        $this->model->is_active = 1;
        $ContactDetailsRecord = $this->model->getContactDetailsRecord();
        if (!empty($ContactDetailsRecord)) {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => 'Already Activated In Other ' . $ContactDetailsRecord->module_name . ' - ' . $ContactDetailsRecord->module_code . '.']);
        } else {
            $transaction = $this->generalModel->saveTransaction([$this->model, $contactModel], ['Contact Details', 'edit']);
            if ($transaction == 'customRedirect') {
                Yii::$app->getSession()->setFlash('success', ['type' => 'success', 'message' => 'Contact activated successfully.']);
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'success', 'message' => 'Could not Activate. Please try again.']);
            }
        }
        $this->redirect(Url::previous());
    }

    public function actionSetDefault($id) {
        $this->model = $this->findModel($id);
        $historyModel = new TblContactDetailsHistory();
        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
        $moduleName = $this->model->module_name;
        $modulecode = $this->model->module_code;
        $contactDetail = TblContactDetails::find()
                ->where([
                    'module_name' => $moduleName,
                    'module_code' => $modulecode,
                    'is_default' => 1,
                ])
                ->all();
        if (!empty($contactDetail)) {
            foreach ($contactDetail as $detail) {
                $detail->is_default = 0;
                $detail->is_contact_verified = 0;
                $detail->save(TRUE, FALSE);
            }
        }
        $this->model->is_default = 1;
        $this->model->is_contact_verified = 0;

        $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Contact Details', 'edit']);
        if ($transaction !== FALSE) {
            Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                'message' => 'Detail Set as default.']);
        } else {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => 'Could not set as default Detail. Please try again.']);
        }
        $this->redirect(Url::previous());
    }

    public function actionUpdateContact() {
        $data = [];
        $data['status'] = 'error';
        $data['message'] = '';
        $modelData = [];
        if (!empty($_POST['detail_code'])) {
            $modelData = $this->findModel($_POST['detail_code']);
            if (!empty($modelData)) {
                $data['status'] = 'success';
            }
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return ['data' => $data, 'modelData' => $modelData];
    }

    public function actionGetContactDetails() {
        $data = [];
        $data['contact_person'] = '';
        $data['mobile_no'] = '';
        if (!empty($_POST)) {
            $model = new TblContactDetails();
            $model->setAttributes($_POST);
            $modelData = $model->getContactDetails();
            if (!empty($modelData)) {
                $name = $modelData->firstname;
                $name .= !empty($modelData->lastname) ? $modelData->lastname : '';
                $name .= !empty($modelData->surname) ? $modelData->surname : '';
                $data['contact_person'] = $name;
                $data['mobile_no'] = $modelData->mobile_no;
            }
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($data);
    }

    public function actionContactDetails(){
        $response = [];
        $module_code = Yii::$app->request->post()['module_code'];
        $module_name = Yii::$app->request->post()['module_name'];
        $contactDetail = TblContactDetails::find()->where(['module_code' => $module_code, 'module_name' => $module_name, 'is_default' => 1, 'is_active' => 1])->one();
        if (!empty($contactDetail)) {
            $response['status'] = 'success';
            $response['data'] = $contactDetail;
        }
        return Json::encode($response);
    }
    
    public function actionIndexOther() {
        $searchModel = new TblContactDetailsSearch();
        $dataProvider = $searchModel->searchcontact(Yii::$app->request->queryParams);

        return $this->render('index_other', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionContactDetailsList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $this->model = new TblContactDetails();
                $data = $this->model->contactDetailList($parents[0]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

}
