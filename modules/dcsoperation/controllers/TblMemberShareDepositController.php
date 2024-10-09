<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblMemberShareDepositSearch;

/**
 * TblMemberShareDepositController implements the CRUD actions for TblMemberShareDeposit model.
 */
class TblMemberShareDepositController extends \app\controllers\ChildController {

    /**
     * Lists all TblMemberShareDeposit models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMemberShareDepositSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
