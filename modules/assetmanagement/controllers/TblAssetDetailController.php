<?php

namespace app\modules\assetmanagement\controllers;

use Yii;
use app\modules\assetmanagement\models\TblAssetDetail;
use app\modules\assetmanagement\models\TblAssetDetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\assetmanagement\models\TblAssetDetailHistory;
use app\modules\assetmanagement\models\TblAssetMaster;
use yii\helpers\Json;
use app\modules\assetmanagement\models\TblAssetTransaction;
use app\modules\assetmanagement\models\TblAssetTransactionSearch;
use app\modules\assetmanagement\models\TblAssetTransactionHistory;
use yii\web\Response;
use app\modules\assetmanagement\models\TblAssetSet;
use app\modules\assetmanagement\models\TblAssetSetHistory;
use yii\widgets\ActiveForm;
use app\models\ChildModel;
use yii\base\UserException;
use app\modules\document\models\TblAttachment;
use yii\data\ActiveDataProvider;

/**
 * TblAssetDetailController implements the CRUD actions for TblAssetDetail model.
 */
class TblAssetDetailController extends \app\controllers\ChildController {

    public $freeAccessActions = ['get-asset-is-serial', 'get-serial-no', 'validate-asset-qty', 'new-sr-no'];

    public function init() {
        parent::init();
        $this->enableCsrfValidation = FALSE;
    }

    /**
     * Lists all TblAssetDetail models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblAssetTransactionSearch();
        $dataProvider = $searchModel->gridsearch(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblAssetDetail model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblAssetTransactionSearch();
        $searchModel->asset_detail_code = $id;
        $searchModel->is_search = 1;
        $attachment = new TblAttachment();
        $attachmentDataProvider = new ActiveDataProvider([
            'query' => $attachment->find()->where(['module_code' => (string)$id, 'module_name' => 'asset']),
        ]);

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id), 'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'attachment' => $attachment,
                    'attachmentDataProvider' => $attachmentDataProvider,
        ]);
    }

    /**
     * Creates a new TblAssetDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblAssetDetail();
        $assetTrans = new TblAssetTransaction();
        $assetTrans->scenario = 'create';
        $this->viewFile = 'create';
        $master = [];
        if ($this->model->load(Yii::$app->request->post())) {
//            $this->model->asset_detail_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            $this->setModel($this->model);
            if ($this->model->validate()) {
                $master[] = $this->model;
                $assetTrans->current_status = $this->model->current_status;
                //            $assetTrans->asset_detail_code = $this->model->asset_detail_code;
                $assetTrans->from_type = 'VEN';
                $assetTrans->from_dest = $this->model->manufacturer_code;
                //            $assetTrans->to_type = Yii::$app->general->getforeignkey($this->model->storeLocCode, 'store_location_type');
                $assetTrans->to_type = isset($this->model->storeLocCode) ? (isset($this->model->storeLocCode->store_location_type) ? $this->model->storeLocCode->store_location_type : NULL) : NULL;
                $assetTrans->to_dest = $this->model->store_location_code;
                $assetTrans->asset_code = $this->model->asset_code;
                $assetTrans->serial_number = $this->model->serial_number;
                $assetTrans->manufacturer_serial_number = $this->model->manufacturer_serial_number;
                $assetTrans->status = 0;
                $assetTrans->union_code = $this->model->union_code;
                $assetTrans->transaction_date = $this->model->put_to_use_date;
                $assetTrans->qty = empty($this->model->qty) ? 1 : $this->model->qty;
                $assetTrans->put_to_use_date = $this->model->put_to_use_date;
                $assetTrans->detail_code = $this->model->detail_code;
                $assetTrans->remain_qty = $assetTrans->qty;
                if ($assetTrans->status == 2) {
                    //                if (empty($assetTrans->inUseSAPCode)) {
                    //                    $this->model->addError('store_location_code', Yii::t('app', 'Please define SAP Code for the destination'));
                    //                    return $this->customRender();
                    //                } else {
                    //                    $assetTrans->sap_code = $assetTrans->inUseSAPCode->sap_code;
                    //                }
                    if (!empty($assetTrans->inUseSAPCode)) {
                        $assetTrans->sap_code = $assetTrans->inUseSAPCode->sap_code;
                    }
                }

                $master[] = $assetTrans;
                $auto_key_config['TblAssetTransaction'][] = ['self_key' => 'asset_detail_code', 'parent_key' => 'asset_detail_code', 'parent_index' => 0];
                $transaction = $this->generalModel->saveTransactionAutoIncForeignKey($master, ['Asset Detail', 'create'], $auto_key_config);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblAssetDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $this->model->store_location_type = Yii::$app->general->getforeignkey($this->model->storeLocCode, 'store_location_type');
        if ($this->model->store_location_type == 3) {
            $this->model->to_plant = Yii::$app->general->getmultiforeignkey($this->model->storeLocCode, ['toDcsCode'], 'plant_code');
            $this->model->to_mcc = Yii::$app->general->getmultiforeignkey($this->model->storeLocCode, ['toDcsCode'], 'mcc_plant_code');
            $this->model->to_bmc = Yii::$app->general->getmultiforeignkey($this->model->storeLocCode, ['toDcsCode'], 'bmc_code');
            $this->model->to_dcs = Yii::$app->general->getforeignkey($this->model->storeLocCode, 'reference_code');
        } elseif ($this->model->store_location_type == 2) {
            $this->model->to_plant = Yii::$app->general->getmultiforeignkey($this->model->storeLocCode, ['toMccPlantCode'], 'plant_code');
            $this->model->to_mcc = Yii::$app->general->getmultiforeignkey($this->model->storeLocCode, ['toMccPlantCode'], 'mcc_plant_code');
            $this->model->to_bmc = Yii::$app->general->getforeignkey($this->model->storeLocCode, 'reference_code');
        }
        if (Yii::$app->request->post()) {
            $historyModel = new TblAssetDetailHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->setModel($this->model);
            if ($this->model->validate()) {
                $transaction = $this->saveData($this->model, $historyModel, ['Asset Detail', 'edit']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Finds the TblAssetDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblAssetDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblAssetDetail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function setModel() {
        $this->model->purchase_date = ($this->model->purchase_date == '') ? null : Yii::$app->formatter->asDate($this->model->purchase_date, DATE_FORMAT);
        $this->model->put_to_use_date = ($this->model->put_to_use_date == '') ? null : Yii::$app->formatter->asDate($this->model->put_to_use_date, DATE_FORMAT);
        $this->model->verification_date = ($this->model->verification_date == '') ? null : Yii::$app->formatter->asDate($this->model->verification_date, DATE_FORMAT);
    }

    public function actionGetAssetIsSerial() {
        $data = [];
        $data['status'] = 'error';
        if (!empty($_POST)) {
            $model = new TblAssetMaster();
            $model->asset_code = $_POST['asset_code'];
            $asset_serial = $model->getAssetData();

            if (!empty($asset_serial)) {
                $data['status'] = 'success';
                $data['is_serial_number'] = $asset_serial->is_serial_number;
            }
        }
        return Json::encode($data);
    }

    public function actionOutAssetTransation() {
        $txnModel = new TblAssetTransaction();
        $searchModel = new TblAssetTransactionSearch();
        $searchModel->status = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('asset_transaction', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'txnModel' => $txnModel,
        ]);
    }

    public function actionOutwardAssetTransaction() {

        if (Yii::$app->request->post()) {

            if (isset($_REQUEST['selection'])) {
                $HisModel = [];
                $saveModel = [];
                $selecteddata = Yii::$app->request->post('selection');
                foreach ($selecteddata as $key => $value) {
                    $this->model = TblAssetTransaction::findOne($value);
                    $historyModel = new TblAssetTransactionHistory();
                    Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                    $HisModel[] = $historyModel;
                    $this->model->status = 1;
                    $saveModel[] = $this->model;
                    $model = new TblAssetTransaction();
                    $model->asset_detail_code = $this->model->asset_detail_code;
                    $model->asset_code = $this->model->asset_code;
                    $model->from_type = $this->model->to_type;
                    $model->from_dest = $this->model->to_dest;
                    $model->to_type = Yii::$app->request->post()['TblAssetTransaction']['to_type'];
                    $model->to_dest = Yii::$app->request->post()['TblAssetTransaction']['to_dest'];
                    $this->model->transaction_date = (Yii::$app->request->post()['TblAssetTransaction']['transaction_date'] == '') ? null : Yii::$app->formatter->asDate(Yii::$app->request->post()['TblAssetTransaction']['transaction_date'], DATE_FORMAT);
                    $model->serial_number = $this->model->serial_number;
                    $model->manufacturer_serial_number = $this->model->manufacturer_serial_number;
                    $model->status = '-1';
                    if ($model->to_type == $model->from_type && $model->to_dest == $model->from_dest) {
                        $model->status = 2;
                    }
                    $model->union_code = $this->model->union_code;
                    $saveModel[] = $model;
                }
                $transaction = $this->generalModel->saveTransaction($saveModel, $HisModel, ['Outward Asset', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            }
        }
    }

    public function actionOutwardUseTransaction() {
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $HisModel = [];
                $saveModel = [];
                $selecteddata = Yii::$app->request->post('selection');
                foreach ($selecteddata as $key => $value) {
                    $this->model = TblAssetTransaction::findOne($value);
                    $historyModel = new TblAssetTransactionHistory();
                    Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                    $HisModel[] = $historyModel;
                    $this->model->status = 2;
                    $saveModel[] = $this->model;
                }
                $transaction = $this->generalModel->saveTransaction($saveModel, $HisModel, ['In-Use Asset', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            }
        }
    }

    public function actionInAssetTransation() {
        $txnModel = new TblAssetTransaction();
        $searchModel = new TblAssetTransactionSearch();

        $searchModel->status = '-1';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('asset_detail_transaction', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'txnModel' => $txnModel,
        ]);
    }

    public function actionInwardAssetTransaction() {
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $HisModel = [];
                $saveModel = [];
                $selecteddata = Yii::$app->request->post('selection');
                foreach ($selecteddata as $key => $value) {
                    $this->model = TblAssetTransaction::findOne($value);
                    $historyModel = new TblAssetTransactionHistory();
                    Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                    $HisModel[] = $historyModel;
                    $this->model->status = 0;
                    $this->model->received_date = (Yii::$app->request->post()['TblAssetTransaction']['received_date'] == '') ? null : Yii::$app->formatter->asDate(Yii::$app->request->post()['TblAssetTransaction']['received_date'], DATE_FORMAT);
                    $this->model->received_by = Yii::$app->request->post()['TblAssetTransaction']['received_by'];
                    $saveModel[] = $this->model;
                }
                $transaction = $this->generalModel->saveTransaction($saveModel, $HisModel, ['Inward Asset', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->redirect(['index']);
    }

    public function actionCheckSerialNumber($serial_number) {
        $message = TblAssetTransaction::getSerialNo($serial_number);
        return $message;
    }

    public function actionAssetTransaction() {
        $model = new TblAssetTransaction();
        if (Yii::$app->request->post()) {
            $saveModel = [];
            $HisModel = [];
            $model->load(Yii::$app->request->post());
            $model->transaction_date = Yii::$app->formatter->asDate($model->transaction_date, DATE_FORMAT);
            if ($model->to_type == 3 || $model->in_ward == '1') {
                $check_data = TblAssetSet::find()->where(['store_location_type' => $model->to_type, 'store_location_code' => $model->to_dest, 'status' => [2]])
                        ->one();
//                if (empty($check_data)) {
//                    $record = ['status' => 'success', 'msg' => Yii::t('app', 'Please define SAP Code for the destination')];
//                    Yii::$app->response->format = Response::FORMAT_JSON;
//                    return Json::encode($record);
//                }
                if (!empty($check_data)) {
                    $historyModel = new TblAssetSetHistory();
                    Yii::$app->operation->history($check_data, $historyModel, UPDATE);
                    $HisModel[] = $historyModel;
                    $check_data->status = 2;
                    $saveModel[] = $check_data;
                    if (!empty($check_data->sap_code)) {
                        $sap_code = $check_data->sap_code;
                    }
                }
            }
            foreach ($model->selected_sr_no as $asset_code => $asset_detail) {
                if ($asset_detail['is_serial_number'] == '1') {
                    $sr_no_detail = json_decode($asset_detail['serial_number']);
                    foreach ($sr_no_detail as $detail) {
                        $data = explode('===', $detail);
                        $sr_no = $data[0];
                        $tr_code = $data[1];
                        $trn_model = TblAssetTransaction::findOne($tr_code);
                        $historyModel = new TblAssetTransactionHistory();
                        Yii::$app->operation->history($trn_model, $historyModel, UPDATE);
                        $HisModel[] = $historyModel;
                        if ($model->in_ward == '0') {
                            $this->setSAPCode($saveModel, $trn_model);
                            $trn_model->status = 1;
                            $out_model = new TblAssetTransaction();
                            $out_model->asset_detail_code = $trn_model->asset_detail_code;
                            $out_model->asset_code = $trn_model->asset_code;
                            $out_model->from_type = $trn_model->to_type;
                            $out_model->from_dest = $trn_model->to_dest;
                            $out_model->to_type = $model->to_type;
                            $out_model->to_dest = $model->to_dest;
                            $out_model->from_mcc = $model->from_mcc;
                            $out_model->to_mcc = $model->to_mcc;
                            $out_model->serial_number = $trn_model->serial_number;
                            $out_model->manufacturer_serial_number = $trn_model->manufacturer_serial_number;
                            $out_model->transaction_date = $model->transaction_date;
                            $out_model->put_to_use_date = $model->transaction_date;
                            $out_model->status = '-1';
                            if ($model->to_type == $model->from_type && $model->to_dest == $model->from_dest) {
                                $out_model->status = 2;
                            }
                            $out_model->union_code = $trn_model->union_code;
                            $out_model->remarks = $model->remarks;
                            $out_model->qty = $out_model->remain_qty = 1;
                            $out_model->sap_code = ($model->to_type == 3) ? (isset($sap_code) ? $sap_code : NULL) : $trn_model->sap_code;
                            $out_model->current_status = $trn_model->current_status;
                            $saveModel[] = $out_model;
                        } else {
                            $trn_model->status = 2;
                            $trn_model->put_to_use_date = $model->transaction_date;
                            $trn_model->remarks = $model->remarks;
                            if (!empty($sap_code)) {
                                $trn_model->sap_code = $sap_code;
                            }
                        }
                        $trn_model->transaction_date = $model->transaction_date;
                        $saveModel[] = $trn_model;
                    }
                } else {
                    $out_qty = $asset_detail['qty'];
                    $trn_model = TblAssetTransaction::find()->where(['tbl_asset_transaction.to_type' => $model->from_type, 'tbl_asset_transaction.to_dest' => $model->from_dest, 'tbl_asset_transaction.asset_code' => $asset_detail['asset_code']])
                            ->joinWith(['assetDetail'])
                            ->andWhere(['!=', 'tbl_asset_transaction.remain_qty', 0])
                            ->orderBy('tbl_asset_detail.purchase_date ASC')
                            ->all();
                    $cnt = 0;
                    while ($out_qty != 0) {
                        $historyModel = new TblAssetTransactionHistory();
                        Yii::$app->operation->history($trn_model[$cnt], $historyModel, UPDATE);
                        $HisModel[] = $historyModel;
                        $act_qty = $trn_model[$cnt]->remain_qty;
                        $trn_model[$cnt]->remain_qty = ($act_qty >= $out_qty) ? $act_qty - $out_qty : 0;
                        ($trn_model[$cnt]->remain_qty == 0) ? $trn_model[$cnt]->status = 1 : NULL;
                        $trn_model[$cnt]->transaction_date = $model->transaction_date;
                        $out_model = new TblAssetTransaction();
                        $out_model->asset_detail_code = $trn_model[$cnt]->asset_detail_code;
                        $out_model->asset_code = $trn_model[$cnt]->asset_code;
                        $out_model->from_type = $trn_model[$cnt]->to_type;
                        $out_model->from_dest = $trn_model[$cnt]->to_dest;
                        $out_model->to_type = $model->to_type;
                        $out_model->to_dest = $model->to_dest;
                        $out_model->serial_number = $trn_model[$cnt]->serial_number;
                        $out_model->manufacturer_serial_number = $trn_model[$cnt]->manufacturer_serial_number;
                        $out_model->transaction_date = $model->transaction_date;
                        $out_model->put_to_use_date = $model->transaction_date;
                        $out_model->qty = ($out_qty >= $act_qty) ? $act_qty : $out_qty;
                        $out_model->remain_qty = $out_model->qty;
                        $out_model->current_status = $trn_model[$cnt]->current_status;
                        if ($model->in_ward == '0') {
                            $out_model->status = '-1';
                            if ($model->to_type == $model->from_type && $model->to_dest == $model->from_dest) {
                                $out_model->status = 2;
                            }
                            $out_model->sap_code = ($model->to_type == 3) ? $sap_code : $trn_model[$cnt]->sap_code;
                        } else {
                            $out_model->status = 2;
                            if (!empty($sap_code)) {
                                $out_model->sap_code = $sap_code;
                            }
                        }
                        /* if ($out_model->status == 2) {
                          $out_model->remain_qty = 0;
                          } */
                        $out_model->union_code = $trn_model[$cnt]->union_code;
                        $out_model->remarks = $model->remarks;
                        $saveModel[] = $out_model;
                        $saveModel[] = $trn_model[$cnt];
                        if ($act_qty >= $out_qty) {
                            $out_qty = 0;
                        } else {
                            $out_qty -= $act_qty;
                        }
                        $cnt++;
                    }
                }
            }
            $transaction = $this->generalModel->saveTransaction($saveModel, $HisModel, ['Outward/In-Use Asset', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['index']);
            } else {
                $msg = Yii::$app->getSession()->getFlash('success')['message'];
                $record = ['status' => 'success', 'msg' => $msg];
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode($record);
            }
        }
        return $this->render('asset_transaction_form', [
                    'model' => $model,
        ]);
    }

    public function actionValidateAssetQty() {
        $data = [];
        $data['status'] = 'error';
        $data['msg'] = '';
        $data['is_serial_number'] = 0;
        if (!empty($_POST) && $_POST['qty'] > 0) {
            $trModel = new TblAssetTransaction();
            $trModel->setAttributes($_POST);
            $trModel->scenario = 'validateOut';
//            $trModel->from_dest = $_POST['from_dest'];
//            $trModel->from_type = $_POST['from_type'];
//            $trModel->asset_code = $_POST['asset_code'];
            $from_qty = $trModel->getQtySum('from');
            $to_qty = $trModel->getQtySum('to');
            $diffQty = $to_qty - $from_qty;
            $msg = '';
            if (!$trModel->validate()) {
                foreach ($trModel->getErrors() as $e) {
                    $errMsg = !empty($e[0]) ? $e[0] : '';
                    $msg .= !empty($msg) ? '<br/>' . Yii::t('app', $errMsg) : Yii::t('app', $errMsg);
                }
            }
            if ($diffQty <= 0 || !empty($msg)) {
                $e = $diffQty <= 0 ? Yii::t('app', 'Quantity not available') : '';
                $msg .= !empty($msg) ? '<br/>' . $e : $e;
                $data['msg'] = $msg;
            } else if ($diffQty < $_POST['qty'] || !empty($msg)) {
                $e = $diffQty < $_POST['qty'] ? Yii::t('app', 'Quantity can not be greater than ') . $diffQty : '';
                $msg .= !empty($msg) ? '<br/>' . $e : $e;
                $data['msg'] = $msg;
            } else {
                $isSerialNo = Yii::$app->general->getforeignkey($trModel->assetCode, 'is_serial_number');
                $data['status'] = 'success';
                $data['is_serial_number'] = $isSerialNo != '' && $isSerialNo != 'N/A' ? $isSerialNo : 0;
            }
        } else {
            $data['msg'] = Yii::t('app', 'Outward Quantity should be greater than 0');
        }
        return Json::encode($data);
    }

    public function actionGetSerialNo() {
        $trModel = new TblAssetTransaction();
        $serialNoRecords = [];
        $selectedRecords = [];
        $sr_count = 0;
        if (!empty($_POST)) {
            $trModel->from_dest = $_POST['from_dest'];
            $trModel->from_type = $_POST['from_type'];
            $trModel->asset_code = $_POST['asset_code'];
            $selectedRecords = $_POST['selected_sr_no'];
            $selectedRecords = !empty($selectedRecords) ? json_decode($selectedRecords) : [];
            $sr_count = $_POST['qty'];
            $serialNoRecords = $trModel->getSerialNoRecords();
        }

        return $this->renderAjax('asset_serial_no_form', [
                    'model' => $trModel,
                    'serialNoRecords' => $serialNoRecords,
                    'selectedRecords' => $selectedRecords,
                    'sr_count' => $sr_count
        ]);
    }

    public function setSAPCode(&$saveModel, $model) {
        if (!empty($model->sap_code)) {
            $trn = TblAssetTransaction::find()->where(['to_dest' => $model->from_dest, 'to_type' => $model->from_type, 'status' => [-1, 0, 2], 'sap_code' => $model->sap_code])
                    ->count();
            $check_cnt = $trn - 1;
            if ($check_cnt <= 0) {
                $asset_set = TblAssetSet::find()->where(['sap_code' => $model->sap_code, 'status' => [-1, 0, 2]])->all();
                $addset = TRUE;
                if (!empty($asset_set)) {
                    foreach ($asset_set as $set) {
                        $historyModel = new TblAssetSetHistory();
                        Yii::$app->operation->history($set, $historyModel, UPDATE);
                        $saveModel[] = $historyModel;
                        if ($set->store_location_code == $model->to_dest) {
                            $addset = FALSE;
                        }
                        $set->status = ($set->store_location_code == $model->to_dest) ? $model->status : 1;
                        $set->is_active = ($set->status == 1) ? 0 : 1;
                        $saveModel[] = $set;
                    }
                }
                if ($addset) {
                    $set = new TblAssetSet();
                    $set->store_location_code = $model->to_dest;
                    $set->attributes = $set->storeLocCode->attributes;
                    $set->sap_code = $model->sap_code;
                    $set->status = $model->status;
                    $saveModel[] = $set;
                }
            }
        }
    }

    public function saveData($model, $hist, $message) {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $master = [];
            $oldSr = $model->oldAttributes['serial_number'];
            $oldMSr = $model->oldAttributes['manufacturer_serial_number'];
            $oldCs = $model->oldAttributes['current_status'];
            $master[] = $model->save();
            $master[] = $hist->save();
            if (!in_array(FALSE, $master)) {
                $assetTrans = new TblAssetTransaction();
                $assetTrans->updateAll(['serial_number' => $model->serial_number, 'current_status' => $model->current_status, 'manufacturer_serial_number' => $model->manufacturer_serial_number], ['serial_number' => $oldSr, 'current_status' => $oldCs, 'manufacturer_serial_number' => $oldMSr, 'asset_detail_code' => $model->asset_detail_code]);
                $transaction->commit();
                Yii::$app->display->message(true, $message[0], $message[1]);
                return 'customRedirect';
            }
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => 'Your transaction is not saved successfully']);
            return 'customRender';
        } catch (UserException $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => $e->getMessage()]);
            return false;
        } catch (\yii\db\Exception $e) {
            $transaction->rollback();
            Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                'message' => htmlspecialchars($e->errorInfo[2], ENT_QUOTES, 'UTF-8')]);
            return false;
        }
    }

    public function actionNewSrNo() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            $asset = explode('##', $parents[0]);
            $code = [];
            $type = [];
            if (!empty($parents[2] && $parents[2] != 'Loading ...')) {
                $code[] = $parents[2];
                $type[] = 1;
            }
            if (!empty($parents[3] && $parents[3] != 'Loading ...')) {
                $code[] = $parents[3];
                $type[] = 2;
            }
            if (!empty($parents[4]) && $parents[4] != 'Loading ...') {
                $code[] = $parents[4];
                $type[] = 3;
            }
            $spare_code = (!empty($parents[5]) && $parents[5] != 'Loading ...') ? $parents[5] : '';
            if (!empty($parents[0])) {
                $bom = new TblAssetTransaction();
                $data = $bom->getNewSrNo($asset[0], $code, $spare_code, $type);
                foreach ($data as $key => $val) {
                    $out[] = array('id' => $val['serial_number'], 'name' => $val['serial_number']);
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        return Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionAssetTransfer() {
        $txnModel = new TblAssetDetail();
        $txnModel->scenario = 'assetTransfer';
        $searchModel = new TblAssetTransactionSearch();
        $searchModel->scenario = 'assetTransfer';
        $dataProvider = $searchModel->assetTranferSearch(Yii::$app->request->queryParams);
        if ($txnModel->load(Yii::$app->request->post()) && $txnModel->validate()) {
            if (isset($_REQUEST['selection'])) {
                $saveModel = [];
                $data = Yii::$app->request->post('selection');
                $where = [];
                foreach ($data as $code) {
                    $where['asset_transaction_code'] = $code;
                    $existData = TblAssetTransaction::find()->where($where)->one();
                    $existData->scenario = 'assetTransfer';
                    $historyModel = new TblAssetTransactionHistory();
                    Yii::$app->operation->history($existData, $historyModel, UPDATE);
                    $saveModel[] = $historyModel;
                    $existData->detail_code = Yii::$app->request->post()['TblAssetDetail']['detail_code'];
                    $saveModel[] = $existData;

                    $assetDetailData = TblAssetDetail::findOne(['asset_detail_code' => $existData->asset_detail_code]);
                    $assetDetailData->scenario = 'assetTransfer';
                    $assetDetailhistoryModel = new TblAssetDetailHistory();
                    Yii::$app->operation->history($assetDetailData, $assetDetailhistoryModel, UPDATE);
                    $saveModel[] = $assetDetailhistoryModel;
                    $assetDetailData->detail_code = $existData->detail_code;
                    $saveModel[] = $assetDetailData;
                }
                $transaction = $this->generalModel->saveTransaction($saveModel, ['Asset Transfer', 'edit']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('asset_transfer', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'txnModel' => $txnModel,
        ]);
    }

}
