<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblBmcDispatch;
use app\modules\collection\models\TblBmcDispatchSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblBmcDispatchController implements the CRUD actions for TblBmcDispatch model.
 */
class TblBmcDispatchController extends \app\controllers\ChildController
{
    /**
     * Lists all TblBmcDispatch models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblBmcDispatchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBmcDispatch model.
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
     * Finds the TblBmcDispatch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblBmcDispatch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblBmcDispatch::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
