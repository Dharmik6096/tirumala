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

/**
 * TblBmcCollectionController implements the CRUD actions for TblBmcCollection model.
 */
class TblBmcCollectionController extends \app\controllers\ChildController {

    public $freeAccessActions = ['validate-dcs', 'validate-rtpl', 'calculate-clr'];

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
        $dataProvider = $searchModel->gridsearch(Yii::$app->request->get());

        $this->model->date_time_of_collection = date('d-m-Y');
        $this->model->milk_type_code = 1;
        $this->model->milk_quality_type_code = 1;
        $this->model->shift_code = 1;

        $this->viewFile = 'create';
        $this->model->scenario = 'create';
        $modelSave = [];
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
                $this->model->qlty_auto = 1;
                $this->model->qty_auto = 1;
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
                    $this->model->dcs_code = $dcs->validDcs($this->model->customer_code);
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
            $modelSave[] = $this->model;
            if ($this->model->validate()) {
                $this->model->qty_mode = Yii::$app->general->getUnionConfiguration($this->model->union_code, 'collection_qty_mode', 'BMC');
                $conversion_const = Yii::$app->general->getUnionConfiguration($this->model->union_code, 'ltr_to_kg_constant', 'BMC');
                $this->model->converted_qty_mode = $this->model->qty_mode == 1 ? 0 : 1;
                $this->model->converted_qty = $this->model->qty_mode == 1 ? $this->model->qty / $conversion_const : $this->model->qty * $conversion_const;
                $transaction = $this->generalModel->saveTransaction($modelSave, ['BMC Collection', ($update) ? 'edit' : 'create']);
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
        $dcs = Yii::$app->request->post('dcs_code');
        $union = Yii::$app->request->post('union_code');
        $type = Yii::$app->request->post('customer_type');
        $bmcModel = new TblBmcCollection();
        if (!empty($type) && strtolower($type) != 'dcs') {
            $data = $bmcModel->validateCustomer($union, $dcs, $type);
            $bmcModel->customer_code = $data;
        } else {
            $model = new TblDcs();
            $data = $model->validDcs($dcs);
            $bmcModel->dcs_code = $data;
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
        $bmcModel = new TblBmcCollection();
        $dcsModel = new TblDcs();
        $dcs = $dcsModel->validDcs($data['dcs_code']);
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
        $data['appl_code'] = $code;
        $model_data = $model->getDcsPurchaseRateApplicableData($data);

        if (!empty($model_data)) {
            $detail_model = new TblDcsPurchaseRateDetails();
            $detail_model->rate_type_code = $model_data->rate_app_code;
            $detail_model->purchase_rate_code = $model_data->purchase_rate_code;
            $rate_type = $detail_model->rateTypeCode->rate_type;
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
        $dataProvider = $searchModel->gridsearch([]);
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

}
