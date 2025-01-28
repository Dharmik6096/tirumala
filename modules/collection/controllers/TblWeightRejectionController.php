<?php

namespace app\modules\collection\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\collection\models\TblWeightRejection;
use app\modules\collection\models\TblWeightRejectionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TblWeightRejectionController implements the CRUD actions for TblWeightRejection model.
 */
class TblWeightRejectionController extends ChildController
{

    /**
     * Lists all TblWeightRejection models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblWeightRejectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblWeightRejection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblWeightRejection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblWeightRejection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
