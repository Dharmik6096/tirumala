<?php

namespace app\modules\assetmanagement\controllers;

use Yii;
use app\modules\assetmanagement\models\TblStoreLocation;
use app\modules\assetmanagement\models\TblStoreLocationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\assetmanagement\models\TblStoreLocationHistory;
use yii\helpers\Json;

/**
 * TblStoreLocationController implements the CRUD actions for TblStoreLocation model.
 */
class TblStoreLocationController extends \app\controllers\ChildController {

    public $freeAccessActions = ['/assetmanagement/tbl-store-location/get-store-location-code'];

    public function init() {
        parent::init();
        $this->enableCsrfValidation = FALSE;
    }

    /**
     * Displays a single TblStoreLocation model.
     * @param string $id
     * @return mixed
     */

    /**
     * Lists all TblStoreLocation models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblStoreLocationSearch();
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

    /**
     * Creates a new TblStoreLocation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblStoreLocation();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->store_location_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Store Location', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblStoreLocation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';

        if (Yii::$app->request->post()) {
            $historyModel = new TblStoreLocationHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Store Location', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Finds the TblStoreLocation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblStoreLocation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblStoreLocation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetStoreLocationCode() {
        $data = [];
        $data['status'] = 'error';
        $data['msg'] = Yii::t('app', 'Storage Location Code not available for selected inputs.');
        $data['sloc_code'] = '';
        if (!empty($_POST)) {
            $sloc_code = !empty($_POST['dest_type_code']) ? $_POST['dest_type_code'] : '';
            $sloc_type = !empty($_POST['dest_type']) ? $_POST['dest_type'] : '';
            $refCode = !empty($_POST[$sloc_type . '_code']) ? $_POST[$sloc_type . '_code'] : '';
            if (!empty($refCode)) {
                $model = new TblStoreLocation();
                $model->store_location_type = $sloc_code;
                $model->reference_code = $refCode;
                $modelData = $model->getRecord();
                if (!empty($modelData)) {
                    $data['status'] = 'success';
                    $data['msg'] = '';
                    $data['sloc_code'] = $modelData->store_location_code;
                }
            }
        }
        echo Json::encode($data);
    }

}
