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

/**
 * TblBmcCollectionController implements the CRUD actions for TblBmcCollection model.
 */
class TblBmcCollectionController extends \app\controllers\ChildController {

    public $freeAccessActions = ['validate-dcs', 'validate-rtpl'];

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

        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->date_time_of_collection = ($this->model->date_time_of_collection) ? Yii::$app->formatter->asDate($this->model->date_time_of_collection, DATE_FORMAT) : '';
            $this->model->date_time_of_collection = $this->model->date_time_of_collection . ' ' . \Yii::$app->general->getshift($this->model->shift_code);
            $this->model->bmc_code = Yii::$app->general->getforeignkey($this->model->dcsCode, 'bmc_code');
            $this->model->qty_mode = 1;
            $this->model->qlty_auto = 1;
            $this->model->qty_auto = 1;
            $this->model->dt_date = date('Y-m-d H:i:s');
            $transaction = $this->generalModel->saveTransaction([$this->model], ['BMC Collection', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        $searchModel = new TblBmcCollectionSearch();
        if (!empty(Yii::$app->request->get())) {
            $searchModel->date_time_of_collection = Yii::$app->request->get('TblBmcCollection')['date_time_of_collection'];
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('create', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
//        return $this->customRender();
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
        $dcs = Yii::$app->request->post('dcs_code');
        $model = new TblDcs();
        $data = $model->validDcs($dcs);
        if (!empty($data)) {
            return Json::encode(['status' => 'success']);
        } else {
            return Json::encode(['status' => 'error']);
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
        $data['shift'] = Yii::$app->request->post('shift');
        $data['dt_date'] = $data['dt_date'] . ' ' . \Yii::$app->general->getshift($data['shift']);
        $data['fat'] = Yii::$app->request->post('fat');
        $data['snf'] = Yii::$app->request->post('snf');
        $bmcModel = new TblBmcCollection();
        $bmcModel->dcs_code = $data['dcs_code'];
        $bmcModel->bmc_code = Yii::$app->general->getforeignkey($bmcModel->dcsCode, 'bmc_code');
        $bmcModel->mcc_plant_code = Yii::$app->general->getforeignkey($bmcModel->dcsCode, 'mcc_plant_code');
        $is_mcc = Yii::$app->general->getforeignkey($bmcModel->bmcData, 'is_mcc');
        $for = $is_mcc == 1 ? 'MCC' : 'BMC';
        $code = $for == 'MCC' ? $bmcModel->mcc_code : $bmcModel->bmc_code;
        $model = new TblDcsPurchaseRateApplicabitity();
        $model->dcs_code = $data['dcs_code'];
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

}
