<?php

namespace app\modules\clienterp\controllers;

use app\controllers\ChildController;
use app\modules\clienterp\models\TblDataExchangeLogSearch;
use app\modules\clienterp\models\TblDataExchangeLog;
use app\modules\clienterp\models\TblDataExchangeLogHistory;
use Yii;

/**
 * TblDataExchangeLogController implements the CRUD actions for TblDataExchangeLog model.
 */
class TblDataExchangeLogController extends ChildController {

    /**
     * Lists all TblDataExchangeLog models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblDataExchangeLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRepush() {
        $deletedata = Yii::$app->request->post('selection');
        $saveModel = [];
        $deleteModel = [];
        foreach ($deletedata as $detailKey => $code) {
            $where['data_exchange_log_code'] = $code;
            $existData = TblDataExchangeLog::find()->where($where)->one();
            if (!empty($existData)) {
                $historyModel = new TblDataExchangeLogHistory();
                Yii::$app->operation->history($existData, $historyModel, DELETE);
                $saveModel[] = $historyModel;
                $deleteModel[] = $existData;
            }
        }
        $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Data Exchange Log', 'delete']);
        if ($transaction == 'customRedirect') {
            return $this->redirect(['index']);
        }
    }

}
