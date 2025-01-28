<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblEvent;
use app\modules\dcsaccounting\models\TblEventSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;

/**
 * TblEventController implements the CRUD actions for TblEvent model.
 */
class TblEventController extends ChildController {

    /**
     * Lists all TblEvent models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblEventSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblEvent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblEvent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblEvent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
