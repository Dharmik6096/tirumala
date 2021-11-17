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

/**
 * TblMilkCollectionController implements the CRUD actions for TblMilkCollection model.
 */
class TblMilkCollectionController extends \app\controllers\ChildController {

    public $freeAccessActions = ['validate-rtpl', 'validate-member', 'calculate-clr', 'list-grid', 'qlty-type-config', 'check-fat-range'];

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
            $this->model->scenario = 'create';
            $this->model->member_code = $this->model->dcs_code . str_pad($this->model->member_code, 4, '0', STR_PAD_LEFT);
            $this->model->qty_mode = Yii::$app->general->getUnionConfiguration($this->model->union_code, 'collection_qty_mode', 'BMC');
            $conversion_const = Yii::$app->general->getUnionConfiguration($this->model->union_code, 'ltr_to_kg_constant', 'BMC');
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
        $this->model = new TblMilkCollection();
        $this->model->member_code = $member;
        $rateClass = Yii::$app->general->getforeignkey($this->model->memberCode, 'rate_class');

        $data['rate_class'] = empty($rateClass) ? 0 : $rateClass;

        $model = new TblPurchaseRateApplicability();
        $model->dcs_code = $data['dcs_code'];
        $model->wef_date = $data['dt_date'];
        $model_data = $model->getPurchaseRateApplicableData($data);

        if (!empty($model_data)) {
            $detail_model = new TblPurchaseRateDetails();
            $detail_model->rate_type_code = $model_data->rate_app_code;
            $detail_model->purchase_rate_code = $model_data->purchase_rate_code;
            $rate_type = !empty($detail_model->rateTypeCode) ? $detail_model->rateTypeCode->rate_type : '';
            $detail_data = $detail_model->getPurchasseRateDetailData($data, $rate_type);

            if (!empty($detail_data)) {
                $response['status'] = 'success';
                $rtpl_data['list'] = $detail_data;
                $response['data'] = $rtpl_data;
            }
        }
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
        (float) $lr1 = Yii::$app->general->getUnionConfiguration($union, 'clr_constant1', 'BMC');
        (float) $lr2 = Yii::$app->general->getUnionConfiguration($union, 'clr_constant2', 'BMC');
        $clr = ($snf - ($fat * $lr1) - $lr2) * 4;
        $response['data'] = $clr;
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($response);
    }

    public function actionUpdateCollection() {
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
//                if ($transaction == 'customRedirect') {
//                    return $this->redirect(['index']);
//                }
            }
        }

        return $this->render('delete', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'configVal' => $configVal,
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
        $response = [];
        $response['status'] = 'error';
        $response['data'] = '';
        (float) $fat = Yii::$app->request->post('fat');
        $union = Yii::$app->request->post('union_code');
        $bmc = Yii::$app->request->post('bmc');
        $milk_type = Yii::$app->request->post('milk_type');
        $range = isset(Yii::$app->session->get('unionConfig')[$union]['buf_min_fat_range_member']) ? Yii::$app->session->get('unionConfig')[$union]['buf_min_fat_range_member'] : '';
        $mapping = new TblBmcMilkType();
        $mapped = $mapping->find()->where(['bmc_code' => $bmc, 'is_active' => 1])->all();

        if (!empty($range) && !empty($mapped) && count($mapped) == 2) {
            $type = [];
            foreach ($mapped as $map) {
                $type[] = $map->milk_type_code;
            }
            //Cow and Buffalo
            if (in_array(1, $type) && in_array(2, $type)) {
                if ($range < $fat && $milk_type != 2) {
                    $response['status'] = 'success';
                    $response['data'] = 2;
                    $response['msg'] = Yii::t('app', 'Milk Type Must Buffalo');
                } elseif ($range >= $fat && $milk_type != 1) {
                    $response['status'] = 'success';
                    $response['data'] = 1;
                    $response['msg'] = Yii::t('app', 'Milk Type Must Cow');
                }
            }
            //Cow and Mix
            if (in_array(1, $type) && in_array(3, $type)) {
                if ($range < $fat && $milk_type != 3) {
                    $response['status'] = 'success';
                    $response['data'] = 3;
                    $response['msg'] = Yii::t('app', 'Milk Type Must Mix');
                } elseif ($range >= $fat && $milk_type != 1) {
                    $response['status'] = 'success';
                    $response['data'] = 1;
                    $response['msg'] = Yii::t('app', 'Milk Type Must Cow');
                }
            }
            //Buffalo and Mix
            if (in_array(2, $type) && in_array(3, $type)) {
                if ($range < $fat && $milk_type != 3) {
                    $response['status'] = 'success';
                    $response['data'] = 3;
                    $response['msg'] = Yii::t('app', 'Milk Type Must Mix');
                } elseif ($range >= $fat && $milk_type != 2) {
                    $response['status'] = 'success';
                    $response['data'] = 2;
                    $response['msg'] = Yii::t('app', 'Milk Type Must Buffalo');
                }
            }
        }
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
            $this->model->qty_mode = Yii::$app->general->getUnionConfiguration($this->model->union_code, 'collection_qty_mode', 'BMC');
            $conversion_const = Yii::$app->general->getUnionConfiguration($this->model->union_code, 'ltr_to_kg_constant', 'BMC');
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

}
