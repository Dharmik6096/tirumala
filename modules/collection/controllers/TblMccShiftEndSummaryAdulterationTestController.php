<?php

namespace app\modules\collection\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\collection\models\TblMccShiftEndSummaryAdulterationTest;
use app\modules\collection\models\TblMccShiftEndSummaryAdulterationTestSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblMccShiftEndSummaryAdulterationTestController implements the CRUD actions for TblMccShiftEndSummaryAdulterationTest model.
 */
class TblMccShiftEndSummaryAdulterationTestController extends ChildController
{
    
    /**
     * Lists all TblMccShiftEndSummaryAdulterationTest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblMccShiftEndSummaryAdulterationTestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblMccShiftEndSummaryAdulterationTest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMccShiftEndSummaryAdulterationTest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblMccShiftEndSummaryAdulterationTest::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
