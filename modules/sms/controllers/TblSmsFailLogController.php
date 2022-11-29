<?php

namespace app\modules\sms\controllers;

use Yii;
use app\modules\sms\models\TblSmsFailLog;
use app\modules\sms\models\TblSmsFailLogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblSmsFailLogController implements the CRUD actions for TblSmsFailLog model.
 */
class TblSmsFailLogController extends \app\controllers\ChildController {

    /**
     * Lists all TblSmsFailLog models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblSmsFailLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblSmsFailLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblSmsFailLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblSmsFailLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
