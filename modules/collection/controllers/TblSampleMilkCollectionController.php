<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblSampleMilkCollectionSearch;
use app\modules\collection\models\TblSampleMilkCollection;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\Json;
use app\components\ActiveForm;

/**
 * TblSampleMilkCollectionController implements the CRUD actions for TblSampleMilkCollection model.
 */
class TblSampleMilkCollectionController extends \app\controllers\ChildController {

    /**
     * Lists all TblSampleMilkCollection models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblSampleMilkCollectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }
    
    public function actionCreate() {
        $this->model = new TblSampleMilkCollection();
        $searchModel = new TblSampleMilkCollectionSearch();
        $searchModel->attributes = Yii::$app->request->get('TblSampleMilkCollection');
        $dataProvider = $searchModel->createsearch();
        $dataProvider->sort = false;
        $this->model->date_time_of_collection = date('d-m-Y');
        $this->model->milk_type_code = 1;
        $this->model->milk_quality_type_code = 1;
        $this->model->shift_code = 1;
        $this->viewFile = 'create';
        if (Yii::$app->request->post()) {
            $this->model->load(Yii::$app->request->post());
            $this->model->setCollectionData($this->model, 'create');
            if ($this->model->validate()) {
                $transaction = $this->generalModel->saveTransaction([$this->model], ['Sample Milk Collection', 'create']);
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
        } 
        return $this->render('create', [
                    'model' => $this->model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
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
        $model = new TblSampleMilkCollection();
        $response = $model->calculateData('rtpl_calculate', '', '', $data['fat'], $data['snf'], $data['milk_type'], $data);
        return Json::encode($response);
    }
    
    public function actionListGrid() {
        $searchModel = new TblSampleMilkCollectionSearch();
        $searchModel->attributes = Yii::$app->request->get('TblSampleMilkCollection');
        $dataProvider = $searchModel->createsearch();
        return $this->renderAjax('_list_grid', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }
    
    public function actionCalculateClr() {
        $response = [];
        $response['status'] = 'success';
        $response['data'] = '';
        (float) $fat = Yii::$app->request->post('fat');
        (float) $snf = Yii::$app->request->post('snf');
        $union = Yii::$app->request->post('union_code');
        $org_code = Yii::$app->request->post('bmcCode');
        $model = new TblSampleMilkCollection();
        $result = $model->calculateData('calculate_clr', $union, $org_code, $fat, $snf);
        $response['data'] = $result['clr'];
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($response);
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
        $model = new TblSampleMilkCollection();
        $response = $model->calculateData('check_fat_range', $union, $bmc, $fat, '', $milk_type);
        $response['status'] = 'error';
        $response['data'] = '';
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($response);
    }

    protected function findModel($id) {
        if (($model = TblSampleMilkCollection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
