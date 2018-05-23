<?php

namespace app\modules\webservice\bmc\v1\controllers;

use app\modules\webservice\controllers\ChildController;
use app\modules\collection\models\TblBmcDispatch;
use Yii;

class BmcDispatchController extends ChildController {

    public function actionAddDispatch() {
        $model = new TblBmcDispatch();
        $model->setAttributes($this->post_data['content']);
        $transaction = $this->generalModel->saveTransaction([$model], ['Bmc Dispatch', 'create']);
        if ($transaction == 'customRedirect') {
            $data = ['message' => 'Bmc Dispatch Added Successfully'];
        } else {
            $data = ['message' => 'Bmc Dispatch Not Added'];
        }
        $this->response['data'] = $data;
    }

    public function actionDispatchList() {
        $model = new TblBmcDispatch();
        $model->setAttributes($this->post_data['content']);
        $model->bmc_code = $this->post_data['bmc_code'];
        $content = $this->post_data['content'];
        $from_date = $content['from_date'] . ' ' . \Yii::$app->general->getshift($content['from_shift']);
        $to_date = $content['to_date'] . ' ' . \Yii::$app->general->getshift($content['to_shift']);
        return $this->response['data'] = $model->getDispatchList($from_date, $to_date);
    }

}
