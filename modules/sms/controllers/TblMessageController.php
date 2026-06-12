<?php

namespace app\modules\sms\controllers;

use Yii;
use app\controllers\ChildController;
use app\modules\sms\models\TblMessageSearch;

class TblMessageController extends ChildController
{
    public function actionIndex()
    {
        $searchModel = new TblMessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
