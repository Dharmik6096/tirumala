<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblLedgerOpeningBalance;
use app\modules\dcsaccounting\models\TblLedgerOpeningBalanceSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;

/**
 * TblLedgerOpeningBalanceController implements the CRUD actions for TblLedgerOpeningBalance model.
 */
class TblLedgerOpeningBalanceController extends ChildController {

    /**
     * Lists all TblLedgerOpeningBalance models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblLedgerOpeningBalanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblLedgerOpeningBalance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblLedgerOpeningBalance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblLedgerOpeningBalance::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
