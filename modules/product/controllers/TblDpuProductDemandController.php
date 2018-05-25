<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblDpuProductDemand;
use app\modules\product\models\TblDpuProductDemandSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblDpuProductDemandController implements the CRUD actions for TblDpuProductDemand model.
 */
class TblDpuProductDemandController extends \app\controllers\ChildController
{
    /**
     * Lists all TblDpuProductDemand models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblDpuProductDemandSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblDpuProductDemand model.
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
     * Finds the TblDpuProductDemand model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblDpuProductDemand the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblDpuProductDemand::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
