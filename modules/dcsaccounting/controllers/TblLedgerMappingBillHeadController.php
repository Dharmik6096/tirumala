<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblLedgerMappingBillHead;
use app\modules\dcsaccounting\models\TblLedgerMappingBillHeadSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;

/**
 * TblLedgerMappingBillHeadController implements the CRUD actions for TblLedgerMappingBillHead model.
 */
class TblLedgerMappingBillHeadController extends ChildController {

    /**
     * Lists all TblLedgerMappingBillHead models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblLedgerMappingBillHeadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblLedgerMappingBillHead model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblLedgerMappingBillHead the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblLedgerMappingBillHead::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
