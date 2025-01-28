<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblVoucher;
use app\modules\dcsaccounting\models\TblVoucherSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;
use app\modules\dcsaccounting\models\TblVoucherTransactionSearch;
use app\modules\dcsaccounting\models\TblVoucherSubLedgerSearch;

/**
 * TblVoucherController implements the CRUD actions for TblVoucher model.
 */
class TblVoucherController extends ChildController {

    /**
     * Lists all TblVoucher models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblVoucherSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblVoucher model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblVoucherTransactionSearch();
        $searchModel->voucher_code = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewSubLedger() {
        $searchModel = new TblVoucherSubLedgerSearch();
        $searchModel->voucher_transaction_code = \Yii::$app->request->post('code');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderAjax('@app/modules/dcsaccounting/views/tbl-voucher-sub-ledger/_form_grid', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblVoucher model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblVoucher the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblVoucher::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
