<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblMilkAnalyzerCalibration;
use app\modules\collection\models\TblMilkAnalyzerCalibrationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblMilkAnalyzerCalibrationController implements the CRUD actions for TblMilkAnalyzerCalibration model.
 */
class TblMilkAnalyzerCalibrationController extends Controller
{
    
    /**
     * Lists all TblMilkAnalyzerCalibration models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblMilkAnalyzerCalibrationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblMilkAnalyzerCalibration model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMilkAnalyzerCalibration the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblMilkAnalyzerCalibration::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
