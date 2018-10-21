<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\CollectionFarmerLocalSale;
use app\modules\collection\models\CollectionFarmerLocalSaleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CollectionFarmerLocalSaleController implements the CRUD actions for CollectionFarmerLocalSale model.
 */
class CollectionFarmerLocalSaleController extends \app\controllers\ChildController {

    /**
     * Lists all CollectionFarmerLocalSale models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new CollectionFarmerLocalSaleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CollectionFarmerLocalSale model.
     * @param string $dtdate
     * @param integer $sampleno
     * @param string $shift
     * @param string $vlccid
     * @return mixed
     */
    public function actionView($dtdate, $sampleno, $shift, $vlccid) {
        return $this->render('view', [
                    'model' => $this->findModel($dtdate, $sampleno, $shift, $vlccid),
        ]);
    }

    /**
     * Creates a new CollectionFarmerLocalSale model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new CollectionFarmerLocalSale();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'dtdate' => $model->dtdate, 'sampleno' => $model->sampleno, 'shift' => $model->shift, 'vlccid' => $model->vlccid]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CollectionFarmerLocalSale model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $dtdate
     * @param integer $sampleno
     * @param string $shift
     * @param string $vlccid
     * @return mixed
     */
    public function actionUpdate($dtdate, $sampleno, $shift, $vlccid) {
        $model = $this->findModel($dtdate, $sampleno, $shift, $vlccid);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'dtdate' => $model->dtdate, 'sampleno' => $model->sampleno, 'shift' => $model->shift, 'vlccid' => $model->vlccid]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CollectionFarmerLocalSale model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $dtdate
     * @param integer $sampleno
     * @param string $shift
     * @param string $vlccid
     * @return mixed
     */
    public function actionDelete($dtdate, $sampleno, $shift, $vlccid) {
        $this->findModel($dtdate, $sampleno, $shift, $vlccid)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CollectionFarmerLocalSale model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $dtdate
     * @param integer $sampleno
     * @param string $shift
     * @param string $vlccid
     * @return CollectionFarmerLocalSale the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($dtdate, $sampleno, $shift, $vlccid) {
        if (($model = CollectionFarmerLocalSale::findOne(['dtdate' => $dtdate, 'sampleno' => $sampleno, 'shift' => $shift, 'vlccid' => $vlccid])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
