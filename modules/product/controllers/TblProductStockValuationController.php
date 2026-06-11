<?php

namespace app\modules\product\controllers;

use Yii;
use app\controllers\ChildController;
use app\modules\product\models\TblProductStockValuationSearch;

class TblProductStockValuationController extends ChildController
{
    public function actionIndex()
    {
        $searchModel = new TblProductStockValuationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
