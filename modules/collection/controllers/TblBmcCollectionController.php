<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblBmcCollection;
use app\modules\collection\models\TblBmcCollectionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\organisation\models\TblDcs;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\dcsoperation\models\TblDcsPurchaseRate;
use app\modules\collection\models\TblBmcCollectionHistory;
use app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabitity;
use app\modules\dcsoperation\models\TblDcsPurchaseRateDetails;
use yii\widgets\ActiveForm;
use app\modules\collection\models\TblCollectionDataAlias;
use yii\base\Model;
use yii\data\ArrayDataProvider;

/**
 * TblBmcCollectionController implements the CRUD actions for TblBmcCollection model.
 */
class TblBmcCollectionController extends \app\controllers\ChildController {

    public $freeAccessActions = ['validate-dcs', 'validate-rtpl', 'calculate-clr', 'list-grid'];

    /**
     * Lists all TblBmcCollection models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBmcCollectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBmcCollection model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblBmcCollection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblBmcCollection();
        $searchModel = new TblBmcCollectionSearch();
        $searchModel->date_time_of_collection = date('Y-m-d');
        $searchModel->shift_code = 1;
        $dataProvider = $searchModel->createsearch(Yii::$app->request->get());
        $dataProvider->sort = false;
        $this->model->date_time_of_collection = date('d-m-Y');
        $this->model->milk_type_code = 1;
        $this->model->milk_quality_type_code = 1;
        $this->model->shift_code = 1;

        $this->viewFile = 'create';
        $this->model->scenario = 'create';
        $modelSave = [];
        $message = 'BMC Collection';
        $type = 'create';
        if (Yii::$app->request->post()) {
            $update = FALSE;
            $this->model->load(Yii::$app->request->post());
            if (!empty(Yii::$app->request->post()['TblBmcCollection']['milk_collection_code'])) {
                $this->model = $this->findModel(Yii::$app->request->post()['TblBmcCollection']['milk_collection_code']);
                $historyModel = new TblBmcCollectionHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $modelSave[] = $historyModel;
                $this->model->load(Yii::$app->request->post());
                $this->model->scenario = 'update';
                $update = TRUE;
            }
            if (!$update) {
                $this->model->qlty_auto = 0;
                $this->model->qty_auto = 0;
                $this->model->dt_date = date('Y-m-d H:i:s');
                $datetime = date('Y-m-d H:i:s');
                $this->model->sample_no = $this->model->getSampleNo();
                $this->model->date_time_of_recieve = $datetime;
                $this->model->type_of_data_receive = 'Manual';
                $this->model->sms_status = 'n';
                $this->model->qlty_time = $datetime;
                $this->model->qty_time = $datetime;
                $this->model->date_time_of_testing = $datetime;
                if (strtolower($this->model->customer_type) == 'dcs') {
                    $dcs = new TblDcs();
                    $this->model->dcs_code = $dcs->validDcs($this->model->customer_code, $this->model->bmc_code);
                    $this->model->customer_code = $this->model->dcs_code;
                    $this->model->village_code = Yii::$app->general->getforeignkey($this->model->dcsCode, 'village_code');
                    $this->model->route_code = Yii::$app->general->getforeignkey($this->model->dcsCode, 'route_code');
                } else {
                    $this->model->dcs_code = NULL;
                    $this->model->customer_code = $this->model->validateCustomer($this->model->union_code, $this->model->customer_code, $this->model->customer_type);
                    $this->model->village_code = Yii::$app->general->getforeignkey($this->model->mainCustomerCode, 'village_code');
                    $this->model->route_code = Yii::$app->general->getforeignkey($this->model->mainCustomerCode, 'route_code');
                }
                $this->model->own_mcc_plant_code = $this->model->mcc_plant_code;
                $this->model->own_bmc_code = $this->model->bmc_code;
            } else {
                if (strtolower($this->model->customer_type) != 'dcs') {
                    $this->model->customer_code = $this->model->validateCustomer($this->model->union_code, $this->model->customer_code, $this->model->customer_type);
                }
            }
            $this->model->date_time_of_collection = !empty($this->model->date_time_of_collection) ? date('Y-m-d', strtotime($this->model->date_time_of_collection)) : '';
            $this->model->date_time_of_collection = $this->model->date_time_of_collection . ' ' . \Yii::$app->general->getshift($this->model->shift_code);
            if ($this->model->validate()) {
                $this->model->qty_mode = Yii::$app->general->getUnionConfiguration($this->model->union_code, 'collection_qty_mode', 'BMC');
                $conversion_const = Yii::$app->general->getUnionConfiguration($this->model->union_code, 'ltr_to_kg_constant', 'BMC');
                $this->model->converted_qty_mode = $this->model->qty_mode == 1 ? 0 : 1;
                $this->model->converted_qty = $this->model->qty_mode == 1 ? $this->model->qty / $conversion_const : $this->model->qty * $conversion_const;
                if (Yii::$app->general->getUnionConfiguration($this->model->union_code, 'collection_approval', 'PORTAL') == 1) {
                    $approvalModel = new TblCollectionDataAlias();
                    $approvalModel->attributes = $this->model->attributes;
                    $approvalModel->purchase_rate_code = $this->model->rate_code;
                    $approvalModel->table_name = 'tbl_bmc_collection';
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
                    $record = ['status' => 'success', 'temp_collection_data' => [], 'msg' => $msg];
                } else {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'error', 'temp_collection_data' => [], 'msg' => $msg];
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

    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        if (Yii::$app->request->post()) {
            $master_model = [];
            $historyModel = new TblBmcCollectionHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $master_model[] = $historyModel;
            $this->model->load(Yii::$app->request->post());
            $this->model->date_time_of_collection = ($this->model->date_time_of_collection) ? Yii::$app->formatter->asDate($this->model->date_time_of_collection, DATE_FORMAT) : '';
            $this->model->date_time_of_collection = $this->model->date_time_of_collection . ' ' . \Yii::$app->general->getshift($this->model->shift_code);
            $master_model[] = $this->model;

            $transaction = $this->generalModel->saveTransaction($master_model, ['BMC Collection', 'edit']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Finds the TblBmcCollection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblBmcCollection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblBmcCollection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionValidateDcs() {
        $response = [];
        $response['status'] = 'error';
        $response['data'] = '';
        $bmc = Yii::$app->request->post('bmc_code');
        $dcs = Yii::$app->request->post('dcs_code');
        $union = Yii::$app->request->post('union_code');
        $type = Yii::$app->request->post('customer_type');
        $mcc = Yii::$app->request->post('mcc');
        $plant = Yii::$app->request->post('plant');
        $date = Yii::$app->request->post('date');
        $bmcModel = new TblBmcCollection();
        if (!empty($type) && strtolower($type) != 'dcs') {
            $data = $bmcModel->validateCustomer($union, $dcs, $type);
            $bmcModel->customer_code = $data;
        } else {
            $model = new TblDcs();
            $data = $model->validDcs($dcs, $bmc);
            $bmcModel->dcs_code = $data;
            $bmcModel->date_time_of_collection = $date;
            $detail = Yii::$app->general->validateDeactivateDcs($bmcModel, $bmcModel->date_time_of_collection);
            if ($detail === false) {
                $data = '';
            }
            $bmcModel->customer_code = $data;
        }
        if (!empty($data)) {
            $name = Yii::$app->general->getCustomer($bmcModel, $type);
            $response['status'] = 'success';
            $response['data'] = $name;
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($response);
    }

    public function actionValidateRtpl() {
        $response = [];
        $response['status'] = 'error';

        $data['bmc_code'] = Yii::$app->request->post('bmc_code');
        $data['dcs_code'] = Yii::$app->request->post('dcs_code');
        $data['milk_type'] = Yii::$app->request->post('milk_type');
        $data['milk_quality_type'] = Yii::$app->request->post('milk_quality_type');
        $data['dt_date'] = Yii::$app->request->post('dt_date');
        $data['dt_date'] = (Yii::$app->request->post('dt_date')) ? Yii::$app->formatter->asDate(Yii::$app->request->post('dt_date'), DATE_FORMAT) : '';
        $data['shift'] = Yii::$app->request->post('shift');
        $data['dt_date'] = $data['dt_date'] . ' ' . \Yii::$app->general->getshift($data['shift']);
        $data['fat'] = Yii::$app->request->post('fat');
        $data['clr'] = Yii::$app->request->post('clr');
        $data['snf'] = Yii::$app->request->post('snf');
        $data['customer_type'] = Yii::$app->request->post('customer_type');
        $data['union'] = Yii::$app->request->post('union_code');
        $validCode = !empty(Yii::$app->request->post('valid_code')) ? Yii::$app->request->post('valid_code') : '';
        $bmcModel = new TblBmcCollection();
        $dcsModel = new TblDcs();
        $dcs = $dcsModel->validDcs($data['dcs_code'], $data['bmc_code']);
        $bmcModel->dcs_code = !empty($dcs) ? $dcs : $data['dcs_code'];
//        $bmcModel->bmc_code = Yii::$app->general->getforeignkey($bmcModel->dcsCode, 'bmc_code');
//        $bmcModel->mcc_plant_code = Yii::$app->general->getforeignkey($bmcModel->dcsCode, 'mcc_plant_code');
//        $is_mcc = Yii::$app->general->getforeignkey($bmcModel->bmcData, 'is_mcc');
//        $for = $is_mcc == 1 ? 'MCC' : 'BMC';
//        $code = $for == 'MCC' ? $bmcModel->mcc_code : $bmcModel->bmc_code;
        $for = !empty($data['customer_type']) ? $data['customer_type'] : 'DCS';
        if (strtolower($for) != 'dcs') {
            $code = $bmcModel->validateCustomer($data['union'], $bmcModel->dcs_code, $for);
        } else {
            $code = $bmcModel->dcs_code;
        }
        $model = new TblDcsPurchaseRateApplicabitity();
        $model->wef_date = $data['dt_date'];
        $data['milk_type'] = $data['milk_type'];
        $data['milk_quality_type_code'] = $data['milk_quality_type'];
        $data['appl_for'] = $for;
        $data['appl_code'] = !empty($validCode) ? $validCode : $code;
        $model_data = $model->getDcsPurchaseRateApplicableData($data);

        if (!empty($model_data)) {
            $detail_model = new TblDcsPurchaseRateDetails();
            $detail_model->rate_type_code = $model_data->rate_app_code;
            $detail_model->purchase_rate_code = $model_data->purchase_rate_code;
            $rate_type = !empty($detail_model->rateTypeCode) ? $detail_model->rateTypeCode->rate_type : '';
            $detail_data = $detail_model->getDcsPurchasseRateDetailData($data, $rate_type);

            if (!empty($detail_data)) {
                $response['status'] = 'success';
                $rtpl_data['list'] = $detail_data;
                $response['data'] = $rtpl_data;
            }
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($response);
    }

    public function actionListGrid() {
        $searchModel = new TblBmcCollectionSearch();
        $searchModel->setAttributes(Yii::$app->request->get('TblBmcCollection'));
        $dataProvider = $searchModel->createsearch([]);
        return $this->renderAjax('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    public function actionUpdateCollection() {
        $data = [];
        $data['status'] = 'error';
        $data['message'] = '';
        $modelData = [];
        $name = '';
        if (!empty($_POST['milk_collection_code'])) {
            $modelData = $this->findModel($_POST['milk_collection_code']);
            if (!empty($modelData)) {
                $model = $modelData;
                $model->getCustomerCodeVal();
                $model->date_time_of_collection = date('d-m-Y', strtotime($model->date_time_of_collection));
                $modelData = $model->attributes;
                $name = Yii::$app->general->getCustomer($model, $model->customer_type);
                $data['status'] = 'success';
            }
        }

        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return ['data' => $data, 'modelData' => $modelData, 'name' => $name]; //$this->renderAjax('_collection', ['model' => $model, 'modelData' => $modelData, 'type' => 'edit']);
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

    public function actionUpdateBmcCollection() {
        $searchModel = new TblBmcCollectionSearch();
        $dataProvider = $searchModel->updatesarch(Yii::$app->request->queryParams);
        $searchModel->scenario = 'deleteMilkCollection';
        $detailModel = $dataProvider->getModels();
        $message = 'BMC Collection';
        $type = 'edit';
        if (Yii::$app->request->post()) {
            foreach ($detailModel as $detail) {
                $detail->scenario = 'update';
                $detail->rtpl = '';
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
                    if (!empty($detalData->oldAttributes) && ($detalData->fat != $detalData->oldAttributes['fat'] || $detalData->snf != $detalData->oldAttributes['snf'] || $detalData->qty != $detalData->oldAttributes['qty'] || $detalData->milk_type_code != $detalData->oldAttributes['milk_type_code'] || $detalData->milk_quality_type_code != $detalData->oldAttributes['milk_quality_type_code'] || $detalData->no_of_can != $detalData->oldAttributes['no_of_can'])) {
                        if (Yii::$app->general->getUnionConfiguration($detalData->union_code, 'collection_approval', 'PORTAL') == 1) {
                            $approvalModel = new TblCollectionDataAlias();
                            $approvalModel->attributes = $detalData->attributes;
                            $approvalModel->old_qty = $detalData->oldAttributes['qty'];
                            $approvalModel->old_fat = $detalData->oldAttributes['fat'];
                            $approvalModel->old_snf = $detalData->oldAttributes['snf'];
                            $approvalModel->old_rtpl = $detalData->oldAttributes['rtpl'];
                            $approvalModel->old_clr = $detalData->oldAttributes['clr'];
                            $approvalModel->old_amount = $detalData->oldAttributes['amount'];
                            $approvalModel->old_milk_type_code = $detalData->oldAttributes['milk_type_code'];
                            $approvalModel->old_milk_quality_type_code = $detalData->oldAttributes['milk_quality_type_code'];
                            $approvalModel->old_purchase_rate_code = $detalData->oldAttributes['rate_code'];
                            $approvalModel->old_no_of_can = $detalData->oldAttributes['no_of_can'];
                            $approvalModel->table_name = 'tbl_bmc_collection';
                            $approvalModel->action_perform = 'UPDATE';
                            $approvalModel->date_time_of_collection = $detalData->date_time_of_collection . ' ' . \Yii::$app->general->getshift($detalData->shift_code);
                            $saveModel[] = $approvalModel;
                            $message = 'Data For Approval';
                            $type = 'create';
                        } else {
                            $existData = $this->findModel($detalData->milk_collection_code);
                            $historyModel = new TblBmcCollectionHistory();
                            Yii::$app->operation->history($existData, $historyModel, DELETE);
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
        ]);
    }

    public function actionDeleteBmcCollection() {
        $searchModel = new TblBmcCollectionSearch();
        $dataProvider = $searchModel->deletesearch(Yii::$app->request->queryParams);
        $searchModel->scenario = 'deleteMilkCollection';
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
                    $existData = TblBmcCollection::find()->where($where)->one();
                    if (Yii::$app->general->getUnionConfiguration($existData->union_code, 'collection_approval', 'PORTAL') == 1) {
                        $ApprovalModel = new TblCollectionDataAlias();
                        $ApprovalModel->attributes = $existData->attributes;
                        $ApprovalModel->setOldAttributesValues($ApprovalModel);
                        $ApprovalModel->table_name = 'tbl_bmc_collection';
                        $ApprovalModel->action_perform = 'DELETE';
                        $saveModel[] = $ApprovalModel;
                    } else {
                        $historyModel = new TblBmcCollectionHistory();
                        Yii::$app->operation->history($existData, $historyModel, DELETE);
                        $saveModel[] = $historyModel;
                        $deleteModel[] = $existData;
                        $message = 'BMC Collection';
                        $type = 'delete';
                    }
                }
                $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, [$message, $type]);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('delete', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
