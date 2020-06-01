<?php

namespace app\modules\tankermovement\controllers;

use Yii;
use app\modules\tankermovement\models\TblSampleBottleTesting;
use app\modules\tankermovement\models\TblSampleBottleTestingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;

/**
 * TblSampleBottleTestingController implements the CRUD actions for TblSampleBottleTesting model.
 */
class TblSampleBottleTestingController extends \app\controllers\ChildController {

    public $freeAccessActions = ['check-sample-no'];

    /**
     * Lists all TblSampleBottleTesting models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblSampleBottleTestingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblSampleBottleTesting model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblSampleBottleTesting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblSampleBottleTesting();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $this->model->sample_bottle_testing_code = Yii::$app->general->getPrimaryCode($this->model);
            $this->model->sample_bottle_testing_date = date('Y-m-d', strtotime($this->model->sample_bottle_testing_date));
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Sample Bottle Testing', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    public function actionCheckSampleNo() {
        $model = new TblSampleBottleTesting();
        $model->trip_code = Yii::$app->request->get('trip_code');
        $model->sample_no = Yii::$app->request->get('sample_no');
        $response = $model->validateSampleNo(TRUE);
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($response);
    }

    /**
     * Finds the TblSampleBottleTesting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblSampleBottleTesting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblSampleBottleTesting::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
