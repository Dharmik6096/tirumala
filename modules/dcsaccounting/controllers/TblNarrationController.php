<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use app\controllers\ChildController;
use app\modules\dcsaccounting\models\TblNarrationSearch;

class TblNarrationController extends ChildController
{
    public function actionIndex()
    {
        $searchModel = new TblNarrationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
