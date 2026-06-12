<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblCollectionDataAlias;
use app\modules\collection\models\TblCollectionDataAliasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\collection\models\TblMilkCollection;
use app\modules\collection\models\TblCollectionDataAliasHistory;
use app\modules\collection\models\TblCollectionDataAliasReject;
use app\modules\collection\models\TblBmcCollection;
use app\modules\collection\models\TblBmcCollectionHistory;
use app\modules\collection\models\TblDcsMilkDispatch;
use app\modules\collection\models\TblDcsMilkDispatchTxn;
use app\modules\collection\models\TblMilkCollectionHistory;
use app\modules\general\models\TblProcessApproval;
use app\modules\general\models\TblProcessApprovalHistory;
use app\modules\collection\models\TblDcsMilkDispatchTxnHistory;
use app\modules\collection\models\TblDcsMilkDispatchHistory;

/**
 * TblCollectionDataAliasController implements the CRUD actions for TblCollectionDataAlias model.
 */
class TblCollectionDataAliasController extends \app\controllers\ChildController {

    public function actionMilkCollectionApprove() {
        $union = !empty(Yii::$app->request->queryParams['TblCollectionDataAliasSearch']) ? (!empty(Yii::$app->request->queryParams['TblCollectionDataAliasSearch']['union_code']) ? Yii::$app->request->queryParams['TblCollectionDataAliasSearch']['union_code'] : '') : '';
        $collection_config = Yii::$app->general->getUnionConfiguration($union, 'collection_approval', 'PORTAL');
        $allowSentbox = Yii::$app->general->getUnionConfiguration($union, 'collection_approval_sentbox', 'PORTAL');
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $succCount = 0;
                $errorCount = 0;
                $deletedata = Yii::$app->request->post('selection');
                foreach ($deletedata as $key => $value_code) {
                    $codes = explode('###', $value_code);
                    $value = $codes[0];
                    $approval_code = !empty($codes[1]) ? $codes[1] : '';
                    $action = !empty($codes[2]) ? $codes[2] : '';
                    $saveModel = [];
                    $deleteModel = [];
                    $operation = Yii::$app->request->post('TblCollectionDataAlias')['operation'];
                    $existData = $this->findModel($value);
                    $approval_status = $existData->approval_status;
                    $status = '';
                    ($operation == 'approve' && ($action == 'CREATE' || $action == 'UPDATE')) ? $existData->scenario = 'MilkCollection' : '';
                    if ($collection_config == 1) {
                        $existData->approval_status = !empty($operation == 'approve') ? 'Approve' : 'Reject';
                        $existData->approved_at = date('Y-m-d H:i:s');
                        $existData->approved_by = Yii::$app->session['UserCode'];
                    }
                    if ($operation == 'approve') {
                        $historyFlag = 'DELETE';
                        if ($existData->validate()) {
                            if ($collection_config == 2 && !empty($approval_code)) {
                                $status = 1;
                                $this->updateApprovalHistory($approval_code, $saveModel, $status);
                                if (strtolower($status) != 'approve') {
                                    $historyFlag = 'UPDATE';
                                }
                            }
                            if ($action == 'CREATE' && (strtolower($status) == 'approve' || empty($approval_code))) {
                                $MainModel = new TblMilkCollection();
                                $MainModel->attributes = $existData->attributes;
                                $MainModel->setModel($MainModel);
                                $saveModel[] = $MainModel;
                            } else if ($action == 'UPDATE' && (strtolower($status) == 'approve' || empty($approval_code))) {
                                $MainModel = new TblMilkCollection();
                                $existMainData = $MainModel->getExistingCollection($existData);
                                if (!empty($existMainData)) {
                                    $historyModel = new TblMilkCollectionHistory();
                                    Yii::$app->operation->history($existMainData, $historyModel, 'UPDATE');
                                    $saveModel[] = $historyModel;
                                    $excludedAttributes = ['sync_status', 'send_status', 'error_desc', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2'];
                                    $filteredAttributes = array_diff_key($existData->attributes, array_flip($excludedAttributes));
                                    $existMainData->attributes = $filteredAttributes;
                                    if ($collection_config == 1 && $allowSentbox == 1 && in_array($existMainData->originating_org_type, ['VLC', 'BMC']) && in_array($existMainData->originating_type, ['23', '24'])) {
                                        $existData->is_sentbox = TRUE;
                                    }
                                    $saveModel[] = $existMainData;
                                }
                            } else if ($action == 'DELETE' && (strtolower($status) == 'approve' || empty($approval_code))) {
                                $MainModel = new TblMilkCollection();
                                $existMainData = $MainModel->getExistingCollection($existData);
                                if (!empty($existMainData)) {
                                    $historyModel = new TblMilkCollectionHistory();
                                    Yii::$app->operation->history($existMainData, $historyModel, 'DELETE');
                                    $saveModel[] = $historyModel;
                                    $deleteModel[] = $existMainData;
                                    if ($collection_config == 1 && $allowSentbox == 1 && in_array($existMainData->originating_org_type, ['VLC', 'BMC']) && in_array($existMainData->originating_type, ['23', '24'])) {
                                        $existData->is_sentbox = TRUE;
                                    }
                                }
                            }
                        }
                        $historyModel = new TblCollectionDataAliasHistory();
                        Yii::$app->operation->history($existData, $historyModel, $historyFlag);
                        $existData->operation = 'UPDATE';
                        $saveModel[] = $historyModel;
                        if ($collection_config == 2) {
                            $existData->approval_status = $status;
                            $existData->approved_at = date('Y-m-d H:i:s');
                            $existData->approved_by = Yii::$app->session['UserCode'];
                            if (strtolower($status) != 'approve') {
                                $saveModel[] = $existData;
                            }
                        }
                    } else if ($operation == 'reject') {
                        if ($collection_config == 2) {
                            $status = 2;
                            $this->updateApprovalHistory($approval_code, $saveModel, $status);
                        }
                        $MainModel = new TblCollectionDataAliasReject();
                        $MainModel->attributes = $existData->attributes;
                        $saveModel[] = $MainModel;
                        $collModel = new TblMilkCollection();
                        $existMainData = $collModel->getExistingCollection($existData);
                        if ($action != 'CREATE' && $collection_config == 1 && $allowSentbox == 1 && in_array($existMainData->originating_org_type, ['VLC', 'BMC']) && in_array($existMainData->originating_type, ['23', '24'])) {
                            $existData->is_sentbox = TRUE;
                        }
                    }
                    if ($existData->validate()) {
                        $succCount++;
                        if (strtolower($status) == 'approve' || strtolower($status) == 'reject' || empty($approval_code)) {
                            $deleteModel[] = $existData;
                        }
                    } else {
                        $errorCount++;
                        $errorMsg = [];
                        foreach ($existData->getErrors() as $err) {
                            if (!empty($err[0])) {
                                $errorMsg[] = $err[0];
                            }
                        }
                        $existData->error_desc = implode(', ', $errorMsg);
                        $existData->approval_status = $approval_status;
                        $existData->scenario = 'approve';
                        $saveModel[] = $existData;
                    }
                    $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Milk Collection Approval', 'edit']);
                }
                $msg = $operation == 'approve' ? ('Milk Collection ' . strtolower($action) . ' approved successfully. <br />Approved count : ' . $succCount . '<br />Not approved count : ' . $errorCount) : ('Milk Collection ' . strtolower($action) . ' rejected successfully.  <br />Rejected count : ' . $succCount . '<br />Not Rejected count : ' . $errorCount);
                Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                    'message' => $msg]);
                $getData = Yii::$app->request->queryParams;
                if (!empty($getData['TblCollectionDataAliasSearch'])) {
                    return $this->redirect(['milk-collection-approve', 'TblCollectionDataAliasSearch' => $getData['TblCollectionDataAliasSearch']]);
                } else {
                    return $this->redirect(['milk-collection-approve']);
                }
            }
        }
        $showFarmer = TRUE;
        $searchModel = new TblCollectionDataAliasSearch();
        $searchModel->table_name = 'tbl_milk_collection';
        $searchModel->scenario = 'approvalCollection';
        if ($collection_config == 2) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);
        } else {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }
        $actionPerform = $searchModel->action_perform ? strtolower($searchModel->action_perform) : 'default';
        $id = 'milk-collection-approve-' . $actionPerform;
        $url = 'milk-collection-approve';
        return $this->render('milk_collection', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'showFarmer' => $showFarmer,
                    'id' => $id,
                    'url' => $url
        ]);
    }

    public function actionBmcCollectionApprove() {
        $union = !empty(Yii::$app->request->queryParams['TblCollectionDataAliasSearch']) ? (!empty(Yii::$app->request->queryParams['TblCollectionDataAliasSearch']['union_code']) ? Yii::$app->request->queryParams['TblCollectionDataAliasSearch']['union_code'] : '') : '';
        $collection_config = Yii::$app->general->getUnionConfiguration($union, 'collection_approval', 'PORTAL');

        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $succCount = 0;
                $errorCount = 0;
                $deletedata = Yii::$app->request->post('selection');
                foreach ($deletedata as $key => $value_code) {
                    $codes = explode('###', $value_code);
                    $value = $codes[0];
                    $approval_code = !empty($codes[1]) ? $codes[1] : '';
                    $action = !empty($codes[2]) ? $codes[2] : '';
                    $saveModel = [];
                    $deleteModel = [];
                    $operation = Yii::$app->request->post('TblCollectionDataAlias')['operation'];
                    $existData = $this->findModel($value);
                    $approval_status = $existData->approval_status;
                    $status = '';
                    ($operation == 'approve' && ($action == 'CREATE' || $action == 'UPDATE')) ? $existData->scenario = 'BmcCollection' : '';
                    if ($operation == 'approve') {
                        $historyFlag = 'DELETE';
                        if ($existData->validate()) {
                            if ($collection_config == 2 && !empty($approval_code)) {
                                $status = 1;
                                $this->updateApprovalHistory($approval_code, $saveModel, $status);
                                if (strtolower($status) != 'approve') {
                                    $historyFlag = 'UPDATE';
                                }
                            }

                            if ($action == 'CREATE' && (strtolower($status) == 'approve' || empty($approval_code))) {
                                $MainModel = new TblBmcCollection();
                                $MainModel->attributes = $existData->attributes;
                                $MainModel->setModel($MainModel);
                                $MainModel->rate_code = $existData->purchase_rate_code;
                                $MainModel->purchase_rate_code = NULL;
                                $saveModel[] = $MainModel;
                            } else if ($action == 'UPDATE' && (strtolower($status) == 'approve' || empty($approval_code))) {
                                $MainModel = new TblBmcCollection();
                                $existMainData = $MainModel->getExistingCollection($existData);
                                if (!empty($existMainData)) {
                                    $historyModel = new TblBmcCollectionHistory();
                                    Yii::$app->operation->history($existMainData, $historyModel, 'UPDATE');
                                    $saveModel[] = $historyModel;
                                    $existMainData->attributes = $existData->attributes;
                                    $saveModel[] = $existMainData;
                                }
                                //$existMainData->attributes = $existData->attributes;
                                //$saveModel[] = $existMainData;
                            } else if ($action == 'DELETE' && (strtolower($status) == 'approve' || empty($approval_code))) {
                                $MainModel = new TblBmcCollection();
                                $existData->old_customer_code = !empty($existData->old_customer_code) ? $existData->old_customer_code : $existData->customer_code;
                                $existMainData = $MainModel->getExistingCollection($existData);
                                if (!empty($existMainData)) {
                                    $historyModel = new TblBmcCollectionHistory();
                                    Yii::$app->operation->history($existMainData, $historyModel, 'DELETE');
                                    $saveModel[] = $historyModel;
                                    $deleteModel[] = $existMainData;
                                }
                                //$deleteModel[] = $existMainData;
                            }
                            if ($existData->route_code != $existData->old_route_code && $existData->customer_type == 'DCS') {
                                $collectionUpdate = new TblBmcCollection();
                                $collectionUpdate->milkCollectionUpdate($existData);
                            }
                        }
                        $historyModel = new TblCollectionDataAliasHistory();
                        Yii::$app->operation->history($existData, $historyModel, $historyFlag);
                        $saveModel[] = $historyModel;
                        if ($collection_config == 2) {
                            $existData->approval_status = $status;
                            $existData->approved_at = date('Y-m-d H:i:s');
                            $existData->approved_by = Yii::$app->session['UserCode'];
                            if (strtolower($status) != 'approve') {
                                $saveModel[] = $existData;
                            }
                        }
                    } else if ($operation == 'reject') {
                        if ($collection_config == 2) {
                            $status = 2;
                            $this->updateApprovalHistory($approval_code, $saveModel, $status);
                        }
                        $MainModel = new TblCollectionDataAliasReject();
                        $MainModel->attributes = $existData->attributes;
                        $saveModel[] = $MainModel;
                    }
                    if ($existData->validate()) {
                        $succCount++;
                        if (strtolower($status) == 'approve' || strtolower($status) == 'reject' || empty($approval_code)) {
                            $deleteModel[] = $existData;
                        }
                    } else {
                        $errorCount++;
                        $errorMsg = [];
                        foreach ($existData->getErrors() as $err) {
                            if (!empty($err[0])) {
                                $errorMsg[] = $err[0];
                            }
                        }
                        $existData->error_desc = implode(', ', $errorMsg);
                        $existData->approval_status = $approval_status;
                        $existData->scenario = 'approve';
                        $saveModel[] = $existData;
                    }

                    $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['BMC Collection Approval', 'edit']);
                }
                $msg = $operation == 'approve' ? ('BMC Collection ' . strtolower($action) . ' approved successfully. <br />Approved count : ' . $succCount . '<br />Not approved count : ' . $errorCount) : ('BMC Collection ' . strtolower($action) . ' rejected successfully.  <br />Rejected count : ' . $succCount . '<br />Not Rejected count : ' . $errorCount);
                Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                    'message' => $msg]);
                $getData = Yii::$app->request->queryParams;
                if (!empty($getData['TblCollectionDataAliasSearch'])) {
                    return $this->redirect(['bmc-collection-approve', 'TblCollectionDataAliasSearch' => $getData['TblCollectionDataAliasSearch']]);
                } else {
                    return $this->redirect(['bmc-collection-approve']);
                }
            }
        }
        $showType = TRUE;
        $searchModel = new TblCollectionDataAliasSearch();
        $searchModel->table_name = 'tbl_bmc_collection';
        if ($collection_config == 2) {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);
        } else {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }
        $searchModel->scenario = 'approvalCollection';
        $actionPerform = $searchModel->action_perform ? strtolower($searchModel->action_perform) : 'default';
        $id = 'bmc-collection-approve-' . $actionPerform;
        $url = 'bmc-collection-approve';
        return $this->render('bmc_collection', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'id' => $id,
                    'url' => $url,
                    'showType' => $showType,
        ]);
    }

    public function actionMilkDispatchApprove() {
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $succCount = 0;
                $errorCount = 0;
                $deletedata = Yii::$app->request->post('selection');
                foreach ($deletedata as $key => $value) {
                    $codes = explode('###', $value);
                    $value = $codes[0];
                    $action = !empty($codes[1]) ? $codes[1] : '';
                    $saveModel = [];
                    $deleteModel = [];
                    $operation = Yii::$app->request->post('TblCollectionDataAlias')['operation'];
                    $existData = $this->findModel($value);
                    ($operation == 'approve' && ($action == 'CREATE' || $action == 'UPDATE')) ? $existData->scenario = 'MilkDispatch' : '';
                    if ($operation == 'approve') {
                        if ($existData->validate()) {
                            if ($action == 'CREATE') {
                                $MainModel = new TblDcsMilkDispatch();
                                $MainModel->attributes = $existData->attributes;
                                $existMainData = $MainModel->getExistingDispatch($existData);
                                if (!empty($existMainData)) {
                                    $MainModel->dcs_milk_dispatch_code = $existMainData->dcs_milk_dispatch_code;
                                } else {
                                    $MainModel->dcs_milk_dispatch_code = Yii::$app->general->getPrimaryCode($MainModel);
                                    $MainModel->date_time_of_dispatch = $existData->date_time_of_collection;
                                    $saveModel[] = $MainModel;
                                }
                                $TxModel = new TblDcsMilkDispatchTxn();
                                $TxModel->attributes = $existData->attributes;
                                $TxModel->attributes = $MainModel->attributes;
                                $TxModel->setModelData($existData, $TxModel);
                                $TxModel->dcs_milk_dispatch_txn_code = Yii::$app->general->getTransactionCode($TxModel, $MainModel->dcs_milk_dispatch_code);
                                $saveModel[] = $TxModel;
                            } else if ($action == 'UPDATE') {
                                $MainModel = new TblDcsMilkDispatch();
                                $existMainData = $MainModel->getExistingDispatch($existData);
                                if (!empty($existMainData)) {
                                    $historyModel = new TblDcsMilkDispatchHistory();
                                    Yii::$app->operation->history($existMainData, $historyModel, 'UPDATE');
                                    $saveModel[] = $historyModel;
                                    $existMainData->attributes = $existData->attributes;
                                    $saveModel[] = $existMainData;

                                    $TxModel = new TblDcsMilkDispatchTxn();
                                    $TxModel->dcs_milk_dispatch_code = $existMainData->dcs_milk_dispatch_code;
                                    $existTxModel = $TxModel->getTxnExistingCollection($existData);
                                    if (!empty($existTxModel)) {
                                        $historyTxnModel = new TblDcsMilkDispatchTxnHistory();
                                        Yii::$app->operation->history($existTxModel, $historyTxnModel, 'UPDATE');
                                        $saveModel[] = $historyTxnModel;

                                        $existTxModel->attributes = $existData->attributes;
                                        $existTxModel->setModelData($existData, $existTxModel);
                                        $saveModel[] = $existTxModel;
                                    }
                                }
                            } else if ($action == 'DELETE') {
                                $MainModel = new TblDcsMilkDispatch();
                                $existMainData = $MainModel->getExistingDispatch($existData);
                                if (!empty($existMainData)) {
                                    $txCount = TblDcsMilkDispatchTxn::find()->where(['dcs_milk_dispatch_code' => $existMainData->dcs_milk_dispatch_code])->count();
                                    if (!empty($existMainData) && $txCount <= 1) {
                                        $deleteModel[] = $existMainData;
                                    }
                                    $TxModel = new TblDcsMilkDispatchTxn();
                                    $TxModel->dcs_milk_dispatch_code = $existMainData->dcs_milk_dispatch_code;
                                    $existTxModel = $TxModel->getTxnExistingCollection($existData);
                                    if (!empty($existTxModel)) {
                                        $historyTxnModel = new TblDcsMilkDispatchTxnHistory();
                                        Yii::$app->operation->history($existTxModel, $historyTxnModel, 'UPDATE');
                                        $saveModel[] = $historyTxnModel;
                                        $deleteModel[] = $existTxModel;
                                    }
                                }
                            }
                        }
                        $historyModel = new TblCollectionDataAliasHistory();
                        Yii::$app->operation->history($existData, $historyModel, DELETE);
                        $saveModel[] = $historyModel;
                    } else if ($operation == 'reject') {
                        $MainModel = new TblCollectionDataAliasReject();
                        $MainModel->attributes = $existData->attributes;
                        $saveModel[] = $MainModel;
                    }
                    if ($existData->validate()) {
                        $succCount++;
                        $deleteModel[] = $existData;
                    } else {
                        $errorCount++;
                        $errorMsg = [];
                        foreach ($existData->getErrors() as $err) {
                            if (!empty($err[0])) {
                                $errorMsg[] = $err[0];
                            }
                        }
                        $existData->error_desc = implode(', ', $errorMsg);
                        $existData->scenario = 'approve';
                        $saveModel[] = $existData;
                    }
                    $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Milk Dispatch Approval', 'edit']);
                }
                $msg = $operation == 'approve' ? ('Milk Dispatch ' . strtolower($action) . ' approved successfully. <br />Approved count : ' . $succCount . '<br />Not approved count : ' . $errorCount) : ('MPP Dispatch ' . strtolower($action) . ' rejected successfully.  <br />Rejected count : ' . $succCount . '<br />Not Rejected count : ' . $errorCount);
                Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                    'message' => $msg]);
                $getData = Yii::$app->request->queryParams;
                if (!empty($getData['TblCollectionDataAliasSearch'])) {
                    return $this->redirect(['milk-dispatch-approve', 'TblCollectionDataAliasSearch' => $getData['TblCollectionDataAliasSearch']]);
                } else {
                    return $this->redirect(['milk-dispatch-approve']);
                }
            }
        }
        $searchModel = new TblCollectionDataAliasSearch();
        $searchModel->table_name = 'tbl_dcs_milk_dispatch';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $searchModel->scenario = 'approvalDispatch';
        $actionPerform = $searchModel->action_perform ? strtolower($searchModel->action_perform) : 'default';
        $id = 'milk-dispatch-approve-' . $actionPerform;
        $url = 'milk-dispatch-approve';
        return $this->render('milk_dispatch', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'id' => $id,
                    'url' => $url,
        ]);
    }

    /**
     * Finds the TblCollectionDataAlias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblCollectionDataAlias the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblCollectionDataAlias::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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

    public function actionQtyImportApproval() {
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $succCount = 0;
                $errorCount = 0;
                $deletedata = Yii::$app->request->post('selection');
                $i = 1;
                foreach ($deletedata as $key => $value) {
                    $operation = Yii::$app->request->post('TblCollectionDataAlias')['operation'];
                    $saveModel = [];
                    $deleteModel = [];
                    $existData = $this->findModel($value);
                    if ($operation == 'approve') {
                        $MainModel = new TblMilkCollection();
                        $MainModel->attributes = $existData->attributes;
                        $MainModel->setModel($MainModel);
                        // $MainModel->sample_no = $MainModel->getSampleNo();
                        $MainModel->scenario = 'importApproval';
                        $historyModel = new TblCollectionDataAliasHistory();
                        Yii::$app->operation->history($existData, $historyModel, DELETE);
                    } else if ($operation == 'reject') {
                        $MainModel = new TblCollectionDataAliasReject();
                        $MainModel->attributes = $existData->attributes;
                        $saveModel[] = $MainModel;
                        $historyModel = new TblCollectionDataAliasHistory();
                        Yii::$app->operation->history($existData, $historyModel, DELETE);
                    }
                    $deleteModel[] = $existData;
                    $saveModel[] = $MainModel;
                    $saveModel[] = $historyModel;

                    $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Milk Collection approved', 'create']);
                    if ($transaction == 'customRedirect') {
                        $succCount++;
                    } else {
                        $errorCount++;
                        $errorMsg = [];
                        foreach ($MainModel->getErrors() as $err) {
                            if (!empty($err[0])) {
                                $errorMsg[] = $err[0];
                            }
                        }
                        $existData->error_desc = implode(', ', $errorMsg);
                        $existData->save();
                    }
                }

                $msg = 'Milk Collection approved successfully. <br />Approved count : ' . $succCount . '<br />Not approved count : ' . $errorCount;
                Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                    'message' => $msg]);
                $getData = Yii::$app->request->queryParams;
                if (!empty($getData['TblCollectionDataAliasSearch'])) {
                    return $this->redirect(['qty-import-approval', 'TblCollectionDataAliasSearch' => $getData['TblCollectionDataAliasSearch']]);
                } else {
                    return $this->redirect(['qty-import-approval']);
                }
            }
        }
        $searchModel = new TblCollectionDataAliasSearch();
        $searchModel->table_name = 'qty_import_approval';
        $searchModel->action_perform = 'IMPORT';
        $dataProvider = $searchModel->qtyimportsearch(Yii::$app->request->queryParams);
        $searchModel->scenario = 'approvalQtyImport';
        return $this->render('_import_approval', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
