<?php

namespace app\modules\transporter\controllers;

use Yii;
use app\modules\transporter\models\TblVehicleWiseRouteMapping;
use app\modules\transporter\models\TblVehicleWiseRouteMappingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblVehicleWiseRouteMappingController implements the CRUD actions for TblVehicleWiseRouteMapping model.
 */
class TblVehicleWiseRouteMappingController extends \app\controllers\ChildController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
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
     * Lists all TblVehicleWiseRouteMapping models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblVehicleWiseRouteMappingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblVehicleWiseRouteMapping model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblVehicleWiseRouteMapping model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($vehicle_code = '')
    {
        $this->model = new TblVehicleWiseRouteMapping();
        $this->model->scenario = 'create';
        $this->model->vehicle_code = $vehicle_code;
        $this->viewFile = 'create';

        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->wef_date = ($this->model->wef_date == '') ? null : Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Vehicle Route Mapping', 'create']);
            if ($transaction == 'customRender') {
                return $this->{$transaction}();
            }else{
                return $this->redirect(['/transporter/tbl-vehicle-master/index']);
            }
        }
        return $this->customRender();
        
    }

    /**
     * Updates an existing TblVehicleWiseRouteMapping model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->vehicle_wise_route_code]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblVehicleWiseRouteMapping model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblVehicleWiseRouteMapping model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVehicleWiseRouteMapping the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblVehicleWiseRouteMapping::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
