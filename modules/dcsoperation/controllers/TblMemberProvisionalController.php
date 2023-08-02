<?php

namespace app\modules\dcsoperation\controllers;

use app\modules\collection\models\TblMilkCollection;
use app\modules\collection\models\TblProvisionalMilkCollection;
use app\modules\collection\models\TblProvisionalMilkCollectionHistory;
use app\modules\collection\models\TblProvisionalMilkCollectionSearch;
use app\modules\dcsoperation\models\TblMember;
use Yii;
use app\modules\dcsoperation\models\TblMemberProvisional;
use app\modules\dcsoperation\models\TblMemberProvisionalSearch;
use app\modules\dcsoperation\models\TblMemberProvisionalHistory;
use \app\modules\organisation\models\TblDcs;
use yii\web\NotFoundHttpException;

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
        $this->model = $this->findModel($id);
        $searchModel = new TblProvisionalMilkCollectionSearch();
        $params = Yii::$app->request->queryParams;
        $searchModel->member_code = $this->model->dcs_code . $this->model->pro_ex_member_code;
        $dataProvider = $searchModel->search($params);
        return $this->render('view', [
                    'model' => $this->model,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
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
        if (isset($this->model->scenarios()[Yii::$app->session['eiplCode']])) {
            $this->model->scenario = Yii::$app->session['eiplCode'];
        }
        if ($this->model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->post('submitBtn') === 'approve') {
                $this->model->is_approved = 1;
                $this->model->approved_at = date('Y-m-d H:i:s');
                $this->model->approved_by = Yii::$app->session['UserCode'];
            } else {
                $this->model->is_approved = 0;
            }
            $this->model->federation_code = $this->model->unionCode->federationCode->federation_code;
            //var_dump($this->model->unionCode->federationCode);exit();
            $this->model->provisional_member_code = Yii::$app->general->getUuid();
            $this->model->member_code = $this->model->getCode();
            $this->model->pro_ex_member_code = $this->model->ex_member_code;
            $this->setModel();
            $this->model->upload = 0;
            if ($_POST['warning'] == '0')
                $validate = Yii::$app->warning->unique_member($this->model, ['member_name', 'dcs_code', 'hamlet_code'], [$this->model->member_name, $this->model->dcs_code, $this->model->hamlet_code]);
            if ($validate == 1 && $this->model->validate()) {
                $this->model->registration_date = empty($this->model->registration_date) ? NULL : Yii::$app->formatter->asDate($this->model->registration_date, DATE_FORMAT);
                $this->model->dob = empty($this->model->dob) ? NULL : Yii::$app->formatter->asDate($this->model->dob, DATE_FORMAT);
                $master_model = [];
                $master_model[] = $this->model;
                if ($this->model->is_approved == 1) {
                    $tblMember = new TblMember();
                    $tblMember->scenario = 'ApprovalMember';
                    $tblMember->attributes = $this->model->attributes;
                    $master_model[] = $tblMember;
                }
                $transaction = $this->generalModel->saveTransaction($master_model, ['member provisional', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['view', 'id' => $this->model->provisional_member_code]);
                }
                return $this->render('create', ['model' => $this->model]);
            }
        }
        return $this->render('create', ['model' => $this->model]);
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
        $this->model->scenario = 'update_provisional_member';
        $tblMember = new TblMember();
        if ($this->model->provisional_from == 'mobile_app') {
            $this->model->ex_member_code = Yii::$app->general->getMaxCode($tblMember, 'ex_member_code', $this->model->dcs_code);
        }
        $searchModel = new TblProvisionalMilkCollectionSearch();
        $params = Yii::$app->request->queryParams;
        $searchModel->member_code = $this->model->dcs_code . $this->model->pro_ex_member_code;
        $dataProvider = $searchModel->search($params);
        if (isset($this->model->scenarios()[Yii::$app->session['eiplCode']])) {
            $this->model->scenario = Yii::$app->session['eiplCode'];
        }
        if (Yii::$app->request->post()) {
            $this->model->federation_code = $this->model->unionCode->federationCode->federation_code;
            if (Yii::$app->request->post('submitBtn') === 'approve') {
                $this->model->is_approved = 1;
                $this->model->approved_at = date('Y-m-d H:i:s');
                $this->model->approved_by = Yii::$app->session['UserCode'];
            } else {
                $this->model->is_approved = 0;
            }
            $historyModel = new TblMemberProvisionalHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $historyModel->provisional_member_code = $this->model->provisional_member_code;
            $this->model->load(Yii::$app->request->post());
//            $this->setModel();
            $this->model->member_code = $this->model->getCode();
            if ($_POST['warning'] == 0)
                $validate = Yii::$app->warning->unique_member($this->model, ['member_name', 'dcs_code', 'hamlet_code'], [$this->model->member_name, $this->model->dcs_code, $this->model->hamlet_code]);
            if ($validate == 1 && $this->model->validate()) {
                $this->model->registration_date = empty($this->model->registration_date) ? NULL : Yii::$app->formatter->asDate($this->model->registration_date, DATE_FORMAT);
                $this->model->dob = empty($this->model->dob) ? NULL : Yii::$app->formatter->asDate($this->model->dob, DATE_FORMAT);
                $master_model = [];
                $child_model = [];
                $deleteModel = [];
                $master_model[] = $this->model;
                if ($this->model->is_approved == 1) {
                    $tblMember = new TblMember();
                    $tblMember->scenario = 'ApprovalMember';
                    $tblMember->attributes = $this->model->attributes;
                    $tblMember->member_code = $tblMember->getCode();
                    $master_model[] = $tblMember;
                    if (strtolower($this->model->provisional_from) == 'collection') {
                        $milkCollectionData = new TblProvisionalMilkCollection();
                        $milkCollectionData = $milkCollectionData->getMilkCollectionData($this->model->dcs_code . $this->model->pro_ex_member_code);
                        if (!empty($milkCollectionData)) {
                            foreach ($milkCollectionData as $key => $value) {
                                $deleteModel[] = $value;
                                $tblMilkCollection = new TblMilkCollection();
                                $tblMilkCollection->attributes = $value->attributes;
                                $tblMilkCollection->member_code = $tblMember->member_code;
                                $tblMilkCollection->is_provisional = 1;
                                $tblProvisionalMilkCollectionHistory = new TblProvisionalMilkCollectionHistory();
                                Yii::$app->operation->history($value, $tblProvisionalMilkCollectionHistory, DELETE);
                                $child_model[] = $tblMilkCollection;
                                $child_model[] = $tblProvisionalMilkCollectionHistory;
                            }
                        }
                    }
                }
                $master_model[] = $historyModel;
                $transaction = $this->generalModel->saveDeleteTransaction($master_model, $child_model, $deleteModel, ['Member Provisional', 'edit']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['view', 'id' => $this->model->provisional_member_code]);
                } else {
                    $ex_error = $this->model->getErrors();
                    if (empty($ex_error)) {
                        $main_error = $tblMember->getErrors();
                        foreach ($main_error as $att => $err) {
                            $this->model->addError($att, $err[0]);
                        }
                    }
                }
            }
        }
        return $this->render('update', ['model' => $this->model, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    public function actionProvisionalMembersApproval() {
        $searchModel = new TblMemberProvisionalSearch();
        $dataProvider = $searchModel->searchApprovalData(Yii::$app->request->queryParams);
        $backUrl[] = '/dcsoperation/tbl-member-provisional/provisional-members-approval';
        if (Yii::$app->request->post() && !empty(Yii::$app->request->post('selection'))) {
            $data = Yii::$app->request->post();
            $selection = $data['selection'];
            $master = [];
            $deleteModel = [];
            $child_model = [];
            $errors = '';
            $success = '';
            $flag = $data['flag'];
            $i = 0;
            foreach ($selection as $key => $value) {
                $this->model = $this->findModel($value);
                if ($this->model->is_approved != 1) {
                    $this->model->is_approved = 1;
                    $this->model->approved_at = date('Y-m-d H:i:s');
                    $this->model->approved_by = Yii::$app->session['UserCode'];
                    if ($this->model->validate()) {
                        $tblMember = new TblMember;
                        $tblMember->scenario = 'ApprovalMember';
                        $tblMember->attributes = $this->model->attributes;
                        $tblMember->member_code = $tblMember->getCode();
                        $historyModel = new TblMemberProvisionalHistory();
                        Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                        $historyModel->provisional_member_code = $this->model->provisional_member_code;
                        $master[] = $tblMember;
                        $master[] = $this->model;
                        $master[] = $historyModel;
                        $milkCollectionData = new TblProvisionalMilkCollection();
                        $milkCollectionData = $milkCollectionData->getMilkCollectionData($this->model->dcs_code . $this->model->pro_ex_member_code);
                        if (!empty($milkCollectionData)) {
                            foreach ($milkCollectionData as $key => $value) {
                                $deleteModel[] = $value;
                                $tblMilkCollection = new TblMilkCollection();
                                $tblMilkCollection->attributes = $value->attributes;
                                $tblMilkCollection->member_code = $tblMember->member_code;
                                $tblMilkCollection->is_provisional = 1;
                                $tblProvisionalMilkCollectionHistory = new TblProvisionalMilkCollectionHistory();
                                Yii::$app->operation->history($value, $tblProvisionalMilkCollectionHistory, DELETE);
                                $child_model[] = $tblMilkCollection;
                                $child_model[] = $tblProvisionalMilkCollectionHistory;
                            }
                        }
                        $i++;
                    } else {
                        $errors .= $this->model->member_code . ',';
                    }
                } else {
                    $errors .= 'Something went wrong.<\br>';
                }
            }
            $modelError = '';
            foreach ($master as $m) {
                if (!$m->validate()) {
                    // var_dump($m);
                    foreach ($m->getErrors() as $key => $value) {
                        $modelError .= $value[0];
                    }
                }
            }
            if ($modelError != '') {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => $modelError]);
                return $this->redirect($backUrl);
            }
            if ($errors == '') {
                $transaction = $this->generalModel->saveDeleteTransaction($master, $child_model, $deleteModel, ['Member Provisional', 'edit']);
                if ($transaction !== FALSE) {
                    if ($transaction == 'customRedirect') {
                        Yii::$app->getSession()->setFlash('success', ['type' => 'success', 'message' => $i . ' records approved successfully.']);
                        return $this->redirect($backUrl);
                    }
                    Yii::$app->getSession()->setFlash('success', ['type' => 'success', 'message' => $i . ' records approved successfully.']);
                    return $this->redirect($backUrl);
                }
            } else {
                Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => 'Incomplete data in following members ' . $errors]);
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

    public function actionProvisionalMilkCollectionList() {
        $data = [];
        $model = new TblMemberProvisionalSearch();
        $model = $model->find()->where(['member_code' => $_POST['member_code']])->one();
        $searchModel = new TblProvisionalMilkCollectionSearch();
        if (!empty($_POST)) {
            $params = Yii::$app->request->queryParams;
            $searchModel->member_code = $_POST['member_code'];
            $dataProvider = $searchModel->search($params);
        }
        // $model = $searchModel->find()->where(['member_code' =>$_POST['member_code']])->one();
        return $this->renderAjax('provisional_milk_collection', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'model' => $model]);
    }

}
