<?php

namespace app\modules\email\controllers;

use Yii;
use app\modules\email\models\TblEmailRuleMaster;
use app\modules\email\models\TblEmailRuleMasterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\email\models\TblEmailRuleMasterHistory;
/**
 * TblEmailRuleMasterController implements the CRUD actions for TblEmailRuleMaster model.
 */
class TblEmailRuleMasterController extends \app\controllers\ChildController
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
     * Lists all TblEmailRuleMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new TblEmailRuleMaster();
        $searchModel = new TblEmailRuleMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblEmailRuleMaster model.
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
     * Creates a new TblEmailRuleMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TblEmailRuleMaster();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->email_rule_master_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblEmailRuleMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->model = $this->findModel($id);
        if (Yii::$app->request->post()) {
            $historyModel = new TblEmailRuleMasterHistory();
            Yii::$app->operation->history($this->model, $historyModel, UPDATE);
            
            $this->model->load(Yii::$app->request->post());
            if($this->model->validate()){
                $transaction = $this->generalModel->saveTransaction([$this->model,$historyModel], ['Email Rule Master', 'edit']);
                return $this->redirect(['view', 'id' => $this->model->email_rule_master_id]);
            }
        } else {
            return $this->render('update', [
                'model' => $this->model,
            ]);
        }
    }

    /**
     * Deletes an existing TblEmailRuleMaster model.
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
     * Finds the TblEmailRuleMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblEmailRuleMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblEmailRuleMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
