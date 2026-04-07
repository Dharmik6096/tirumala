<?php

namespace app\modules\product\controllers;

use app\modules\collection\models\TblMilkCollection;
use Yii;
use app\modules\product\models\TblIndentMaster;
use app\modules\product\models\TblIndentMasterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use app\modules\general\models\TblApprovalStagesDetail;
use app\modules\general\models\TblProcessApproval;
use app\modules\general\models\TblProcessApprovalHistory;
use app\modules\product\models\TblIndentMasterHistory;
use app\modules\payment\models\TblPaymentCycleApplicability;
use app\modules\payment\models\TblMonthlyCreditLimit;

/**
 * TblIndentMasterNewController implements the CRUD actions for TblIndentMaster model.
 */
class TblIndentMasterNewController extends \app\controllers\ChildController {

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

    public function actionIndexOther() {
        $searchModel = new TblIndentMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index_other', [
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
            $this->model->status = 0;
            $this->model->scenario = 'create';
            if ($this->model->validate()) {
                $modelStages = new TblApprovalStagesDetail();
                $modelStages->setApprovalData($this->model->union_code, 'indent_master', $this->model->indent_code, $modelSave, $approval_stages);
                $this->model->status = empty($approval_stages) ? 2 : 0;
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

    public function actionCreateOther() {
        $this->model = new TblIndentMaster();
        $searchModel = new TblIndentMasterSearch();
        $searchModel->attributes = Yii::$app->request->get('TblIndentMaster');
        $dataProvider = $searchModel->createsearch([]);

        $dataProvider->sort = false;
        $this->viewFile = 'create_other';
        $modelSave = [];
        $message = 'Indent Master';
        $type = 'create';
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->model->indent_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            $this->model->indent_date = Yii::$app->formatter->asDate($this->model->indent_date, DATE_FORMAT) . ' 00:00:00.000000';
            $this->model->customer_type = $this->model->customer_type == 1 ? 'MEMBER' : 'DCS';
            $this->model->customer_code = $this->model->customer_type == 'MEMBER' ? $this->model->customer_code : $this->model->dcs_code;
            $this->model->member_code = $this->model->customer_type == 'MEMBER' ? $this->model->customer_code : NULL;
            $this->model->status = 0;
            $this->model->scenario = 'createOther';
            if ($this->model->validate()) {
                $modelStages = new TblApprovalStagesDetail();
                $modelStages->setApprovalData($this->model->union_code, 'indent_master', $this->model->indent_code, $modelSave, $approval_stages);
                $this->model->status = empty($approval_stages) ? 2 : 0;
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
            return $this->render('create_other', [
                        'model' => $this->model,
                        'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
            ]);
        }
        return $this->render('create_other', [
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

    public function actionListGridOther() {
        $searchModel = new TblIndentMasterSearch();
        $searchModel->attributes = Yii::$app->request->get('TblIndentMaster');
        $dataProvider = $searchModel->createsearch([]);
        return $this->renderAjax('_list_grid_other', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    public function actionIndentApproval() {
        $searchModel = new TblIndentMasterSearch();
        $searchModel->scenario = 'indentApprove';
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $status = !empty($_REQUEST['operation']) ? ($_REQUEST['operation'] == 'approve' ? 2 : 3) : 0;
                $codes = empty($_REQUEST['selection']) ? [] : $_REQUEST['selection'];
                $msg = $status == 2 ? 'Approved' : 'Rejected';
                $where = [];
                foreach ($codes as $code) {
                    $where['process_approval_code'] = $code;
                    $existData = TblProcessApproval::find()->where($where)->one();
                    if (!empty($existData)) {
                        $historyModel = new TblProcessApprovalHistory();
                        Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                        $saveModel[] = $historyModel;
                        $existData->status = $status;
                        $saveModel[] = $existData;

                        $existIndentData = TblIndentMaster::find()->where(['indent_code' => $existData->process_code])->one();
                        if (!empty($existIndentData)) {
                            $existApprovalLevel = TblProcessApproval::find()->where(['process_code' => $existIndentData->indent_code, 'process_name' => 'indent_master', 'status' => 0])->count();
                            $historyModel = new TblIndentMasterHistory();
                            Yii::$app->operation->history($existIndentData, $historyModel, 'UPDATE');
                            $saveModel[] = $historyModel;
                            $existIndentData->status = $status == 3 ? 3 : ($existApprovalLevel == 1 ? 2 : 1);
                            $existIndentData->status_by = \Yii::$app->user->identity->user_code;
                            $existIndentData->status_date = date('Y-m-d H:i:s');
                            $saveModel[] = $existIndentData;
                        }
                    }
                }

                $transaction = $this->generalModel->saveTransaction($saveModel, ['Indent ' . $msg, 'create']);
                if ($transaction == 'customRedirect') {
//                    return $this->redirect(['index']);
                }
            }
        }
        $dataProvider = $searchModel->indentapprovesearch(Yii::$app->request->queryParams);

        return $this->render('indent_approval', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndentApprovalOther() {
        $searchModel = new TblIndentMasterSearch();
        $searchModel->scenario = 'indentApproveNew';
        $backUrl[] = '/product/tbl-indent-master/indent-approval-other';
        $indentMaster = new TblIndentMaster();
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $status = !empty($_REQUEST['operation']) ? ($_REQUEST['operation'] == 'approve' ? 1 : 2) : 0;
                $codes = empty($_REQUEST['selection']) ? [] : $_REQUEST['selection'];
                $msg = $status == 1 ? 'Approved' : 'Rejected';
                $where = [];

                $approvalFlag = true;
                foreach ($codes as $code) {
                    $postData = Yii::$app->request->post()['TblIndentMaster'][$code];
                    $where['process_approval_code'] = $code;
                    $existData = TblProcessApproval::find()->where($where)->one();
                    if (!empty($existData)) {
                        $historyModel = new TblProcessApprovalHistory();
                        Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                        $saveModel[] = $historyModel;
                        $existData->status = $status;
                        $existData->status_date = date('Y-m-d H:i:s');
                        $existData->status_by = \Yii::$app->user->identity->user_code;
                        $saveModel[] = $existData;

                        if ($existData->approval_mode == 'flexi') {

                            $all_level = TblProcessApproval::find()
                                            ->where(['process_code' => $existData->process_code, 'status' => 0])
                                            ->andWhere(['<>', 'process_approval_code', $existData->process_approval_code])
                                            ->andWhere(['level' => $existData->level])->all();

                            foreach ($all_level as $level) {
                                $approvalHistoryModel = new TblProcessApprovalHistory();
                                Yii::$app->operation->history($level, $approvalHistoryModel, 'UPDATE');
                                $saveModel[] = $approvalHistoryModel;
                                $level->status_date = date('Y-m-d H:i:s');
                                $level->status_by = \Yii::$app->user->identity->user_code;
                                $level->status = $existData->status;
                                $saveModel[] = $level;
                            }
                        }
                        $existIndentData = TblIndentMaster::find()->where(['indent_code' => $existData->process_code])->one();
                        if (!empty($existIndentData)) {
                            $existIndentData->scenario = 'approve';
                            $existApprovalLevel = TblProcessApproval::find()->select(['COUNT(*) as cnt'])
                                    ->where(['process_code' => $existIndentData->indent_code, 'process_name' => 'indent_master', 'status' => 0])
                                    ->groupBy(['level', 'approval_mode'])
                                    ->count();

                            $historyModel = new TblIndentMasterHistory();
                            Yii::$app->operation->history($existIndentData, $historyModel, 'UPDATE');
                            $saveModel[] = $historyModel;

                            $existIndentData->status = $status == 2 ? 3 : ($existApprovalLevel == 1 ? 2 : 1);
                            $existIndentData->status_by = \Yii::$app->user->identity->user_code;
                            $existIndentData->status_date = date('Y-m-d H:i:s');
                            $level = $indentMaster->getApprovalLevel($existIndentData->indent_code);
                            if (empty($level)) {
                                $existIndentData->approve_qty = $postData['approve_qty'];
                                $existIndentData->rejected_qty = $postData['rejected_qty'];
                                $existIndentData->amount = $postData['amount'];
                                $existIndentData->approve_remarks = $postData['approve_remarks'];
                            }
                            $saveModel[] = $existIndentData;
                        }
                    }
                }
                $modelError = '';
                foreach ($saveModel as $m) {
                    if (!$m->validate()) {
                        foreach ($m->getErrors() as $key => $value) {
                            $modelError .= $value[0];
                        }
                    }
                }
                if ($modelError == '') {
                    $approvalFlag = false;
                    $transaction = $this->generalModel->saveTransaction($saveModel, ['Indent ' . $msg, 'create']);
                    if ($transaction == 'customRedirect') {
                        return $this->redirect(['index-other']);
                    }
                } else {
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => $modelError]);
                    return $this->redirect($backUrl);
                }
            }
        }
        $dataProvider = $searchModel->indentapprovesearch(Yii::$app->request->queryParams, 'portal_sp_pending_indent_approval_other');
        $isIndentApprovalCreditLimitCheck = Yii::$app->general->getUnionConfiguration(Yii::$app->session->get('Unions'), 'is_indent_approval_credit_limit_check', 'PORTAL') == 1 ? TRUE : FALSE;
        if (!empty($searchModel->group_by) && !empty($dataProvider->allModels)) {
            $resultArray = [];
            foreach ($dataProvider->allModels as $item) {
                $key = $item['dcs_code'] . '-' . $item['member_code'] . '-' . $item['product_code'] . '-' . $item['product_name'];
                if (!isset($resultArray[$key])) {
                    $creditAmount = 'Not Applicable';
                    if (!empty($item['member_code']) && $isIndentApprovalCreditLimitCheck) {
                        $fromDate = date('Y-m-d', strtotime($searchModel->from_date));
                        $toDate = date('Y-m-d', strtotime($searchModel->to_date));
                        $model = new TblMilkCollection();
                        $modelData = $model->find()
                                ->select(['amount' => 'ISNULL(SUM(ISNULL(amount, 0)), 0)'])
                                ->where(['between', 'date_time_of_collection', $fromDate, $toDate])
                                ->andWhere(['member_code' => $item['member_code']])
                                ->one();
                        $creditAmount = 0;
                        if (!empty($modelData->amount)) {
                            $creditAmount = $modelData->amount;
                        }
                    }
                    $resultArray[$key] = [
                        'dcs_code' => $item['dcs_code'],
                        'member_code' => $item['member_code'],
                        'product_code' => $item['product_code'],
                        'product_name' => $item['product_name'],
                        'qty' => 0.00,
                        'allow_edit' => $item['allow_edit'],
                        'dcs_code' => $item['dcs_code'],
                        'dcs_ref_code' => $item['dcs_ref_code'],
                        'dcs_name' => $item['dcs_name'],
                        'member_code' => $item['member_code'],
                        'member_ref_code' => $item['member_ref_code'],
                        'member_name' => $item['member_name'],
                        'product_name' => $item['product_name'],
                        'warehouse_code' => $item['store_location_name'],
                        'rate' => $item['rate'],
                        'user_name' => $item['user_name'],
                        'login_type' => $item['login_type'],
                        'department' => $item['department'],
                        'credit_amount' => $creditAmount,
                        'member_array' => []
                    ];
                }
                $resultArray[$key]['qty'] += $item['qty'];
                $resultArray[$key]['member_array'][] = $item;
            }
            $dataProvider->allModels = array_values($resultArray);
        }
        return $this->render('indent_approval', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'indentMaster' => $indentMaster,
                    'visibledata' => True,
        ]);
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

    public function actionDelete() {
        $this->model = $this->findModel(Yii::$app->request->post('id'));
        $deleteModel = [];
        $saveModel = [];

        $historyModel = new TblIndentMasterHistory();
        Yii::$app->operation->history($this->model, $historyModel, DELETE);
        $deleteModel[] = $this->model;
        $saveModel[] = $historyModel;

        $details = TblProcessApproval::find()->where(['process_code' => $this->model->indent_code, 'process_name' => 'indent_master'])->all();
        foreach ($details as $key => $id) {
            $detailHistory = new TblProcessApprovalHistory();
            Yii::$app->operation->history($id, $detailHistory, DELETE);
            $deleteModel[] = $details[$key];
            $saveModel[] = $detailHistory;
        }
        $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Indent Master', 'edit']);

        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => 'Record is successfully deleted.'];
        } else {
            $record = ['status' => 'error', 'msg' => 'This record cannot be deleted due to some reference Error.'];
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionGetAvailableCredit() {
        $union = Yii::$app->request->post('union_code');
        $bmc = Yii::$app->request->post('bmc_code');
        $date = Yii::$app->request->post('indent_date');
        $code = Yii::$app->request->post('customer_code');
        $creditLimitCheckMonthly = Yii::$app->general->getUnionConfiguration($union, 'credit_limit_check_monthly', 'PORTAL');
        $creditAmount = 0;

        if ($creditLimitCheckMonthly == 1) {
            $model = new TblMonthlyCreditLimit();
            $model->customer_type = 'MEMBER';
            $model->customer_code = $code;
            $model->union_code = $union;
            $modelData = $model->getMonthlyCreditLimit(date('Y-m-d', strtotime($date)));
            if (!empty($modelData->final_amount)) {
                $creditAmount = $modelData->final_amount;
            }
            $date = Yii::$app->request->post('date');
            list($fromDate, $toDate) = Yii::$app->general->getMonthStartEndDate($date, 'current');
        } else {
            $model = new TblPaymentCycleApplicability();
            $model->applicable_type = 'DCS';
            $model->applicable_code = $bmc;
            $model->applicable_for = 'BMC';
            $modelData = $model->getApplicablePaymentCycle(date('Y-m-d', strtotime($date)));
            if (!empty($modelData)) {
                $fromDate = date('Y-m-d', strtotime($modelData->from_date));
                $toDate = date('Y-m-d', strtotime($modelData->to_date));
                $model = new TblMilkCollection();
                $modelData = $model->find()
                        ->select(['amount' => 'ISNULL(SUM(ISNULL(amount, 0)), 0)'])
                        ->where(['between', 'date_time_of_collection', $modelData->from_date, $modelData->to_date])
                        ->andWhere(['member_code' => $code, 'union_code' => $union])
                        ->one();

                if (!empty($modelData->amount)) {
                    $creditAmount = $modelData->amount;
                }
            }
        }
        if (!empty($fromDate)) {
            $model = new TblIndentMaster();
            $data = $model->find()
                    ->select(['indent_total_amount' => 'ISNULL(SUM(ISNULL(amount, 0)),0)'])
                    ->where(['between', 'cast(indent_date as date)', $fromDate, $toDate])
                    ->andWhere(['customer_type' => 'MEMBER', 'customer_code' => $code, 'union_code' => $union])
                    ->one();
            $indentTotalAmount = 0;
            if (!empty($data->indent_total_amount)) {
                $indentTotalAmount = $data->indent_total_amount;
            }

            $availableCredit = $creditAmount - $indentTotalAmount;
            return Json::encode(['status' => 'success', 'credit' => $availableCredit]);
        } else {
            return Json::encode(['status' => 'error', 'credit' => 0]);
        }
    }

}
