<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblMemberDownloadSearch;

class TblMemberDownloadController extends \app\controllers\ChildController {
    /**
     * Lists all TblMember models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMemberDownloadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
