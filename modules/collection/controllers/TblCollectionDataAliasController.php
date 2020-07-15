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

/**
 * TblCollectionDataAliasController implements the CRUD actions for TblCollectionDataAlias model.
 */
class TblCollectionDataAliasController extends \app\controllers\ChildController {

    public function actionMilkCollectionApprove() {
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $succCount = 0;
                $errorCount = 0;
                $deletedata = Yii::$app->request->post('selection');
                foreach ($deletedata as $key => $value) {
                    $saveModel = [];
                    $deleteModel = [];
                    $action = Yii::$app->request->post('TblCollectionDataAlias')['action_perform'];
                    $operation = Yii::$app->request->post('TblCollectionDataAlias')['operation'];
                    $existData = $this->findModel($value);
                    ($operation == 'approve' && ($action == 'CREATE' || $action == 'UPDATE')) ? $existData->scenario = 'MilkCollection' : '';
                    if ($operation == 'approve') {
                        if ($existData->validate()) {
                            if ($action == 'CREATE') {
                                $MainModel = new TblMilkCollection();
                                $MainModel->attributes = $existData->attributes;
                                $MainModel->setModel($MainModel);
                                $saveModel[] = $MainModel;
                            } else if ($action == 'UPDATE') {
                                $MainModel = new TblMilkCollection();
                                $existMainData = $MainModel->getExistingCollection($existData);
                                $existMainData->attributes = $existData->attributes;
                                $saveModel[] = $existMainData;
                            } else if ($action == 'DELETE') {
                                $MainModel = new TblMilkCollection();
                                $existMainData = $MainModel->getExistingCollection($existData);
                                $deleteModel[] = $existMainData;
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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $searchModel->scenario = 'approvalCollection';
        $showField = $searchModel->action_perform == 'UPDATE' ? TRUE : FALSE;
        $id = 'milk-collection-approve-' . strtolower($searchModel->action_perform);
        $url = 'milk-collection-approve';
        return $this->render('milk_collection', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'showFarmer' => $showFarmer,
                    'showField' => $showField,
                    'id' => $id,
                    'url' => $url
        ]);
    }

    public function actionBmcCollectionApprove() {
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $succCount = 0;
                $errorCount = 0;
                $deletedata = Yii::$app->request->post('selection');
                foreach ($deletedata as $key => $value) {
                    $saveModel = [];
                    $deleteModel = [];
                    $action = Yii::$app->request->post('TblCollectionDataAlias')['action_perform'];
                    $operation = Yii::$app->request->post('TblCollectionDataAlias')['operation'];
                    $existData = $this->findModel($value);
                    ($operation == 'approve' && ($action == 'CREATE' || $action == 'UPDATE')) ? $existData->scenario = 'BmcCollection' : '';
                    if ($operation == 'approve') {
                        if ($existData->validate()) {
                            if ($action == 'CREATE') {
                                $MainModel = new TblBmcCollection();
                                $MainModel->attributes = $existData->attributes;
                                $MainModel->setModel($MainModel);
                                $MainModel->rate_code = $existData->purchase_rate_code;
                                $MainModel->purchase_rate_code = NULL;
                                $saveModel[] = $MainModel;
                            } else if ($action == 'UPDATE') {
                                $MainModel = new TblBmcCollection();
                                $existMainData = $MainModel->getExistingCollection($existData);
                                $existMainData->attributes = $existData->attributes;
                                $saveModel[] = $existMainData;
                            } else if ($action == 'DELETE') {
                                $MainModel = new TblBmcCollection();
                                $existMainData = $MainModel->getExistingCollection($existData);
                                $deleteModel[] = $existMainData;
                            }
                        }
                        $historyModel = new TblBmcCollectionHistory();
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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $searchModel->scenario = 'approvalCollection';
        $showField = $searchModel->action_perform == 'UPDATE' ? TRUE : FALSE;
        $id = 'bmc-collection-approve-' . strtolower($searchModel->action_perform);
        $url = 'bmc-collection-approve';
        return $this->render('bmc_collection', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'showField' => $showField,
                    'id' => $id,
                    'url' => $url,
                    'showType' => $showType,
        ]);
    }

    public function actionVillageDispatchApprove() {
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $succCount = 0;
                $errorCount = 0;
                $deletedata = Yii::$app->request->post('selection');
                foreach ($deletedata as $key => $value) {
                    $saveModel = [];
                    $deleteModel = [];
                    $action = Yii::$app->request->post('TblCollectionDataAlias')['action_perform'];
                    $operation = Yii::$app->request->post('TblCollectionDataAlias')['operation'];
                    $existData = $this->findModel($value);
                    ($operation == 'approve' && ($action == 'CREATE' || $action == 'UPDATE')) ? $existData->scenario = 'DispatchVillage' : '';
                    if ($operation == 'approve') {
                        if ($existData->validate()) {
                            if ($action == 'CREATE') {
                                $MainModel = new VillageDispatch();
                                $MainModel->attributes = $existData->attributes;
                                $saveModel[] = $MainModel;
                            } else if ($action == 'UPDATE') {
                                $MainModel = new VillageDispatch();
                                $existMainData = $MainModel->getExistingCollection($existData);
                                $existMainData->attributes = $existData->attributes;
                                $saveModel[] = $existMainData;
                            } else if ($action == 'DELETE') {
                                $MainModel = new VillageDispatch();
                                $existMainData = $MainModel->getExistingCollection($existData);
                                $deleteModel[] = $existMainData;
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
                    $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['MPP Dispatch Approval', 'edit']);
                }
                $msg = $operation == 'approve' ? ('MPP Dispatch ' . strtolower($action) . ' approved successfully. <br />Approved count : ' . $succCount . '<br />Not approved count : ' . $errorCount) : ('MPP Dispatch ' . strtolower($action) . ' rejected successfully.  <br />Rejected count : ' . $succCount . '<br />Not Rejected count : ' . $errorCount);
                Yii::$app->getSession()->setFlash('success', ['type' => 'success',
                    'message' => $msg]);
                $getData = Yii::$app->request->queryParams;
                if (!empty($getData['TblCollectionDataAliasSearch'])) {
                    return $this->redirect(['village-dispatch-approve', 'TblCollectionDataAliasSearch' => $getData['TblCollectionDataAliasSearch']]);
                } else {
                    return $this->redirect(['village-dispatch-approve']);
                }
            }
        }
        $searchModel = new TblCollectionDataAliasSearch();
        $searchModel->table_name = 'VillageDispatch';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $searchModel->scenario = 'approvalCollection';
        $showField = $searchModel->action_perform == 'UPDATE' ? TRUE : FALSE;
        $id = 'village-dispatch-approve-' . strtolower($searchModel->action_perform);
        $url = 'collection-village-approve';
        return $this->render('village_dispatch', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'showField' => $showField,
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

}
