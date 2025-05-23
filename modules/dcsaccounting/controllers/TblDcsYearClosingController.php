<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblDcsYearClosing;
use app\modules\dcsaccounting\models\TblDcsYearClosingSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;

/**
 * TblDcsYearClosingController implements the CRUD actions for TblDcsYearClosing model.
 */
class TblDcsYearClosingController extends ChildController {

    /**
     * Lists all TblDcsYearClosing models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDcsYearClosingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblDcsYearClosing model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblDcsYearClosing the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblDcsYearClosing::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
