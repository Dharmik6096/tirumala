<?php

namespace app\modules\dcsaccounting\controllers;

use Yii;
use yii\web\Controller;
use app\modules\dcsaccounting\models\TblNarrationTypeSearch;
class TblNarrationTypeController extends Controller
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
