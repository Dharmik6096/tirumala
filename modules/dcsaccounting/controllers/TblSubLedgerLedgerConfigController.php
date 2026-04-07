<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblSubLedgerLedgerConfig;
use app\modules\dcsaccounting\models\TblSubLedgerLedgerConfigSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;

/**
 * TblSubLedgerLedgerConfigController implements the CRUD actions for TblSubLedgerLedgerConfig model.
 */
class TblSubLedgerLedgerConfigController extends ChildController {

    /**
     * Lists all TblSubLedgerLedgerConfig models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblSubLedgerLedgerConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblSubLedgerLedgerConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblSubLedgerLedgerConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblSubLedgerLedgerConfig::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
