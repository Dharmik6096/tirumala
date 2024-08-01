<?php

namespace app\modules\clienterp\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\clienterp\models\TblClientErpApiLog;
use app\modules\clienterp\models\TblClientErpApiLogSearch;
use yii\web\NotFoundHttpException;

/**
 * TblClientErpApiLogController implements the CRUD actions for TblClientErpApiLog model.
 */
class TblClientErpApiLogController extends ChildController
{
    /**
     * Lists all TblClientErpApiLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblClientErpApiLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblClientErpApiLog model.
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
     * Finds the TblClientErpApiLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblClientErpApiLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblClientErpApiLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
