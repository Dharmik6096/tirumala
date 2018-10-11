<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblMilkCollectionTemp;
use app\modules\collection\models\TblMilkCollectionTempSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\dcsoperation\models\TblPurchaseRate;
use yii\web\Response;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use app\modules\dcsoperation\models\TblMember;
use yii\data\ArrayDataProvider;
use app\modules\collection\models\TblMilkCollection;
use app\modules\collection\models\TblMilkCollectionHistory;
use app\modules\collection\models\TblMilkCollectionTempHistory;

/**
 * TblMilkCollectionTempController implements the CRUD actions for TblMilkCollectionTemp model.
 */
class TblMilkCollectionTempController extends \app\controllers\ChildController {

    public $freeAccessActions = ['validate-member', 'validate-rtpl', 'milk-collection-list'];

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TblMilkCollectionTemp models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMilkCollectionTempSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMilkCollectionTemp model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblMilkCollectionTemp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblMilkCollectionTemp();
        $searchModel = new TblMilkCollectionTempSearch();
        if (!empty(Yii::$app->request->get())) {
            $this->model->load(Yii::$app->request->get());
            $this->model->date_time_of_collection = date('d-m-Y', strtotime($this->model->date_time_of_collection));
            $dcs_code = Yii::$app->request->get('TblMilkCollectionTemp')['dcs_code'];
            $searchModel->dcs_code = $dcs_code;
        }
        $dataProvider = $searchModel->searchDcsMilkColl(Yii::$app->request->queryParams);
        $this->viewFile = 'create';
        if (Yii::$app->request->post() && !isset($_POST['export_type'])) {
            $this->model->load(Yii::$app->request->post());
            if ($this->model->validate()) {
                $this->model->mobile_no = Yii::$app->general->getforeignkey($this->model->memberCode, 'mobile_no');
                $this->model->name = Yii::$app->general->getforeignkey($this->model->memberCode, 'member_name');
                $this->model->village_code = Yii::$app->general->getforeignkey($this->model->dcsCode, 'village_code');
                $datetime = date('Y-m-d H:i:s');
                $this->model->date_time_of_collection = date('Y-m-d', strtotime($this->model->date_time_of_collection)) . ' ' . Yii::$app->general->getshift($this->model->shift);
                $this->model->date_time_of_recieve = $datetime;
                $this->model->dt_date = $datetime;
                $this->model->qlty_time = $datetime;
                $this->model->qty_time = $datetime;
                $this->model->type_of_data_receive = 'Manual';
                $this->model->status = 'Accept';
                $this->model->qty_mode = 1;
                $this->model->qlty_auto = 1;
                $this->model->qty_auto = 1;
                $this->model->sms_status = 'n';
                $this->model->route_code = Yii::$app->general->getforeignkey($this->model->dcsCode, 'route_code');
                $this->model->sample_no = $this->model->getSampleNo();
                $transaction = $this->generalModel->saveTransaction([$this->model], ['Manual Milk Collection', 'create']);
                if ($transaction == 'customRedirect') {
                    $msg = Yii::$app->getSession()->getFlash('success')['message'];
                    $record = ['status' => 'success', 'temp_collection_data' => [], 'msg' => $msg];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return Json::encode($record);
                }
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($this->model));
            }
        } else {
            return $this->render('create', [
                        'model' => $this->model,
                        'searchModel' => $searchModel, 'dataProvider' => $dataProvider
            ]);
        }
    }

    /**
     * Updates an existing TblMilkCollectionTemp model.
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
     * Deletes an existing TblMilkCollectionTemp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblMilkCollectionTemp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMilkCollectionTemp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMilkCollectionTemp::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionValidateRtpl() {
        $data['dcs_code'] = Yii::$app->request->post('dcs_code');
        $data['milk_type'] = Yii::$app->request->post('milk_type');
        $data['milk_quality_type'] = Yii::$app->request->post('milk_quality_type');
        $data['dt_date'] = Yii::$app->request->post('dt_date');
        $data['dt_date'] = (Yii::$app->request->post('dt_date')) ? Yii::$app->formatter->asDate(Yii::$app->request->post('dt_date'), DATE_FORMAT) : '';
        $data['shift'] = Yii::$app->request->post('shift');
        $data['dt_date'] = $data['dt_date'] . ' ' . \Yii::$app->general->getshift($data['shift']);
        $data['fat'] = Yii::$app->request->post('fat');
        $data['snf'] = Yii::$app->request->post('snf');

        $model = new TblPurchaseRate();
        $rtpl_data['list'] = $model->purchaseRate($data);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        if (!empty($rtpl_data['list'])) {
            return Json::encode(['status' => 'success', 'data' => $rtpl_data]);
        } else {
            return Json::encode(['status' => 'error']);
        }
    }

    public function actionValidateMember() {
        $data = [];
        $data['status'] = 'error';
        $data['message'] = '';
        if (!empty($_POST)) {
            $model = new TblMember();
            $model->member_code = $_POST['dcs_code'] . str_pad($_POST['member_code'], 4, '0', STR_PAD_LEFT);
            $member = $model->getmember();
            if (!empty($member)) {
                $data = ['status' => 'success'];
            } else {
                $data = ['status' => 'error'];
            }
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Json::encode($data);
    }

    public function actionGetTempData() {
        $searchModel = new TblMilkCollectionTemp();
        $searchModel->load(Yii::$app->request->queryParams);
        $sp_name = 'sp_milk_collection_temp';
        $sp_param = [];
        $sp_param[] = !empty($searchModel->dcs_code) ? $searchModel->dcs_code : NULL;
        $sp_param[] = !empty($searchModel->is_approved) ? $searchModel->is_approved : NULL;
        $sp_param[] = !empty($searchModel->is_updated) ? $searchModel->is_updated : NULL;
        $output = \Yii::$app->general->getSpData($sp_name, $sp_param);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $output,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [],
                'attributes' => [
                    'total_count',
                    'total_qty',
                    'dcs_code',
                    'dcs',
                    'is_approved',
                    'is_updated',
                    'date_time_of_collection',
                    'shift_code',
                    'shift',
                ],
            ],
        ]);

        if (Yii::$app->request->post() && !empty(Yii::$app->request->post('selection'))) {
            $data = Yii::$app->request->post();
            $selection = $data['selection'];
            $master = [];
            $child = [];
            $flag = $data['flag'];
            foreach ($selection as $select) {
                $array = explode('::', $select);
                $model = new TblMilkCollectionTemp();
                $model->dcs_code = !empty($array[0]) ? $array[0] : '';
                $model->shift = !empty($array[2]) ? $array[2] : '';
                $model->date_time_of_collection = !empty($array[1]) ? date('Y-m-d', strtotime($array[1])) . ' ' . Yii::$app->general->getshift($model->shift) : '';
                $model->is_approved = !empty($array[3]) ? $array[3] : '';
                $model->is_updated = !empty($array[4]) ? $array[4] : '';
                $model_data = $model->getData();
                if (!empty($model_data)) {
                    foreach ($model_data as $temp_coll_data) {
                        $tempHistoryModel = new TblMilkCollectionTempHistory();
                        Yii::$app->operation->history($temp_coll_data, $tempHistoryModel, 'UPDATE');
                        $child[] = $tempHistoryModel;
                        $temp_coll_data->is_approved = 2;
                        if ($flag == 'approve') {
                            $temp_coll_data->is_approved = 1;
                            $coll_model = new TblMilkCollection();
                            $coll_model->sample_no = $temp_coll_data->sample_no;
                            $coll_model->dcs_code = $temp_coll_data->dcs_code;
                            $coll_model->milk_type_code = $temp_coll_data->milk_type_code;
                            $coll_model->date_time_of_collection = $temp_coll_data->date_time_of_collection;
                            $coll_model->shift = $temp_coll_data->shift;
                            $coll_data = $coll_model->getExistingData();
                            if (!empty($coll_data)) {
                                $historyModel = new TblMilkCollectionHistory();
                                Yii::$app->operation->history($coll_data, $historyModel, 'UPDATE');
                                $child[] = $historyModel;
                                $coll_model = $coll_data;
                            }
                            $coll_model->member_code = $temp_coll_data->member_code;
                            $coll_model->milk_quality_type_code = $temp_coll_data->milk_quality_type_code;
                            $coll_model->name = $temp_coll_data->name;
                            $coll_model->mobile_no = $temp_coll_data->mobile_no;
                            $coll_model->fat = $temp_coll_data->fat;
                            $coll_model->snf = $temp_coll_data->snf;
                            $coll_model->clr = $temp_coll_data->clr;
                            $coll_model->water = $temp_coll_data->water;
                            $coll_model->qty = $temp_coll_data->qty;
                            $coll_model->rtpl = $temp_coll_data->rtpl;
                            $coll_model->amount = $temp_coll_data->amount;
                            $coll_model->auto_flag = $temp_coll_data->auto_flag;
                            $coll_model->date_time_of_recieve = $temp_coll_data->date_time_of_recieve;
                            $coll_model->village_code = $temp_coll_data->village_code;
                            $coll_model->type_of_data_receive = $temp_coll_data->type_of_data_receive;
                            $coll_model->rate_code = $temp_coll_data->rate_code;
                            $coll_model->dt_date = $temp_coll_data->dt_date;
                            $coll_model->qlty_time = $temp_coll_data->qlty_time;
                            $coll_model->qty_time = $temp_coll_data->qty_time;
                            $coll_model->qty_mode = 0;
                            $coll_model->qlty_auto = 0;
                            $coll_model->qty_auto = 0;
                            $coll_model->sms_status = 'n';
                            $coll_model->bmc_code = $temp_coll_data->bmc_code;
                            $coll_model->route_code = $temp_coll_data->route_code;
                            $master[] = $coll_model;
                        }
                        $master[] = $temp_coll_data;
                    }
                }
            }
            $transaction = $this->generalModel->saveTransaction($master, $child, ['Milk Collection', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->redirect(['get-temp-data']);
            }
        }
        return $this->render('_form_grid_dcs', [
                    'model' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

    public function actionMilkCollectionList() {
        $data = [];
        $searchModel = new TblMilkCollectionTempSearch();
        if (!empty($_POST)) {
            $data = $_POST;
        }
        $searchModel->setAttributes($data);
        $searchModel->date_time_of_collection = date('Y-m-d', strtotime($searchModel->date_time_of_collection)) . ' ' . Yii::$app->general->getshift($searchModel->shift);
        $dataProvider = $searchModel->searchDetailMilkColl();
        return $this->renderAjax('milk_collection_list', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

}
