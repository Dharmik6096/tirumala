<?php

namespace app\modules\welfarescheme\controllers;

use Yii;
use app\modules\welfarescheme\models\TblSchemeApplication;
use app\modules\welfarescheme\models\TblSchemeApplicationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\welfarescheme\models\TblSchemeApplicationHistory;
use app\modules\welfarescheme\models\TblSchemeApplicationDocuments;
use app\modules\welfarescheme\models\TblSchemeApplicationDocumentsHistory;
use app\modules\welfarescheme\models\TblSchemeApplicationDocumentsSearch;
use app\modules\welfarescheme\models\TblSchemeApplicationApproval;
use app\modules\welfarescheme\models\TblSchemeApplicationApprovalSearch;
use app\modules\welfarescheme\models\TblSchemeDocumentMapping;
use app\modules\welfarescheme\models\TblSchemeApprovalStages;
use app\modules\dcsoperation\models\TblMember;
use app\modules\organisation\models\TblDcs;
use app\modules\welfarescheme\models\TblSchemeMaster;
use yii\helpers\Json;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\base\Model;

/**
 * TblSchemeApplicationController implements the CRUD actions for TblSchemeApplication model.
 */
class TblSchemeApplicationController extends \app\controllers\ChildController {

    public $freeAccessActions = ['validate-customer', 'scheme-list'];

    /**
     * Lists all TblSchemeApplication models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblSchemeApplicationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblSchemeApplication model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblSchemeApplication model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblSchemeApplication();
        $model->scenario = 'add_application';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->customer_type != 'MEMBER') {
                $model->dcs_code = NULL;
            }
            $scheme_detail = $model->getSchemeDetail();
            $model->scheme_value = $scheme_detail['scheme_value'];
            $model->min_pouring_day = $scheme_detail['min_pouring_day'];
            $model->min_pouring_qty = $scheme_detail['min_pouring_qty'];
            $model->actual_pouring_day = $scheme_detail['p_day'];
            $model->actual_pouring_qty = $scheme_detail['p_qty'];
            $transaction = $this->generalModel->saveTransaction([$model], ['Scheme Application', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['add-document', 'id' => $model->application_id]);
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing TblSchemeApplication model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $model->scenario = 'add_application';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->application_id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    public function actionAddDocument($id) {
        $model = $this->findModel($id);
        $doc_mapping = TblSchemeDocumentMapping::find()->where(['scheme_id' => $model->scheme_id])->all();
        $doc_model = [];
        foreach ($doc_mapping as $doc) {
            $doc->application_id = $model->application_id;
            $app_doc = $doc->applicationDocument;
            $master_doc = $doc->documentMaster;
            if (empty($app_doc)) {
                $app_doc = new TblSchemeApplicationDocuments;
                $app_doc->application_id = $model->application_id;
                $app_doc->scheme_id = $model->scheme_id;
                $app_doc->doc_id = $doc->doc_id;
            }
            $app_doc->is_mandate = $doc->is_mandate;
            $app_doc->doc_name = $master_doc->doc_name .= ($doc->is_mandate == 1) ? ' *' : '';
            $doc_model[] = $app_doc;
        }
        if (Yii::$app->request->post()) {
            $projec_dir = str_replace('\\', '/', realpath(\Yii::$app->basePath));
            $doc_folder = '/web/welfarescheme/';
            $doc_path = $projec_dir . $doc_folder;
            if (Yii::$app->general->checkDirectory($doc_path)) {
                $error_msg = '';
                $save_model = [];
                foreach ($doc_model as $key => $d) {
                    if (!empty($d->app_doc_id)) {
                        $historyModel = new TblSchemeApplicationDocumentsHistory();
                        Yii::$app->operation->history($d, $historyModel, UPDATE);
                        $save_model[] = $historyModel;
                    }
                }
                Model::loadMultiple($doc_model, Yii::$app->request->post());
                foreach ($doc_model as $key => $d) {
                    $d->file_name = UploadedFile::getInstance($d, '[' . $key . ']file_name');
                    if (!empty($d->file_name)) {
                        $file_name = $model->customer_type . $model->customer_code . '_' . $model->scheme_id . '_' . $model->application_id . '_' . $d->doc_id . '_' . $d->file_name->baseName . '.' . $d->file_name->extension;
                        $d->file_path = $doc_path . $file_name;
                        if (!$d->file_name->saveAs($d->file_path)) {
                            $error_msg .= $d->doc_name . '<br/>';
                        }
                        $d->file_name = $file_name;
                        $save_model[] = $d;
                    } else if ($d->is_mandate == 1) {
                        $error_msg .= $d->doc_name . '<br/>';
                    }
                }
                if (empty($error_msg)) {
                    $approval_stages = $model->approvalStages;
                    foreach ($approval_stages as $stage) {
                        $stage_model = new TblSchemeApplicationApproval();
                        $stage_model->application_id = $model->application_id;
                        $stage_model->scheme_id = $model->scheme_id;
                        $stage_model->level = $stage->level;
                        $stage_model->user_code = $stage->user_code;
                        $stage_model->approval_mode = $stage->approval_mode;
                        $save_model[] = $stage_model;
                    }
                    $model->application_status = 'registered';
                    $save_model[] = $model;
                    $transaction = $this->generalModel->saveTransaction($save_model, ['Scheme Application Registration', 'create']);
                    if ($transaction == 'customRedirect') {
                        return $this->customRedirect();
                    }
                } else {
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                        'message' => 'Please Upload Following Document <br/><br/>' . $error_msg]);
                }
            }
        }
        return $this->render('add_document', [
                    'model' => $model,
                    'doc_model' => $doc_model
        ]);
    }

    public function actionPendingApproval() {
        $searchModel = new TblSchemeApplicationSearch();
        $dataProvider = $searchModel->pendingApproval(Yii::$app->request->queryParams);

        return $this->render('pending_approval', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionApproveApplication($id, $app_approval_id) {
        $model = TblSchemeApplicationApproval::findOne($app_approval_id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model_save = [];
            $model->status_date = date('Y-m-d');
            $model->status_by = \Yii::$app->user->identity->user_code;
            $model_save[] = $model;
            if ($model->approval_mode == 'flexi') {
                //find all pending approval for same level and update
                // update application status 
            }
            $transaction = $this->generalModel->saveTransaction($model_save, ['Scheme Application Approval', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['pending-approval']);
            }
        }
        return $this->render('approve_application', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TblSchemeApplication model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblSchemeApplication model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblSchemeApplication the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblSchemeApplication::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionValidateCustomer() {
        $response = [];
        $response['status'] = 'error';
        $bmc = Yii::$app->request->post('bmc_code');
        $customer_code = Yii::$app->request->post('customer_code');
        $dcs_code = Yii::$app->request->post('dcsCode');
        $union = Yii::$app->request->post('union_code');
        $type = Yii::$app->request->post('customer_type');
        $mcc = Yii::$app->request->post('mcc');
        $plant = Yii::$app->request->post('plant');
        $date = Yii::$app->request->post('date');
        $scheme_id = Yii::$app->request->post('schemeId');
        $headModel = new TblSchemeApplication();
        $headModel->union_code = $union;
        $headModel->customer_type = $type;
        $headModel->plant_code = $plant;
        $headModel->mcc_plant_code = $mcc;
        $headModel->bmc_code = $bmc;
        $headModel->scheme_id = $scheme_id;
        $headModel->application_date = $date;
        if (!empty($type) && strtolower($type) == 'member') {
            $model = new TblMember();
            $memberCode = str_pad($customer_code, 4, '0', STR_PAD_LEFT);
            $data = $model->validateMember($dcs_code, $memberCode);
            $headModel->dcs_code = $dcs_code;
            $headModel->customer_code = !empty($data) ? $data->member_code : '';
            if (!empty($data)) {
                $detail = Yii::$app->general->validateDeactivateDcs($headModel, $headModel->application_date, '', TRUE, $headModel->customer_code);
                if ($detail === false) {
                    $data = '';
                }
            }
        } else if (!empty($type) && strtolower($type) != 'dcs') {
            $headModel->customer_code = $customer_code;
            $data = Yii::$app->general->validateCustomerCode($headModel);
            $headModel->customer_code = $data;
        } else {
            $model = new TblDcs();
            $data = $model->validDcs($customer_code, $bmc);
            $headModel->dcs_code = $data;
            $detail = Yii::$app->general->validateDeactivateDcs($headModel, $headModel->application_date);
            if ($detail === false) {
                $data = '';
            }
            $headModel->customer_code = $data;
        }
        if (!empty($data)) {
            $name = Yii::$app->general->getCustomer($headModel, $type);
            $response['status'] = 'success';
            $response['customer_name'] = $name;
            $response['customer_code'] = $headModel->customer_code;
            $scheme_detail = $headModel->getSchemeDetail();
            $response['scheme_value'] = $scheme_detail['scheme_value'];
            $response['min_pouring_day'] = $scheme_detail['min_pouring_day'];
            $response['min_pouring_qty'] = $scheme_detail['min_pouring_qty'];
            $response['actual_pouring_qty'] = $scheme_detail['p_qty'];
            $response['actual_pouring_day'] = $scheme_detail['p_day'];
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($response);
    }

    public function actionSchemeList() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if (!empty($parents[0]) && !empty($parents[1])) {
                $scheme = new TblSchemeMaster();
                $data = $scheme->getUnionSchemeList($parents[0], $parents[1]);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $key, 'name' => $val);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

}
