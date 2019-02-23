<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblQualityCollection;
use app\modules\collection\models\TblQualityCollectionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblQualityCollectionController implements the CRUD actions for TblQualityCollection model.
 */
class TblQualityCollectionController extends \app\controllers\ChildController {

    /**
     * Lists all TblQualityCollection models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblQualityCollectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblQualityCollection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblQualityCollection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblQualityCollection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
