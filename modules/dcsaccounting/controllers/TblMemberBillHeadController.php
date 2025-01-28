<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblMemberBillHead;
use app\modules\dcsaccounting\models\TblMemberBillHeadSearch;
use app\controllers\ChildController;
use yii\web\NotFoundHttpException;

/**
 * TblMemberBillHeadController implements the CRUD actions for TblMemberBillHead model.
 */
class TblMemberBillHeadController extends ChildController {

    /**
     * Lists all TblMemberBillHead models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMemberBillHeadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblMemberBillHead model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblMemberBillHead the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMemberBillHead::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
