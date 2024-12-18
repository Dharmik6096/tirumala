<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblMilkCollectionSpecialCode;
use app\modules\collection\models\TblMilkCollectionSpecialCodeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblMilkCollectionSpecialCodeController implements the CRUD actions for TblMilkCollectionSpecialCode model.
 */
class TblMilkCollectionSpecialCodeController extends Controller {

    /**
     * Lists all TblMilkCollectionSpecialCode models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMilkCollectionSpecialCodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMilkCollectionSpecialCode model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id) {
        if (($model = TblMilkCollectionSpecialCode::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
