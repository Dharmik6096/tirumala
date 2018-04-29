<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblHeadLoadTransaction;
use app\modules\dcsoperation\models\TblHeadLoadTransactionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblHeadLoadTransactionController implements the CRUD actions for TblHeadLoadTransaction model.
 */
class TblHeadLoadTransactionController extends \app\controllers\ChildController
{

    protected $searchModel;
    protected $dataProvider;
    /**
     * Lists all TblHeadLoadTransaction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblHeadLoadTransactionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblHeadLoadTransaction model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TblHeadLoadTransaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->model = new TblHeadLoadTransaction();

        $this->searchModel = new TblHeadLoadTransactionSearch();
        $this->searchModel->head_load_code = Yii::$app->request->get('id');
        $this->dataProvider = $this->searchModel->search(Yii::$app->request->queryParams);
        
        if ( $this->model->load(Yii::$app->request->post())) {
            
            $this->model->head_load_transaction_code = $this->model->getCode();
            $this->model->head_load_code = Yii::$app->request->get('id');
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Head Load Transaction', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->customRender();
    }

    protected function customRender() {
        return $this->render('create', [
            'model' => $this->model,'searchModel' => $this->searchModel,
            'dataProvider' => $this->dataProvider,
        ]);
    }

    protected function customRedirect() {
        return $this->redirect(['create', 'id' => $this->model->head_load_code]);
    }

    /**
     * Updates an existing TblHeadLoadTransaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->head_load_transaction_code]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblHeadLoadTransaction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblHeadLoadTransaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblHeadLoadTransaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblHeadLoadTransaction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
