<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblDpuShiftEndSummary;
use app\modules\collection\models\TblDpuShiftEndSummarySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblDpuShiftEndSummaryController implements the CRUD actions for TblDpuShiftEndSummary model.
 */
class TblDpuShiftEndSummaryController extends \app\controllers\ChildController {

    /**
     * Lists all TblDpuShiftEndSummary models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDpuShiftEndSummarySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblDpuShiftEndSummary model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $BMCCode
     * @param string $dtdate
     * @param string $VillageCode
     * @return TblDpuShiftEndSummary the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($BMCCode, $dtdate, $VillageCode) {
        if (($model = TblDpuShiftEndSummary::findOne(['BMCCode' => $BMCCode, 'dtdate' => $dtdate, 'VillageCode' => $VillageCode])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
