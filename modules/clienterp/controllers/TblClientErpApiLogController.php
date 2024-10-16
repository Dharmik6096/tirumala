<?php

namespace app\modules\clienterp\controllers;

use app\components\WebApi;
use app\controllers\ChildController;
use app\modules\clienterp\components\EiplResponse;
use Yii;
use app\modules\clienterp\models\TblClientErpApiLog;
use app\modules\clienterp\models\TblClientErpApiLogSearch;
use app\modules\tankermovement\models\TblMilkVehicleEntryTransaction;
use app\modules\tankermovement\models\TblMilkVehicleEntryTransactionSearch;
use yii\web\NotFoundHttpException;

/**
 * TblClientErpApiLogController implements the CRUD actions for TblClientErpApiLog model.
 */
class TblClientErpApiLogController extends ChildController
{
    /**
     * Lists all TblClientErpApiLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $queryParams = Yii::$app->request->queryParams;
    
        if (!empty($queryParams['TblClientErpApiLogSearch'])) {
            $queryParams['TblMilkVehicleEntryTransactionSearch'] = $queryParams['TblClientErpApiLogSearch'];
        } elseif (!empty($queryParams['TblMilkVehicleEntryTransactionSearch'])) {
            $queryParams['TblClientErpApiLogSearch'] = $queryParams['TblMilkVehicleEntryTransactionSearch'];
        }
        $erpProcessName = $queryParams['TblClientErpApiLogSearch']['erp_process_name'] ?? null;
        if ($erpProcessName == 1) {
            $searchModel = new TblClientErpApiLogSearch();
            $dataProvider = $searchModel->search($queryParams);
        } else {
            $searchModel = new TblMilkVehicleEntryTransactionSearch();
            $dataProvider = $searchModel->searchMilkReceipt($queryParams);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblClientErpApiLog model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $erp_process_name = null)
    {
        if ($id === null) {
            throw new \yii\web\BadRequestHttpException('Missing required parameter: id');
        }
        $searchModel = new TblClientErpApiLogSearch();
        $searchModel->scenario = 'viewLog';
        $dataProvider = [];
        if($erp_process_name == 2){
            $model = TblMilkVehicleEntryTransaction::findOne($id);
            $searchModel->desc2 = $id;
            $searchModel->erp_process_name = $erp_process_name;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        } else {
            $model = $this->findModel($id);
            $searchModel->desc1 = $model->desc1;
            $searchModel->erp_process_name = $erp_process_name;
        }
        return $this->render('view', [
            'model' => $model,
            'erp_process_name' => $erp_process_name,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }

    public function actionRepush($id){
        // $model = $this->findModel($id);
        $model = TblClientErpApiLog::find()->where(['desc2' => $id])->orderBy('log_id')->one();
        if(!empty($model)){
            $requestTimestamp = date('Y-m-d H:i:s');
            try {
                $model->request_url = \Yii::$app->params['clienterp_authentication']['cargill']['jde_base_url'].$model->end_point;
                $base_url = \Yii::$app->params['clienterp_authentication']['cargill']['jde_base_url'];           
                $api = new WebApi();
                $api->return_actual = true;
                $api->serverUrl = $base_url;
                $api->apiurl = $model->end_point;
                $api->body = json_decode($model->request_payload);
                $authentication = Yii::$app->params['clienterp_authentication']['cargill']['authentication'];
                $api->header_info['Authorization'] = "Basic " . base64_encode($authentication);
                $result = $api->GuzzleCURL();
                $httpCode = $result->getStatusCode();
                $response = $result->getBody()->getContents();
                $responseTimestamp = date('Y-m-d H:i:s');
                $this->setLogData($model, $response, $requestTimestamp, $responseTimestamp, $model->request_payload, $httpCode);
            }  catch (\GuzzleHttp\Exception\RequestException $ex) {
                $response = $ex->hasResponse() ? $ex->getResponse()->getBody()->getContents() : $ex->getMessage();
                $httpCode = $ex->hasResponse() ? $ex->getResponse()->getStatusCode() : 408;
                $responseTimestamp = date('Y-m-d H:i:s');
                $this->setLogData($model, $response, $requestTimestamp, $responseTimestamp, $model->request_payload, $httpCode);
            }  catch (\Throwable $ex) {
                $responseTimestamp = date('Y-m-d H:i:s');
                $response = $ex->getMessage();
                $httpCode = 500;
                $this->setLogData($model, $response, $requestTimestamp, $responseTimestamp, $model->request_payload, $httpCode);
            }
            if($model->request_desc == 'milk receipt'){
                $txnModel = TblMilkVehicleEntryTransaction::findOne($id);
                if(!empty($txnModel)){
                    $txnModel->status = ($httpCode == 200) ? 2 : 3;
                    $txnModel->response_msg = ($httpCode == 200) ? 'Milk reciept send successfully' : json_encode($response);
                    $txnModel->pick_datetime = $requestTimestamp;
                    $txnModel->cron_pick_datetime = $requestTimestamp;
                    $txnModel->response_datetime = $responseTimestamp;
                    $txnModel->save();
                }
            }
        }
        return $this->customRedirect();
    }

    public function setLogData($request, $response, $requestTimestamp, $responseTimestamp, $requestJson, $httpCode) {
        $eiplResponse = new EiplResponse();
        $logData = $request;
        $logData['status_code'] = $httpCode;
        $logData['status_message'] = !empty($response->jde__simpleMessage) ? json_encode($response->jde__simpleMessage) : '';
        $logData['status_response'] = !empty($response->jde__status) ? json_encode($response->jde__status) : 'ERROR';
        unset($logData['created_at'], $logData['created_by'], $logData['updated_at'], $logData['updated_by']);
        $eiplResponse->saveRequestResponseLog($request, $response, $requestTimestamp, $responseTimestamp, $logData, $requestJson);
    }

    /**
     * Finds the TblClientErpApiLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblClientErpApiLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblClientErpApiLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
