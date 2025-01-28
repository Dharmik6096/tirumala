<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblLedgerGroups;
use app\modules\dcsaccounting\models\TblLedgerGroupsSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;

/**
 * TblLedgerGroupsController implements the CRUD actions for TblLedgerGroups model.
 */
class TblLedgerGroupsController extends ChildController {

    /**
     * Lists all TblLedgerGroups models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblLedgerGroupsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblLedgerGroups model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblLedgerGroups the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblLedgerGroups::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
