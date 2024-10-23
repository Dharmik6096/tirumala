<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblAllowManualCollectionRange;
use app\modules\collection\models\TblAllowManualCollectionRangeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\modules\general\models\TblApprovalStagesDetail;
use app\modules\collection\models\TblAllowManualCollectionRangeHistory;
use app\modules\general\models\TblProcessApproval;
use app\modules\general\models\TblProcessApprovalHistory;

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
        return $this->render('view', [
                    'model' => $this->findModel($id),
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
        $master_model = [];
        $this->model->scenario = 'create';
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->model->is_approved = 0;
            $this->model->from_date = Yii::$app->formatter->asDate($this->model->from_date, DATE_FORMAT) . ' ' . Yii::$app->general->getshift($this->model->from_shift);
            $this->model->to_date = Yii::$app->formatter->asDate($this->model->to_date, DATE_FORMAT) . ' ' . Yii::$app->general->getshift($this->model->to_shift);
            $this->model->application_type = 'BMC';
            if ($this->model->table_name == 'tbl_milk_collection') {
                $this->model->application_type = 'DCS';
            }
            if ($this->model->validate()) {
                $auto_key_config = [];
                $manualCollectionConfig = Yii::$app->general->getUnionConfiguration($this->model->union_code, 'workflow_for_manual_collection', 'PORTAL');
                if (in_array($manualCollectionConfig, [1, 2])) {
                    if ($manualCollectionConfig == 2) {
                        $i = 0;
                        $modelStages = new TblApprovalStagesDetail();
                        $modelStages->setProcessWiseApprovalData($this->model, $this->model->union_code, 'tbl_allow_manual_collection_range', $master_model, $auto_key_config, $i, true, 'allow_manual_collection_code');
                    } else {
                        $this->model->approval_status = 'Pending';
                        $master_model[] = $this->model;
                    }
                    $message = 'Data For Approval';
                    $type = 'create';
                } else {
                    $this->model->is_approved = 1;
                    $this->model->approval_status = 'Approve';
                    $this->model->approved_at = date('Y-m-d H:i:s');
                    $this->model->approved_by = Yii::$app->session['UserCode'];
                    $master_model[] = $this->model;
                }
                if (!empty($auto_key_config)) {
                    $transaction = $this->generalModel->saveTransactionMultiAutoIncForeignKey($master_model, [$message, $type], $auto_key_config);
                } else {
                    $transaction = $this->generalModel->saveTransaction($master_model, [$message, $type]);
                }
                if ($transaction == 'customRedirect') {
                    return $this->{$transaction}();
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
        $collection_config = Yii::$app->general->getUnionConfiguration(Yii::$app->session->get('Unions'), 'workflow_for_manual_collection', 'PORTAL');
        if (Yii::$app->request->post()) {
            $successCount = 0;
            $errorCount = 0;
            if (isset($_REQUEST['selection'])) {
                $deletedata = Yii::$app->request->post('selection');
                foreach ($deletedata as $key => $value_code) {
                    $codes = explode('###', $value_code);
                    $value = $codes[0];
                    $approval_code = !empty($codes[1]) ? $codes[1] : '';
                    $saveModel = [];
                    $operation = Yii::$app->request->post('TblAllowManualCollectionRange')['operation'];
                    $existData = $this->findModel($value);
                    $status = '';
                    if (strtolower($operation) == 'approve') {
                        if ($collection_config == 2 && !empty($approval_code)) {
                            $status = 1;
                            $this->updateApprovalHistory($approval_code, $saveModel, $status);
                        }

                        $historyModel = new TblAllowManualCollectionRangeHistory();
                        Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                        $saveModel[] = $historyModel;
                        if ($collection_config == 2) {
                            $existData->approval_status = $status;
                            $existData->approved_at = date('Y-m-d H:i:s');
                            $existData->approved_by = Yii::$app->session['UserCode'];
                            $saveModel[] = $existData;
                        }

                        if (strtolower($status) == 'approve' || empty($approval_code)) {
                            $existData->approval_status = 'Approve';
                            $existData->approved_at = date('Y-m-d H:i:s');
                            $existData->approved_by = Yii::$app->session['UserCode'];
                            $saveModel[] = $existData;
                        }
                        if (strtolower($status) == 'approve' || strtolower($existData->approval_status) == 'approve') {
                            $existData->is_approved = 1;
                            $saveModel[] = $existData;
                        }
                    } else if (strtolower($operation) == 'reject') {
                        if ($collection_config == 2) {
                            $status = 2;
                            $this->updateApprovalHistory($approval_code, $saveModel, $status);
                        }
                        if (strtolower($status) == 'reject' || empty($approval_code)) {
                            $historyModel = new TblAllowManualCollectionRangeHistory();
                            Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                            $saveModel[] = $historyModel;
                            $existData->approval_status = 'Reject';
                            $existData->approved_at = date('Y-m-d H:i:s');
                            $existData->approved_by = Yii::$app->session['UserCode'];
                            $saveModel[] = $existData;
                        }
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
        if ($collection_config == 2) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true, true);
        } else {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, false, true);
        }
        $searchModel->scenario = 'approvalCollection';
        return $this->render('approve_collection', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    protected function updateApprovalHistory($approval_code, &$saveModel, &$status) {
        $approvalModel = TblProcessApproval::findOne($approval_code);
        if ($approvalModel) {
            $historyApproval = new TblProcessApprovalHistory();
            Yii::$app->operation->history($approvalModel, $historyApproval, 'UPDATE');
            $saveModel[] = $historyApproval;
            $approvalModel->status = $status;
            $saveModel[] = $approvalModel;
            $approvalModel->ApprovalList($approvalModel, $saveModel, $status);
        }
    }

}
