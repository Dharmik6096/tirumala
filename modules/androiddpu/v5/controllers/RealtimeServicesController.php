<?php

namespace app\modules\androiddpu\v5\controllers;

use yii\web\UploadedFile;
use app\modules\document\models\TblAttachment;
use Yii;
use app\modules\tankermovement\models\TblMilkVehicleEntryQlty;
use app\modules\tankermovement\models\TblVehicleTrip;
use app\modules\tankermovement\models\TblVehicleTripDetail;
use app\modules\tankermovement\models\TblBmcDispatchStock;
use app\modules\tankermovement\models\TblBmcMilkDispatchTxn;
use app\modules\tankermovement\models\TblBmcMilkDispatch;
use app\modules\transporter\models\TblVehicleMaster;
use app\modules\collection\controllers\TblMccShiftLockController;
use app\modules\tankermovement\models\TblRawFgMaterialReceipt;
use yii\db\Expression;
use app\modules\syncutility\models\TblForceSyncRequest;

class RealtimeServicesController extends \app\modules\androiddpu\v4\controllers\RealtimeServicesController {

    public function actionSaveAttachment() {
        $saveModel = [];
        $message = 'Unable to Saved!';
        $postData = $this->post_data['content'];
        if (!empty($_FILES)) {
            $uploads = UploadedFile::getInstancesByName("attachment");
            $auto_inc = 0;
            $attach_path = Yii::$app->params['document_upload'] . 'material_receipt';
            if (Yii::$app->general->checkDirectory($attach_path)) {
                foreach ($uploads as $key => $file) {
                    $extension = pathinfo($file->name, PATHINFO_EXTENSION);
                    $file_name = $file->name;
                    $attachment = $attach_path . '/' . $file_name;
                    if ($file->saveAs($attachment)) {
                        $modelAttachment = new TblAttachment();
                        $modelAttachment->module_name = !empty($postData['module_name']) ? $postData['module_name'] : '';
                        $modelAttachment->module_code = !empty($postData['module_code']) ? $postData['module_code'] : '';
                        $modelAttachment->attachment_type = $extension;
                        $modelAttachment->file_name = $file_name;
                        $modelAttachment->remarks = !empty($postData['remarks']) ? $postData['remarks'] : '';
                        $modelAttachment->attachment = Yii::$app->urlManager->createAbsoluteUrl('') . $attachment;
                        $modelAttachment->originating_org_code = !empty($this->post_data['organization_code']) ? $this->post_data['organization_code'] : '';
                        $modelAttachment->originating_org_type = !empty($this->post_data['organization_type']) ? $this->post_data['organization_type'] : '';
                        $modelAttachment->created_by = $modelAttachment->originating_org_code;
                        $modelAttachment->originating_type = 23;
                        $saveModel[] = $modelAttachment;
                        $auto_inc++;
                    }
                }
            }

            $transaction = $this->generalModel->saveTransaction($saveModel, ['Attachment', 'create']);
            if ($transaction == 'customRedirect') {
                $message = 'Successfully Saved!';
            }
        }
        $this->response['data'] = ['message' => $message];
        return $this->response;
    }

    public function actionGetReceiptTrip() {
        $res_data = [];
        $data = $this->post_data;
        if (!empty($data['content'])) {
            $content = $data['content'];
            if (!empty($data['organization_type']) && !empty($data['organization_code']) && !empty($content['vehicle_code']) && !empty($content['transaction_date'])) {
                $type = $data['organization_type'];
                if (strtoupper($type) == 'PLANT') {
                    $tripData = [];
                    $tripModel = new TblVehicleTripDetail();
                    $tripList = $tripModel->getOpenTripList('receipt', $content['vehicle_code'], $content['transaction_date']);
                    foreach ($tripList as $k => $trip_code) {
                        $tripArray = [];
                        $tripArray['trip_code'] = $trip_code;
                        $milkVehicleEntryQlty = new TblMilkVehicleEntryQlty();
                        $milkVehicleEntryQlty->trip_code = $trip_code;
                        $milkVehicleEntryQlty->plant_code = $data['organization_code'];
                        $milkVehicleEntryQlty->union_code = $milkVehicleEntryQlty->plantCode->union_code;
                        $milkVehicleEntryQltyData = $milkVehicleEntryQlty->getMilkVehicleEntryQlty(TRUE);
                        $tripArray['msg'] = '';
                        $dispatchFrom = $dispatchFromCode = '';
                        $tripModel->trip_code = $trip_code;
                        $dispatch = $tripModel->getTripData();
                        if (!empty($dispatch)) {
                            $dispatchFrom = strtoupper($dispatch->source_org_type);
                            $dispatchFromCode = $dispatch->source_org_code;
                        }
                        $tripArray['dispatchFrom'] = $dispatchFrom;
                        $tripArray['dispatchFromCode'] = $dispatchFromCode;
                        $tripArray['lotQltyValidate'] = $milkVehicleEntryQltyData['lotQltyValidate'];
                        $tripArray['lotQltyData'] = $milkVehicleEntryQltyData['lotQltyData'];
                        $tripData[] = $tripArray;
                    }
                    $res_data = $tripData;
                }
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionCloseTrip() {
        $res_data = [];
        $res_data['message'] = 'Trip Not Closed.';
        $data = $this->post_data;
        if (!empty($data['content'])) {
            $content = $data['content'];
            if (!empty($data['organization_type']) && !empty($data['organization_code']) && !empty($content['trip_code']) && !empty($content['grn_no'])) {
                $type = $data['organization_type'];
                if (strtoupper($type) == 'PLANT') {
                    $tripModel = new TblVehicleTrip();
                    $tripModel->trip_code = $content['trip_code'];
                    $tripModel = $tripModel->getTripData();
                    if (!empty($tripModel)) {
                        $tripModel->scenario = 'closetrip';
                        $tripModel->grn_no = $content['grn_no'];
                        $tripModel->trip_status = 'closed';
                        $tripModel->trip_sub_status = 'cleaning_pending';
                        $tripModel->sub_status_time = date('Y-m-d H:i:s');
                        if ($tripModel->save()) {
                            $res_data['message'] = 'Trip Closed Successfully.';
                            $tripModel->plant_code = $data['organization_code'];
                            $plantData = $tripModel->plantCode;
                            $remarks = $plantData->ref_code . '-' . $plantData->name;
                            $trackingDetail = ['visibility_status' => 1, 'module_code' => NULL, 'module_type' => NULL];
                            Yii::$app->general->setVehicleTripTrackingDetail($tripModel, $trackingDetail, $remarks);
                            $tripModel->addAutoQaCleaning($remarks);
                        }
                    }
                }
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionGenerateTrip() {
        $response_data = $stock_data = $dispatch_data = [];
        $trip_data = NULL;
        $data = $this->post_data;
        if ($data['organization_type'] == 'BMC') {
            $model = new TblVehicleTrip();
            $model->attributes = $data['content'];
            $model->bmc_code = $data['organization_code'];
            $bmcDetail = $model->bmcCode;
            if (!empty($bmcDetail)) {
                $model->union_code = $bmcDetail->union_code;
                $model->plant_code = $bmcDetail->plant_code;
                $model->mcc_plant_code = $bmcDetail->mcc_plant_code;

                $bmcMilkDispatchTxnModel = new TblBmcMilkDispatchTxn();
                $bmcMilkDispatchTxnModel->bmc_code = $model->bmc_code;
                $bmcMilkDispatchTxnModel->transaction_date = $model->transaction_date;
                $testReportNo = $bmcMilkDispatchTxnModel->generateTestReportNo();


                /*  $postData = $data['content'];
                  $from_datetime = $postData['from_date'];
                  $to_datetime = $postData['to_date'];
                  $dispatch_count = $postData['dispatch_count'];

                  -- stock detail --
                  if ($dispatch_count == '1') {
                  $stock_date = TblBmcDispatchStock::find()->where(['bmc_code' => $model->bmc_code])->orderBy(['to_date' => SORT_DESC, 'created_at' => SORT_DESC])->one();
                  if (!empty($stock_date)) {
                  $from_datetime = date('Y-m-d H:i:s', strtotime('+12 hours', strtotime($stock_date->to_date)));
                  if ($to_datetime < $from_datetime) {
                  $to_datetime = $from_datetime;
                  }
                  }
                  }
                  $query = \Yii::$app->db->createCommand("{CALL sp_portal_bmc_purchase_detail (:bmc_code,:from_datetime,:to_datetime)}")
                  ->bindValue(':from_datetime', $from_datetime)
                  ->bindValue(':to_datetime', $to_datetime)
                  ->bindValue(':bmc_code', $model->bmc_code);
                  $stock_data = $query->queryAll(); */
                /* stock detail */

                if ($model->validate()) {
                    $model->generateAutoTrip = TRUE;
                    $result = $model->setModel();
                    if ($result[0] && !empty($result[2])) {
                        $response_data = $result[2];
                        $model->trip_code = $result[2]['trip_code'];
                        $tripData = $model->getTripData();
                        $tankerMovementWithTripSubStatus = Yii::$app->general->getUnionConfiguration($model->union_code, 'tanker_movement_with_trip_sub_status', 'PORTAL');
                        if (!empty($tripData)) {
                            $tripDetail = new TblVehicleTripDetail();
                            $tripDetail->trip_code = $model->trip_code;
                            $tripDetail->source_org_type = 'bmc';
                            $tripDetail->source_org_code = $model->bmc_code;
                            $tripDetail = $tripDetail->getTripDetails($tankerMovementWithTripSubStatus);
                            if (!empty($tripDetail)) {
                                $trip_data['trip_code'] = $tripData->trip_code;
                                $trip_data['trip_status'] = $tripData->trip_status;
                                $trip_data['destination_type'] = $tripDetail['destination_type'];
                                $trip_data['destination_code'] = $tripDetail['destination_code'];
                                $trip_data['is_auto_trip'] = $tripDetail['is_auto_trip'];
                                $trip_data['is_last_destination'] = $tripDetail['is_last_destination'];
                                $trip_data['arrival_time'] = date('Y-m-d H:i:s', strtotime($tripDetail['arrival_time']));
                                $trip_data['vehicle_trip_detail_code'] = $tripDetail['vehicle_trip_detail_code'];
                                $bmcDispatch = new TblBmcMilkDispatchTxn();
                                $bmcDispatch->trip_code = $tripData->trip_code;
                                $bmcDispatch->vehicle_code = $tripData->vehicle_code;
                                $dispatch = $bmcDispatch->getCompartmentWiseDispatchData();
                                foreach ($dispatch as $k => $v) {
                                    $d_data = [];
                                    $d_data['total_qty'] = $v['total_qty'];
                                    $d_data['capacity'] = $v['capacity'];
                                    $d_data['compartment_no'] = $k;
                                    $dispatch_data[] = $d_data;
                                }
                            }
                        } else if ($tankerMovementWithTripSubStatus != '1') {
                            $transaction = $this->generalModel->saveTransaction($result[1], ['Vehicle Trip', 'create']);
                            if ($transaction == 'customRedirect') {
                                $tripDetail = $result[1][2];
                                $trip_data['trip_code'] = $tripDetail->trip_code;
                                $trip_data['trip_status'] = $result[1][0]->trip_status;
                                $trip_data['destination_type'] = $tripDetail->destination_type;
                                $trip_data['destination_code'] = $tripDetail->destination_code;
                                $trip_data['is_auto_trip'] = '1';
                                $trip_data['is_last_destination'] = '0';
                                $trip_data['arrival_time'] = $tripDetail->arrival_time;
                                $trip_data['vehicle_trip_detail_code'] = $tripDetail->vehicle_trip_detail_code;
                                $response = Yii::$app->general->getColumnName($tripDetail->source_org_type);
                                $remarks = '';
                                if (!empty($response['rel'])) {
                                    $sourceData = $tripDetail->{$response['rel'] . 'Source'};
                                    $remarks = $sourceData->{$response['ref_code']} . '-' . $sourceData->{$response['name']};
                                }
                                $trackingDetail = ['visibility_status' => 1, 'module_code' => NULL, 'module_type' => NULL];
                                Yii::$app->general->setVehicleTripTrackingDetail($result[1][0], $trackingDetail, $remarks);
                            }
                        }
                    }
                }
                if (!empty($trip_data)) {
                    $trip_data["fromDate"] = NULL;
                    $trip_data["toDate"] = NULL;
                }
                $bmcMilkDispatchModel = new TblBmcMilkDispatch();
                $bmcMilkDispatchModel->bmc_code = $model->bmc_code;
                $stock_detail = $bmcMilkDispatchModel->getFromDateToDate();

                $stock_data['fromDateTime'] = $stock_detail['from_datetime'];
                $stock_data['toDateTime'] = $stock_detail['to_datetime'];
                $stock_data['physicalStockOnly'] = $stock_detail['physical_stock_only'];
                $stock_data['stockData'] = $stock_detail['stock_data'];


                $response_data['stockDetail'] = $stock_data;
                $response_data['tripDetail'] = $trip_data;
                $response_data['dispatchDetail'] = $dispatch_data;
                $response_data['testReportNo'] = $testReportNo;
            }
        }
        $this->response['data'] = $response_data;

        return $this->response;
    }

    public function actionUpdateTripStatus() {
        $res_data = [];
        $res_data['message'] = 'Trip Not Updated.';
        $saveModel = [];
        $deleteModel = [];
        $data = $this->post_data;
        if ($data['organization_type'] == 'BMC') {
            $postData = $data['content'];
            $tripModel = new TblVehicleTrip();
            $tripModel->attributes = $postData;
            $tripModel = $tripModel->getTripData();
            if (!empty($tripModel)) {
                $challan_no = $postData['challan_no'];
                $source_org_type = 'bmc';
                $source_org_code = $data['organization_code'];
                $destination_type = $postData['destination_type'];
                $destination_code = $postData['destination_code'];
                $is_last_destination = $postData['is_last_destination'];
                $remarks = !empty($postData['remarks']) ? $postData['remarks'] : 'AMCS Dispatch';
                $tripModel->trip_status = $is_last_destination == 1 ? 'tankerfull' : 'open';
                $saveModel[] = $tripModel;
                $validation = TRUE;
                $tripModel->addTripRoute($saveModel, $deleteModel, $challan_no, $source_org_type, $source_org_code, $destination_type, $destination_code, $validation, $is_last_destination);
                $transaction = $this->generalModel->saveDeleteTransaction($saveModel, [], $deleteModel, ['Vehicle Trip', 'edit']);
                if ($transaction == 'customRedirect') {
                    $res_data['message'] = 'Trip Updated Successfully.';
                    /*
                      $response = Yii::$app->general->getColumnName('bmc');
                      if (!empty($response['rel'])) {
                      $model = new TblBmcMilkDispatch();
                      $model->source_org_code = $source_org_code;
                      $sourceData = $model->{$response['rel'] . 'Source'};
                      $remarks = $sourceData->{$response['ref_code']} . '-' . $sourceData->{$response['name']} . '-' . $remarks;
                      }
                      $tripModel->trip_sub_status = 'bmc_dispatch';
                      $tripModel->sub_status_time = date('Y-m-d H:i:s');
                      Yii::$app->general->setVehicleTripTrackingDetail($tripModel, $remarks);
                     */
                }
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionTripVehicleList() {
        $res_data = [];
        $data = $this->post_data;
        if (!empty($data['content'])) {
            $content = $data['content'];
            if (!empty($data['organization_type']) && !empty($data['organization_code']) && !empty($content['process_type'])) {
                $milkReceipt = ($content['process_type'] == 'receipt') ? TRUE : FALSE;
                $tripmodel = new TblVehicleTrip();
                if ($data['organization_type'] == 'PLANT') {
                    $tripmodel->plant_code = $data['organization_code'];
                    $unionDetail = $tripmodel->plantCode;
                } else {
                    $tripmodel->bmc_code = $data['organization_code'];
                    $unionDetail = $tripmodel->bmcCode;
                }
                if (!empty($unionDetail)) {
                    $model = new TblVehicleMaster();
                    $data = $model->getVehicleMaster($unionDetail->union_code, $data['organization_type'], $data['organization_code'], $milkReceipt, date('Y-m-d'));
                    foreach ($data as $v => $p) {
                        $res_data[] = [
                            'vehicle_code' => $v,
                            'parsing_no' => $p
                        ];
                    }
                }
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionQltyTripList() {
        $res_data = [];
        $data = $this->post_data;
        if (!empty($data['organization_type']) && !empty($data['organization_code']) && $data['organization_type'] == 'PLANT') {
            $tripmodel = new TblVehicleTrip();
            $tripmodel->plant_code = $data['organization_code'];
            $unionDetail = $tripmodel->plantCode;
            if (!empty($unionDetail)) {
                $model = new TblVehicleTripDetail();
                $res_data = $model->getOpenTripDetailList($unionDetail->union_code, 'milk_entry_qlty_merge', '', $data['organization_code']);
            }
        }

        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionBmcShiftLockList() {
        $res_data = [];
        $data = $this->post_data;
        if (!empty($data['content'])) {
            $content = $data['content'];
            if (!empty($data['organization_type']) && !empty($data['organization_code']) && $data['organization_type'] == 'BMC' && !empty($content['from_date']) && !empty($content['to_date'])) {
                $orgDetail = $this->getOrgDetail($data['organization_type'], $data['organization_code'], FALSE);
                if ($orgDetail) {
                    $params = [
                        'f_mcc_code' => ',' . implode(',', $orgDetail['mcc_plant_code']) . ',',
                        'from_date' => $content['from_date'],
                        'to_date' => $content['to_date'],
                    ];
                    $res_data = \Yii::$app->general->getSpData('sp_app_amcs_v5_shift_lock_list', $params);
                }
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionBmcDataLock() {
        $res_data = [];
        try {
            $data = $this->post_data;
            if (!empty($data['content'])) {
                $content = $data['content'];
                if (!empty($data['organization_type']) && !empty($data['organization_code']) && $data['organization_type'] == 'BMC') {
                    $orgDetail = $this->getOrgDetail($data['organization_type'], $data['organization_code'], FALSE);
                    if ($orgDetail) {
                        $shift_lock = new TblMccShiftLockController('tbl-mcc-shift-lock', Yii::$app->getModule('collection'));
                        Yii::$app->session->set('eiplCode', $orgDetail['eipl_code']);
                        if ($content['bmc_lock'] == 1) {
                            $shift_lock->actionBmcDataLock($content['mcc_plant_code'], date('Y-m-d', strtotime($content['date_time_of_collection'])), $content['shift_code'], $content['qty'], $content['avg_fat'], $content['avg_snf'], $content['amount'], 'api_response');
                        } else {
                            $shift_lock->actionBmcDataUnlock($content['mcc_plant_code'], date('Y-m-d', strtotime($content['date_time_of_collection'])), $content['shift_code'], $content['qty'], $content['avg_fat'], $content['avg_snf'], $content['amount'], 'api_response');
                        }
                        if (Yii::$app->session->hasFlash('success')) {
                            $res_data = Yii::$app->session->getFlash('success')['message'];
                        }
                    }
                }
                Yii::$app->session->remove('eiplCode');
            }
            $this->response['data'] = ['message' => $res_data];
        } catch (\Throwable $e) {
            $this->response['data'] = ['message' => $e->getMessage()];
        }
        return $this->response;
    }

    public function actionTankerDestinationList() {
        $res_data = [];
        $data = $this->post_data;
        if (!empty($data['organization_type']) && !empty($data['organization_code'])) {
            $orgDetail = $this->getOrgDetail($data['organization_type'], $data['organization_code'], FALSE);
            if (!empty($orgDetail['union_code'])) {
                $res_data = \Yii::$app->general->getSpData('sp_app_amcs_v5_tanker_destination_list', ['union_code' => $orgDetail['union_code']]);
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionGetReceiptSeqNumber() {
        $res_data = [];
        $msg = 'Data Not Found.';
        $data = $this->post_data;
        if (!empty($data['content'])) {
            $content = $data['content'];
            if (!empty($data['organization_type']) && !empty($data['organization_code']) && !empty($content['receipt_seq_number'])) {
                $receiptSeqNumber = $content['receipt_seq_number'];
                $lastSlashPos = strrpos($receiptSeqNumber, '/');
                $prefix = substr($receiptSeqNumber, 0, $lastSlashPos + 1);
                if (strtoupper($data['organization_type']) == 'PLANT') {
                    $maxReceiptSequenceNumber = TblRawFgMaterialReceipt::find()
                            ->select([new Expression("MAX(CONVERT(INT, RIGHT(receipt_seq_number, 4))) as max_receipt_seq_number")])
                            ->where(['plant_code' => $data['organization_code']])
                            ->andWhere(new Expression("SUBSTRING(receipt_seq_number, 1, :prefix_length) = :seq_number", [':seq_number' => $prefix, ':prefix_length' => strlen($prefix)]))
                            ->andWhere(['is not', 'receipt_seq_number', null])
                            ->scalar();
                    $msg = 'Data Found.';
                    $nextSeq = !empty($maxReceiptSequenceNumber) ? (int) $maxReceiptSequenceNumber + 1 : 1;
                    $res_data = ['receiptSeqNumber' => $prefix . str_pad($nextSeq, 4, '0', STR_PAD_LEFT)];
                }
            }
        }
        $this->response['error']['message'] = [$msg];
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionSyncRequest() {
        $res_data = [];
        $data = $this->post_data;
        if (!empty($data['organization_type']) && !empty($data['organization_code'])) {
            $model = new TblForceSyncRequest();
            $model->dcs_code = $data['organization_code'];
            $modelData = $model->find()->where(['ISNULL(is_download, 0)' => 0])
                            ->andWhere(['dcs_code' => $data['organization_code']])->asArray()->all();
            if (!empty($modelData)) {
                $res_data = $modelData;
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionSyncRequestAcknowledgement() {
        $res_data = [];
        $data = $this->post_data;
        if (!empty($data['content'])) {
            $content = $data['content'];
            if (!empty($data['organization_type']) && !empty($data['organization_code'])) {
                $res_data['message'] = 'Acknowledgement Updated.';
                $reqCodes = explode(',', $content['force_sync_request_code']);
                $model = new TblForceSyncRequest();
                $model->updateAll(['is_download' => 1, 'updated_at' => date('Y-m-d H:i:s')], ['force_sync_request_code' => $reqCodes]);
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

}
