<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblVoucherTypeLedgerConfig;
use app\modules\dcsaccounting\models\TblVoucherTypeLedgerConfigSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;

/**
 * TblVoucherTypeLedgerConfigController implements the CRUD actions for TblVoucherTypeLedgerConfig model.
 */
class TblVoucherTypeLedgerConfigController extends ChildController {

    /**
     * Lists all TblVoucherTypeLedgerConfig models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVoucherTypeLedgerConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblVoucherTypeLedgerConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblVoucherTypeLedgerConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVoucherTypeLedgerConfig::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
