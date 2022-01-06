<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblLoanProductSaleDetails;
use app\modules\payment\models\TblLoanProductSaleDetailsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\controllers\ChildController;

/**
 * TblLoanProductSaleDetailsController implements the CRUD actions for TblLoanProductSaleDetails model.
 */
class TblLoanProductSaleDetailsController extends ChildController {

    /**
     * Lists all TblLoanProductSaleDetails models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblLoanProductSaleDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
