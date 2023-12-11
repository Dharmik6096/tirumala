<?php

namespace app\modules\tms\controllers;

use Yii;
use app\modules\tms\models\TblUserAttendance;
use app\modules\tms\models\TblUserAttendanceSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;

/**
 * TblUserAttendanceController implements the CRUD actions for TblUserAttendance model.
 */
class TblUserAttendanceController extends ChildController
{
    /**
     * Lists all TblUserAttendance models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblUserAttendanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblUserAttendance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblUserAttendance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblUserAttendance::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
