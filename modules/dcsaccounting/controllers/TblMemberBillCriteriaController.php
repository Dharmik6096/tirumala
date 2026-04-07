<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblMemberBillCriteria;
use app\modules\dcsaccounting\models\TblMemberBillCriteriaSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;

/**
 * TblMemberBillCriteriaController implements the CRUD actions for TblMemberBillCriteria model.
 */
class TblMemberBillCriteriaController extends ChildController {

    /**
     * Lists all TblMemberBillCriteria models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMemberBillCriteriaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblMemberBillCriteria model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblMemberBillCriteria the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMemberBillCriteria::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
