<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblSampleMilkCollectionSearch;
use app\modules\collection\models\TblSampleMilkCollection;

/**
 * TblSampleMilkCollectionController implements the CRUD actions for TblSampleMilkCollection model.
 */
class TblSampleMilkCollectionController extends \app\controllers\ChildController {

    /**
     * Lists all TblSampleMilkCollection models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblSampleMilkCollectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id) {
        if (($model = TblSampleMilkCollection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
