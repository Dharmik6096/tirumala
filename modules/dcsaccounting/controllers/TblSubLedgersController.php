<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblSubLedgers;
use app\modules\dcsaccounting\models\TblSubLedgersSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use app\modules\dcsaccounting\models\TblLedgerSubLedgersMappingSearch;

/**
 * TblSubLedgersController implements the CRUD actions for TblSubLedgers model.
 */
class TblSubLedgersController extends ChildController {

    /**
     * Lists all TblSubLedgers models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblSubLedgersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblSubLedgers model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblLedgerSubLedgersMappingSearch();
        $searchModel->sub_ledger_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblSubLedgers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblSubLedgers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblSubLedgers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
