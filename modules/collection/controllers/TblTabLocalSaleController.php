<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblTabLocalSale;
use app\modules\collection\models\TblTabLocalSaleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblTabLocalSaleController implements the CRUD actions for TblTabLocalSale model.
 */
class TblTabLocalSaleController extends \app\controllers\ChildController {

    /**
     * Lists all TblTabLocalSale models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblTabLocalSaleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblTabLocalSale model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblTabLocalSale the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblTabLocalSale::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
