<?php

namespace app\modules\configuration\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\configuration\models\TblFsDataSearch;

class TblFsDataController extends ChildController {

    public function actionIndex() {
        $searchModel = new TblFsDataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
