<?php

namespace app\modules\vsp\controllers;

use Yii;
use app\modules\vsp\models\TblTransitRecoverySearch;

/**
 * TblTransitRecoveryController implements the CRUD actions for TblTransitRecovery model.
 */
class TblTransitRecoveryController extends \app\controllers\ChildController {

    /**
     * Lists all TblTransitRecovery models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblTransitRecoverySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
