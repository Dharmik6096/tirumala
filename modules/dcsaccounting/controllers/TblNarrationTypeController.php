<?php

namespace app\modules\dcsaccounting\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\dcsaccounting\models\TblNarrationTypeSearch;

class TblNarrationTypeController extends ChildController
{
    public function actionIndex()
    {
        $searchModel = new TblNarrationTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
