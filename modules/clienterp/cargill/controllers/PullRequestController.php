<?php

namespace app\modules\clienterp\cargill\controllers;

use Yii;
use app\models\GeneralModel;
use app\modules\product\models\TblPlantDispatch;
use app\modules\product\models\TblPlantDispatchTxn;
use yii\widgets\ActiveForm;
use yii\web\Response;
use yii\helpers\Json;
use app\modules\clienterp\components\EiplResponseCode;

class PullRequestController extends PullMasterController {

    public $generalModel;

    public function actionPlantDispatch() {
        $request = Yii::$app->request->getRawBody();
        $plantdisModel = new TblPlantDispatch();
        $this->generalModel = new GeneralModel();
        $plantdisModel->scenario = 'bmc';
        $plantdisModel->plant_dispatch_code = Yii::$app->general->getCodeAutoIncrement($plantdisModel);
        $plantdisModel->document_no = $request['document_no'];
        $plantdisModel->document_date = $request['document_date'];
        $plantdisModel->dispatch_date = $request['dispatch_date'];
        $plantdisModel->plant_code = $request['plant_code'];
        $plantdisModel->bmc_code = $request['to_location'];
        $plantdisModel->remarks = $request['remarks'] ? $request['remarks'] : null;
        if ($plantdisModel->validate()) {
            $save_model[] = $plantdisModel;
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return Json::encode(ActiveForm::validate($plantdisModel));
        }
        $i = 1;
        foreach ($request['transactions'] as $requestData) {
            $txModel = new TblPlantDispatchTxn();
            $txModel->scenario = 'product';
            $txModel->product_code = $requestData['itemCode'];
            $txModel->sap_batch_no = $requestData['batchNo'];
            $txModel->qty = $requestData['qty'];
            $txModel->rate = $requestData['rate'];
            if (!is_numeric($txModel->qty) || !is_numeric($txModel->rate)) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode([
                            'error' => 'Non-numeric value encountered for qty or rate.'
                ]);
            }
            $txModel->amount = ($txModel->qty) * $txModel->rate;
            $txModel->plant_dispatch_txn_code = Yii::$app->general->getCodeAutoIncrement($txModel, $i);
            $txModel->plant_dispatch_code = $plantdisModel->plant_dispatch_code;
            if ($txModel->validate()) {
                $save_model[] = $txModel;
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return Json::encode(ActiveForm::validate($txModel));
            }
            $i++;
        }
        $transaction = $this->generalModel->saveTransaction($save_model, ['Plant Dispatch', 'create']);
        if ($transaction == 'customRedirect') {
            $responseCodes = new EiplResponseCode();
            $txnRefCode = isset($save_model[1]) ? $save_model[1]->plant_dispatch_txn_code : null;
            $msg = Yii::$app->getSession()->getFlash('success')['message'];
            $statusCode = $responseCodes->statusSuccess;
            $record = ['statusCode' => $statusCode, 'message' => [], 'data' => ['txnRefCode' => $txnRefCode]];
            echo json_encode($record);
        } else {
            $msg = Yii::$app->getSession()->getFlash('success')['message'];
            $record = ['status' => 'success', 'msg' => $msg];
            Yii::$app->response->format = Response::FORMAT_JSON;
            return Json::encode($record);
        }
    }

}
