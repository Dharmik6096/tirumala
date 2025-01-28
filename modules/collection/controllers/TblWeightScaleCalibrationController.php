<?php

namespace app\modules\collection\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\collection\models\TblWeightScaleCalibration;
use app\modules\collection\models\TblWeightScaleCalibrationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblWeightScaleCalibrationController implements the CRUD actions for TblWeightScaleCalibration model.
 */
class TblWeightScaleCalibrationController extends ChildController
{
    
    /**
     * Lists all TblWeightScaleCalibration models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblWeightScaleCalibrationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblWeightScaleCalibration model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblWeightScaleCalibration the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblWeightScaleCalibration::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
