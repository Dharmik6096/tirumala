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
            $requestTimestamp = date('Y-m-d H:i:s');
            $model->request_url = \Yii::$app->params['clienterp_authentication']['cargill']['jde_base_url'].$model->end_point;
            $base_url = \Yii::$app->params['clienterp_authentication']['cargill']['jde_base_url'];           
            $api = new WebApi();
            $api->serverUrl = $base_url;
            $api->apiurl = $model->end_point;
            $api->body = json_decode($model->request_payload);
            $api->authentication = Yii::$app->params['clienterp_authentication']['cargill']['authentication'];
            $response = $api->GuzzlePostData();


            // $header = json_decode($model->request_header);
            // $ch = curl_init();
            // curl_setopt($ch, CURLOPT_URL, $model->request_url);
            // curl_setopt($ch, CURLOPT_HEADER, FALSE);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $model->request_payload);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            // curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            // if (false) {
            //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            // }  // Skip SSL Verification
            // $response = curl_exec($ch);
            // $httpCode = '';
            // if ($response === false) {
            // } else {
            //     $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            // }
            // curl_close($ch);

            $responseTimestamp = date('Y-m-d H:i:s');
            $this->setLogData($model, $response, $requestTimestamp, $responseTimestamp, $model->request_payload, $httpCode);
        }
    }

    public function setLogData($request, $response, $requestTimestamp, $responseTimestamp, $requestJson, $httpCode) {
        $this->response = new EiplResponse();
        $logData = $request;
        $logData['status_code'] = $httpCode;
        $logData['status_message'] = !empty($response->jde__simpleMessage) ? json_encode($response->jde__simpleMessage) : '';
        $logData['status_response'] = !empty($response->jde__status) ? json_encode($response->jde__status) : '';
        unset($logData['created_at'], $logData['created_by'], $logData['updated_at'], $logData['updated_by']);
        $this->response->logData = $logData;
        $this->response->saveRequestResponseLog($request, $response, $requestTimestamp, $responseTimestamp, $requestJson);
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
