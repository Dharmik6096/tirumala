<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblVoucherTypes;
use app\modules\dcsaccounting\models\TblVoucherTypesSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;

/**
 * TblVoucherTypesController implements the CRUD actions for TblVoucherTypes model.
 */
class TblVoucherTypesController extends ChildController {

    /**
     * Lists all TblVoucherTypes models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVoucherTypesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblVoucherTypes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblVoucherTypes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVoucherTypes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
