<?php

namespace app\modules\import\controllers;

use Yii;
use app\modules\import\models\TblImportFileLog;
use app\modules\import\models\TblImportFileLogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TblImportFileLogController implements the CRUD actions for TblImportFileLog model.
 */
class TblImportFileLogController extends \app\controllers\ChildController {

    /**
     * Lists all TblImportFileLog models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblImportFileLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
