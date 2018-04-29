<?php

namespace app\modules\transporter\controllers;

use Yii;
use app\modules\transporter\models\TblVehicleKmInfo;
use app\modules\transporter\models\TblVehicleKmInfoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\transporter\models\TblVehicleMaster;
use app\modules\transporter\models\TblVehicleKmInfoHistory;
use app\components\Model;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * TblVehicleKmInfoController implements the CRUD actions for TblVehicleKmInfo model.
 */
class TblVehicleKmInfoController extends \app\controllers\ChildController {

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
     * Lists all TblVehicleKmInfo models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVehicleKmInfoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblVehicleKmInfo model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblVehicleKmInfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblVehicleKmInfo();

        $searchModel = new \app\modules\transporter\models\TblVehicleMasterSearch();

        $searchModel->scenario = 'km_info_create';
        $dataProvider = $searchModel->searchVehicle(Yii::$app->request->queryParams);
        $count = count($dataProvider->models);
        $models[0] = new TblVehicleKmInfo();
        for ($i = 0; $i < $count; $i++){
            $models[$i] = new TblVehicleKmInfo();
        }
        $this->viewFile = 'create';
        if (Yii::$app->request->post()) {
            $data = Yii::$app->request->post();
            $this->model->load(Yii::$app->request->post());
            $modelAttributesLoaded = Model::createMultiple(TblVehicleKmInfo::classname());
            Model::loadMultiple($modelAttributesLoaded, Yii::$app->request->post());
            $validate = ArrayHelper::merge(
                            ActiveForm::validateMultiple($modelAttributesLoaded), ActiveForm::validate($this->model)
            );
            if (!$validate) {
                $main_model = [];
                $exist_data_model = [];
                foreach ($data['TblVehicleKmInfo'] as $id => $values) {
                    if($values['route_code'] != '' && ($values['morning_kms'] != '' || $values['evening_kms'] != '' || $values['extra_kms'] != '' || $values['total_kms'] != '')){
                        $model = new TblVehicleKmInfo();
                        $wef_date = Yii::$app->formatter->asDate($values['wef_date'], DATE_FORMAT);
                        foreach ($values as $model_keys => $model_values) {
                            $model->$model_keys = $model_values;
                            $model->wef_date = $wef_date;
                        }
                        $exist_data = $this->model->existData($model);
                        foreach ($exist_data as $exist) {
                            $exist_data_model[] = $exist;
                        }
                        $main_model[] = $model;
                    }
                }
                $transaction = $this->generalModel->saveTransaction($main_model,$exist_data_model, ['Vehicle KM Information', 'create']);
                if ($transaction !== FALSE) {
                    return $this->{$transaction}();
                }           
            }
            $models = $modelAttributesLoaded;
        }
        return $this->render('create', [
                    'model' => $models,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing TblVehicleKmInfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->model = $this->findModel($id);
        $this->viewFile = 'update';
        $this->model->scenario = 'update';
        if (Yii::$app->request->post()) {
            $historyModel = new TblVehicleKmInfoHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->wef_date = ($this->model->wef_date == '') ? null : Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model, $historyModel], ['Km Wise Rate', 'edit']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->render('update', ['model' => $this->model]);
    }

    /**
     * Deletes an existing TblVehicleKmInfo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblVehicleKmInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblVehicleKmInfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVehicleKmInfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
