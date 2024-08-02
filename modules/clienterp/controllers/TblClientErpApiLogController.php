<?php

namespace app\modules\clienterp\controllers;

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
        echo '<pre>';
        print_r($model);
        echo '</pre>';
        die;
        if(!empty($model)){
            $requestTimestamp = date('Y-m-d H:i:s');
            $url = \Yii::$app->params['clienterp_authentication']['cargill']['jde_base_url'].$model->end_point;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $model->request_payload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $model->request_header);
            if (false) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            }  // Skip SSL Verification
            $response = curl_exec($ch);
            if ($response === false) {
            } else {
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            }
            curl_close($ch);
            $responseTimestamp = date('Y-m-d H:i:s');
            $this->setLogData($model, $response, $requestTimestamp, $responseTimestamp, $model->request_payload, $httpCode);
        }
    }

    public function setLogData($request, $response, $requestTimestamp, $responseTimestamp, $requestJson, $httpCode) {
        $this->response = new EiplResponse();
        $logData = $request;
        $logData = [
            'status_code' => $httpCode,
            'status_message' => !empty($response->jde__simpleMessage) ? json_encode($response->jde__simpleMessage) : '',
        ];
        unset($logData['created_at']);
        unset($logData['created_by']);
        unset($logData['updated_at']);
        unset($logData['updated_by']);
        echo '<pre>';
        print_r($logData);
        echo '</pre>';
        die;
        $this->response->logData = $logData;
        $this->response->saveRequestResponseLog($requestJson, $response, $requestTimestamp, $responseTimestamp, $requestJson);
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
