<?php

namespace app\modules\transporter\controllers;

use Yii;
use app\modules\transporter\models\TblVehicleBillingType;
use app\modules\transporter\models\TblVehicleBillingTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblVehicleBillingTypeController implements the CRUD actions for TblVehicleBillingType model.
 */
class TblVehicleBillingTypeController extends \app\controllers\ChildController
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
     * Lists all TblVehicleBillingType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblVehicleBillingTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblVehicleBillingType model.
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
     * Creates a new TblVehicleBillingType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($vehicle_code = '')
    {
        $this->model = new TblVehicleBillingType();
        $this->model->scenario = 'create';
        $this->model->vehicle_code = $vehicle_code;
        $this->viewFile = 'create';

        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->wef_date = ($this->model->wef_date == '') ? null : Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Vehicle Billing Type', 'create']);
            if ($transaction == 'customRender') {
                return $this->{$transaction}();
            }else{
                if($vehicle_code != ''){
                    return $this->redirect(['/transporter/tbl-vehicle-master/index']);
                }else{
                    return $this->redirect(['/transporter/tbl-vehicle-billing-type/index']);
                }
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblVehicleBillingType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->vehicle_billing_code]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblVehicleBillingType model.
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
     * Finds the TblVehicleBillingType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVehicleBillingType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblVehicleBillingType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
