<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblDcsLocationDetailSearch;

class TblDcsLocationDetailController extends \app\controllers\ChildController {

    public function actionIndex() {
        $searchModel = new TblDcsLocationDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
