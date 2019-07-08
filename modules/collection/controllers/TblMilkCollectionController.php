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
use app\modules\collection\models\TblBmcCollection;
use app\modules\collection\models\TblMilkCollectionHistory;
use app\modules\collection\models\TblBmcCollectionHistory;

/**
 * TblMilkCollectionController implements the CRUD actions for TblMilkCollection model.
 */
class TblMilkCollectionController extends \app\controllers\ChildController {

    public $freeAccessActions = ['validate-rtpl'];

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
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->mobile_no = Yii::$app->general->getforeignkey($this->model->memberCode, 'mobile_no');
            $this->model->name = Yii::$app->general->getforeignkey($this->model->memberCode, 'member_name');
            $this->model->village_code = Yii::$app->general->getforeignkey($this->model->dcsCode, 'village_code');
            $datetime = date('Y-m-d H:i:s');
            $this->model->date_time_of_collection = date('Y-m-d') . ' ' . Yii::$app->general->getshift($this->model->shift);
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
            $this->model->sample_no = $this->model->getSampleNo();
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Milk Collection', 'create']);
            if ($transaction == 'customRedirect') {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
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
                    'mcc_name'
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

}
