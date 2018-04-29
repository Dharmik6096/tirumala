<?php

namespace app\modules\payment\controllers;

use Yii;
use app\modules\payment\models\TblUnionCreditLimit;
use app\modules\payment\models\TblUnionCreditLimitSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\payment\models\TblUnionCreditLimitHistory;
/**
 * TblUnionCreditLimitController implements the CRUD actions for TblUnionCreditLimit model.
 */
class TblUnionCreditLimitController extends \app\controllers\ChildController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TblUnionCreditLimit models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblUnionCreditLimitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblUnionCreditLimit model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblUnionCreditLimit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->model = new TblUnionCreditLimit();

        $this->viewFile = 'create';
        if ($this->model->load(Yii::$app->request->post())) {
            $this->model->wef_date = date('Y-m-d', strtotime($this->model->wef_date));
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Union Credit Limit', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        } 
        return $this->customRender();
    }

    /**
     * Updates an existing TblUnionCreditLimit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $historyModel = new TblUnionCreditLimitHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            $this->model->load(Yii::$app->request->post());
            $this->model->wef_date = date('Y-m-d', strtotime($this->model->wef_date));
            if($this->model->validate()){
                $transaction = $this->generalModel->saveTransaction([$this->model,$historyModel], ['Union Credit Limit', 'edit']);
                return $this->redirect(['view', 'id' => $this->model->union_credit_limit_code]);
            }
        } else {
            return $this->render('update', [
                'model' => $this->model,
            ]);
        }
    }

    /**
     * Deletes an existing TblUnionCreditLimit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblUnionCreditLimit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblUnionCreditLimit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblUnionCreditLimit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
}
