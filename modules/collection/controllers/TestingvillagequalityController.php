<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\Testingvillagequality;
use app\modules\collection\models\TestingvillagequalitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TestingvillagequalityController implements the CRUD actions for Testingvillagequality model.
 */
class TestingvillagequalityController extends \app\controllers\ChildController
{
    /**
     * Lists all Testingvillagequality models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TestingvillagequalitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Testingvillagequality model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $dtdate
     * @param string $mccid
     * @param integer $sampleno
     * @param string $shift
     * @return Testingvillagequality the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($dtdate, $mccid, $sampleno, $shift)
    {
        if (($model = Testingvillagequality::findOne(['dtdate' => $dtdate, 'mccid' => $mccid, 'sampleno' => $sampleno, 'shift' => $shift])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
