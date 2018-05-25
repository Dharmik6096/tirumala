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
            if ($this->model->collection_type == 2) {
                $this->model->scenario = 'transporter';
            }
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
        $data['dcs_code'] = Yii::$app->request->post('dcs_code');
        $data['milk_type'] = Yii::$app->request->post('milk_type');
        $data['milk_quality_type'] = Yii::$app->request->post('milk_quality_type');
        $data['dt_date'] = Yii::$app->request->post('dt_date');
        $data['dt_date'] = (Yii::$app->request->post('dt_date')) ? Yii::$app->formatter->asDate(Yii::$app->request->post('dt_date'), DATE_FORMAT) : '';
        $data['shift'] = Yii::$app->request->post('shift');
        $data['dt_date'] = $data['dt_date'] . ' ' . \Yii::$app->general->getshift($data['shift']);
        $data['fat'] = Yii::$app->request->post('fat');
        $data['snf'] = Yii::$app->request->post('snf');

        $model = new TblDcsPurchaseRate();
        $rtpl_data['list'] = $model->purchaseRate($data);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        if (!empty($rtpl_data['list'])) {
            return Json::encode(['status' => 'success', 'data' => $rtpl_data]);
        } else {
            return Json::encode(['status' => 'error']);
        }
    }

}
