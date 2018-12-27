<?php

namespace app\modules\configuration\controllers;

use Yii;
use app\modules\configuration\models\TblMilkCollectionConfig;
use app\modules\configuration\models\TblMilkCollectionConfigSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\dcsoperation\models\TblQualityParam;
use app\modules\configuration\models\TblMilkCollectionConfigHistory;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * TblMilkCollectionConfigController implements the CRUD actions for TblMilkCollectionConfig model.
 */
class TblMilkCollectionConfigController extends \app\controllers\ChildController {

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
     * Lists all TblMilkCollectionConfig models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMilkCollectionConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMilkCollectionConfig model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblMilkCollectionConfig model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblMilkCollectionConfig();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->code]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblMilkCollectionConfig model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblMilkCollectionConfig model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblMilkCollectionConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMilkCollectionConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMilkCollectionConfig::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionMilkCollection() {
        $params_model = new TblQualityParam();
//        $params = $params_model->getQualityParam();
        $model = new TblMilkCollectionConfig();
        $union_code = Yii::$app->session->get('organizations_code');
        $union = explode(',', $union_code);
        if (count($union) == 1) {
            $model->union_code = $union[0];
            $data = $model->getData();
            if (!empty($data)) {
                $model = $this->findModel($data->code);
            }
        }
        $model->scenario = 'milkCollection';

        if (Yii::$app->request->post()) {
            if (!empty($data)) {
                $historyModel = new TblMilkCollectionConfigHistory();
                Yii::$app->operation->history($model, $historyModel, UPDATE);
            } else {
                $historyModel = [];
            }
            $model->load(Yii::$app->request->post());
            if ($model->validate() && empty($model->getErrors())) {
                $master_model[] = $model;
                if (!empty($historyModel)) {
                    $master_model[] = $historyModel;
                }
                $this->generalModel->saveTransaction($master_model, [], ['Milk Collection', 'edit']);

                $result = 'success';
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                $url = \yii\helpers\Url::to(['tabs', 'tab' => 'w0-tab1']);
                return ['status' => $result, 'url' => $url];
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        } else {
            return json_encode($this->renderAjax('milk_collection', [
                        'model' => $model,
            ]));
        }
    }

    public function actionTabs() {
        $params_model = new TblQualityParam();
        $params = $params_model->getQualityParam();

        $model = new TblMilkCollectionConfig();
        if ($model->load(Yii::$app->request->post())) {
            $model->validate();
        }
        return $this->render('tabs', ['model' => $model, 'params' => $params,]);
    }

}
