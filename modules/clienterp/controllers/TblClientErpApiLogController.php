<?php

namespace app\modules\clienterp\controllers;

use app\components\WebApi;
use app\controllers\ChildController;
use app\modules\clienterp\components\EiplResponse;
use Yii;
use app\modules\clienterp\models\TblClientErpApiLog;
use app\modules\clienterp\models\TblClientErpApiLogSearch;
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
        $searchModel = new TblClientErpApiLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionRepush($id){
        $model = $this->findModel($id);
        if(!empty($model)){
            $httpCode = '';
            $response = '';
            $requestTimestamp = date('Y-m-d H:i:s');
            try {
                $model->request_url = \Yii::$app->params['clienterp_authentication']['cargill']['jde_base_url'].$model->end_point;
                $base_url = \Yii::$app->params['clienterp_authentication']['cargill']['jde_base_url'];           
                $api = new WebApi();
                $api->serverUrl = $base_url;
                $api->apiurl = $model->end_point;
                $api->body = json_decode($model->request_payload);
                $authentication = Yii::$app->params['clienterp_authentication']['cargill']['authentication'];
                $api->header_info['Authorization'] = "Basic " . base64_encode($authentication);
                $response = $api->GuzzlePostData();
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
                $this->setLogData($model, $response, $requestTimestamp, $responseTimestamp, $model->request_payload, $httpCode);
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
        $eiplResponse->logData = $logData;
        $eiplResponse->saveRequestResponseLog($request, $response, $requestTimestamp, $responseTimestamp, $requestJson);
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
