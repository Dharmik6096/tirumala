<?php

namespace app\modules\dcsoperation\controllers;

use app\modules\dcsoperation\models\TblMember;
use Yii;
use app\modules\dcsoperation\models\TblMemberProvisional;
use app\modules\dcsoperation\models\TblMemberProvisionalSearch;
use app\modules\dcsoperation\models\TblMemberProvisionalHistory;
use \app\modules\organisation\models\TblDcs;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\webservice\eipl\models\TblEiplAppLogin;
use app\modules\sms\models\TblAlertNotification;
use app\modules\webservice\eipl\models\TblEiplAppLoginHistory;

/**
 * TblMemberProvisionalController implements the CRUD actions for TblMemberProvisional model.
 */
class TblMemberProvisionalController extends \app\controllers\ChildController {


    /**
     * Lists all TblMemberProvisional models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMemberProvisionalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMemberProvisional model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblMemberProvisional model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblMemberProvisional();
        $this->viewFile = 'create';
        $this->setDefaultModel();
        $validate = 1;

        if ($this->model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->post('submitBtn')==='approve'){
                $this->model->is_approved = 1;
                $this->model->approved_at = date('Y-m-d H:i:s');
                $this->model->approved_by = Yii::$app->session['UserCode'];
                if(Yii::$app->session['eiplCode'] == 'NIFPL'){
                    $this->model->scenario = 'approveMember';
                }
            }
            else{
                $this->model->is_approved = 0;
            }
            $this->model->federation_code = $this->model->unionCode->federationCode->federation_code;
            //var_dump($this->model->unionCode->federationCode);exit();
            $this->model->provisional_member_code = Yii::$app->general->getPrimaryCode($this->model);
            $this->model->member_code = $this->model->getCode();
            $this->model->pro_ex_member_code = $this->model->ex_member_code;
            $this->setModel();
            $this->model->upload = 0;
            if ($_POST['warning'] == '0')
                $validate = Yii::$app->warning->unique_member($this->model, ['member_name', 'dcs_code', 'hamlet_code'], [$this->model->member_name, $this->model->dcs_code, $this->model->hamlet_code]);
            if ($validate == 1) {
                $this->model->registration_date = empty($this->model->registration_date) ? NULL : Yii::$app->formatter->asDate($this->model->registration_date, DATE_FORMAT);
                $master_model = [];
                $master_model[] = $this->model;
                if($this->model->is_approved == 1){
                    $tblMember = new TblMember();
                    $tblMember->setAttributes($this->model);
                    $master_model[] = $tblMember;
                }
                $transaction = $this->generalModel->saveTransaction($master_model, ['member provisional', 'create']);
                if ($transaction !== FALSE) {
                    if ($transaction == 'customRedirect') {
                        if (Yii::$app->general->isVendor($this->model->dcs_code, 'BIPL')) {
                            $this->model->generateBiplMemberFiles();
                        }
                    }
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblMemberProvisional model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $validate = 1;
        $this->setModel();

        if (Yii::$app->request->post()) {
            $this->model->federation_code = $this->model->unionCode->federationCode->federation_code;
            if(Yii::$app->request->post('submitBtn')==='approve'){
                $this->model->is_approved = 1;
                $this->model->approved_at = date('Y-m-d H:i:s');
                $this->model->approved_by = Yii::$app->session['UserCode'];
                if(Yii::$app->session['eiplCode'] == 'NIFPL'){
                    $this->model->scenario = 'approveMember';
                }
            }
            else{
                $this->model->is_approved = 0;
            }
            $historyModel = new TblMemberProvisionalHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $historyModel->provisional_member_code = $this->model->provisional_member_code;
            $this->model->load(Yii::$app->request->post());
//            $this->setModel();
            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique_member($this->model, ['member_name', 'dcs_code', 'hamlet_code'], [$this->model->member_name, $this->model->dcs_code, $this->model->hamlet_code]);
            if ($validate == 1) {
                $this->model->registration_date = empty($this->model->registration_date) ? NULL : Yii::$app->formatter->asDate($this->model->registration_date, DATE_FORMAT);
                $master_model = [];
                $master_model[] = $this->model;
                if($this->model->is_approved == 1){
                    $tblMember = new TblMember();
                    $tblMember->setAttributes($this->model);
                    $tblMember->setAttributes($this->model->getAttributes());
                    $master_model[] = $tblMember;
                }
                $master_model[] = $historyModel;
                $transaction = $this->generalModel->saveTransaction($master_model, ['member provisional', 'edit']);
                if ($transaction !== FALSE) {
                    if ($transaction == 'customRedirect') {
                        if (Yii::$app->general->isVendor($this->model->dcs_code, 'BIPL')) {
                            $this->model->generateBiplMemberFiles();
                        }
                    }
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    protected function customRedirect() {
        return $this->redirect(['view', 'id' => $this->model->provisional_member_code]);
    }

    protected function customRender() {
        return $this->render($this->viewFile, ['model' => $this->model,
        ]);
    }

    public function actionProvisionalMembersApproval() {
        $searchModel = new TblMemberProvisionalSearch();
        $dataProvider = $searchModel->searchApprovalData(Yii::$app->request->queryParams);
        $backUrl[] = '/dcsoperation/tbl-member-provisional/provisional-members-approval';
        if (Yii::$app->request->post() && !empty(Yii::$app->request->post('selection'))) {
            $data = Yii::$app->request->post();
            $selection = $data['selection'];
            $master = [];
            $errors = '';
            $success = '';
            $flag = $data['flag'];
            foreach ($selection as $key => $value) {
                $this->model = $this->findModel($value);
                if($this->model->is_approved != 1){
                    $this->model->is_approved = 1;
                    $this->model->approved_at = date('Y-m-d H:i:s');
                    $this->model->approved_by = Yii::$app->session['UserCode'];
                    if(Yii::$app->session['eiplCode'] == 'NIFPL'){
                        $this->model->scenario = 'approveMember';
                    }
                    if ($this->model->validate()){
                        $tblMember = new TblMember;
                        $tblMember->setAttributes($this->model);
                        $tblMember->setAttributes($this->model->getAttributes());
                        $historyModel = new TblMemberProvisionalHistory();
                        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                        $historyModel->provisional_member_code = $this->model->provisional_member_code;
                        $master[] = $tblMember;
                        $master[] = $this->model;
                        $master[] = $historyModel;

                        $success .= $this->model->member_code.' has approved.<\br>'; 
                    }
                    else{
                        $errors .= $this->model->member_code.' has some data missing.<\br>';
                    }
                }
                else{
                    $errors .= 'Something went wrong.<\br>';
                }
            }
            if($errors == ''){
                $transaction = $this->generalModel->saveTransaction($master, ['member provisional approval', 'edit']);
                if ($transaction !== FALSE) {
                    if ($transaction == 'customRedirect') {
                        Yii::$app->getSession()->setFlash('success', ['type' => 'success','message' => $success]);
                        return $this->redirect($backUrl);
                    }
                    Yii::$app->getSession()->setFlash('success', ['type' => 'success','message' => $success]);
                    return $this->redirect($backUrl);
                }
            }
            else{
                Yii::$app->getSession()->setFlash('success', ['type' => 'error','message' => $errors]);
                return $this->redirect($backUrl);
            }
        }

        return $this->render('_bulk_approval_grid', [
                    'model' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblMemberProvisional model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblMemberProvisional the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($provisional_member_code) {
        if (($model = TblMemberProvisional::findOne(['provisional_member_code' => $provisional_member_code])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDistrictCode() {
        if (isset($_POST['dcs_code'])) {
            $dcs = TblDcs::findOne($_POST['dcs_code']);
            $dist_code = $dcs->district_code;
            return $dist_code;
        }
        return false;
    }

    private function setDefaultModel() {
        $this->model->no_of_buffalo = $this->model->no_of_cow_cross = $this->model->no_of_cow_ind = $this->model->total_animals = 0;
    }

    private function setModel() {
        $this->model->dob = empty($this->model->dob) ? NULL : $this->model->dob;
        $this->model->member_name = ucwords($this->model->member_name);
        $this->model->ex_member_code = str_pad($this->model->ex_member_code, 4, '0', STR_PAD_LEFT);
    }

}
