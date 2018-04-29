<?php

namespace app\modules\staffmanagement\controllers;

use Yii;
use app\modules\staffmanagement\models\TblStaffSalaryProcessing;
use app\modules\staffmanagement\models\TblStaffSalaryProcessingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblStaffSalaryProcessingController implements the CRUD actions for TblStaffSalaryProcessing model.
 */
class TblStaffSalaryProcessingController extends \app\controllers\ChildController
{
   

    /**
     * Lists all TblStaffSalaryProcessing models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblStaffSalaryProcessingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblStaffSalaryProcessing model.
     * @param string $month
     * @param string $union_code
     * @param string $sub_center_code
     * @param string $staff_member_code
     * @param string $designation_code
     * @param string $dcs_code
     * @return mixed
     */
    public function actionView($month, $union_code, $sub_center_code, $staff_member_code, $designation_code, $dcs_code)
    {
        return $this->render('view', [
            'model' => $this->findModel($month, $union_code, $sub_center_code, $staff_member_code, $designation_code, $dcs_code),
        ]);
    }

    /**
     * Creates a new TblStaffSalaryProcessing model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TblStaffSalaryProcessing();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'month' => $model->month, 'union_code' => $model->union_code, 'sub_center_code' => $model->sub_center_code, 'staff_member_code' => $model->staff_member_code, 'designation_code' => $model->designation_code, 'dcs_code' => $model->dcs_code]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblStaffSalaryProcessing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $month
     * @param string $union_code
     * @param string $sub_center_code
     * @param string $staff_member_code
     * @param string $designation_code
     * @param string $dcs_code
     * @return mixed
     */
    public function actionUpdate($month, $union_code, $sub_center_code, $staff_member_code, $designation_code, $dcs_code)
    {
        $model = $this->findModel($month, $union_code, $sub_center_code, $staff_member_code, $designation_code, $dcs_code);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'month' => $model->month, 'union_code' => $model->union_code, 'sub_center_code' => $model->sub_center_code, 'staff_member_code' => $model->staff_member_code, 'designation_code' => $model->designation_code, 'dcs_code' => $model->dcs_code]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblStaffSalaryProcessing model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $month
     * @param string $union_code
     * @param string $sub_center_code
     * @param string $staff_member_code
     * @param string $designation_code
     * @param string $dcs_code
     * @return mixed
     */
    public function actionDelete($month, $union_code, $sub_center_code, $staff_member_code, $designation_code, $dcs_code)
    {
        $this->findModel($month, $union_code, $sub_center_code, $staff_member_code, $designation_code, $dcs_code)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblStaffSalaryProcessing model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $month
     * @param string $union_code
     * @param string $sub_center_code
     * @param string $staff_member_code
     * @param string $designation_code
     * @param string $dcs_code
     * @return TblStaffSalaryProcessing the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($month, $union_code, $sub_center_code, $staff_member_code, $designation_code, $dcs_code)
    {
        if (($model = TblStaffSalaryProcessing::findOne(['month' => $month, 'union_code' => $union_code, 'sub_center_code' => $sub_center_code, 'staff_member_code' => $staff_member_code, 'designation_code' => $designation_code, 'dcs_code' => $dcs_code])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
