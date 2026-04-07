<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblSubLedgerOpeningBalance;
use app\modules\dcsaccounting\models\TblSubLedgerOpeningBalanceSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;

/**
 * TblSubLedgerOpeningBalanceController implements the CRUD actions for TblSubLedgerOpeningBalance model.
 */
class TblSubLedgerOpeningBalanceController extends ChildController {

    /**
     * Lists all TblSubLedgerOpeningBalance models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblSubLedgerOpeningBalanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblSubLedgerOpeningBalance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblSubLedgerOpeningBalance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblSubLedgerOpeningBalance::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
