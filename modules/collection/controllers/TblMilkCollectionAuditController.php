<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblMilkCollectionAuditSearch;
use app\modules\collection\models\TblMilkCollectionAudit;
use yii\web\NotFoundHttpException;

/**
 * TblMilkCollectionAuditController implements the CRUD actions for TblMilkCollectionAudit model.
 */
class TblMilkCollectionAuditController extends \app\controllers\ChildController {

    /**
     * Lists all TblMilkCollection models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMilkCollectionAuditSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Displays a single TblMilkCollection model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }
    
    /**
     * Finds the TblMilkCollection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblMilkCollection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMilkCollectionAudit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
