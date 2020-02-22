<?php

namespace app\modules\bkgprocess\controllers;

use Yii;
use app\modules\bkgprocess\models\TblFtpTxnLog;
use app\modules\bkgprocess\models\TblFtpTxnLogSearch;
use yii\web\NotFoundHttpException;
use yii\data\ArrayDataProvider;
use app\modules\bkgprocess\models\TblFileCreator;

/**
 * TblFtpTxnLogController implements the CRUD actions for TblFtpTxnLog model.
 */
class TblFtpTxnLogController extends \app\controllers\ChildController {

    /**
     * Lists all TblFtpTxnLog models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblFtpTxnLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (isset(\Yii::$app->request->post()['selection']) && !empty(\Yii::$app->request->post()['selection'])) {
            $this->reprocess(\Yii::$app->request->post()['selection']);
            return $this->redirect(['index']);
        }
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function reprocess($ids) {
        $master = [];
        foreach ($ids as $id) {
            $model = TblFtpTxnLog::findOne($id);
            $model->status = 4;
            $file_create = new TblFileCreator();
            $file_create->attributes = $model->creatorId->attributes;
            $file_create->activity_type = 'MANUAL';
            $file_create->file_status = $file_create->status = 0;
            $file_create->updated_at = $file_create->updated_by = NULL;
            $master[] = $model;
            $master[] = $file_create;
        }
        $transaction = $this->generalModel->saveTransaction($master, ['File Re-Sent', 'edit']);
    }

    /**
     * Finds the TblFtpTxnLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblFtpTxnLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblFtpTxnLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
