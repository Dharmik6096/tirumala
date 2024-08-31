<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblLocalMilkRate;
use app\modules\dcsoperation\models\TblLocalMilkRateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\dcsoperation\models\TblLocalMilkSaleRate;

/**
 * TblLocalMilkSaleRateController implements the CRUD actions for TblLocalMilkSaleRate model.
 */
class TblLocalMilkRateController extends \app\controllers\ChildController {

    /**
     * Lists all TblLocalMilkSaleRate models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblLocalMilkRateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

     /**
     * Displays a single TblProductSaleRate model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $searchModel = new TblLocalMilkRateSearch();
        $query = TblLocalMilkRate::find()->select(['milk_quality_type_code', 'union_code'])->where(['local_milk_rate_code' => $id])->one();
        $milk_quality_type_code = $query['milk_quality_type_code'];
        $searchModel->milk_quality_type_code = $milk_quality_type_code;
        $searchModel->union_code = $query['union_code'];
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblProductSaleRate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblLocalMilkRate();
        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
//            $this->model->product_sale_rate_code = Yii::$app->general->getPrimaryCode($this->model);
            $this->model->wef_date = Yii::$app->formatter->asDate($this->model->wef_date, DATE_FORMAT);
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Product Sale Rate', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    /**
     * Updates an existing TblLocalMilkSaleRate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->local_sale_rate_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblLocalMilkSaleRate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblLocalMilkSaleRate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblLocalMilkSaleRate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblLocalMilkRate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
     public function actionLocalMilkRateApplicability($id) {
        $model = $this->findModel($id);
        $appModel = Yii::$app->getModule('applicability');
        $appModel->model = new TblLocalMilkSaleRate();
        $appModel->model->wef_date = $model->wef_date;
        $appModel->union_code = $model->union_code;
        $appModel->field_name = 'local_milk_sale_rate_code';
        $appModel->field_value = $id;
        $appModel->trans_label = 'local milk rate applicability';
        $appModel->mcc_field_name = 'applicable_code';
        $appModel->options = ['tanker_rate'];
//        $appModel->assignStaticData = [
//            'rate' => $model->rate,
//        ];
        $appModel->header_title = ' [Local Milk Rate: ' . $model->rate . '] ';
        $appModel->fields = [
            
            'wef_date' => ['view' => ['grid', 'create'], 'type' => 'date', 'value' => function($model) {
                    return Yii::$app->controls->view_date($model->wef_date);
                }],
//            'applicable_for' => ['view' => ['grid', 'create'], 'value' => function($model) {
//                    return Yii::$app->general->getforeignkey($model->customerTypeFor, 'customer_desc');
//                }],
            'dcs_code' => ['view' => ['grid', 'create'], 'value' => 'applicable_code'],
//            'ref_code' => ['view' => ['grid'], 'label' => Yii::t('app', 'Code'), 'value' => function($model) {
//                    return Yii::$app->general->getCustomer($model, $model->applicable_for, false, FALSE, TRUE);
//                }],
//            'name' => ['view' => ['grid'], 'value' => function($model) {
//                    return $model->getName($model->applicable_for);
//                }],
        ];
        $appModel->dcs_filters = ['DCS' => Yii::t('app', 'DCS')];
        $appModel->actions = ['delete' => ['option' => 'product_sale_rate_applicability_code,product_sale_rate_applicability_code,tbl-product-rate/delete-applicability,allowDelete()']];

        return $appModel->createApp();
    }

}
