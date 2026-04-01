<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\modules\dcsaccounting\models\TblAccountPostingSearch;

class TblAccountPostingController extends \app\controllers\ChildController {

    public function actionIndex() {
        $searchModel = new TblAccountPostingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
