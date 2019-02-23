<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblWeightCollection;
use app\modules\collection\models\TblWeightCollectionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblWeightCollectionController implements the CRUD actions for TblWeightCollection model.
 */
class TblWeightCollectionController extends \app\controllers\ChildController {


    /**
     * Lists all TblWeightCollection models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblWeightCollectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblWeightCollection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblWeightCollection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblWeightCollection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
