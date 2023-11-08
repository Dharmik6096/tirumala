<?php

namespace app\modules\product\controllers;

use Yii;
use app\controllers\ChildController;
use app\modules\product\models\TblProductStockSapSearch;

class TblProductStockSapController extends ChildController
{
    public function actionIndex()
    {
        $searchModel = new TblProductStockSapSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
