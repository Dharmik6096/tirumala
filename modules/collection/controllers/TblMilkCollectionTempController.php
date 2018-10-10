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

/**
 * TblMilkCollectionTempController implements the CRUD actions for TblMilkCollectionTemp model.
 */
class TblMilkCollectionTempController extends \app\controllers\ChildController {

    public $freeAccessActions = ['validate-member'];

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
                $transaction = $this->generalModel->saveTransaction([$this->model], ['Milk Collection Temp', 'create']);
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
                    'dcs_code',
                    'is_approved',
                    'is_updated',
                    'date_time_of_collection',
                    'shift',
                ],
            ],
        ]);

        return $this->render('_form_grid_dcs', [
                    'model' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

}
