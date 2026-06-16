<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblAllowManualCollectionRange;
use app\modules\collection\models\TblAllowManualCollectionRangeSearch;
use yii\web\NotFoundHttpException;
use app\modules\general\models\TblApprovalStagesDetail;
use app\modules\collection\models\TblAllowManualCollectionRangeHistory;
use app\modules\document\models\TblAttachment;
use app\modules\general\models\TblProcessApproval;
use app\modules\general\models\TblProcessApprovalHistory;
use app\modules\complaint\models\TblComplainSearch;
use yii\data\ActiveDataProvider;

/**
 * TblAllowManualCollectionRangeController implements the CRUD actions for TblAllowManualCollectionRange model.
 */
class TblAllowManualCollectionRangeController extends \app\controllers\ChildController {

    /**
     * Lists all TblAllowManualCollectionRange models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblAllowManualCollectionRangeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblAllowManualCollectionRange model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $this->model = $this->findModel($id);
        $searchModel = new TblAllowManualCollectionRangeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $attachment = new TblAttachment();
        $dataProviderOther = new ActiveDataProvider([
            'query' => $attachment->find()->where(['module_code' => (string) $id, 'module_name' => 'tbl_allow_manual_collection_range']),
        ]);
        return $this->render('view', [
                    'model' => $this->model,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                    'dataProviderOther' => $dataProviderOther,
                    'attachment' => $attachment,
        ]);
    }

    /**
     * Creates a new TblAllowManualCollectionRange model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblAllowManualCollectionRange();
        $this->viewFile = 'create';
        $message = 'Manual Collection';
        $type = 'create';
        $error = '';
        $backUrl[] = '/collection/tbl-allow-manual-collection-range/create';
        $master_model = [];
        $this->model->scenario = 'create';
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->model->is_approved = 0;
            $this->model->from_date = Yii::$app->formatter->asDate($this->model->from_date, DATE_FORMAT) . ' ' . Yii::$app->general->getshift($this->model->from_shift);
            $this->model->to_date = Yii::$app->formatter->asDate($this->model->to_date, DATE_FORMAT) . ' ' . Yii::$app->general->getshift($this->model->to_shift);
            if ($this->model->validate()) {
                $auto_key_config = [];
                $i = 0;
                $this->model->setManualCollectionData($master_model, $auto_key_config, $i, false, $message, $type, $error);
                if ($error == '') {
                    if (!empty($auto_key_config)) {
                        $transaction = $this->generalModel->saveTransactionMultiAutoIncForeignKey($master_model, [$message, $type], $auto_key_config);
                    } else {
                        $transaction = $this->generalModel->saveTransaction($master_model, [$message, $type]);
                    }
                    if ($transaction == 'customRedirect') {
                        return $this->{$transaction}();
                    }
                } else {
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error', 'message' => $error]);
                    return $this->redirect($backUrl);
                }
            }
        }
        return $this->customRender();
    }

    protected function findModel($id) {
        if (($model = TblAllowManualCollectionRange::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionManualCollectionApproval() {
        $manualCollectionModel = new TblAllowManualCollectionRange();

        if (Yii::$app->request->post()) {
            $successCount = 0;
            $errorCount = 0;
            if (isset($_REQUEST['selection'])) {
                $deletedata = Yii::$app->request->post('selection');
                $collectionPostData = Yii::$app->request->post()['TblAllowManualCollectionRange'];
                $remarks = Yii::$app->request->post()['approve_remarks'];
                foreach ($deletedata as $key => $value_code) {
                    $codes = explode('###', $value_code);
                    $value = $codes[0];
                    $approval_code = !empty($codes[1]) ? $codes[1] : '';
                    $saveModel = [];
                    $operation = Yii::$app->request->post('TblAllowManualCollectionRange')['operation'];
                    $existData = $this->findModel($value);
                    $config_key = 'manual_collection_request_approval_' . $existData->entry_type;
                    $collection_config = Yii::$app->general->getUnionConfiguration(Yii::$app->session->get('Unions'), $config_key, 'PORTAL');
                    $status = '';
                    if (strtolower($operation) == 'approve') {
                        if ($collection_config == 2 && !empty($approval_code)) {
                            $status = 1;
                            $remark = $remarks . $collectionPostData[$value_code]['remark'];
                            $this->updateApprovalHistory($approval_code, $saveModel, $status, $remark);
                        }

                        $historyModel = new TblAllowManualCollectionRangeHistory();
                        Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                        $saveModel[] = $historyModel;
                        if ($collection_config == 2) {
                            $existData->approval_status = $status;
                            $existData->approved_at = date('Y-m-d H:i:s');
                            $existData->approved_by = Yii::$app->session['UserCode'];
                            $existData->remark = $remarks . $collectionPostData[$value_code]['remark'];
                        }
                        if (strtolower($status) == 'approve' || empty($approval_code)) {
                            $existData->approval_status = 'Approve';
                            $existData->approved_at = date('Y-m-d H:i:s');
                            $existData->approved_by = Yii::$app->session['UserCode'];
                            $existData->remark = $remarks . $collectionPostData[$value_code]['remark'];
                        }
                        if (strtolower($status) == 'approve' || strtolower($existData->approval_status) == 'approve') {
                            $existData->is_approved = 1;
                        }
                        $saveModel[] = $existData;
                    } else if (strtolower($operation) == 'reject') {
                        if (!empty($approval_code)) {
                            $status = 2;
                            $this->updateApprovalHistory($approval_code, $saveModel, $status, $existData->remark);
                        }
                        if (strtolower($status) == 'reject' || empty($approval_code)) {
                            $historyModel = new TblAllowManualCollectionRangeHistory();
                            Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                            $saveModel[] = $historyModel;
                            $existData->approval_status = 'Reject';
                            $existData->approved_at = date('Y-m-d H:i:s');
                            $existData->approved_by = Yii::$app->session['UserCode'];
                        }
                        $existData->remark = $remarks . $collectionPostData[$value_code]['remark'];
                        $saveModel[] = $existData;
                    }
                    $transaction = $this->generalModel->saveTransaction($saveModel, ['Allow Collection Approval', 'edit']);
                    if ($transaction == 'customRedirect') {
                        $successCount++;
                    } else {
                        $errorCount++;
                    }
                }
                $msg = $operation == 'approve' ? ('Allow Collection approved successfully. <br />Approved count : ' . $successCount . '<br />Not approved count : ' . $errorCount) : ('Allow Collection rejected successfully.  <br />Rejected count : ' . $successCount . '<br />Not Rejected count : ' . $errorCount);
                Yii::$app->getSession()->setFlash('success', ['type' => 'success', 'message' => $msg]);
                $getData = Yii::$app->request->queryParams;
                if (!empty($getData['TblAllowManualCollectionRangeSearch'])) {
                    return $this->redirect(['manual-collection-approval', 'TblAllowManualCollectionRangeSearch' => $getData['TblAllowManualCollectionRangeSearch']]);
                } else {
                    return $this->redirect(['manual-collection-approval']);
                }
            }
        }

        $searchModel = new TblAllowManualCollectionRangeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true, true);

        return $this->render('approve_collection', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'manualCollectionModel' => $manualCollectionModel,
        ]);
    }

    protected function updateApprovalHistory($approval_code, &$saveModel, &$status, $remarks = '') {
        $approvalModel = TblProcessApproval::findOne($approval_code);
        if ($approvalModel) {
            $historyApproval = new TblProcessApprovalHistory();
            Yii::$app->operation->history($approvalModel, $historyApproval, 'UPDATE');
            $saveModel[] = $historyApproval;
            $approvalModel->status = $status;
            $approvalModel->remarks = $remarks;
            $saveModel[] = $approvalModel;
            $approvalModel->ApprovalList($approvalModel, $saveModel, $status);
        }
    }

    public function actionViewComplainInfo($id) {
        $manualCollectionModel = $this->findModel($id);
        $manualCollectionSearchModel = new TblAllowManualCollectionRangeSearch();
        $manualCollectionSearchModel->dcs_code = $manualCollectionModel->dcs_code;
        $manualCollectionSearchModel->from_date = date('Y-m-d H:i:s', strtotime('-2 months'));
        $manualCollectionSearchModel->to_date = date('Y-m-d H:i:s');
        $dataProvider = $manualCollectionSearchModel->search(Yii::$app->request->queryParams, false, false, true);
        $countQuery = clone $dataProvider->query;
        $totalComplainCount = $countQuery->andWhere(['IS NOT', 'tbl_allow_manual_collection_range.complain_code', null])->count();
        return $this->render('view_complain_info', [
                    'model' => $manualCollectionModel,
                    'dataProvider' => $dataProvider,
                    'manualCollectionSearchModel' => $manualCollectionSearchModel,
                    'totalComplainCount' => $totalComplainCount,
        ]);
    }

    public function actionComplainActivityAjax($id) {
        $manualCollectionModel = $this->findModel($id);
        $manualCollectionSearchModel = new TblAllowManualCollectionRangeSearch();
        $manualCollectionSearchModel->dcs_code = $manualCollectionModel->dcs_code;
        $manualCollectionSearchModel->from_date = date('Y-m-d H:i:s', strtotime('-2 months'));
        $manualCollectionSearchModel->to_date = date('Y-m-d H:i:s');
        $dataProvider = $manualCollectionSearchModel->search(Yii::$app->request->queryParams, false, false, true);
        return $this->renderAjax('_complaint_activity', [
                    'model' => $manualCollectionModel,
                    'dataProvider' => $dataProvider,
                    'searchModel' => $manualCollectionSearchModel,
        ]);
    }
}
