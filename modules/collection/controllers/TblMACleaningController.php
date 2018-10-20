<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblMACleaning;
use app\modules\collection\models\TblMACleaningSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblMACleaningController implements the CRUD actions for TblMACleaning model.
 */
class TblMACleaningController extends Controller
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
     * Lists all TblMACleaning models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblMACleaningSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMACleaning model.
     * @param string $BMCCode
     * @param string $cleaningdatetime
     * @param string $dtdate
     * @param string $PPCode
     * @return mixed
     */
    public function actionView($BMCCode, $cleaningdatetime, $dtdate, $PPCode)
    {
        return $this->render('view', [
            'model' => $this->findModel($BMCCode, $cleaningdatetime, $dtdate, $PPCode),
        ]);
    }

    /**
     * Creates a new TblMACleaning model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TblMACleaning();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'BMCCode' => $model->BMCCode, 'cleaningdatetime' => $model->cleaningdatetime, 'dtdate' => $model->dtdate, 'PPCode' => $model->PPCode]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblMACleaning model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $BMCCode
     * @param string $cleaningdatetime
     * @param string $dtdate
     * @param string $PPCode
     * @return mixed
     */
    public function actionUpdate($BMCCode, $cleaningdatetime, $dtdate, $PPCode)
    {
        $model = $this->findModel($BMCCode, $cleaningdatetime, $dtdate, $PPCode);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'BMCCode' => $model->BMCCode, 'cleaningdatetime' => $model->cleaningdatetime, 'dtdate' => $model->dtdate, 'PPCode' => $model->PPCode]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblMACleaning model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $BMCCode
     * @param string $cleaningdatetime
     * @param string $dtdate
     * @param string $PPCode
     * @return mixed
     */
    public function actionDelete($BMCCode, $cleaningdatetime, $dtdate, $PPCode)
    {
        $this->findModel($BMCCode, $cleaningdatetime, $dtdate, $PPCode)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblMACleaning model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $BMCCode
     * @param string $cleaningdatetime
     * @param string $dtdate
     * @param string $PPCode
     * @return TblMACleaning the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($BMCCode, $cleaningdatetime, $dtdate, $PPCode)
    {
        if (($model = TblMACleaning::findOne(['BMCCode' => $BMCCode, 'cleaningdatetime' => $cleaningdatetime, 'dtdate' => $dtdate, 'PPCode' => $PPCode])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
