<?php

namespace app\modules\rmrd\controllers;

use Yii;
use app\modules\rmrd\models\Log;
use app\modules\rmrd\models\LogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\report\models\TblDpuRequest;
use app\modules\rmrd\models\DPUSERVERLOG;

/**
 * LogController implements the CRUD actions for Log model.
 */
class LogController extends Controller {

    public $freeAccessActions = ['read-log'];

    public function actionReadLog() {
        $model = new Log();
        $model = $model->getRecord();
        $this->saveDpuRequest($model);
        $model = new DPUSERVERLOG();
        $model = $model->getRecord();
        $this->saveDpuRequest($model, 'REIL');
    }

    private function saveDpuRequest($model, $type = 'RMRD') {
        $dcs_list = [];
        foreach ($model as $data) {
            if ($type == 'REIL') {
                $data->dcs_code = !empty($data->dcsCode) ? $data->dcsCode->dcs_code : NULL;
            }
            if (!in_array($data->dcs_code, $dcs_list)) {
                $DpuReq = new TblDpuRequest();
                $DpuReq->dcs_code = $data->dcs_code;
                $DpuReq->request_time = explode(' ', $data->Date)[1];
                $DpuReq->shift_code = ($DpuReq->request_time > '12:00:00') ? 2 : 1;
                $DpuReq->request_date = date('Y-m-d', strtotime($data->Date));
                if (empty($DpuReq->getData())) {
                    $DpuReq->datetime = date('Y-m-d H:i:s');
                    $DpuReq->save(false);
                }
                $dcs_list[] = $data->dcs_code;
            }
        }
    }

}
