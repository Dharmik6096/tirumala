<?php

namespace app\modules\organisation\controllers;

use Yii;
use app\controllers\ChildController;
use app\modules\organisation\models\TblMasterHierarchy;
use app\modules\organisation\models\TblMasterHierarchySearch;

class TblMasterHierarchyController extends ChildController {
    /**
     * Lists all TblMasterHierarchy models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMasterHierarchySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }
}
?>