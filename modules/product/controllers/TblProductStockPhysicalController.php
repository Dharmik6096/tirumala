<?php

namespace app\modules\product\controllers;

use Yii;
use app\controllers\ChildController;
use app\modules\product\models\TblProductStockPhysicalSearch;

class TblProductStockPhysicalController extends ChildController
{
    public function actionIndex()
    {
        $searchModel = new TblProductStockPhysicalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
