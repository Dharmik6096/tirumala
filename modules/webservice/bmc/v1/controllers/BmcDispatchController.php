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
            $this->response['data'] = $data;
        } else {
            $data = ['message' => 'Bmc Dispatch Not Added Successfully'];
            $this->response['data'] = $data;
        }
    }

}
