<?php

namespace app\modules\collection\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\collection\models\TblBmcCollectionAudit;
use app\modules\collection\models\TblBmcCollectionAuditSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblBmcCollectionAuditController implements the CRUD actions for TblBmcCollectionAudit model.
 */
class TblBmcCollectionAuditController extends ChildController
{

    /**
     * Lists all TblBmcCollectionAudit models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblBmcCollectionAuditSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblBmcCollectionAudit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblBmcCollectionAudit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblBmcCollectionAudit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
