<?php

namespace app\modules\dcsoperation\controllers;

use Yii;
use app\modules\dcsoperation\models\TblBiplSmartSearch;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblMember;
use app\modules\organisation\models\TblDcsHistory;
use app\modules\dcsoperation\models\TblMemberHistory;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Response;
use app\modules\dcsoperation\models\TblPurchaseRate;
use app\modules\dcsoperation\models\TblPurchaseRateHistory;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use app\modules\dcsoperation\models\TblPurchaseRateApplicabilityHistory;

class TblBiplSmartController extends \app\controllers\ChildController {

    public function actionRepushBulkData() {
        $searchModel = new TblBiplSmartSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $searchModel->grid_filter = false;
        if (Yii::$app->request->post()) {
            if (!empty($_REQUEST['selection'])) {
                $type = !empty($_REQUEST['type']) ? $_REQUEST['type'] : '';
                foreach ($_REQUEST['selection'] as $code) {
                    if (!empty($type) && $type == 'dcs') {
                        $model = TblDcs::findOne($code);
                        $historyModel = new TblDcsHistory();
                        $model->is_sentbox = false;
                    } elseif (!empty($type) && $type == 'rate') {
                        $model = TblPurchaseRate::findOne($code);
                        $historyModel = new TblPurchaseRateHistory();
                    } elseif (!empty($type) && $type == 'rateapp') {
                        $model = TblPurchaseRateApplicability::findOne($code);
                        $historyModel = new TblPurchaseRateApplicabilityHistory();
                    } else {
                        $model = TblMember::findOne($code);
                        $historyModel = new TblMemberHistory();
                        $model->is_sentbox = false;
                    }
                    Yii::$app->operation->history($model, $historyModel, UPDATE);
                    $historyModel->operation_type = 'BIPLREPUSH';
                    $model->data_post_status = 0;
                    $model->resp_desc = null;
                    $model->response_datetime = null;
                    $model->picked_datetime = null;
                    if ($model->save(true, false)) {
                        $historyModel->save();
                        $record = ['status' => 'success', 'msg' => 'Logs re-pushed successfully.'];
                    } else {
                        $errors = $model->getFirstErrors();
                        $errorMsg = !empty($errors) ? implode(', ', $errors) : 'Failed to re-push Logs.';
                        $record = ['status' => 'error', 'msg' => $errorMsg];
                    }
                }
                Yii::$app->response->format = trim(Response::FORMAT_JSON);
                return Json::encode($record);
            }
        }

        return $this->render('_repush_data', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
