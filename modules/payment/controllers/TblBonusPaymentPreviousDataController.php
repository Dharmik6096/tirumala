<?php

namespace app\modules\payment\controllers;

use Yii;
use app\controllers\ChildController;
use app\modules\payment\models\TblBonusPaymentPreviousDataSearch;
use app\modules\payment\models\TblBonusPaymentPreviousData;

/**
 * TblBillHeadTransactionController implements the CRUD actions for TblBillHeadTransaction model.
 */
class TblBonusPaymentPreviousDataController extends ChildController {

    /**
     * Lists all TblBillHeadTransaction models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblBonusPaymentPreviousDataSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id) {
        if (($model = TblBonusPaymentPreviousData::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
