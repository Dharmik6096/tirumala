<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblMilkCollection;
use app\modules\collection\models\TblMilkCollectionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\dcsoperation\models\TblPurchaseRate;
use yii\web\Response;
use yii\helpers\Json;
use yii\data\ArrayDataProvider;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use app\modules\dcsoperation\models\TblPurchaseRateDetails;
use app\modules\dcsoperation\models\TblMember;
use app\modules\collection\models\TblCollectionDataAlias;
use app\modules\syncutility\models\TblInbox;
use app\modules\syncutility\models\TblSyncLog;
use yii\widgets\ActiveForm;
use yii\base\Model;
use app\modules\collection\models\TblMilkCollectionHistory;
use app\modules\organisation\models\TblUnions;
use app\modules\collection\models\OnlineCollectionModel;
use app\modules\organisation\models\TblBmcMilkType;
use app\modules\bkgprocess\models\TblFtpTxnLog;
use PHPExcel;
use app\modules\general\models\TblApprovalStagesDetail;
use app\modules\collection\models\TblIotTemperatureSearch;

/**
 * TblMilkCollectionController implements the CRUD actions for TblMilkCollection model.
 */
class TblMilkCollectionController extends \app\controllers\ChildController {

    public $freeAccessActions = ['validate-rtpl', 'validate-member', 'calculate-clr', 'list-grid', 'qlty-type-config', 'check-fat-range'];
    public $fileDownloadArr = [];

    /**
     * Lists all TblMilkCollection models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMilkCollectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMilkCollection model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblMilkCollection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblMilkCollection();
        $searchModel = new TblMilkCollectionSearch();
        $searchModel->attributes = Yii::$app->request->get('TblMilkCollection');
        $dataProvider = $searchModel->createsearch([]);

        $dataProvider->sort = false;
        $this->model->date_time_of_collection = date('d-m-Y');
        $this->model->milk_type_code = 1;
        $this->model->milk_quality_type_code = 1;
        $this->model->shift_code = 1;
        $this->viewFile = 'create';
        $modelSave = [];
        $message = 'Milk Collection';
        $type = 'create';
        $auto_key_config = [];
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->model->setCollectionData($this->model, 'create');

            if ($this->model->validate()) {
                $this->model->postDataSet($this->model, 'create', $modelSave, $auto_key_config, $message, $type);

                if (!empty($auto_key_config)) {
                    $transaction = $this->generalModel->saveTransactionMultiAutoIncForeignKey($modelSave, [$message, $type], $auto_key_config);
                } else {
                    $transaction = $this->generalModel->saveTransaction($modelSave, [$message, $type]);
                }
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

    /**
     * Updates an existing TblMilkCollection model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->milk_collection_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblMilkCollection model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblMilkCollection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMilkCollection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMilkCollection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionValidateRtpl() {
        $response = [];
        $response['status'] = 'error';
        $data['dcs_code'] = Yii::$app->request->post('dcs_code');
        $data['milk_type'] = Yii::$app->request->post('milk_type');
        $data['milk_quality_type'] = Yii::$app->request->post('milk_quality_type');
        $data['dt_date'] = Yii::$app->request->post('dt_date');
        $data['dt_date'] = (Yii::$app->request->post('dt_date')) ? Yii::$app->formatter->asDate(Yii::$app->request->post('dt_date'), DATE_FORMAT) : '';
        $data['shift'] = Yii::$app->request->post('shift_code');
        $data['dt_date'] = $data['dt_date'] . ' ' . \Yii::$app->general->getshift($data['shift']);
        $data['fat'] = Yii::$app->request->post('fat');
        $data['snf'] = Yii::$app->request->post('snf');
        $member = Yii::$app->request->post('member');

        $model = new TblMilkCollection();
        $response = $model->calculateData('rtpl_calculate', '', '', $data['fat'], $data['snf'], $data['milk_type'], $data, $member);


        return Json::encode($response);
    }

    public function actionRepostSapData() {
        $searchModel = new TblMilkCollectionSearch();
        $searchModel->scenario = 'repostSapData';
        $searchModel->load(Yii::$app->request->queryParams);
        $searchModel->sap_collection_type = !empty($searchModel->sap_collection_type) ? $searchModel->sap_collection_type : 0;

        $output = [];
        if ($searchModel->validate()) {
            if (empty($searchModel->f_union_code)) {
                $searchModel->f_union_code = !empty(Yii::$app->session->get('organizations_code')) ? ',' . Yii::$app->session->get('organizations_code') . ',' : 0;
            }
            if (empty($searchModel->f_plant_code)) {
                $searchModel->f_plant_code = !empty(Yii::$app->session->get('Plant')) ? ',' . Yii::$app->session->get('Plant') . ',' : 0;
            }
            if (empty($searchModel->f_mcc_code)) {
                $searchModel->f_mcc_code = !empty(Yii::$app->session->get('MCC')) ? ',' . Yii::$app->session->get('MCC') . ',' : 0;
            }
            $searchModel->from_date = !empty($searchModel->from_date) ? $searchModel->from_date : date('d-m-Y');
            $searchModel->from_shift = !empty($searchModel->from_shift) ? $searchModel->from_shift : 3;
            $searchModel->to_date = !empty($searchModel->to_date) ? $searchModel->to_date : date('d-m-Y');
            $searchModel->to_shift = !empty($searchModel->to_shift) ? $searchModel->to_shift : 3;
            $sp_name = 'sp_sap_data_repost';
            $sp_param = [];
            $sp_param[] = '001'; //!empty($searchModel->f_union_code) ? $searchModel->f_union_code : 0;
            $sp_param[] = !empty($searchModel->f_plant_code) ? $searchModel->f_plant_code : 0;
            $sp_param[] = !empty($searchModel->f_mcc_code) ? $searchModel->f_mcc_code : 0;
            $sp_param[] = date('Y-m-d', strtotime($searchModel->from_date)) . ' ' . \Yii::$app->general->getshift($searchModel->from_shift) . '.000';
            $sp_param[] = date('Y-m-d', strtotime($searchModel->to_date)) . ' ' . \Yii::$app->general->getshift($searchModel->to_shift) . '.000';
            $sp_param[] = $searchModel->sap_collection_type;
            $sp_param[] = !empty($searchModel->sap_data_post_status) || $searchModel->sap_data_post_status == '0' ? $searchModel->sap_data_post_status : NULL;
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $output,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [],
                'attributes' => [
                    'data_post_id',
                    'collection_date',
                    'shift',
                    'sample_no',
                    'sap_status',
                    'mcc_name',
                    'society_code',
                    'dcs_name',
                    'member_code',
                    'member_name',
                    'fat',
                    'snf',
                    'quantity',
                    'response_description'
                ],
            ],
        ]);

        if (Yii::$app->request->post() && !empty(Yii::$app->request->post('selection'))) {
            $data = Yii::$app->request->post();
            $selection = $data['selection'];
            $master = [];
            $child = [];
            $flag = $data['flag'];
            $modelName = $flag == '1' ? 'TblBmcCollection' : 'TblMilkCollection';
            foreach ($selection as $select) {
                $model_name = Yii::$app->path->define($modelName);
                $model = new $model_name();
                $model->data_post_id = $select;
                $modelData = $model->find()
                        ->where(['data_post_id' => $model->data_post_id])
                        ->one();

                if (!empty($modelData)) {
                    $hModel = $modelName . 'History';
                    $h_model_name = Yii::$app->path->define($hModel);
                    $historyModel = new $h_model_name();
                    Yii::$app->operation->history($modelData, $historyModel, 'UPDATE');
                    $child[] = $historyModel;
                    $model = $modelData;
                    $model->data_post_status = null;
                    $master[] = $model;
                }
            }

            $transaction = $this->generalModel->saveTransaction($master, $child, ['SAP Data Repost', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['repost-sap-data']);
            }
        }
//        var_dump($searchModel->validate());die;
        return $this->render('_repost_sap_data', [
                    'model' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

    public function actionListGrid() {
        $searchModel = new TblMilkCollectionSearch();
        $searchModel->attributes = Yii::$app->request->get('TblMilkCollection');
        $dataProvider = $searchModel->createsearch([]);
        return $this->renderAjax('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    public function actionValidateMember() {
        $member = Yii::$app->request->post('member_code');
        $model = new TblMember();
        $data = $model->validMember($member);
        if (!empty($data)) {
            return Json::encode(['status' => 'success', 'member_details' => $data]);
        } else {
            return Json::encode(['status' => 'error']);
        }
    }

    public function actionCalculateClr() {
        $response = [];
        $response['status'] = 'success';
        $response['data'] = '';
        (float) $fat = Yii::$app->request->post('fat');
        (float) $snf = Yii::$app->request->post('snf');
        $union = Yii::$app->request->post('union_code');
        $org_code = Yii::$app->request->post('bmcCode');
        $model = new TblMilkCollection();
        $result = $model->calculateData('calculate_clr', $union, $org_code, $fat, $snf);
        $response['data'] = $result['clr'];
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($response);
    }

    public function actionUpdateCollection() {
        $searchModel = new TblMilkCollectionSearch();
        $postData = Yii::$app->request->post();
        $collCodes = [];
        if (!empty($postData) && !empty($postData['collectionCodes'])) {
            $collCodes = $postData['collectionCodes'];
            $collCodes = array_values((array) json_decode($collCodes));
        }
        $dataProvider = $searchModel->updatesarch(Yii::$app->request->queryParams, $collCodes);
        $searchModel->scenario = 'updateMilkCollection';
        $detailModel = $dataProvider->getModels();
        $message = 'Milk Collection';
        $type = 'edit';
        $allowSentbox = Yii::$app->general->getUnionConfiguration($searchModel->union_code, 'collection_approval_sentbox', 'PORTAL');
        $collectionApprovalConfig = Yii::$app->general->getUnionConfigResult($searchModel->union_code, 'collection_approval');
        $configVal = isset(Yii::$app->session->get('unionConfig')[$searchModel->union_code]['qlty_wise_collection']) ? Yii::$app->session->get('unionConfig')[$searchModel->union_code]['qlty_wise_collection'] : 0;
        $config = $configVal == 1 ? TRUE : FALSE;
        if (Yii::$app->request->post()) {
            foreach ($detailModel as $detail) {
                $detail->scenario = 'update';
//                $detail->rtpl = '';
            }
            $modelData = [];
            Model::loadMultiple($detailModel, Yii::$app->request->post());

            foreach ($detailModel as $detail) {
                $detail->scenario = 'update';
                $modelData[] = $detail;
            }

            if (Model::validateMultiple($modelData)) {
                $saveModel = [];
                $i = 0;
                $auto_key_config = [];
                foreach ($modelData as $detailKey => $detalData) {
                    if (!empty($detalData->oldAttributes) && ($detalData->fat != $detalData->oldAttributes['fat'] || $detalData->snf != $detalData->oldAttributes['snf'] || $detalData->rtpl != $detalData->oldAttributes['rtpl'] || $detalData->qty != $detalData->oldAttributes['qty'] || $detalData->milk_type_code != $detalData->oldAttributes['milk_type_code'] || $detalData->milk_quality_type_code != $detalData->oldAttributes['milk_quality_type_code'] || $detalData->antibiotic != $detalData->oldAttributes['antibiotic'])) {
                        if (in_array($collectionApprovalConfig, [1, 2])) {
                            $approvalModel = new TblCollectionDataAlias();
                            $excludedAttributes = ['sync_status', 'send_status', 'error_desc', 'created_at', 'created_by', 'updated_at', 'updated_by', 'x_col1', 'x_col2'];
                            $filteredAttributes = array_diff_key($detalData->attributes, array_flip($excludedAttributes));
                            $approvalModel->attributes = $filteredAttributes;
                            $approvalModel->old_qty = $detalData->oldAttributes['qty'];
                            $approvalModel->old_fat = $detalData->oldAttributes['fat'];
                            $approvalModel->old_snf = $detalData->oldAttributes['snf'];
                            $approvalModel->old_rtpl = $detalData->oldAttributes['rtpl'];
                            $approvalModel->old_amount = $detalData->oldAttributes['amount'];
                            $approvalModel->old_milk_type_code = $detalData->oldAttributes['milk_type_code'];
                            $approvalModel->old_milk_quality_type_code = $detalData->oldAttributes['milk_quality_type_code'];
                            $approvalModel->old_purchase_rate_code = $detalData->oldAttributes['purchase_rate_code'];
                            $approvalModel->old_clr = $detalData->oldAttributes['clr'];
                            $approvalModel->table_name = 'tbl_milk_collection';
                            $approvalModel->action_perform = 'UPDATE';
                            $approvalModel->date_time_of_collection = $detalData->date_time_of_collection . ' ' . \Yii::$app->general->getshift($detalData->shift_code);
                            $approvalModel->x_col1 = Yii::$app->general->getUuid();
                            $approvalModel->x_col3 = \Yii::$app->session->get('organizations_code');
                            $approvalModel->x_col4 = 'PORTAL';
                            $approvalModel->x_col5 = 0;
                            if ($collectionApprovalConfig == 1 && $allowSentbox == 1 && in_array($detalData->originating_org_type, ['VLC', 'BMC']) && in_array($detalData->originating_type, ['23', '24'])) {
                                $approvalModel->approval_status = 'Pending';
                                $approvalModel->is_sentbox = TRUE;
                                $approvalModel->operation = 'INSERT';
                            }
                            if ($collectionApprovalConfig == 2) {
                                $modelStages = new TblApprovalStagesDetail();
                                $modelStages->setProcessWiseApprovalData($approvalModel, $approvalModel->union_code, 'tbl_milk_collection', $saveModel, $auto_key_config, $i, TRUE, 'collection_data_alias_code');
                                $i++;
                            } else {
                                $saveModel[] = $approvalModel;
                            }
                            $message = 'Data For Approval';
                            $type = 'create';
                        } else {
                            $existData = $this->findModel($detalData->milk_collection_code);
                            $historyModel = new TblMilkCollectionHistory();
                            Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                            $saveModel[] = $historyModel;
                            $existData->attributes = $detalData->attributes;
                            $existData->date_time_of_collection = $detalData->date_time_of_collection . ' ' . \Yii::$app->general->getshift($detalData->shift_code);
                            $saveModel[] = $existData;
                        }
                    }
                }
                if (!empty($auto_key_config)) {
                    $transaction = $this->generalModel->saveTransactionMultiAutoIncForeignKey($saveModel, [$message, $type], $auto_key_config);
                } else {
                    $transaction = $this->generalModel->saveTransaction($saveModel, [$message, $type]);
                }
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            }
        }
        if (!empty($detailModel)) {
            $dataProvider = new ArrayDataProvider([
                'allModels' => $detailModel,
                'pagination' => FALSE,
            ]);
        }
        return $this->render('update', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'detailModel' => $detailModel,
                    'config' => $config,
        ]);
    }

    public function actionDeleteCollection() {
        $searchModel = new TblMilkCollectionSearch();
        $dataProvider = $searchModel->deletesearch(Yii::$app->request->queryParams);
        $msg = '';
        if (!empty($searchModel->from_date) && !empty($searchModel->from_shift) && !empty($searchModel->from_date) && !empty($searchModel->to_shift)) {
            $from_date = date('Y-m-d', strtotime($searchModel->from_date));
            $from_shift = \Yii::$app->general->getshift($searchModel->from_shift);
            $from_date .= ' ' . $from_shift;
            $to_date = date('Y-m-d', strtotime($searchModel->to_date));
            $to_shift = \Yii::$app->general->getshift(strtoupper($searchModel->to_shift));
            $to_date .= ' ' . $to_shift;

            $sp_param = [];
            $sp_param[] = $searchModel->union_code;
            $sp_param[] = $searchModel->plant_code;
            $sp_param[] = $searchModel->bmc_code;
            $sp_param[] = 'BMC';
            $sp_param[] = 'DCS';
            $sp_param[] = $from_date;
            $sp_param[] = $to_date;
            $sp_param[] = 'data_lock_member';
            $sp_param[] = 'billing_lock_member';
            $sp_name = 'sp_validate_payment_cycle_lock_collection';
            $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
            $type = 'success';
            $a = [];
            $msg = '';
            if (!empty($output)) {
                $type = 'error';
                $msg = Yii::t('app', 'Payment Cycle Locked for Following BMC.');
                $msg .= '<ul>';
                foreach ($output as $fcK => $fcVal) {
                    $mccCode = $fcVal['bmcCode'];
                    if (!in_array($mccCode, $a)) {
                        $a[] = $mccCode;
                        $msg .= '<li>' . $fcVal['bmc'] . '</li>';
                    }
                }
                $msg .= '</ul>';
            } else {
                $sp_param = [];
                $sp_param[] = $searchModel->union_code;
                $sp_param[] = $searchModel->plant_code;
                $sp_param[] = $searchModel->mcc_plant_code;
                $sp_param[] = $from_date;
                $sp_param[] = $to_date;
                $sp_param[] = 'milk_collection';
                $sp_name = 'sp_validate_mcc_shift_lock_collection';
                $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
                $type = 'success';
                $msg = '';
                $a = [];
                if (!empty($output)) {
                    $type = 'error';
                    $msg = Yii::t('app', 'Shift Locked for Following MCC.');
                    $msg .= '<ul>';
                    foreach ($output as $fcK => $fcVal) {
                        $mccCode = $fcVal['mccCode'];
                        if (!in_array($mccCode, $a)) {
                            $a[] = $mccCode;
                            $msg .= '<li>' . $fcVal['mcc'] . '</li>';
                        }
                    }
                    $msg .= '</ul>';
                }
            }
        }
        if (!empty($msg)) {
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => $msg]);
        }
        $searchModel->scenario = 'deleteMilkCollection';
        $configVal = isset(Yii::$app->session->get('unionConfig')[$searchModel->union_code]['qlty_wise_collection']) ? Yii::$app->session->get('unionConfig')[$searchModel->union_code]['qlty_wise_collection'] : 0;
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $deleteModel = [];
                $message = 'Data For Approval';
                $type = 'create';
                $deletedata = Yii::$app->request->post('selection');
                $codes = empty(Yii::$app->request->post('selection')) ? [] : Yii::$app->request->post('selection');
                $where = [];
                $i = 0;
                $auto_key_config = [];
                foreach ($deletedata as $detailKey => $code) {
                    $where['milk_collection_code'] = $code;
                    $existData = TblMilkCollection::find()->where($where)->one();
                    if (!empty($existData)) {
                        $collectionApprovalConfig = Yii::$app->general->getUnionConfiguration($existData->union_code, 'collection_approval', 'PORTAL');
                        $allowSentbox = Yii::$app->general->getUnionConfiguration($existData->union_code, 'collection_approval_sentbox', 'PORTAL');
                        if (in_array($collectionApprovalConfig, [1, 2])) {
                            $ApprovalModel = new TblCollectionDataAlias();
                            $excludedAttributes = ['sync_status', 'send_status', 'error_desc', 'created_at', 'created_by', 'updated_at', 'updated_by', 'x_col1', 'x_col2'];
                            $filteredAttributes = array_diff_key($existData->attributes, array_flip($excludedAttributes));
                            $ApprovalModel->attributes = $filteredAttributes;
                            $ApprovalModel->setOldAttributesValues($ApprovalModel);
                            $ApprovalModel->table_name = 'tbl_milk_collection';
                            $ApprovalModel->action_perform = 'DELETE';
                            $ApprovalModel->x_col1 = Yii::$app->general->getUuid();
                            $ApprovalModel->x_col3 = \Yii::$app->session->get('organizations_code');
                            $ApprovalModel->x_col4 = 'PORTAL';
                            $ApprovalModel->x_col5 = 0;
                            if ($collectionApprovalConfig == 1 && $allowSentbox == 1 && in_array($existData->originating_org_type, ['VLC', 'BMC']) && in_array($existData->originating_type, ['23', '24'])) {
                                $ApprovalModel->approval_status = 'Pending';
                                $ApprovalModel->is_sentbox = TRUE;
                                $ApprovalModel->operation = 'INSERT';
                            }
                            if ($collectionApprovalConfig == 2) {
                                $modelStages = new TblApprovalStagesDetail();
                                $modelStages->setProcessWiseApprovalData($ApprovalModel, $existData->union_code, 'tbl_milk_collection', $saveModel, $auto_key_config, $i, TRUE, 'collection_data_alias_code');
                                $i++;
                            } else {
                                $saveModel[] = $ApprovalModel;
                            }
                        } else {
                            $historyModel = new TblMilkCollectionHistory();
                            Yii::$app->operation->history($existData, $historyModel, DELETE);
                            $saveModel[] = $historyModel;
                            $deleteModel[] = $existData;
                            $message = 'Milk Collection';
                            $type = 'delete';
                        }
                    }
                }
                if (!empty($auto_key_config)) {
                    $transaction = $this->generalModel->saveTransactionMultiAutoIncForeignKey($saveModel, [$message, $type], $auto_key_config);
                } else {
                    $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, [$message, $type]);
                }
            }
        }

        return $this->render('delete', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'configVal' => $configVal,
                    'msg' => $msg
        ]);
    }

    public function actionOnlineCollection() {
        $defaultToggle = true;
        $model = new OnlineCollectionModel();
        $model->load(Yii::$app->request->queryParams);
        if (!empty($model->search_date)) {
            $defaultToggle = false;
        }

        $model->search_date = !empty($model->search_date) ? $model->search_date : date('Y-m-d');
        $model->from_time = !empty($model->from_time) ? $model->from_time : '05:00';
        $model->to_time = !empty($model->to_time) ? $model->to_time : '22:00';
        $union_str = '0';
        if (!empty(Yii::$app->session->get('Unions'))) {
            $union_str = Yii::$app->session->get('Unions');
            $union_str = ',' . $union_str . ',';
        } else {
            $unionModel = new TblUnions();
            $union = $unionModel->getActiveUnions();
            $union_ary = ArrayHelper::getColumn($union, 'union_code');
            $union_str = implode(',', $union_ary);
        }
        $plant_str = '0';
        if (!empty(Yii::$app->session->get('Plant'))) {
            $plant_str = Yii::$app->session->get('Plant');
            $plant_str = $plant_str;
        }
        $mcc_str = '0';
        if (!empty(Yii::$app->session->get('MCC'))) {
            $mcc_str = Yii::$app->session->get('MCC');
            $mcc_str = $mcc_str;
        }

        $bmc_str = '0';
        if (!empty(Yii::$app->session->get('BMC'))) {
            $bmc_str = Yii::$app->session->get('BMC');
            $bmc_str = $bmc_str;
        }

        $dcs_str = '0';
        if (!empty(Yii::$app->session->get('Dcs'))) {
            $dcs_str = Yii::$app->session->get('Dcs');
            $dcs_str = $dcs_str;
        }
        $data = [];
        $data['union'] = $union_str;
        $data['plant'] = $plant_str;
        $data['mcc'] = $mcc_str;
        $data['bmc'] = $bmc_str;
        $data['dcs'] = $dcs_str;
        $sp_param = [];
        $sp_param[] = $union_str; //empty($data['union']) ? '0' : $data['union'];
        $sp_param[] = $plant_str; //empty($data['plant']) ? '0' : $data['plant'];
        $sp_param[] = $mcc_str; //empty($data['mcc']) ? '0' : $data['mcc'];
        $sp_param[] = $bmc_str; //empty($data['bmc']) ? '0' : $data['bmc'];
        $sp_param[] = $dcs_str; //empty($data['dcs']) ? '0' : $data['dcs'];
        $sp_param[] = date('Y-m-d', strtotime($model->search_date)) . ' ' . $model->from_time; //date('Y-m-d H:i:s');
        $sp_param[] = date('Y-m-d', strtotime($model->search_date)) . ' ' . $model->to_time; //date('Y-m-d H:i:s');
        $sp_name = 'sp_portal_online_collection';
        $output = \Yii::$app->general->getSpData($sp_name, $sp_param);
        return $this->render('online_collection', [
                    'onlineData' => $output,
                    'model' => $model,
                    'defaultToggle' => $defaultToggle
        ]);
    }

    public function actionQltyTypeConfig() {
        $union = Yii::$app->request->post('union');
        $config = isset(Yii::$app->session->get('unionConfig')[$union]['qlty_wise_collection']) ? Yii::$app->session->get('unionConfig')[$union]['qlty_wise_collection'] : 0;
        return Json::encode(['status' => 'success', 'config' => $config]);
    }

    public function actionCheckFatRange() {
        (float) $fat = Yii::$app->request->post('fat');
        $union = Yii::$app->request->post('union_code');
        $bmc = Yii::$app->request->post('bmc');
        $milk_type = Yii::$app->request->post('milk_type');
        $model = new TblMilkCollection();
        $response = $model->calculateData('check_fat_range', $union, $bmc, $fat, '', $milk_type);
        $response['status'] = 'error';
        $response['data'] = '';
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($response);
    }

    public function actionBulkDeleteCollection() {
        $searchModel = new TblMilkCollectionSearch();
        $searchModel->scenario = 'bulkdeleteMilkCollection';

        $configVal = isset(Yii::$app->session->get('unionConfig')[$searchModel->union_code]['qlty_wise_collection']) ? Yii::$app->session->get('unionConfig')[$searchModel->union_code]['qlty_wise_collection'] : 0;

        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $deleteModel = [];
                $message = 'Data For Approval';
                $type = 'create';
                $deletedata = empty(Yii::$app->request->post('selection')) ? [] : Yii::$app->request->post('selection');
                $where = [];
                foreach ($deletedata as $code) {
                    $exist = explode('###', $code);
                    $where['dcs_code'] = $exist[0];
                    $where['date_time_of_collection'] = $exist[1];
                    $where['milk_type_code'] = $exist[2];
                    $existData = TblMilkCollection::find()->where($where)->all();
                    foreach ($existData as $delete) {
                        if (Yii::$app->general->getUnionConfiguration($delete->union_code, 'collection_approval', 'PORTAL') == 1) {
                            $ApprovalModel = new TblCollectionDataAlias();
                            $ApprovalModel->attributes = $delete->attributes;
                            $ApprovalModel->setOldAttributesValues($ApprovalModel);
                            $ApprovalModel->table_name = 'tbl_milk_collection';
                            $ApprovalModel->action_perform = 'DELETE';
                            $existApproval = $ApprovalModel->getExistApproval();
                            if (empty($existApproval)) {
                                $saveModel[] = $ApprovalModel;
                            }
                        } else {
                            $historyModel = new TblMilkCollectionHistory();
                            Yii::$app->operation->history($delete, $historyModel, DELETE);
                            $saveModel[] = $historyModel;
                            $deleteModel[] = $delete;
                            $message = 'Milk Collection';
                            $type = 'delete';
                        }
                    }
                }
                $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, [$message, $type]);
            }
        }

        $dataProvider = $searchModel->searchdcswisesummary(Yii::$app->request->queryParams);
        return $this->render('dcs_wise_delete', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'configVal' => $configVal,
        ]);
    }

    public function actionDeleteMemberWise() {
        if (Yii::$app->request->post()) {
            $postData = Yii::$app->request->post()['collectionData'];
            $saveModel = [];
            $deleteModel = [];
            $deletedata = explode(',', $postData);
            foreach ($deletedata as $code) {
                $where['milk_collection_code'] = $code;
                $existData = TblMilkCollection::find()->where($where)->one();
                if (Yii::$app->general->getUnionConfiguration($existData->union_code, 'collection_approval', 'PORTAL') == 1) {
                    $ApprovalModel = new TblCollectionDataAlias();
                    $ApprovalModel->attributes = $existData->attributes;
                    $ApprovalModel->setOldAttributesValues($ApprovalModel);
                    $ApprovalModel->table_name = 'tbl_milk_collection';
                    $ApprovalModel->action_perform = 'DELETE';
                    $saveModel[] = $ApprovalModel;
                } else {
                    $historyModel = new TblMilkCollectionHistory();
                    Yii::$app->operation->history($existData, $historyModel, DELETE);
                    $saveModel[] = $historyModel;
                    $deleteModel[] = $existData;
                }
            }
            $response = [];
            $response['status'] = 'error';
            $response['message'] = 'error';
            $message = 'Milk Collection';
            $type = 'delete';
            $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, [$message, $type]);
            if ($transaction == 'customRedirect') {
                $response['status'] = 'success';
            } else {
                $response['message'] = 'error';
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return Json::encode($response);
        }

        $searchModel = new TblMilkCollectionSearch();
        $searchModel->scenario = 'bulkdeleteMilkCollection';
        $dataProvider = $searchModel->deletemembersearch(Yii::$app->request->queryParams);
        $redirectUrl = [];
        $redirectUrl[] = 'delete-member-wise';
        foreach (Yii::$app->request->get() as $key => $value) {
            $redirectUrl[$key] = $value;
        }
        return $this->renderAjax('_delete_member_wise', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'redirectUrl' => $redirectUrl
        ]);
    }

    public function actionIndexAllow() {
        $searchModel = new TblMilkCollectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index_allow', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewAllow($id) {
        return $this->render('view_allow', [
                    'model' => $this->findModel($id),
        ]);
    }

    public function actionCreateAllow() {
        $this->model = new TblMilkCollection();
        $searchModel = new TblMilkCollectionSearch();
        $searchModel->attributes = Yii::$app->request->get('TblMilkCollection');
        $dataProvider = $searchModel->createsearch([]);
        $dataProvider->sort = false;
        $this->model->date_time_of_collection = date('d-m-Y');
        $this->model->milk_type_code = 1;
        $this->model->milk_quality_type_code = 1;
        $this->model->shift_code = 1;
        $this->viewFile = 'create';
        $modelSave = [];
        $message = 'Milk Collection';
        $type = 'create';
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $datetime = date('Y-m-d H:i:s');
            $this->model->date_time_of_collection = Yii::$app->formatter->asDate($this->model->date_time_of_collection, DATE_FORMAT) . ' ' . Yii::$app->general->getshift($this->model->shift_code);
            $this->model->date_time_of_recieve = $datetime;
            $this->model->qlty_time = $datetime;
            $this->model->qty_time = $datetime;
            $this->model->type_of_data_receive = 'Manual';
            $this->model->qty_mode = 0;
            $this->model->qlty_auto = 0;
            $this->model->qty_auto = 0;
            $this->model->setModel($this->model);
            $this->model->scenario = 'create_allow';

            $this->model->member_code = $this->model->dcs_code . str_pad($this->model->member_code, 4, '0', STR_PAD_LEFT);
            $this->model->qty_mode = Yii::$app->general->getUnionConfiguration($this->model->union_code, 'collection_qty_mode', 'VLC');
            $conversion_const = Yii::$app->general->getUnionConfiguration($this->model->union_code, 'ltr_to_kg_constant', 'VLC');
            $this->model->converted_qty_mode = $this->model->qty_mode == 1 ? 0 : 1;
            $this->model->converted_qty = $this->model->qty_mode == 1 ? $this->model->qty / $conversion_const : $this->model->qty * $conversion_const;
            if ($this->model->validate()) {
                if (Yii::$app->general->getUnionConfiguration($this->model->union_code, 'collection_approval', 'PORTAL') == 1) {
                    $approvalModel = new TblCollectionDataAlias();
                    $approvalModel->attributes = $this->model->attributes;
                    $approvalModel->table_name = 'tbl_milk_collection';
                    $approvalModel->action_perform = 'CREATE';
                    $approvalModel->setOldAttributesValues($approvalModel);
                    $modelSave[] = $approvalModel;
                    $message = 'Data For Approval';
                    $type = 'create';
                } else {
                    $modelSave[] = $this->model;
                }
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
            return $this->render('create_allow', [
                        'model' => $this->model,
                        'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
            ]);
        }
        return $this->render('create_allow', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdateAllow($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->milk_collection_code]);
        } else {
            return $this->render('update_allow', [
                        'model' => $model,
            ]);
        }
    }

    public function actionDeleteCollectionAllow() {
        $searchModel = new TblMilkCollectionSearch();
        $dataProvider = $searchModel->deletesearch(Yii::$app->request->queryParams);
        $searchModel->scenario = 'deleteMilkCollection';
        $configVal = isset(Yii::$app->session->get('unionConfig')[$searchModel->union_code]['qlty_wise_collection']) ? Yii::$app->session->get('unionConfig')[$searchModel->union_code]['qlty_wise_collection'] : 0;
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $deleteModel = [];
                $message = 'Data For Approval';
                $type = 'create';
                $deletedata = Yii::$app->request->post('selection');
                $codes = empty(Yii::$app->request->post('selection')) ? [] : Yii::$app->request->post('selection');
                $where = [];
                foreach ($deletedata as $code) {
                    $where['milk_collection_code'] = $code;
                    $existData = TblMilkCollection::find()->where($where)->one();
                    if (Yii::$app->general->getUnionConfiguration($existData->union_code, 'collection_approval', 'PORTAL') == 1) {
                        $ApprovalModel = new TblCollectionDataAlias();
                        $ApprovalModel->attributes = $existData->attributes;
                        $ApprovalModel->setOldAttributesValues($ApprovalModel);
                        $ApprovalModel->table_name = 'tbl_milk_collection';
                        $ApprovalModel->action_perform = 'DELETE';
                        $saveModel[] = $ApprovalModel;
                    } else {
                        $historyModel = new TblMilkCollectionHistory();
                        Yii::$app->operation->history($existData, $historyModel, DELETE);
                        $saveModel[] = $historyModel;
                        $deleteModel[] = $existData;
                        $message = 'Milk Collection';
                        $type = 'delete';
                    }
                }
                $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, [$message, $type]);
            }
        }

        return $this->render('delete_allow', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'configVal' => $configVal,
        ]);
    }

    public function actionUpdateCollectionAllow() {
        $searchModel = new TblMilkCollectionSearch();
        $dataProvider = $searchModel->updatesarch(Yii::$app->request->queryParams);
        $searchModel->scenario = 'updateMilkCollection';
        $detailModel = $dataProvider->getModels();
        $message = 'Milk Collection';
        $type = 'edit';
        $configVal = isset(Yii::$app->session->get('unionConfig')[$searchModel->union_code]['qlty_wise_collection']) ? Yii::$app->session->get('unionConfig')[$searchModel->union_code]['qlty_wise_collection'] : 0;
        $config = $configVal == 1 ? TRUE : FALSE;
        if (Yii::$app->request->post()) {
            foreach ($detailModel as $detail) {
                $detail->scenario = 'update_allow';
            }
            $modelData = [];
            Model::loadMultiple($detailModel, Yii::$app->request->post());

            foreach ($detailModel as $detail) {
                $detail->scenario = 'update_allow';
                $modelData[] = $detail;
            }

            if (Model::validateMultiple($modelData)) {
                $saveModel = [];
                foreach ($modelData as $detalData) {
                    if (!empty($detalData->oldAttributes) && ($detalData->fat != $detalData->oldAttributes['fat'] || $detalData->snf != $detalData->oldAttributes['snf'] || $detalData->rtpl != $detalData->oldAttributes['rtpl'] || $detalData->qty != $detalData->oldAttributes['qty'] || $detalData->milk_type_code != $detalData->oldAttributes['milk_type_code'] || $detalData->milk_quality_type_code != $detalData->oldAttributes['milk_quality_type_code'])) {
                        if (Yii::$app->general->getUnionConfiguration($detalData->union_code, 'collection_approval', 'PORTAL') == 1) {
                            $approvalModel = new TblCollectionDataAlias();
                            $approvalModel->attributes = $detalData->attributes;
                            $approvalModel->old_qty = $detalData->oldAttributes['qty'];
                            $approvalModel->old_fat = $detalData->oldAttributes['fat'];
                            $approvalModel->old_snf = $detalData->oldAttributes['snf'];
                            $approvalModel->old_rtpl = $detalData->oldAttributes['rtpl'];
                            $approvalModel->old_amount = $detalData->oldAttributes['amount'];
                            $approvalModel->old_milk_type_code = $detalData->oldAttributes['milk_type_code'];
                            $approvalModel->old_milk_quality_type_code = $detalData->oldAttributes['milk_quality_type_code'];
                            $approvalModel->old_purchase_rate_code = $detalData->oldAttributes['purchase_rate_code'];
                            $approvalModel->old_clr = $detalData->oldAttributes['clr'];
                            $approvalModel->table_name = 'tbl_milk_collection';
                            $approvalModel->action_perform = 'UPDATE';
                            $approvalModel->date_time_of_collection = $detalData->date_time_of_collection . ' ' . \Yii::$app->general->getshift($detalData->shift_code);
                            $saveModel[] = $approvalModel;
                            $message = 'Data For Approval';
                            $type = 'create';
                        } else {
                            $existData = $this->findModel($detalData->milk_collection_code);
                            $historyModel = new TblMilkCollectionHistory();
                            Yii::$app->operation->history($existData, $historyModel, 'UPDATE');
                            $saveModel[] = $historyModel;
                            $existData->attributes = $detalData->attributes;
                            $existData->date_time_of_collection = $detalData->date_time_of_collection . ' ' . \Yii::$app->general->getshift($detalData->shift_code);
                            $saveModel[] = $existData;
                        }
                    }
                }
                $transaction = $this->generalModel->saveTransaction($saveModel, [$message, $type]);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index-allow']);
                }
            }
        }
        if (!empty($detailModel)) {
            $dataProvider = new ArrayDataProvider([
                'allModels' => $detailModel,
                'pagination' => FALSE,
            ]);
        }
        return $this->render('update', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'detailModel' => $detailModel,
                    'config' => $config,
        ]);
    }

    public function actionAndroidCollection() {
        $model = new TblMilkCollection();
        if ($model->load(Yii::$app->request->post())) {
            $error_file = [];
            $fileName = Yii::$app->request->post()['TblFtpTxnLog']['file_name'];
            $files = array_filter(explode(',', $fileName));
            $path = Yii::$app->basePath . '/web/android/';
            $msg = '';
            if (Yii::$app->general->checkDirectory($path)) {
                $file_name = Yii::$app->basePath . '/web/android/' . $files[1];
                $status = 'error';
                $msg = 'Invalid File Uploaded<br/>';
                $cnt = 0;
                $file_id = [];
                $saveModel = [];
                $file_data = file($file_name);
                $str = '';
                try {
                    foreach ($file_data as $key => $value) {
                        if (trim(substr($value, -2)) == '=' || strlen($value) <= 35) {
                            $str = trim($str) . trim($value);
                            $keyA = Yii::$app->general->SetSecurityEncryptionKey('UNION', $model->union_code);
                            Yii::$app->encrypter->setGlobalPassword($keyA);
                            $filedata = Yii::$app->general->decryptData(str_replace(' ', '', $str));
                            $jsonData = (array) json_decode($filedata);
                            $request = $this->camelCaseToUnderscore($jsonData);
                            $inbox = new TblInbox();
                            $inbox->setAttributes($request);
                            $existData = $inbox->find()->where(['uuid' => $inbox->uuid])->one();
                            $sync_log = new TblSyncLog();
                            $existSync = $sync_log->find()->where(['uuid' => $inbox->uuid])->one();
                            if (empty($existData) && empty($existSync)) {
                                $saveModel[] = $inbox;
                            }
                            $str = '';
                            $status = 'success';
                            $msg = 'Files Uploaded Successfully<br/>';
                        } else {
                            $str = $str . $value;
                        }
                    }
                    if (!empty($saveModel)) {
                        $transaction = $this->generalModel->saveTransaction($saveModel, ['file uploaded', 'create']);
                        if ($transaction == 'customRedirect') {
                            $msg = 'Files Uploaded Successfully<br/>';
                        }
                    }
                } catch (\yii\db\Exception $e) {
                    $msg .= 'Following files not uploaded' . implode('<br/>', $e);
                }
                if (!empty($error_file)) {
                    $msg .= 'Following files not uploaded' . implode('<br/>', $error_file);
                }
            } else {
                $status = 'error';
                $msg = 'Error While Save data';
            }
            $result = ['status' => $status, 'data' => $msg];
            Yii::$app->getSession()->setFlash('success', ['type' => $status,
                'message' => $msg]);
            return (Json::encode($result));
        } else {
            return $this->render('import_collection', ['model' => $model]);
        }
    }

    public function actionImportFile() {
        $path = Yii::$app->basePath . '/web/android/';
        Yii::$app->general->checkDirectory($path, '0777');
        try {
            $file = \yii\web\UploadedFile::getInstanceByName('file');
            $name = date('YmdHis') . rand(1000, 9999) . $file->name;
            if ($file->saveAs($path . $name)) {
                $record = ['status' => 'success', 'filename' => $name, 'msg' => $name, 'datefile' => $file->name];
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

    public function &camelCaseToUnderscore(&$post_data) {
        if (is_array($post_data)) {
            $post_data = array_combine(array_map(function($str) {
                        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $str));
                    }, array_keys($post_data)), array_values($post_data));
            foreach ($post_data as $key => $val) {
                if (is_array($post_data[$key])) {
                    $arr1 = array_combine(array_map(function($str) {
                                return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $str));
                            }, array_keys($post_data[$key])), array_values($post_data[$key]));
                    $post_data[$key] = $arr1;
                    $this->camelCaseToUnderscore($post_data[$key]);
                }
            }
            return $post_data;
        }
        return $post_data;
    }

    public function actionSapUpload() {
        $searchModel = new TblMilkCollectionSearch();
        $searchModel->scenario = 'sap-upload';
        $dataProvider = $searchModel->searchsapupload(Yii::$app->request->queryParams);

        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $status = !empty($_REQUEST['operation']) ? ($_REQUEST['operation']) : '';
                $codes = empty($_REQUEST['selection']) ? [] : $_REQUEST['selection'];
                $data_array = [];
                $controls = [];
                $output = [];
                $fileArray = [];
                $checkArray = [];
                $msg = '';
                $eiplCode = Yii::$app->session->get('eiplCode');
                if ($eiplCode != 'ANANDA') {
                    foreach ($codes as $code) {
                        $data = explode('###', $code);
                        $data_array['module_name'] = 'TblMilkCollection_cdpl_VM';
                        $data_array['module_code'] = $data[4];
                        $data_array['mcc_plant_code'] = $data[2];
                        $data_array['union_code'] = $data[0];
                        $data_array['applicable_date'] = $data[5];
                        $data_array['shift_code'] = $data[6];
                        $data_array['bmc_code'] = $data[3];
                        $data_array['dcs_code'] = $data[4];
                        $data_array['from_date'] = $data[5];
                        $data_array['to_date'] = $data[5];
                        if ($status == 'upload') {
                            $ftp_model = new TblFtpTxnLog();
                            $ftp_model->exportData($data_array, $title = '', $output, $mccRefCode = '', FALSE, FALSE);
                            $msg = Yii::t('app', 'FTP Uploaded Successfully.');
                        } elseif (in_array($status, ['download', 'bulk_download', 'bulk_download_shift_wise'])) {
                            if ($eiplCode == 'DODLA') {
                                $searchParam = !empty(Yii::$app->request->queryParams['TblMilkCollectionSearch']) ? Yii::$app->request->queryParams['TblMilkCollectionSearch'] : NULL;
                                if (in_array($status, ['bulk_download', 'bulk_download_shift_wise']) && !empty($searchParam) && !empty($searchParam['from_date']) && !empty($searchParam['from_shift']) && !empty($searchParam['to_date']) && !empty($searchParam['to_shift'])) {
                                    $from_datetime = Yii::$app->formatter->asDate($searchParam['from_date'], DATE_FORMAT) . ' ' . Yii::$app->general->getshift($searchParam['from_shift']);
                                    $to_datetime = Yii::$app->formatter->asDate($searchParam['to_date'], DATE_FORMAT) . ' ' . Yii::$app->general->getshift($searchParam['to_shift']);
                                    $key = $from_datetime . '~~' . $to_datetime;
                                } else {
                                    $from_datetime = date('Y-m-d', strtotime($data[5])) . ' ' . Yii::$app->general->getshift($data[6]);
                                    $key = $from_datetime . '~~' . $from_datetime;
                                }
                                if (empty($checkArray[$key]['dcs_code'])) {
                                    $checkArray[$key]['dcs_code'] = [];
                                }
                                $checkArray[$key]['union_code'] = $data[0];
                                $checkArray[$key]['mcc_plant_code'] = $data[2];
                                $checkArray[$key]['bmc_code'] = $data[3];
                                $checkArray[$key]['dcs_code'][] = $data[4];
                            } else {
                                $controls['union_code'] = $data[0];
                                $controls['mcc_plant_code'] = $data[2];
                                $controls['bmc_code'] = $data[3];
                                $controls['dcs_code'] = $data[4];
                                $controls['from_date'] = $data[5];
                                $controls['to_date'] = $data[5];
                                $sp = 'mis_vmcc_collection_date_wise';

                                if ($eiplCode == 'DODLA') {
                                    $sp = 'mis_vmcc_collection_date_wise_dodla';
                                }
                                $output = \Yii::$app->general->getSpData($sp, $controls);

                                if (!empty($output)) {
                                    $downLoadArray = [];
                                    foreach ($output as $detail) {
                                        $plant = 'Agent_Code';
                                        if (!empty($detail[$plant]) && strtolower($detail[$plant]) != 'total') {
                                            if (empty($downLoadArray[$detail[$plant]])) {
                                                $downLoadArray[$detail[$plant]] = [];
                                            }
                                            $downLoadArray[$detail[$plant]][] = $detail;
                                        }
                                    }
                                    foreach ($downLoadArray as $bmc => $download) {
                                        $title = $download[0]['Plant_Code'] . '_' . $bmc . '_VMCC_' . str_replace('-', '_', Yii::$app->controls->view_date($data[5])) . '_' . $data[6];
                                        if ($eiplCode == 'DODLA') {
                                            $title = $download[0]['Plant_Code'] . '_VMCC_' . str_replace('-', '_', Yii::$app->controls->view_date($data[5])) . '_' . $data[6];
                                        }
                                        $this->downloadData($title, $download, $fileArray);
                                    }
                                    $this->fileDownloadArr = $fileArray;
                                }
                            }
                        }
                    }
                }
                if ($eiplCode == 'DODLA') {
                    $all_data = [];
                    $all_data_m = [];
                    $all_data_e = [];
                    $postData = Yii::$app->request->post();
                    foreach ($checkArray as $checkKey => $checkAr) {
                        $dcs_codes = array_unique($checkAr['dcs_code']);
                        $dateArr = explode('~~', $checkKey);
// $date = $dateArr[0] . ' ' . Yii::$app->general->getshift($dateArr[1]);
                        $controls['union_code'] = $checkAr['union_code'];
                        $controls['mcc_plant_code'] = $checkAr['mcc_plant_code'];
                        $controls['bmc_code'] = $checkAr['bmc_code'];
                        $controls['dcs_code'] = implode(',', $dcs_codes);
                        $controls['from_date'] = $dateArr[0];
                        $controls['to_date'] = $dateArr[1];
                        $sp = 'mis_vmcc_collection_date_wise';

                        if ($eiplCode == 'DODLA') {
                            $sp = 'mis_vmcc_collection_date_wise_dodla';
                        }
                        $output = \Yii::$app->general->getSpData($sp, $controls);
                        if (!empty($output)) {
                            if ($postData['operation'] == 'bulk_download') {
                                foreach ($output as $bulk_output) {
                                    $all_data[] = $bulk_output;
                                }
                            } else if ($postData['operation'] == 'bulk_download_shift_wise') {
                                foreach ($output as $bulk_output) {
                                    if (in_array(strtoupper($bulk_output['Shift_Id']), ['1', 'M'])) {
                                        $all_data_m[] = $bulk_output;
                                    } else {
                                        $all_data_e[] = $bulk_output;
                                    }
                                }
                            } else {
                                $downLoadArray = [];
                                $downLoadArray[] = $output;

                                foreach ($downLoadArray as $bmc => $download) {
                                    if ($eiplCode == 'DODLA') {
                                        $title = $download[0]['Plant_Code'] . '_VMCC_' . str_replace('-', '_', Yii::$app->controls->view_date($dateArr[0])) . '_' . $dateArr[1];
                                    } else {
                                        $title = $download[0]['Plant_Code'] . '_' . $bmc . '_VMCC_' . str_replace('-', '_', Yii::$app->controls->view_date($dateArr[0])) . '_' . $dateArr[1];
                                    }
                                    $this->downloadData($title, $download, $fileArray);
                                }
                                $this->fileDownloadArr = $fileArray;
                            }
                        }
                    }
                    $user = isset(\Yii::$app->user->identity->user_code) ? \Yii::$app->user->identity->user_code : '00000000000000';
                    $user = $user . '_' . date('YmdHis') . rand(1000, 9999);
                    if (!empty($all_data)) {
                        $title = $user . '_ALL_VMCC_DATA';
                        $this->downloadData($title, $all_data, $fileArray);
                        $this->fileDownloadArr = $fileArray;
                    }
                    if (!empty($all_data_m)) {
                        $title = $user . '_ALL_VMCC_DATA_M';
                        $this->downloadData($title, $all_data_m, $fileArray);
                        $this->fileDownloadArr = $fileArray;
                    }
                    if (!empty($all_data_e)) {
                        $title = $user . '_ALL_VMCC_DATA_E';
                        $this->downloadData($title, $all_data_e, $fileArray);
                        $this->fileDownloadArr = $fileArray;
                    }
                }

                if ($eiplCode == 'ANANDA') {
                    $dateToDcsMapping = [];

                    foreach ($codes as $code) {
                        $data = explode('###', $code);
                        $shift = $data[6];
                        $dateToDcsMapping[$data[5]]['dcs_codes'][] = $data[4];
                    }
                    foreach ($dateToDcsMapping as $date => $controlData) {
                        $controls['union_code'] = $data[0];
                        $controls['mcc_plant_code'] = $data[2];
                        $controls['dcs_code'] = ',' . implode(',', $controlData['dcs_codes']) . ',';
                        $controls['from_date'] = $date;
                        $controls['to_date'] = $date;
                        $controls['operation'] = $status;
                        $controls['data_type_filter'] = $searchModel->data_type_filter;
                        $sp = 'rpt_MIS_SDSAPReport_Ananda_upload';

                        $output = \Yii::$app->general->getSpData($sp, $controls);
                        $update_data = $output;

                        foreach ($output as $bulk_output) {
                            $all_data[] = $bulk_output;
                        }
                        if (!empty($output)) {
                            $data_array = [];
                            $data_array['module_name'] = 'TblMilkCollection_Ananda';
                            $data_array['module_code'] = !empty($output[0]['Plant']) ? $output[0]['Plant'] : '';
                            $data_array['mcc_plant_code'] = !empty($output[0]['Plant']) ? $output[0]['Plant'] : '';
                            $data_array['union_code'] = $data[0];
                            $data_array['applicable_date'] = $searchModel->from_date . ' ' . Yii::$app->general->getshift($searchModel->from_shift);
                            $data_array['shift_code'] = $searchModel->from_shift;
                            $data_array['bmc_code'] = NULL;
                            $data_array['from_date'] = $searchModel->from_date . ' ' . Yii::$app->general->getshift($searchModel->from_shift);
                            $data_array['to_date'] = $searchModel->to_date . ' ' . Yii::$app->general->getshift($searchModel->to_shift);
                            $title = $update_data[0]['ftp_txn_file_name'];

                            $all_data = array_map(function($item) {
                                unset($item['ftp_txn_file_name'], $item['data_post_status'], $item['ftp_txn_file_name']);
                                return $item;
                            }, $all_data);
                        }
                    }
                    if (!empty($all_data)) {
                        if ($status == 'upload') {
                            $ftp_model = new TblFtpTxnLog();
                            $result = $ftp_model->exportData($data_array, $title, $all_data, '', false, TRUE, TRUE);
                            $model = new TblMilkCollection();
                            if (!empty($result)) {
                                $model->updateProcessStatus('SUCCESS', '2', 2, $update_data[0]['data_post_status'], $update_data[0]['ftp_txn_file_name']);
                                $msg = Yii::t('app', 'FTP Uploaded Successfully.');
                            } else {
                                $model->updateProcessStatus('ERROR', '0', 0, $update_data[0]['data_post_status'], $update_data[0]['ftp_txn_file_name']);
                                $msg = Yii::t('app', 'FTP Upload Failed. Please try again later.');
                            }
                        } else if ($status = 'download') {
                            $this->downloadData($title, $all_data, $fileArray);
                            $this->fileDownloadArr = $fileArray;
                        }
                    } else {
                        $msg = Yii::t('app', 'No data found for the given parameters.');
                    }
                }

                if ($status == 'upload') {
                    $record = ['status' => 'success', 'msg' => 'FTP Uploaded Successfully.'];
                    Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                        'message' => $msg]);
                }
            }
        }
        return $this->render('sap_upload', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'fileDownloadArr' => $this->fileDownloadArr
        ]);
    }

    public function actionMemberWiseDetail() {
        $searchModel = new TblMilkCollectionSearch();
        $dataProvider = $searchModel->detailmembersearch(Yii::$app->request->queryParams);

        return $this->renderAjax('_member_wise_detail', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDcsWiseFtpUpload($union_code, $mcc_plant_code, $bmc_code, $dcs_code, $date_time_of_collection, $shift_id, $data_type) {
        $data_array = [];
        $output = [];
        $eiplCode = Yii::$app->session->get('eiplCode');

        if ($eiplCode == 'ANANDA') {
            $controls['union_code'] = $union_code;
            $controls['mcc_plant_code'] = $mcc_plant_code;
            $controls['dcs_code'] = $dcs_code;
            $controls['from_date'] = $date_time_of_collection;
            $controls['to_date'] = $date_time_of_collection;
            $controls['operation'] = 'upload';
            $controls['data_type_filter'] = $data_type;
            $sp = 'rpt_MIS_SDSAPReport_Ananda_upload';

            $output = \Yii::$app->general->getSpData($sp, $controls);
            $data = $output;
            if (!empty($output)) {
                $data_array['module_name'] = 'TblMilkCollection_Ananda';
                $data_array['module_code'] = !empty($output[0]['Plant']) ? $output[0]['Plant'] : '';
                $data_array['mcc_plant_code'] = !empty($output[0]['Plant']) ? $output[0]['Plant'] : '';
                $data_array['union_code'] = $union_code;
                $data_array['applicable_date'] = $date_time_of_collection;
                $data_array['shift_code'] = $date_time_of_collection;
                $data_array['bmc_code'] = NULL;
                $data_array['from_date'] = $date_time_of_collection;
                $data_array['to_date'] = $date_time_of_collection;
                $title = $data[0]['ftp_txn_file_name'];

                $output = array_map(function($item) {
                    unset($item['ftp_txn_file_name'], $item['data_post_status'], $item['ftp_txn_file_name']);
                    return $item;
                }, $output);

                $ftp_model = new TblFtpTxnLog();
                $result = $ftp_model->exportData($data_array, $title, $output, '', false, TRUE, TRUE);
                $model = new TblMilkCollection();
                if (!empty($result)) {
                    $model->updateProcessStatus('SUCCESS', '2', 2, $data[0]['data_post_status'], $data[0]['ftp_txn_file_name']);
                    $record = ['status' => 'success', 'msg' => 'FTP Uploaded Successfully.'];
                } else {
                    $model->updateProcessStatus('ERROR', '0', 0, $data[0]['data_post_status'], $data[0]['ftp_txn_file_name']);
                    $record = ['status' => 'error', 'msg' => 'FTP Upload Failed. Please try again later.'];
                }
            } else {
                $record = ['status' => 'error', 'msg' => 'No data found for the given parameters.'];
            }
        } else {
            $data_array['module_name'] = 'TblMilkCollection_cdpl_VM';
            $data_array['module_code'] = $dcs_code;
            $data_array['mcc_plant_code'] = $mcc_plant_code;
            $data_array['union_code'] = $union_code;
            $data_array['applicable_date'] = $date_time_of_collection;
            $data_array['shift_code'] = $shift_id;
            $data_array['bmc_code'] = $bmc_code;
            $data_array['dcs_code'] = $dcs_code;
            $data_array['from_date'] = $date_time_of_collection;
            $data_array['to_date'] = $date_time_of_collection;
            $ftp_model = new TblFtpTxnLog();
            $ftp_model->exportData($data_array, $title = '', $output);
            $record = ['status' => 'success', 'msg' => 'FTP Uploaded Successfully.'];
        }

        Yii::$app->getSession()->setFlash($record['status'], $record['msg']);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function downloadData($title, $download, &$fileArray) {
        $header = [
            'mime' => '	application/vnd.ms-excel',
            'extension' => 'xls',
            'writer' => 'Excel2007',
        ];
        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->getActiveSheet();
        $file_header = !empty($download) ? array_keys($download[0]) : [];
        $sheet->fromArray(
                $file_header, // The data to set
                NULL, // Array values with this value will not be set
                'A1'         // Top left coordinate of the worksheet range where
//    we want to set these values (default is A1)
        );
        $sheet->fromArray(
                $download, // The data to set
                NULL, // Array values with this value will not be set
                'A2'         // Top left coordinate of the worksheet range where
//    we want to set these values (default is A1)
        );
        $file_name = $title . '.' . 'xls';
        $path = Yii::$app->basePath . '/web/sap_data_files/';
        Yii::$app->general->checkDirectory($path);
        $fileArray[] = $file_name;
        $fileName = $path . '/' . $file_name;
        fopen($fileName, "w+");
        $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
        $objPHPExcel->getActiveSheet()->getProtection()->setPassword('password');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($fileName);
    }

    public function actionRealTimeCollection() {
        if (Yii::$app->request->isAjax) {
            $cur_time = date_create(date('H:i:s'));
            $morning_time = date_create('16:00:00');
            $diff = date_diff($morning_time, $cur_time);
            $time = '06:00:00';
            if (($diff->h > 0 || $diff->i > 0) && $diff->invert == 0) {
                $time = '18:00:00';
            }
            $sp_param = [];
            $sp_param[] = date('Y-m-d') . ' ' . $time;
            $mcc_weight_data = \Yii::$app->general->getSpData('sp_mis_realtime_mcc_collection_weight', $sp_param);
            $mcc_quality_data = \Yii::$app->general->getSpData('sp_mis_realtime_mcc_collection_quality', $sp_param);
            return $this->renderAjax('_real_time_collection_details', ['mcc_weight_data' => $mcc_weight_data, 'mcc_quality_data' => $mcc_quality_data]);
        }
        return $this->render('real_time_collection');
    }

    public function actionIotTemperature() {
        $searchModel = new TblIotTemperatureSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('iot_temperature_index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
