<?php

namespace app\modules\tms\controllers;

use Yii;
use app\modules\tms\models\TblTaskType;
use app\modules\tms\models\TblTaskTypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblTaskTypeController implements the CRUD actions for TblTaskType model.
 */
class TblTaskTypeController extends Controller
{
    /**
     * Lists all TblTaskType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblTaskTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Finds the TblTaskType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblTaskType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblTaskType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
