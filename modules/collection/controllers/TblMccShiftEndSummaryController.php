<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblMccShiftEndSummary;
use app\modules\collection\models\TblMccShiftEndSummarySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblMccShiftEndSummaryController implements the CRUD actions for TblMccShiftEndSummary model.
 */
class TblMccShiftEndSummaryController extends Controller
{
    
    /**
     * Lists all TblMccShiftEndSummary models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblMccShiftEndSummarySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblMccShiftEndSummary model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMccShiftEndSummary the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblMccShiftEndSummary::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
