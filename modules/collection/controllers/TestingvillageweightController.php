<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\Testingvillageweight;
use app\modules\collection\models\TestingvillageweightSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TestingvillageweightController implements the CRUD actions for Testingvillageweight model.
 */
class TestingvillageweightController extends \app\controllers\ChildController
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
     * Lists all Testingvillageweight models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TestingvillageweightSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Testingvillageweight model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $dtdate
     * @param string $mccid
     * @param integer $sampleno
     * @param string $shift
     * @return Testingvillageweight the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($dtdate, $mccid, $sampleno, $shift)
    {
        if (($model = Testingvillageweight::findOne(['dtdate' => $dtdate, 'mccid' => $mccid, 'sampleno' => $sampleno, 'shift' => $shift])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
