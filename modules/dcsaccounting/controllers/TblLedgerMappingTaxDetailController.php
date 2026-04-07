<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblLedgerMappingTaxDetail;
use app\modules\dcsaccounting\models\TblLedgerMappingTaxDetailSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;

/**
 * TblLedgerMappingTaxDetailController implements the CRUD actions for TblLedgerMappingTaxDetail model.
 */
class TblLedgerMappingTaxDetailController extends ChildController {

    /**
     * Lists all TblLedgerMappingTaxDetail models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblLedgerMappingTaxDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblLedgerMappingTaxDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblLedgerMappingTaxDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblLedgerMappingTaxDetail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
