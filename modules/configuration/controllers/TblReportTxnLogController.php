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

    public function actionBlockRequest() {
        $model = new TblReportTxnLog();
        $searchModel = new TblReportTxnLogSearch();
        $searchModel->scenario = 'block_request';
        $dataProvider = $searchModel->searchPending(Yii::$app->request->queryParams);
        if (Yii::$app->request->post()) {
            if (isset($_REQUEST['selection'])) {
                $selectedIds = $_REQUEST['selection'];
                $total = count($_REQUEST['selection']);
                if (!empty($selectedIds)) {
                    $attributes = ['status' => '4'];
                    $condition = ['status' => '0', 'report_txn_log_id' => $selectedIds];
                    $success = $model->updateAll($attributes, $condition);
                }
                $msg = $success . ' Requests out of ' . $total . ' are successfully blocked.';
                Yii::$app->getSession()->setFlash('success', ['type' => 'error',
                    'message' => Yii::t('app', $msg)]);
               return $this->redirect(Yii::$app->request->referrer);
            }
        } else {
            return $this->render('block_request', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'title' => 'Block Request List'
            ]);
        }
    }
}
