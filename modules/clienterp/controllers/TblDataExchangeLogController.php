<?php

namespace app\modules\clienterp\controllers;

use app\controllers\ChildController;
use app\modules\clienterp\models\TblDataExchangeLogSearch;
use Yii;

/**
 * TblDataExchangeLogController implements the CRUD actions for TblDataExchangeLog model.
 */
class TblDataExchangeLogController extends ChildController
{
    /**
     * Lists all TblDataExchangeLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblDataExchangeLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}