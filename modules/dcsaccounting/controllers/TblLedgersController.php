<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblLedgers;
use app\modules\dcsaccounting\models\TblLedgersSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use app\modules\dcsaccounting\models\TblLedgerSubLedgersMappingSearch;

/**
 * TblLedgersController implements the CRUD actions for TblLedgers model.
 */
class TblLedgersController extends ChildController {

    /**
     * Lists all TblLedgers models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblLedgersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblLedgers model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblLedgerSubLedgersMappingSearch();
        $searchModel->ledger_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblLedgers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblLedgers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblLedgers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
