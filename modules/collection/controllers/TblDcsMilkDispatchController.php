<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblDcsMilkDispatch;
use app\modules\collection\models\TblDcsMilkDispatchSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\collection\models\TblDcsMilkDispatchTxnSearch;
use app\modules\collection\models\TblDcsMilkDispatchTxn;
use app\modules\organisation\models\TblDcs;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use app\modules\dcsoperation\models\TblPurchaseRateDetails;
use yii\widgets\ActiveForm;
use app\modules\collection\models\TblCollectionDataAlias;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use app\modules\collection\models\TblDcsMilkDispatchTxnHistory;
use app\modules\collection\models\TblDcsMilkDispatchHistory;

/**
 * TblDcsMilkDispatchController implements the CRUD actions for TblDcsMilkDispatch model.
 */
class TblDcsMilkDispatchController extends \app\controllers\ChildController {

    public $freeAccessActions = ['validate-dcs', 'validate-rtpl', 'calculate-clr', 'list-grid'];

    /**
     * Lists all TblDcsMilkDispatch models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDcsMilkDispatchTxnSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblDcsMilkDispatch model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $bsearchModel = new TblDcsMilkDispatchTxnSearch();
        $bsearchModel->dcs_milk_dispatch_code = $id;
        $bdataProvider = $bsearchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'bdataProvider' => $bdataProvider, 'bsearchModel' => $bsearchModel,
        ]);
    }

    /**
     * Creates a new TblDcsMilkDispatch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblDcsMilkDispatch();
        $searchModel = new TblDcsMilkDispatchSearch();
        $searchModel->date_time_of_dispatch = date('Y-m-d');
        $searchModel->shift_code = 1;
        $dataProvider = $searchModel->createsearch(Yii::$app->request->get());
        $dataProvider->sort = false;
        $this->model->date_time_of_dispatch = date('d-m-Y');
        $txModel = new TblDcsMilkDispatchTxn();
        $txModel->milk_type_code = 1;
        $txModel->milk_quality_type_code = 1;
        $this->model->shift_code = 1;
        $txModel->scenario = 'create';

        $this->viewFile = 'create';
        $this->model->scenario = 'create';
        $modelSave = [];
        $message = 'Milk Dispatch';
        $type = 'create';
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $txModel->load(Yii::$app->request->post());
            $txModel->dcs_code = $this->model->dcs_code;
            $this->model->date_time_of_dispatch = !empty($this->model->date_time_of_dispatch) ? date('Y-m-d', strtotime($this->model->date_time_of_dispatch)) : '';
            $this->model->date_time_of_dispatch = $this->model->date_time_of_dispatch . ' ' . \Yii::$app->general->getshift($this->model->shift_code);
            if ($txModel->validate()) {
                $this->model->validateUnique($this->model, $txModel);
                $txModel->union_code = $this->model->union_code;
                Yii::$app->general->validateRateRange($txModel, 'avg_fat', 'avg_snf');
            }
            if (empty($this->model->getErrors()) && empty($txModel->getErrors()) && $this->model->validate() && $txModel->validate()) {
                if (Yii::$app->general->getUnionConfiguration($this->model->union_code, 'collection_approval', 'PORTAL') == 1) {
                    $approvalModel = new TblCollectionDataAlias();
                    $approvalModel->attributes = $this->model->attributes;
                    $approvalModel->attributes = $txModel->attributes;
                    $approvalModel->setModelAttributes($txModel, $approvalModel);
                    $approvalModel->table_name = 'tbl_dcs_milk_dispatch';
                    $approvalModel->action_perform = 'CREATE';
                    $approvalModel->date_time_of_collection = $this->model->date_time_of_dispatch;
                    $approvalModel->qty_mode = 0;
                    $modelSave[] = $approvalModel;
                    $message = 'Data For Approval';
                    $type = 'create';
                } else {
                    $existMainData = $this->model->getExistingData($this->model);
                    if (empty($existMainData)) {
                        $this->model->dcs_milk_dispatch_code = Yii::$app->general->getPrimaryCode($this->model);
                        $modelSave[] = $this->model;
                    }
                    $txModel->dcs_milk_dispatch_code = !empty($existMainData) ? $existMainData->dcs_milk_dispatch_code : $this->model->dcs_milk_dispatch_code;
                    $txModel->dcs_milk_dispatch_txn_code = Yii::$app->general->getTransactionCode($txModel, $txModel->dcs_milk_dispatch_code);
                    $modelSave[] = $txModel;
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
                $err = [];
                foreach ($this->model->getErrors() as $key => $value) {
                    $err[$key] = $value;
                }
                foreach ($txModel->getErrors() as $key => $value) {
                    $err[$key] = $value;
                }

                return Json::encode($err);
//                return Json::encode(ActiveForm::validate($this->model, $txModel));
            }
        } else {
            return $this->render('create', [
                        'model' => $this->model,
                        'searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'txModel' => $txModel,
            ]);
        }
        return $this->render('create', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'txModel' => $txModel,
        ]);
    }

    /**
     * Updates an existing TblDcsMilkDispatch model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->dcs_milk_dispatch_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblDcsMilkDispatch model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblDcsMilkDispatch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblDcsMilkDispatch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDcsMilkDispatch::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionListGrid() {
        $searchModel = new TblDcsMilkDispatchSearch();
        $searchModel->setAttributes(Yii::$app->request->get('TblDcsMilkDispatch'));
        $dataProvider = $searchModel->createsearch([]);
        return $this->renderAjax('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
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

        $model = new TblPurchaseRateApplicability();
        $model->dcs_code = $data['dcs_code'];
        $model->wef_date = $data['dt_date'];
        $data['rate_class'] = '(0, 1)';
        $model_data = $model->getPurchaseRateApplicableData($data);

        if (!empty($model_data)) {
            $detail_model = new TblPurchaseRateDetails();
            $detail_model->rate_type_code = $model_data->rate_app_code;
            $detail_model->purchase_rate_code = $model_data->purchase_rate_code;
            $rate_type = $detail_model->rateTypeCode->rate_type;
            $detail_data = $detail_model->getPurchasseRateDetailData($data, $rate_type);

            if (!empty($detail_data)) {
                $response['status'] = 'success';
                $rtpl_data['list'] = $detail_data;
                $response['data'] = $rtpl_data;
            }
        }
        return Json::encode($response);
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

    public function actionValidateDcs() {
        $response = [];
        $response['status'] = 'error';
        $response['data'] = '';
        $bmc = Yii::$app->request->post('bmc_code');
        $dcs = Yii::$app->request->post('dcs_code');
        $date = Yii::$app->request->post('date');
        $dispModel = new TblDcsMilkDispatch();
        $model = new TblDcs();
        $data = $model->validDcs($dcs, $bmc);
        $dispModel->dcs_code = $data;
        $dispModel->date_time_of_dispatch = $date;
        $detail = Yii::$app->general->validateDeactivateDcs($dispModel, $dispModel->date_time_of_dispatch);
        if ($detail === false) {
            $data = '';
        }
        if (!empty($data)) {
            $name = Yii::$app->general->getforeignkey($dispModel->dcsCode, 'dcs_name');
            $response['status'] = 'success';
            $response['data'] = $name;
            $response['code'] = $data;
        }
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($response);
    }

    public function actionUpdateMilkDispatch() {
        $searchModel = new TblDcsMilkDispatchTxnSearch();
        $dataProvider = $searchModel->updatesarch(Yii::$app->request->queryParams);
        $searchModel->scenario = 'deleteMilkDispatch';
        $detailModel = $dataProvider->getModels();
        $message = 'Milk Dispatch';
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

                    if (!empty($detalData->oldAttributes) && ($detalData->avg_fat != $detalData->oldAttributes['avg_fat'] || $detalData->avg_snf != $detalData->oldAttributes['avg_snf'] || $detalData->dispatch_qty != $detalData->oldAttributes['dispatch_qty'] || $detalData->milk_type_code != $detalData->oldAttributes['milk_type_code'] || $detalData->milk_quality_type_code != $detalData->oldAttributes['milk_quality_type_code'])) {
                        $existData = $this->findModel($detalData->dcs_milk_dispatch_code);
                        if (Yii::$app->general->getUnionConfiguration(Yii::$app->general->getforeignkey($detalData->dcsMilkDispatch, 'union_code'), 'collection_approval', 'PORTAL') == 1) {
                            $approvalModel = new TblCollectionDataAlias();
                            $approvalModel->attributes = $existData->attributes;
                            $approvalModel->attributes = $detalData->attributes;
                            $approvalModel->setModelAttributes($detalData, $approvalModel);
                            $approvalModel->old_qty = $detalData->oldAttributes['dispatch_qty'];
                            $approvalModel->old_fat = $detalData->oldAttributes['avg_fat'];
                            $approvalModel->old_snf = $detalData->oldAttributes['avg_snf'];
                            $approvalModel->old_rtpl = $detalData->oldAttributes['rtpl'];
                            $approvalModel->old_clr = $detalData->oldAttributes['avg_clr'];
                            $approvalModel->old_amount = $detalData->oldAttributes['total_amount'];
                            $approvalModel->old_milk_type_code = $detalData->oldAttributes['milk_type_code'];
                            $approvalModel->old_milk_quality_type_code = $detalData->oldAttributes['milk_quality_type_code'];
                            $approvalModel->old_purchase_rate_code = $detalData->oldAttributes['purchase_rate_code'];
                            $approvalModel->table_name = 'tbl_dcs_milk_dispatch';
                            $approvalModel->action_perform = 'UPDATE';
                            $date = Yii::$app->general->getforeignkey($detalData->dcsMilkDispatch, 'date_time_of_dispatch');
                            $approvalModel->date_time_of_collection = !empty($date) ? date('Y-m-d', strtotime($date)) : '';
                            $approvalModel->date_time_of_collection = $approvalModel->date_time_of_collection . ' ' . \Yii::$app->general->getshift(Yii::$app->general->getforeignkey($detalData->dcsMilkDispatch, 'shift_code'));
                            $saveModel[] = $approvalModel;
                            $message = 'Data For Approval';
                            $type = 'create';
                        } else {
                            $historyModelMain = new TblDcsMilkDispatchHistory();
                            Yii::$app->operation->history($existData, $historyModelMain, 'UPDATE');
                            $saveModel[] = $historyModelMain;

                            $existTxnData = TblDcsMilkDispatchTxn::findOne($detalData->dcs_milk_dispatch_txn_code);
                            $historyModel = new TblDcsMilkDispatchTxnHistory();
                            Yii::$app->operation->history($existTxnData, $historyModel, 'UPDATE');
                            $saveModel[] = $historyModel;
                            $existTxnData->attributes = $detalData->attributes;
                            $existTxnData->date_time_of_dispatch = $existData->date_time_of_dispatch . ' ' . \Yii::$app->general->getshift($existData->shift_code);
                            $saveModel[] = $existTxnData;
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

    public function actionDeleteMilkDispatch() {
        $searchModel = new TblDcsMilkDispatchTxnSearch();
        $dataProvider = $searchModel->deletesearch(Yii::$app->request->queryParams);
        $searchModel->scenario = 'deleteMilkDispatch';
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $deleteModel = [];
                $deletedata = Yii::$app->request->post('selection');
                $codes = empty(Yii::$app->request->post('selection')) ? [] : Yii::$app->request->post('selection');
                $where = [];
                $message = 'Data For Approval';
                $type = 'create';
                foreach ($deletedata as $code) {
                    $where['dcs_milk_dispatch_txn_code'] = $code;
                    $existTxnData = TblDcsMilkDispatchTxn::find()->where($where)->one();
                    $existData = TblDcsMilkDispatch::find()->where(['dcs_milk_dispatch_code' => $existTxnData->dcs_milk_dispatch_code])->one();
                    if (Yii::$app->general->getUnionConfiguration($existData->union_code, 'collection_approval', 'PORTAL') == 1) {
                        $ApprovalModel = new TblCollectionDataAlias();
                        $ApprovalModel->attributes = $existData->attributes;
                        $ApprovalModel->attributes = $existTxnData->attributes;
                        $ApprovalModel->setModelAttributes($existTxnData, $ApprovalModel);
                        $ApprovalModel->table_name = 'tbl_dcs_milk_dispatch';
                        $ApprovalModel->action_perform = 'DELETE';
                        $date = $existData->date_time_of_dispatch;
                        $ApprovalModel->date_time_of_collection = !empty($date) ? date('Y-m-d', strtotime($date)) : '';
                        $ApprovalModel->date_time_of_collection = $ApprovalModel->date_time_of_collection . ' ' . \Yii::$app->general->getshift($existData->shift_code);
                        $saveModel[] = $ApprovalModel;
                    } else {
                        $historyModelMain = new TblDcsMilkDispatchHistory();
                        Yii::$app->operation->history($existData, $historyModelMain, DELETE);
                        $saveModel[] = $historyModelMain;
                        $txCount = TblDcsMilkDispatchTxn::find()->where(['dcs_milk_dispatch_code' => $existData->dcs_milk_dispatch_code])->count();

                        if ($txCount == 1) {
                            $deleteModel[] = $existData;
                        }

                        $historyModel = new TblDcsMilkDispatchTxnHistory();
                        Yii::$app->operation->history($existTxnData, $historyModel, DELETE);
                        $saveModel[] = $historyModel;
                        $deleteModel[] = $existTxnData;
                        $message = 'Milk Dispatch';
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
