<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblMccShiftLockStaging;
use app\modules\collection\models\TblMccShiftLockStagingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\collection\models\TblMccShiftLockStagingHistory;
use yii\helpers\Json;
use yii\web\Response;
use app\modules\collection\models\TblMccShiftLockSearch;
use app\components\WebApi;

/**
 * TblMccShiftLockStagingController implements the CRUD actions for TblMccShiftLockStaging model.
 */
class TblMccShiftLockStagingController extends \app\controllers\ChildController {

    /**
     * Lists all TblMccShiftLockStaging models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMccShiftLockStagingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMccShiftLockStaging model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    /**
     * Creates a new TblMccShiftLockStaging model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblMccShiftLockStaging();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->staging_code]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblMccShiftLockStaging model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->staging_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblMccShiftLockStaging model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblMccShiftLockStaging model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblMccShiftLockStaging the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMccShiftLockStaging::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionRePushData() {
        $id = Yii::$app->request->get()['id'];
        $saveModel = [];
        if (!empty($id)) {
            $this->model = new TblMccShiftLockStaging();
            $shiftLockData = $this->model->findOne($id);
            if (!empty($shiftLockData)) {
                $api = new WebApi();
                $api->serverUrl = 'https://login.microsoftonline.com/2c11ed1f-0dff-46b9-94e9-8cbe83717417/oauth2/token';
                $api->authentication = FALSE;
                $bodyData = [
                    'grant_type' => 'client_credentials',
                    'client_id' => '20e569c1-4277-462b-b32c-01fc8516b4a8',
                    'client_secret' => '4wD7Q~o~2sb6uJY77edU7GBNPHDHFs0KFCxz3',
                    'resource' => 'https://mmd-test.sandbox.operations.dynamics.com'
                ];
                $api->body = json_encode($bodyData);
                $api->header_info = ['Cookie: buid=0.ASoAH-0RLP8NuUaU6Yy-g3F0FxUAAAAAAAAAwAAAAAAAAAAqAAA.AQABAAEAAAD--DLA3VO7QrddgJg7WevrvVFNQrKzy_CROckT6gVKweNqD3cIE_2e6sZvqDLziOl8pO63n7RLdlIlGAuwxD62Enrb1pzwLrCCeemK4klCumlwbqCg2J9DH0skWUTDYnkgAA; esctx=AQABAAAAAAD--DLA3VO7QrddgJg7Wevr6ffEbthd6xIgF9p_ALeJBUHpIFF8fjoU4RbhU6__vXSrMIrFkvh22Pkix_9Le-2mYya7B8dKnuUP_rbwfpzClwoS9Ky7NfwLUT_ZHQKSykQ94qj7dGTJP5ADjUi---2djJon1PEOjTv7Y6o3MstQTGOfn3_UANRXj-6c84mvRDIgAA; x-ms-gateway-slice=estsfd; stsservicecookie=estsfd; fpc=AmO0_u8SW0RBlh7R1d1Hu_TyqFelAQAAACCE-NgOAAAAMek5pAEAAACJhPjYDgAAAA'];

                $response = $api->SapDataIntegration();
                $responseData = json_decode(json_encode($response), true);

                if (!empty($responseData['token_type']) && !empty($responseData['resource']) && !empty($responseData['access_token'])) {
                    $body = [];
                    $loopData = [];
                    $loopDetailData = [];
                    $shiftLockData->updateAll(['data_post_status' => 1, 'picked_datetime' => date('Y-m-d H:i:s')], ['staging_code' => $shiftLockData->staging_code]);

                    $loopData['orderNumber'] = $shiftLockData->staging_code;

                    $mccVendor = Yii::$app->general->getforeignkey($shiftLockData->mccPlantCode, 'vendor_code');
                    $loopData['vendorAccount'] = !empty($mccVendor) ? $mccVendor : 'VADD00099';
//                    $loopData['vendorAccount'] = 'VADD00099';
                    $loopData['orderDate'] = date('m-d-Y', strtotime($shiftLockData->date_time_of_collection));
                    $loopData['companyId'] = '';
                    $loopData['sourceSystem'] = '';

                    $loopDetailData['itemNumber'] = 'RM000001';
                    $loopDetailData['quantity'] = $shiftLockData->qty;
                    $loopDetailData['lineAmount'] = $shiftLockData->amount;
                    $loopDetailData['locationId'] = '';
                    $loopDetailData['FAT'] = $shiftLockData->avg_fat;
                    $loopDetailData['SNF'] = $shiftLockData->avg_snf;

                    $body['purchaseOrderHeaderRequest'] = $loopData;
                    $body['purchaseOrderLineRequestList']['list'][] = $loopDetailData;
                    $postData = [];
                    $postData = json_encode($body);

                    $tokenType = $responseData['token_type'];
                    $token = $responseData['access_token'];
                    $resource = $responseData['resource'];
                    $request_url = $resource . '/api/services/TECServiceGroup/TECServices/savePurchaseOrder';

                    $api->serverUrl = $request_url;
                    $api->header_info = ['Authorization: ' . $tokenType . ' ' . $token];
                    $api->authentication = FALSE;
                    $api->body = $postData;

                    $response = $api->ExchangeData();
                    $responseData = json_decode(json_encode($response), false);

                    if (!empty($responseData)) {
                        $status = $responseData->status;
                        $resp_desc = $responseData->statusDescription;
                        $shiftLockData->updateAll(['data_post_status' => 2, 'resp_status' => $status, 'resp_desc' => $resp_desc, 'response_datetime' => date('Y-m-d H:i:s'), 'x_col1' => $responseData->guidD365, 'x_col2' => $responseData->fnoOrderNumber], ['staging_code' => $shiftLockData->staging_code]);
                    }
                }

                $historyModel = new TblMccShiftLockStagingHistory();
                Yii::$app->operation->history($shiftLockData, $historyModel, 'UPDATE');
                $saveModel[] = $historyModel;
                $transaction = $this->generalModel->saveTransaction($saveModel, ['Shift Lock', 'edit']);
            }
            $record = ['status' => 'success', 'msg' => 'DATA RE-PUSH Successfully.'];
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

}
