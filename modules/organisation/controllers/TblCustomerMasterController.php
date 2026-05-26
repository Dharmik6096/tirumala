<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblCustomerMasterSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\organisation\models\TblCustomerMasterHistory;
use yii\helpers\Json;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\details\models\TblBankDetails;
use app\modules\details\models\TblContactDetails;
use app\modules\details\models\TblBankDetailsSearch;
use app\modules\details\models\TblContactDetailsSearch;
use app\modules\organisation\models\TblCustomerDeactiveSearch;
use yii\web\Response;
use app\modules\document\controllers\TblAttachmentController;

/**
 * TblCustomerMasterController implements the CRUD actions for TblCustomerMaster model.
 */
class TblCustomerMasterController extends \app\controllers\ChildController {

    public $freeAccessActions = ['customer-type', 'customer-code-list', 'get-customer-type', 'excode-prefix', 'activate-customer-code-list'];
    public $bankDetails;
    public $contactDetails;

    /**
     * Lists all TblCustomerMaster models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblCustomerMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblCustomerMaster model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {

        $bsearchModel = new TblBankDetailsSearch();
        $bsearchModel->module_name = 'customer';
        $bsearchModel->module_code = $id;
        $bdataProvider = $bsearchModel->search(Yii::$app->request->queryParams);
        $csearchModel = new TblContactDetailsSearch();
        $csearchModel->module_name = 'customer';
        $csearchModel->module_code = $id;
        $cdataProvider = $csearchModel->search(Yii::$app->request->queryParams);
        $dsearchModel = new TblCustomerDeactiveSearch();
        $dsearchModel->customer_code = $id;
        $ddataProvider = $dsearchModel->viewsearch(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'bdataProvider' => $bdataProvider, 'bsearchModel' => $bsearchModel,
                    'cdataProvider' => $cdataProvider, 'csearchModel' => $csearchModel,
                    'dsearchModel' => $dsearchModel, 'ddataProvider' => $ddataProvider,
        ]);
    }

    /**
     * Creates a new TblCustomerMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblCustomerMaster();
        $this->viewFile = 'create';
        $this->bankDetails = new TblBankDetails();
        $this->contactDetails = new TblContactDetails();
        $this->contactDetails->form_validation_type = 'customer-create';
        $this->contactDetails->scenario = 'additional';
        $this->model->scenario = 'createFront';
        if ($this->model->load(Yii::$app->request->post())) {
            $exCode = $this->model->customer_code_ex;
            $this->model->customer_code_ex = $this->model->prefix . $exCode;
            $this->model->customer_code = $this->model->getCode();
//            $this->model->customer_code_ex = $this->model->getCodeEx();
            $this->model->x_col1 = $this->model->same_milk_type . '#' . $this->model->diff_milk_type;
            $mapList = [];
            $this->bankDetails->load(Yii::$app->request->post());
            $errMsg = '';
            $bankValidate = 1;
            if (!empty($this->bankDetails->bank_code)) {
                $this->bankDetails->setModel('customer', $this->model->customer_code);
                $this->bankDetails->scenario = 'bank_selected';
                array_push($mapList, $this->bankDetails);

                $bankValidate = Yii::$app->warning->codeWarningBankAc($this->bankDetails, 'warning');
            }
            $this->contactDetails->load(Yii::$app->request->post());
            if (!empty($this->contactDetails->mobile_no)) {
                $this->contactDetails->setModel('customer', $this->model->customer_code);
                array_push($mapList, $this->contactDetails);
            }
            if ($bankValidate == 1 && empty($this->model->getErrors())) {
                $this->model->customer_code_ex = !empty($this->model->prefix . $exCode) ? $this->model->prefix . $exCode : $this->model->customer_code_ex;
                $transaction = $this->generalModel->saveTransaction([$this->model], $mapList, ['Customer Master', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->{$transaction}();
                }
            }
            $this->model->customer_code_ex = $exCode;
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblCustomerMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $this->model->scenario = 'updateFront';
        $x_col1 = explode('#', $this->model->x_col1);
        $prefix = Yii::$app->general->getforeignkey($this->model->customerTypePre, 'code_prefix');
        $exCode = str_replace($prefix, '', $this->model->customer_code_ex);
        if ($exCode != $this->model->customer_code_ex) {
            $this->model->prefix = $prefix;
            $this->model->customer_code_ex = $exCode;
        }
        if (isset($x_col1)) {
            if (isset($x_col1[0]) && isset($x_col1[1])) {
                $this->model->same_milk_type = $x_col1[0];
                $this->model->diff_milk_type = $x_col1[1];
            }
        }
        if (Yii::$app->request->post()) {
            $historyModel = new TblCustomerMasterHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $exCode = $this->model->customer_code_ex;
            $this->model->customer_code_ex = $prefix . $exCode;
            $this->model->x_col1 = $this->model->same_milk_type . '#' . $this->model->diff_milk_type;
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Customer Master', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
            $this->model->customer_code_ex = $exCode;
        }
        return $this->customRender();
    }

    /**
     * Deletes an existing TblCustomerMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblCustomerMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblCustomerMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblCustomerMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCustomerType() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0])) {
                $routes = new TblCustomerMaster();
                $bmc = $parents[0];
                $data = $routes->customerType($bmc);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionCustomerCodeList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[1])) {
                if (strtolower($parents[1]) == 'dcs') {
                    $dateFilter = !empty($parents[2]) ? $parents[2] : '';
                    $mccs = new TblDcs();
                    $data = $mccs->getBMCDCSList($parents[0], 'TRUE', '', $dateFilter);
                } else {
                    $model = new TblCustomerMaster();
                    $data = $model->getCustomerCodeList($parents[0], $parents[1]);
                }
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
                return;
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionActivateCustomerCodeList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[1]) && !empty($parents[2]) && !empty($parents[3])) {
                if (strtolower($parents[0]) == 'dcs') {
                    $mccs = new TblDcs();
                    $data = $mccs->getBMCDCSList($parents[2], 'TRUE', '', $parents[3]);
                } else {
                    $model = new TblCustomerMaster();
                    $data = $model->getActivateCustomerCodeList($parents[1], $parents[2], $parents[0], $parents[3]);
                }
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionGetCustomerType() {
        $out = null;

        if (isset($_POST['depdrop_parents'])) {
            $value = $_POST['depdrop_parents'];
            $unionCode = $value[0];
            $where = !empty($value[1]) ? (array) json_decode($value[1]) : [];
            $notInType = !empty($value[2]) ? (array) json_decode($value[2]) : [];
            $paymentcycleModel = new TblCustomerType();
            $list = $paymentcycleModel->getCustomerTypes($unionCode, $where, $notInType);
            foreach ($list as $key => $r) {
                $out[] = array('id' => $key,
                    'name' => $r);
            }
            return Json::encode(['output' => $out]);
        }
        return Json::encode(['output' => '', 'selected' => $selected]);
    }

    protected function customRender() {
        return $this->render($this->viewFile, ['model' => $this->model,
                    'bankDetails' => $this->bankDetails,
                    'contactDetails' => $this->contactDetails
        ]);
    }

    public function actionBankDetails($id) {
        $bankDetails = new TblBankDetails();
        $searchModel = new TblBankDetailsSearch();
        $searchModel->module_name = 'customer';
        $searchModel->module_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $modelDcs = $this->findModel($id);
        return $this->render('../../../details/views/tbl-bank-details/create', [
                    'model' => $bankDetails,
                    'id' => $id,
                    'module' => 'customer',
                    'dist' => $modelDcs->district_code,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dist_field' => 'tblcustomermaster-district_code'
        ]);
    }

    public function actionContactDetails($id) {
        $contactDetails = new TblContactDetails();
        $searchModel = new TblContactDetailsSearch();
        $searchModel->module_name = 'customer';
        $searchModel->module_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('../../../details/views/tbl-contact-details/create', [
                    'model' => $contactDetails,
                    'id' => $id,
                    'module' => 'customer',
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

    public function actionExcodePrefix() {
        $response = [];
        $response['status'] = 'error';
        $response['data'] = '';
        $model = new TblCustomerMaster();
        $model->union_code = Yii::$app->request->post('union_code');
        $model->customer_type = Yii::$app->request->post('type');
        $response['status'] = 'success';
        $response['data'] = Yii::$app->general->getforeignkey($model->customerTypePre, 'code_prefix');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($response);
    }

    public function actionImportAttachements() {
        $model = new TblCustomerMaster();
        if (isset($_POST['code'])) {
            $model->customer_code = $_POST['code'];
        }
        $saveModel = [];
        if ($model->load(Yii::$app->request->post())) {
            $files = !empty(Yii::$app->request->post()['TblCustomerMaster']['file_name']) ? Yii::$app->request->post()['TblCustomerMaster']['file_name'] : '';
            Yii::$app->general->setAttachment($saveModel, $files, $model->customer_code, 'TblCustomerMaster');
            $transaction = $this->generalModel->saveTransaction($saveModel, ['Image Uploaded', 'edit']);
            return $this->redirect(['index']);
        }
        return $this->renderAjax('_attachment_upload_popup', ['model' => $model]);
    }

    public function actionImportFile() {
        $path = Yii::$app->basePath . '/web/upload/images/';
        Yii::$app->general->checkDirectory($path, '0777');
        try {
            $file = \yii\web\UploadedFile::getInstanceByName('file');
            $name = date('YmdHis') . rand(1000, 9999) . $file->name;
            if ($file->saveAs($path . $name)) {
                $record = ['status' => 'success', 'filename' => $name, 'msg' => $name];
            } else {
                $record = ['status' => 'error', 'filename' => $name, 'msg' => 'File Not Uploaded Due to Error'];
            }
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        } catch (\Exception $e) {
            $record = ['status' => 'error', 'msg' => 'File Not Uploaded Due to Error'];
            Yii::$app->response->format = trim(Response::FORMAT_JSON);
            return Json::encode($record);
        }
    }

    public function actionCustomerDocumentUpload($id) {
        $model = $this->findModel($id);
        $module_code = $model->customer_code;
        $module_name = 'tbl_customer_master';
        $val = new TblAttachmentController($this->id, $this->module);
        return $val->actiondocumentUpload('customer', $id, $model, $module_code, $module_name);
    }

}
