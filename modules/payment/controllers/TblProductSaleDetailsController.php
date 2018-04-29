<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblProductSaleDetails;
use app\modules\payment\models\TblProductSaleDetailsSearch;
use app\modules\product\models\TblProductRateApplicability;
use app\modules\product\models\TblProductRate;
use app\modules\product\models\TblProduct;
use app\modules\payment\models\TblProductSale;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * TblProductSaleDetailsController implements the CRUD actions for TblProductSaleDetails model.
 */
class TblProductSaleDetailsController extends \app\controllers\ChildController {

    /**
     * Lists all TblProductSaleDetails models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblProductSaleDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblProductSaleDetails model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblProductSaleDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblProductSaleDetails();
        if (Yii::$app->request->post()) {
            $jsonData = Json::decode(Yii::$app->request->post('sales_date'));
            $data = Json::decode(Yii::$app->request->post('sales_details'));

            $list = [];
            $main_model = new TblProductSale();
            $main_model->addProductSaleData($jsonData);
            $dta = $this->getBaseData($data, $main_model);
            $list = $dta['list'];
            $main_model->amount = $dta['amt'];
            $main_model->amount_due = $dta['amt'];
            $transaction = $this->generalModel->saveTransaction([$main_model], $list, ['Product Sale', 'create']);
            if ($transaction !== FALSE) {
                return $this->redirect(['/payment/tbl-product-sale/sale-payment', 'id' => $main_model->product_sale_code]);
            }
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblProductSaleDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->sale_detail_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblProductSaleDetails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblProductSaleDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblProductSaleDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblProductSaleDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionLoadRate() {
        if (!empty($_POST['product_code']) && !empty($_POST['dcs_code'])) {
            $date = date('Y-m-d');
//            $app=  TblProductRateApplicability::find()->innerJoinWith('productRateCode')->select(['product_rate_applicability_code','tbl_product_rate.rate','max(tbl_product_rate_applicability.wef_date) as dt'])->groupBy(['product_rate_applicability_code','rate'])->having(['<=','max([tbl_product_rate_applicability].[wef_date])',$date])->where(['tbl_product_rate.product_code'=>$_POST['product_code'],'tbl_product_rate_applicability.dcs_code'=>$_POST['dcs_code']])->createCommand()->queryOne();

            /*  Patch suggested by Karan shah for product sale process. */
            $appQuery = TblProductRateApplicability::find()->innerJoinWith('productRateCode')->select(['product_rate_applicability_code', 'tbl_product_rate.rate', 'max(tbl_product_rate_applicability.wef_date) as dt'])->groupBy(['product_rate_applicability_code', 'rate', 'tbl_product_rate_applicability.wef_date'])->having(['<=', 'max([tbl_product_rate_applicability].[wef_date])', $date])->where(['tbl_product_rate.product_code' => $_POST['product_code'], 'tbl_product_rate_applicability.dcs_code' => $_POST['dcs_code']]);
            $query = TblProductRate::find()->select(['wef_date', 'product_rate_code', 'union_code'])->where(['product_code' => $_POST['product_code'], 'union_code' => $_POST['union_code']])->andWhere(['<=', 'wef_date', $date])->orderBy(['wef_date' => SORT_DESC])->one();
            if ($appQuery->count() == 0) {
                $this->setAppModel($query);
            } else {

                $queryApp = $appQuery->orderBy(['tbl_product_rate_applicability.wef_date' => SORT_DESC])->createCommand()->queryOne();
                $wef_date = date('Y-m-d', strtotime($queryApp['dt']));
                if ($wef_date != $query['wef_date']) {
                    $this->setAppModel($query);
                }
            }
            $app = $appQuery->orderBy(['tbl_product_rate_applicability.wef_date' => SORT_DESC])->createCommand()->queryOne();

            if (!($app)) {
                $app = ['product_rate_applicability_code' => 0, 'rate' => 0.0];
            }
            echo json_encode($app);
        }
    }

    private function getBaseData($data, $main_model) {
        $list = [];
        $amt = 0;
        if (!empty($main_model)) {
            foreach ($data as $key => $detail) {
                if ($detail != null) {
                    $model = new TblProductSaleDetails();
                    $model->product_sale_code = $main_model->product_sale_code;
                    $model->sale_detail_code = (string) (Yii::$app->general->getCodeAutoIncrement($model) + $key);
                    $model->product_code = $detail['product_code'];
                    $model->rate_app_code = $detail['rate_app_code'];
                    $model->rate = $detail['rate'];
                    $model->qty = $detail['qty'];
                    $model->amount = $detail['amount'];
                    $amt = $amt + $model->amount;
                    //$list[] = $model;
                    array_push($list, $model);
                }
            }
        }
        $dta = ['list' => $list, 'amt' => $amt];
        return $dta;
    }

    private function setAppModel($query) {
        $appModel = new TblProductRateApplicability();
        $appModel->wef_date = $query['wef_date'];
        $appModel->product_rate_code = $query['product_rate_code'];
        $appModel->union_code = $query['union_code'];
        $appModel->dcs_code = $_POST['dcs_code'];
        $appModel->save();
    }

}
