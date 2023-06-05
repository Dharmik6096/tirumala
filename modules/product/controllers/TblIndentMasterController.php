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
use app\modules\general\models\TblApprovalStagesDetail;
use app\modules\general\models\TblProcessApproval;
use app\modules\general\models\TblProcessApprovalHistory;
use app\modules\product\models\TblIndentMasterHistory;

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
            $this->model->customer_code = $this->model->dcs_code;
            $this->model->customer_type = 'DCS';
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
        $searchModel->scenario = 'indentApprove';
        $backUrl[] = '/product/tbl-indent-master/indent-approval-other';
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $status = !empty($_REQUEST['operation']) ? ($_REQUEST['operation'] == 'approve' ? 2 : 3) : 0;
                $codes = empty($_REQUEST['selection']) ? [] : $_REQUEST['selection'];
                $msg = $status == 2 ? 'Approved' : 'Rejected';
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
                        $saveModel[] = $existData;

                        $existIndentData = TblIndentMaster::find()->where(['indent_code' => $existData->process_code])->one();
                        if (!empty($existIndentData)) {
                            $existIndentData->scenario = 'approve';
                            $existApprovalLevel = TblProcessApproval::find()->where(['process_code' => $existIndentData->indent_code, 'process_name' => 'indent_master', 'status' => 0])->count();
                            $historyModel = new TblIndentMasterHistory();
                            Yii::$app->operation->history($existIndentData, $historyModel, 'UPDATE');
                            $saveModel[] = $historyModel;
                            $existIndentData->status = $status == 3 ? 3 : ($existApprovalLevel == 1 ? 2 : 1);
                            $existIndentData->status_by = \Yii::$app->user->identity->user_code;
                            $existIndentData->status_date = date('Y-m-d H:i:s');

                            $level = Yii::$app->general->getApprovalLevel($existIndentData->indent_code);
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
//                    return $this->redirect(['index']);
                    }
                } else {
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => $modelError]);
                    return $this->redirect($backUrl);
                }
            }
        }
        $indentMaster = new TblIndentMaster();
        $dataProvider = $searchModel->indentapprovesearch(Yii::$app->request->queryParams, 'portal_sp_pending_indent_approval_other');

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

}
