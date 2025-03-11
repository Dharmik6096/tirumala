<?php

namespace app\modules\androiddpu\v5\controllers;

use yii\web\UploadedFile;
use app\modules\document\models\TblAttachment;
use Yii;
use app\modules\tankermovement\models\TblMilkVehicleEntryQlty;
use app\modules\tankermovement\models\TblVehicleTrip;
use app\modules\tankermovement\models\TblVehicleTripDetail;

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
                    $tripList = new TblVehicleTripDetail();
                    $tripList = $tripList->getOpenTripList('receipt', $content['vehicle_code'], $content['transaction_date']);
                    foreach ($tripList as $k => $trip_code) {
                        $tripArray = [];
                        $tripArray['trip_code'] = $trip_code;
                        $milkVehicleEntryQlty = new TblMilkVehicleEntryQlty();
                        $milkVehicleEntryQlty->trip_code = $trip_code;
                        $milkVehicleEntryQlty->plant_code = $data['organization_code'];
                        $milkVehicleEntryQlty->union_code = $milkVehicleEntryQlty->plantCode->union_code;
                        $milkVehicleEntryQltyData = $milkVehicleEntryQlty->getMilkVehicleEntryQlty();
                        if ($milkVehicleEntryQltyData['validation']) {
                            $tripArray['msg'] = 'Quality not Done or exceeded time limit for selected trip.';
                        } else {
                            $tripArray['msg'] = '';
                        }
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
                            Yii::$app->general->setVehicleTripTrackingDetail($tripModel, $remarks);
                        }
                    }
                }
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

}
