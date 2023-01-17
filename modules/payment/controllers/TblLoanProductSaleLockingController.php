<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblLoanProductSaleLocking;
use app\modules\payment\models\TblLoanProductSaleLockingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\payment\models\TblLoanProductSaleDetails;
use app\modules\payment\models\TblLoanProductSaleDetailsSearch;

/**
 * TblLoanProductSaleLockingController implements the CRUD actions for TblLoanProductSaleLocking model.
 */
class TblLoanProductSaleLockingController extends \app\controllers\ChildController {

    /**
     * Lists all TblLoanProductSaleLocking models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblLoanProductSaleLockingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblLoanProductSaleLocking model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        $searchModel = new TblLoanProductSaleDetailsSearch();
        $searchModel->data_lock = 1;
        $searchModel->lock_date = $model->locking_date;
        $searchModel->reference_code = $id;
        $dataProvider = $searchModel->viewsearch(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $model,
                    'searchModel' => $searchModel, 'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblLoanProductSaleLocking model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $searchModel = new TblLoanProductSaleLockingSearch();
        $searchModel->scenario = 'saleLockData';
        $saveModel = [];
        $dataProvider = $searchModel->locksearch(Yii::$app->request->queryParams);
        if (Yii::$app->request->post()) {
            $searchData = Yii::$app->request->post()['TblLoanProductSaleLockingSearch'];
            $postCodes = Yii::$app->request->post()['sale_detail_code'];
            $model = new TblLoanProductSaleLocking();
            $model->setAttributes($searchData);
            $model->locking_code = Yii::$app->general->getCodeAutoIncrement($model);
            $model->from_date = !empty($model->from_date) ? date('Y-m-d', strtotime($model->from_date)) : '';
            $model->to_date = !empty($model->to_date) ? date('Y-m-d', strtotime($model->to_date)) : '';
            $model->locking_date = !empty($model->locking_date) ? date('Y-m-d', strtotime($model->locking_date)) : '';
            $model->total_count = count($postCodes);
            if (Yii::$app->request->post()['submitType'] == 'lock') {
                $saveModel[] = $model;
                foreach ($postCodes as $updateData) {
                    if (!empty($updateData)) {
                        $modelSale = new TblLoanProductSaleDetails();
                        $existSale = $modelSale->find()->where(['sale_detail_code' => $updateData])->one();
//                        $historyModel = new TblLoanProductSaleDetailsHistory();
//                        Yii::$app->operation->history($existSale, $historyModel, UPDATE);
//                        $saveModel[] = $historyModel;
                        $existSale->data_lock = 1;
                        $existSale->lock_date = $model->locking_date;
                        $existSale->reference_code = $model->locking_code;
                        $saveModel[] = $existSale;
                    }
                }
                
                $transaction = $this->generalModel->saveTransaction($saveModel, ['PM Sale Data Lock', 'create']);
                if ($transaction == 'customRedirect') {
                    return $this->redirect(['index']);
                }
            } else {
//                $dataProviderDownload = $searchModel->downloadsearch(Yii::$app->request->queryParams);
                $this->downloadData($dataProvider->getModels());
            }
        }
        $dataProvider = $searchModel->locksearch(Yii::$app->request->queryParams);
        return $this->render('create', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblLoanProductSaleLocking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblLoanProductSaleLocking the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblLoanProductSaleLocking::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
