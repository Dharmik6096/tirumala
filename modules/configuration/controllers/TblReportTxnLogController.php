<?php

namespace app\modules\configuration\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use app\modules\configuration\models\TblReportTxnLogSearch;
use app\modules\configuration\models\TblReportTxnLog;

/**
 * TblReportTxnLogController implements the CRUD actions for TblReportTxnLog model.
 */
class TblReportTxnLogController extends \app\controllers\ChildController {

    /**
     * Lists all TblReportTxnLog models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblReportTxnLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the TblReportTxnLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblReportTxnLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblReportTxnLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
