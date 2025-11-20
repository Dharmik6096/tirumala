<?php

namespace app\modules\organisation\controllers;

use app\controllers\ChildController;
use Yii;
use app\modules\organisation\models\TblMasterTransferDataUpdate;
use app\modules\organisation\models\TblMasterTransferDataUpdateSearch;
use yii\web\NotFoundHttpException;

/**
 * TblMasterTransferDataUpdateController implements the CRUD actions for TblMasterTransferDataUpdate model.
 */
class TblMasterTransferDataUpdateController extends ChildController {

    /**
     * Lists all TblMasterTransferDataUpdate models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMasterTransferDataUpdateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblMasterTransferDataUpdate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->model = new TblMasterTransferDataUpdate();
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            $this->model->from_date = date('Y-m-d H:i:s', strtotime($this->model->from_date . ' ' . Yii::$app->general->getshift($this->model->from_shift)));
            $this->model->to_date = date('Y-m-d H:i:s', strtotime($this->model->to_date . ' ' . Yii::$app->general->getshift($this->model->to_shift)));
            $sp_params = Yii::$app->request->post()['TblMasterTransferDataUpdate'];
            $sp_params['from_date'] = $this->model->from_date;
            $sp_params['to_date'] = $this->model->to_date;
            unset($sp_params['from_shift'], $sp_params['to_shift']);
            // $output = \Yii::$app->general->getSpData('portal_master_transfer_data_update', $sp_params);
            // foreach ($output as $res) {
            //     $this->model->status = $res['retuns_value'];
            // }
            $transaction = $this->generalModel->saveTransaction([$this->model], ['Master Transfer Data Update', 'create']);
            if ($transaction !== FALSE) {
                return $this->{$transaction}();
            }
        }
        return $this->render('create', ['model' => $this->model]);
    }
    
}
