<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblMemberIncentiveSearch;

/**
 * TblMemberIncentiveController implements the CRUD actions for TblMemberIncentive model.
 */
class TblMemberIncentiveController extends \app\controllers\ChildController {

    public function actionIndex() {
        $searchModel = new TblMemberIncentiveSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
