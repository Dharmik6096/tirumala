<?php

namespace app\modules\collection\controllers;

use Yii;
use app\modules\collection\models\TblMccShiftLock;
use app\modules\collection\models\TblMccShiftLockSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\collection\models\TblBmcCollectionSearch;
use yii\helpers\Json;
use yii\web\Response;
use app\modules\collection\models\TblMccShiftLockHistory;
use app\modules\collection\models\TblMccShiftLockStaging;
use app\components\WebApi;
use app\modules\vsp\models\TblVspTransitRecovery;
use app\modules\bkgprocess\models\TblFtpTxnLog;

/**
 * TblMccShiftLockController implements the CRUD actions for TblMccShiftLock model.
 */
class TblMccShiftLockController extends \app\controllers\ChildController {

    /**
     * Lists all TblMccShiftLock models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TblMccShiftLockSearch();
        $searchModel->scenario = 'shiftLock';
        $dataProvider = $searchModel->shiftlocksearch(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblMccShiftLock model.
     * @param string $id
     * @return mixed
     */
    public function actionView($mcc = '', $date = '', $shift = '') {
        $this->model = new TblMccShiftLock();
        $model = $this->model->find()->where(['mcc_plant_code' => $mcc, 'cast(date_time_of_collection as date)' => $date, 'shift_code' => $shift])->one();
        $searchModel = new TblMccShiftLockSearch();
        $searchModel->shift_lock_code = $model->shift_lock_code;
        $dataProvider = $searchModel->viewsearch(Yii::$app->request->queryParams);
        return $this->render('view', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblMccShiftLock model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TblMccShiftLock();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->shift_lock_code]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblMccShiftLock model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->shift_lock_code]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblMccShiftLock model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblMccShiftLock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TblMccShiftLock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TblMccShiftLock::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionLockData() {
        $union = Yii::$app->request->get()['union'];
        $plant = Yii::$app->request->get()['plant'];
        $mcc = Yii::$app->request->get()['mcc'];
        $date = Yii::$app->request->get()['date'];
        $shift = Yii::$app->request->get()['shift'];
        $qty = Yii::$app->request->get()['qty'];
        $fat = Yii::$app->request->get()['fat'];
        $snf = Yii::$app->request->get()['snf'];
        $amount = Yii::$app->request->get()['amount'];
        $saveModel = [];
        if (!empty($mcc)) {
            $this->model = new TblMccShiftLock();
            $this->model->union_code = $union;
            $this->model->plant_code = $plant;
            $this->model->mcc_plant_code = $mcc;
            $this->model->date_time_of_collection = $date;
            $this->model->shift_code = $shift;
            $this->model->qty = $qty;
            $this->model->avg_fat = $fat;
            $this->model->avg_snf = $snf;
            $this->model->amount = $amount;
            $existData = $this->model->getExistData();
            $Recovery = TRUE;
            if (Yii::$app->session->get('eiplCode') == 'MMD') {
                $recoveryModel = new TblVspTransitRecovery();
                $existRecovery = $recoveryModel->getExistData($this->model);
                $Recovery = $existRecovery > 0 ? TRUE : FALSE;
            }
            if ($Recovery) {
                if (!empty($existData)) {
                    $this->model = $this->findModel($existData->shift_lock_code);
                    $historyModel = new TblMccShiftLockHistory();
                    Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                    $saveModel[] = $historyModel;
                } else {
                    $this->model->shift_lock_code = Yii::$app->general->getCodeAutoIncrement($this->model);
                }
                $this->model->data_lock = 1;
                $this->model->bmc_lock = 1;
                $this->model->member_lock = 1;
                $this->model->product_sale_lock = 1;

                $staging = new TblMccShiftLockStaging();
                $attribute = $this->model->attributes;
                $staging->setAttributes($attribute);
                $stagingData = $staging->find()->where(['shift_lock_code' => $this->model->shift_lock_code])->one();

                if (!empty($stagingData)) {
                    $stagingData->setAttributes($attribute);
                    $stagingData->data_post_status = 0;
                    $stagingData->picked_datetime = NULL;
                    $stagingData->response_datetime = NULL;
                    $stagingData->resp_status = NULL;
                    $stagingData->resp_desc = NULL;
                    $saveModel[] = $stagingData;
                } else {
                    $ConcateDate = date('d', strtotime($date)) . '' . date('m', strtotime($date)) . '' . date('y', strtotime($date));
                    $ConcateShift = $shift;
                    $staging->staging_code = $this->model->mcc_plant_code . '-' . $ConcateDate . '-' . $ConcateShift;
                    $saveModel[] = $staging;
                }
                $saveModel[] = $this->model;
                $transaction = $this->generalModel->saveTransaction($saveModel, ['Shift Lock', 'edit']);
                if ($transaction == 'customRedirect') {
                    if (Yii::$app->session->get('eiplCode') == 'MMD') {
                        $api = new WebApi();
                        $api->serverUrl = 'https://login.microsoftonline.com/2c11ed1f-0dff-46b9-94e9-8cbe83717417/oauth2/token';
                        $api->authentication = FALSE;
//                        $bodyData = [
//                            'grant_type' => 'client_credentials',
//                            'client_id' => '20e569c1-4277-462b-b32c-01fc8516b4a8',
//                            'client_secret' => '4wD7Q~o~2sb6uJY77edU7GBNPHDHFs0KFCxz3',
//                            'resource' => 'https://mmd-test.sandbox.operations.dynamics.com'
//                        ];
                        $bodyData = [
                            'grant_type' => 'client_credentials',
                            'client_id' => '1ebf2967-31cd-48aa-a95d-47872d9c1660',
                            'client_secret' => 'wSz7Q~.xR387Nc4bpNLaIQeJucLqv7.4a0Z5b',
                            'resource' => 'https://mmd-prd.operations.dynamics.com'
                        ];
                        $api->body = json_encode($bodyData);
                        $api->header_info = ['Cookie: buid=0.ASoAH-0RLP8NuUaU6Yy-g3F0FxUAAAAAAAAAwAAAAAAAAAAqAAA.AQABAAEAAAD--DLA3VO7QrddgJg7WevrvVFNQrKzy_CROckT6gVKweNqD3cIE_2e6sZvqDLziOl8pO63n7RLdlIlGAuwxD62Enrb1pzwLrCCeemK4klCumlwbqCg2J9DH0skWUTDYnkgAA; esctx=AQABAAAAAAD--DLA3VO7QrddgJg7Wevr6ffEbthd6xIgF9p_ALeJBUHpIFF8fjoU4RbhU6__vXSrMIrFkvh22Pkix_9Le-2mYya7B8dKnuUP_rbwfpzClwoS9Ky7NfwLUT_ZHQKSykQ94qj7dGTJP5ADjUi---2djJon1PEOjTv7Y6o3MstQTGOfn3_UANRXj-6c84mvRDIgAA; x-ms-gateway-slice=estsfd; stsservicecookie=estsfd; fpc=AmO0_u8SW0RBlh7R1d1Hu_TyqFelAQAAACCE-NgOAAAAMek5pAEAAACJhPjYDgAAAA'];

                        $response = $api->SapDataIntegration();
                        $responseData = json_decode(json_encode($response), true);

                        if (!empty($responseData['token_type']) && !empty($responseData['resource']) && !empty($responseData['access_token'])) {
                            $shiftLock = new TblMccShiftLockStaging();
                            $shiftLockData = $shiftLock->getLockShift(10);

                            foreach ($shiftLockData as $key => $value) {
                                $body = [];
                                $loopData = [];
                                $loopDetailData = [];
                                $value->updateAll(['data_post_status' => 1, 'picked_datetime' => date('Y-m-d H:i:s')], ['staging_code' => $value->staging_code]);

                                $loopData['orderNumber'] = $value->staging_code;
                                $mccVendor = Yii::$app->general->getforeignkey($value->mccPlantCode, 'vendor_code');
                                $loopData['vendorAccount'] = !empty($mccVendor) ? $mccVendor : '';
                                $loopData['orderDate'] = date('m-d-Y', strtotime($value->date_time_of_collection));
                                $loopData['companyId'] = '';
                                $loopData['sourceSystem'] = '';

                                $loopDetailData['itemNumber'] = 'RM000001';
                                $loopDetailData['quantity'] = $value->qty;
                                $loopDetailData['lineAmount'] = $value->amount;
                                $loopDetailData['locationId'] = '';
                                $loopDetailData['FAT'] = $value->avg_fat;
                                $loopDetailData['SNF'] = $value->avg_snf;

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
                                    $value->updateAll(['data_post_status' => 2, 'resp_status' => $status, 'resp_desc' => $resp_desc, 'response_datetime' => date('Y-m-d H:i:s'), 'x_col1' => $responseData->guidD365, 'x_col2' => $responseData->fnoOrderNumber], ['staging_code' => $value->staging_code]);
                                }
                            }
                        }
                    }
                    $record = ['status' => 'success', 'msg' => 'DATA LOCK Successfully.'];
                } else {
                    $record = ['status' => 'error', 'msg' => 'DATA Not LOCK Successfully.'];
                }
            } else {
                $record = ['status' => 'success', 'msg' => 'Transit Reovery Not Availbale.'];
            }
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionUnlock() {
        $union = Yii::$app->request->get()['union'];
        $plant = Yii::$app->request->get()['plant'];
        $mcc = Yii::$app->request->get()['mcc'];
        $date = Yii::$app->request->get()['date'];
        $shift = Yii::$app->request->get()['shift'];
        $qty = Yii::$app->request->get()['qty'];
        $fat = Yii::$app->request->get()['fat'];
        $snf = Yii::$app->request->get()['snf'];
        $amount = Yii::$app->request->get()['amount'];
        $saveModel = [];
        if (!empty($mcc)) {
            $this->model = new TblMccShiftLock();
            $this->model->union_code = $union;
            $this->model->plant_code = $plant;
            $this->model->mcc_plant_code = $mcc;
            $this->model->date_time_of_collection = $date;
            $this->model->shift_code = $shift;
            $this->model->qty = $qty;
            $this->model->avg_fat = $fat;
            $this->model->avg_snf = $snf;
            $this->model->amount = $amount;
            $existData = $this->model->getExistData();
            if (!empty($existData)) {
                $this->model = $this->findModel($existData->shift_lock_code);
                $historyModel = new TblMccShiftLockHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $saveModel[] = $historyModel;
            } else {
                $this->model->shift_lock_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            }
            $this->model->data_lock = 0;
            $this->model->bmc_lock = 0;
            $this->model->member_lock = 0;
            $this->model->product_sale_lock = 0;
            $saveModel[] = $this->model;
            $transaction = $this->generalModel->saveTransaction($saveModel, ['Shift Lock', 'edit']);
            if ($transaction == 'customRedirect') {
                $record = ['status' => 'success', 'msg' => 'DATA UN-LOCK Successfully.'];
            } else {
                $record = ['status' => 'error', 'msg' => 'DATA Not UN-LOCK Successfully.'];
            }
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionIndexOther() {
        $searchModel = new TblMccShiftLockSearch();
        $searchModel->scenario = 'shiftLock';
        $dataProvider = $searchModel->shiftlocksearch(Yii::$app->request->queryParams, 'portal_mcc_shift_lock_data_other');

        return $this->render('index_other', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function updateRecords($mcc, $date, $shift, $qty, $fat, $snf, $amount, $updateField, $val, $lockMessage, $unlockMessage, $url = 'index-other') {
        $saveModel = [];
        $model = new TblMccShiftLock();
        $model->mcc_plant_code = $mcc;
        $model->union_code = Yii::$app->general->getforeignkey($model->mccPlantCode, 'union_code');
        $model->plant_code = Yii::$app->general->getforeignkey($model->mccPlantCode, 'plant_code');
        $model->date_time_of_collection = $date;
        $model->shift_code = $shift;
        $model->qty = $qty;
        $model->avg_fat = $fat;
        $model->avg_snf = $snf;
        $model->amount = $amount;
        $model->{$updateField} = $val;
        $modelData = $model->getExistData();
        $title = $model->{$updateField} == 1 ? $lockMessage : $unlockMessage;

        if (!empty($modelData)) {
            $historyModel = new TblMccShiftLockHistory();
            Yii::$app->operation->history($modelData, $historyModel, UPDATE);
            $modelData->{$updateField} = $val;
            $saveModel[] = $historyModel;
            if ($modelData->bmc_lock == 1 && $modelData->member_lock == 1 && $modelData->product_sale_lock) {
                $modelData->data_lock = 1;
            }
            $saveModel[] = $modelData;
        } else {
            $model->shift_lock_code = Yii::$app->general->getCodeAutoIncrement($model);
            $saveModel[] = $model;
        }

        $transaction = $this->generalModel->saveTransaction($saveModel, [$title, 'edit']);
        if ($transaction == 'customRedirect') {
            $record = ['status' => 'success', 'msg' => $title . ' Successfully.'];
        } else {
            $record = ['status' => 'error', 'msg' => $title . 'Not Successfully.'];
        }
        return $this->redirect([$url]);
    }

    public function actionBmcDataLock($mcc, $date, $shift, $qty, $fat, $snf, $amount, $url = 'index-other') {
        if (Yii::$app->session->get('eiplCode') != 'MMD') {
            $this->generateFTPFile($mcc, $date, $shift, 'TblBmcCollection_collection');
        }
        $this->updateRecords($mcc, $date, $shift, $qty, $fat, $snf, $amount, 'bmc_lock', 1, 'Data Lock - BMC', 'Data Unlock - BMC', $url);
    }

    public function actionMemberDataLock($mcc, $date, $shift, $qty, $fat, $snf, $amount, $url = 'index-other') {
        if (Yii::$app->session->get('eiplCode') != 'MMD') {
            $this->generateFTPFile($mcc, $date, $shift, 'TblMilkCollection');
        }
        $this->updateRecords($mcc, $date, $shift, $qty, $fat, $snf, $amount, 'member_lock', 1, 'Data Lock - Member', 'Data Unlock - Member', $url);
    }

    public function actionProductSaleLock($mcc, $date, $shift, $qty, $fat, $snf, $amount, $url = 'index-other') {
        $this->updateRecords($mcc, $date, $shift, $qty, $fat, $snf, $amount, 'product_sale_lock', 1, 'Data Lock - Product Sale', 'Data Unlock - Product Sale', $url);
    }

    public function actionBmcDataUnlock($mcc, $date, $shift, $qty, $fat, $snf, $amount, $url = 'index-other') {
        $this->updateRecords($mcc, $date, $shift, $qty, $fat, $snf, $amount, 'bmc_lock', 0, 'Data Lock - BMC', 'Data Unlock - BMC', $url);
    }

    public function actionMemberDataUnlock($mcc, $date, $shift, $qty, $fat, $snf, $amount, $url = 'index-other') {
        $this->updateRecords($mcc, $date, $shift, $qty, $fat, $snf, $amount, 'member_lock', 0, 'Data Lock - Member', 'Data Unlock - Member', $url);
    }

    public function actionProductSaleUnlock($mcc, $date, $shift, $qty, $fat, $snf, $amount, $url = 'index-other') {
        $this->updateRecords($mcc, $date, $shift, $qty, $fat, $snf, $amount, 'product_sale_lock', 0, 'Data Lock - Product Sale', 'Data Unlock - Product Sale', $url);
    }

    public function actionLockDataOther() {
        $union = Yii::$app->request->get()['union'];
        $plant = Yii::$app->request->get()['plant'];
        $mcc = Yii::$app->request->get()['mcc'];
        $date = Yii::$app->request->get()['date'];
        $shift = Yii::$app->request->get()['shift'];
        $qty = Yii::$app->request->get()['qty'];
        $fat = Yii::$app->request->get()['fat'];
        $snf = Yii::$app->request->get()['snf'];
        $amount = Yii::$app->request->get()['amount'];
        $saveModel = [];
        if (!empty($mcc)) {
            $this->model = new TblMccShiftLock();
            $this->model->union_code = $union;
            $this->model->plant_code = $plant;
            $this->model->mcc_plant_code = $mcc;
            $this->model->date_time_of_collection = $date;
            $this->model->shift_code = $shift;
            $this->model->qty = $qty;
            $this->model->avg_fat = $fat;
            $this->model->avg_snf = $snf;
            $this->model->amount = $amount;
            $this->model->bmc_lock = $amount;
            $this->model->amount = $amount;
            $this->model->amount = $amount;
            $existData = $this->model->getExistData();

            if (!empty($existData)) {
                $this->model = $this->findModel($existData->shift_lock_code);
                $historyModel = new TblMccShiftLockHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $saveModel[] = $historyModel;
            } else {
                $this->model->shift_lock_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            }
            $this->model->data_lock = 1;
            $this->model->bmc_lock = 1;
            $this->model->member_lock = 1;
            $this->model->product_sale_lock = 1;
            $saveModel[] = $this->model;
            $transaction = $this->generalModel->saveTransaction($saveModel, ['Shift Lock', 'edit']);
            if ($transaction == 'customRedirect') {
                $record = ['status' => 'success', 'msg' => 'DATA LOCK Successfully.'];
            } else {
                $record = ['status' => 'error', 'msg' => 'DATA Not LOCK Successfully.'];
            }
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function actionUnlockOther() {
        $union = Yii::$app->request->get()['union'];
        $plant = Yii::$app->request->get()['plant'];
        $mcc = Yii::$app->request->get()['mcc'];
        $date = Yii::$app->request->get()['date'];
        $shift = Yii::$app->request->get()['shift'];
        $qty = Yii::$app->request->get()['qty'];
        $fat = Yii::$app->request->get()['fat'];
        $snf = Yii::$app->request->get()['snf'];
        $amount = Yii::$app->request->get()['amount'];
        $saveModel = [];
        if (!empty($mcc)) {
            $this->model = new TblMccShiftLock();
            $this->model->union_code = $union;
            $this->model->plant_code = $plant;
            $this->model->mcc_plant_code = $mcc;
            $this->model->date_time_of_collection = $date;
            $this->model->shift_code = $shift;
            $this->model->qty = $qty;
            $this->model->avg_fat = $fat;
            $this->model->avg_snf = $snf;
            $this->model->amount = $amount;
            $existData = $this->model->getExistData();
            if (!empty($existData)) {
                $this->model = $this->findModel($existData->shift_lock_code);
                $historyModel = new TblMccShiftLockHistory();
                Yii::$app->operation->history($this->model, $historyModel, UPDATE);
                $saveModel[] = $historyModel;
            } else {
                $this->model->shift_lock_code = Yii::$app->general->getCodeAutoIncrement($this->model);
            }
            $this->model->data_lock = 0;
            $this->model->bmc_lock = 0;
            $this->model->member_lock = 0;
            $this->model->product_sale_lock = 0;
            $saveModel[] = $this->model;
            $transaction = $this->generalModel->saveTransaction($saveModel, ['Shift Lock', 'edit']);
            if ($transaction == 'customRedirect') {
                $record = ['status' => 'success', 'msg' => 'DATA UN-LOCK Successfully.'];
            } else {
                $record = ['status' => 'error', 'msg' => 'DATA Not UN-LOCK Successfully.'];
            }
        }
        Yii::$app->getSession()->setFlash('success');
        Yii::$app->response->format = trim(Response::FORMAT_JSON);
        return Json::encode($record);
    }

    public function generateFTPFile($mcc, $date, $shift, $module_name) {
        $date .= ' ' . \Yii::$app->general->getshift($shift);
        $model = new TblMccShiftLock();
        $model->mcc_plant_code = $mcc;
        $model->union_code = Yii::$app->general->getforeignkey($model->mccPlantCode, 'union_code');
        $data_array = [];
        $data_array['module_name'] = $module_name;
        $data_array['module_code'] = $mcc;
        $data_array['mcc_plant_code'] = $mcc;
        $data_array['union_code'] = $model->union_code;
        $data_array['applicable_date'] = $date;
        $data_array['shift_code'] = $shift;
        $data_array['bmc_code'] = '0';
        $data_array['from_date'] = $date;
        $data_array['to_date'] = $date;
//        if (in_array($mcc, ['7300', '7304', '7300', '7301', '7303', '7304', '7384', '7583', '7416', '7423', '7424', '7302'])) {
        $ftp_model = new TblFtpTxnLog();
        $ftp_model->exportData($data_array);
//        }
    }

}
