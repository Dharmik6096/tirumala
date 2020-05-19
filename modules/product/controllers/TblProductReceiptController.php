<?php

namespace app\modules\product\controllers;

use Yii;
use app\modules\product\models\TblProductReceipt;
use app\modules\product\models\TblProductReceiptTransactionSearch;
use app\modules\product\models\TblProductReceiptSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblProductReceiptController implements the CRUD actions for TblProductReceipt model.
 */
class TblProductReceiptController extends \app\controllers\ChildController {

    /**
     * Lists all TblProductReceipt models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblProductReceiptSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblProductReceipt model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblProductReceiptTransactionSearch();
        $searchModel->product_receipt_code = Yii::$app->getRequest()->getQueryParam('id');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $this->findModel($id), 'searchModel' => $searchModel, 'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Finds the TblProductReceipt model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblProductReceipt the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblProductReceipt::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
