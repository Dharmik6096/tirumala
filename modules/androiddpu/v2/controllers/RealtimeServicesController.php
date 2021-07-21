<?php

namespace app\modules\androiddpu\v2\controllers;

use app\modules\androiddpu\controllers\RestController;
use Yii;
use app\modules\dcsoperation\models\TblPurchaseRate;
use app\modules\dcsoperation\models\TblPurchaseRateDetails;
use app\modules\dcsoperation\models\TblMember;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use app\modules\organisation\models\TblDcs;
use app\modules\syncutility\models\TblRealtimeSyncError;
use app\modules\dcsoperation\models\TblDcsPurchaseRate;
use app\modules\dcsoperation\models\TblDcsPurchaseRateDetails;
use app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabitity;
use app\modules\tankermovement\models\TblVehicleTrip;

class RealtimeServicesController extends RestController {

    public function actionPurchaseRate() {
        $res_data = [];
        $res_data['purchaseRate'] = NULL;
        $res_data['purchaseRateBased'] = [];
        $res_data['purchaseRateApplicability'] = NULL;
        $res_data['purchaseRateApplicabilityMultiple'] = [];
        $data = $this->post_data;
        $org_code = $data['organization_code'];
        $org_type = $data['organization_type'];
        $rate_type = !empty($data['content']['rate_type']) ? $data['content']['rate_type'] : NULL;
        $multi_applicability = TRUE;
        if ($rate_type == 'MEMBER') {
            $model = new TblPurchaseRate();
            $app_model = new TblPurchaseRateApplicability();
        } else if ($rate_type == 'BMC') {
            $model = new TblDcsPurchaseRate();
            $app_model = new TblDcsPurchaseRateApplicabitity();
        } else {
            $multi_applicability = FALSE;
            $model = new TblPurchaseRate();
            $app_model = new TblPurchaseRateApplicability();
        }
        $model->purchase_rate_code = $data['content']['purchase_rate_code'];
        $rate = $model->getRateRecord();
        if (!empty($rate)) {
            $res_data['purchaseRate'] = $rate->attributes;
            $based_data = [];
            $base_record = $rate->purchaseRateBased;
            foreach ($base_record as $b) {
                $based_data[] = $b->attributes;
            }
            $res_data['purchaseRateBased'] = $based_data;
            if ($multi_applicability) {
                $applicability_array = [];
                $app_model->purchase_rate_code = $rate->purchase_rate_code;
                $orgDetail = $this->getOrgDetail($org_type, $org_code, FALSE);
                $dcs_code = $orgDetail['dcs_code'];
                $bmc_code = $orgDetail['bmc_code'];
                $mcc_plant_code = $orgDetail['mcc_plant_code'];
                $plant_code = $orgDetail['plant_code'];
                $applicability_data = $app_model->getPendingApplicability($data['device_id'], $data['token'], $dcs_code, $bmc_code, $mcc_plant_code, $plant_code);
                foreach ($applicability_data as $applicability) {
                    $applicability_array[] = $applicability->attributes;
                }
                $res_data['purchaseRateApplicabilityMultiple'] = $applicability_array;
            } else {
                $rate->app_org_code = $org_code;
                $res_data['purchaseRateApplicability'] = !empty($rate->purchaseRateApplicability) ? $rate->purchaseRateApplicability[0]->attributes : NULL;
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionPurchaseRateDetail() {
        $data = $this->post_data;
        $org_code = $data['organization_code'];
        $org_type = $data['organization_type'];
        $orgDetail = $this->getOrgDetail($org_type, $org_code, FALSE);
        $dcs_code = !empty($orgDetail['dcs_code']) ? ',' . implode(',', $orgDetail['dcs_code']) . ',' : '0';
        $model = new TblPurchaseRateDetails();
        $model->attributes = $data['content'];
        $model->rate_type = empty($model->rate_type) ? 'MEMBER' : $model->rate_type;
        $model->rate_class = empty($model->rate_class) ? '0' : $model->rate_class;
        $res_data = Yii::$app->general->getSpData('sp_app_amcs_v2_purchase_rate_detail', [$model->purchase_rate_code, $model->milk_quality_type_code, $model->milk_type_code, $model->rate_type, $model->rate_class, $dcs_code]);
        if (!empty($res_data)) {
            $res_data = array_column($res_data, 'detail');
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionMemberDownload() {
        $data = $this->post_data;
        $model = new TblMember();
        $model->attributes = $data['content'];
        $res_data = Yii::$app->general->getSpData('sp_app_amcs_v2_member_detail', [$model->dcs_code]);
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionDownloadAcknowledgement() {
        $data = $this->post_data['content'];
        $res_data = [];
        $res_data['message'] = 'Acknowledgement Updated.';
        if (!empty($data['ack_type'])) {
            if ($data['ack_type'] == 'RATE') {
                $model = new TblPurchaseRateApplicability();
                $model->attributes = $data;
                $model->updateAll(['updated_at' => date('Y-m-d H:i:s'), 'download_date_time' => date('Y-m-d H:i:s'), 'is_download' => 0], ['purchase_rate_code' => $model->purchase_rate_code, 'dcs_code' => $model->dcs_code]);
                $model = new TblDcs();
                $model->attributes = $data;
                $model->updateAll(['updated_at' => date('Y-m-d H:i:s'), 'rate_flag' => 0, 'member_rate_code' => NULL], ['dcs_code' => $model->dcs_code]);
            } else if ($data['ack_type'] == 'MEMBER') {
                $model = new TblDcs();
                $model->attributes = $data;
                $model->updateAll(['updated_at' => date('Y-m-d H:i:s'), 'is_name_request' => 0], ['dcs_code' => $model->dcs_code]);
            }
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionRateDownloadAcknowledgement() {
        $data = $this->post_data;
        $res_data = [];
        $res_data['message'] = 'Acknowledgement Updated.';
        $rate_app_code = $data['content']['rate_app_code'];
        if ($data['content']['rate_type'] == 'BMC') {
            $query = Yii::$app->db->createCommand("insert into tbl_rate_download_ack (rate_app_code,purchase_rate_code,wef_date,shift_code,applicable_code,applicable_for,device_id,hash_key,union_code,download_date_time) select rate_app_code,purchase_rate_code,wef_date,shift_code,applicable_code,applicable_for,:device_id,:hash_key,union_code,:download_date_time from tbl_dcs_purchase_rate_applicability where rate_app_code in ($rate_app_code)");
        } else if ($data['content']['rate_type'] == 'MEMBER') {
            $query = Yii::$app->db->createCommand("insert into tbl_rate_download_ack (rate_app_code,purchase_rate_code,wef_date,shift_code,applicable_code,applicable_for,device_id,hash_key,union_code,download_date_time) select rate_app_code,purchase_rate_code,wef_date,shift_code,dcs_code,'MEMBER',:device_id,:hash_key,union_code,:download_date_time from tbl_purchase_rate_applicability where rate_app_code in ($rate_app_code)");
        }
        $query->bindValue(':device_id', $data['device_id'])
                ->bindValue(':hash_key', $data['token'])
                ->bindValue(':download_date_time', date('Y-m-d H:i:s'))
                ->execute();
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionSaveJson() {
        $unique_key = 'x_col1';
        $data = [];
        $post_data = $this->post_data;
        $success_id = [];
        $error_id = [];
        if (!empty($post_data['content'])) {
            $tr_data = $post_data['content'];
            foreach ($tr_data as $transaction_data) {
                try {
                    $model_name = str_replace(' ', '', ucwords(str_replace('_', ' ', $transaction_data['table_name'])));
                    $model_name = Yii::$app->path->define($model_name);
                    $json = $transaction_data['json'];
                    $model = new $model_name();
                    $model->setAttributes($json);
                    $masterModel = [];
                    //  if (isset($transaction_data['operation_type']) && $transaction_data['operation_type'] == 'UPDATE') {
                    $primaryKey = empty($unique_key) ? $model->tableSchema->primaryKey[0] : $unique_key;
                    $key = $model->$primaryKey;
                    $model_data = $model->find()->where([$primaryKey => $key])->one();
                    if (!empty($model_data)) {
                        $model = $model_data;
                        $this->setHistoryModel($model, $model_name, $masterModel);
                        $model->setAttributes($json);
                    }
                    //  }
                    $model = $this->SetDataType($model);
                    $model->scenario = 'androidsync';
                    $masterModel[] = $model;
                    $transaction = $this->generalModel->saveTransaction($masterModel, ['transactional data', 'create']);
                    if ($transaction == 'customRedirect') {
                        $success_id[] = $json[$unique_key];
                    } else {
                        $error = true;
                        $saveErrorLog = false;
                        $this->setInboxError($post_data, $transaction_data, $model->getErrors(), $transaction, $error, $saveErrorLog);
                        // if ($error || !$saveErrorLog) {
                        $error_id[] = $json[$unique_key];
                        // } else {
                        //     $success_id[] = $json[$unique_key];
                        // }
                    }
                } catch (\Throwable $ex) {
                    $msg = !empty($ex->xdebug_message) ? [$ex->xdebug_message] : [];
                    $this->setInboxError($post_data, $transaction_data, $msg, 'Exception');
                    $error_id[] = $json[$unique_key];
                }
            }
        }
        $data['success_id'] = implode('#', $success_id);
        $data['error_id'] = implode('#', $error_id);
        $this->response['data'] = $data;
        return $this->response;
    }

    public function SetDataType($model) {
        $scema = $model->getTableSchema();
        foreach ($model->attributes as $key => $a) {
            if (!empty($a)) {
                $type = $scema->columns[$key]->type;
                if ($type == 'datetime') {
                    $a = Yii::$app->controls->save_datetime($a);
                }
                $model->{$key} = $a;
            }
        }
        return $model;
    }

    private function setHistoryModel($model, $model_name, &$masterModel) {
        $history = $model_name . 'History';
        $historyModel = new $history();
        Yii::$app->operation->history($model, $historyModel, 'UPDATE');
        $masterModel[] = $historyModel;
    }

    public function setInboxError($post_data, $data, $error_log, $transaction, &$error = true, &$saveErrorLog = true) {
        $modelErr = json_encode($error_log);
        $inbox_model = new TblRealtimeSyncError();
        $inbox_model->attributes = $post_data;
        $inbox_model->attributes = $data;
        $inbox_model->json_text = Yii::$app->request->getRawBody();
        $inbox_model->url = Yii::$app->request->hostInfo . Yii::$app->request->url;
        $inbox_model->validation_error = $modelErr;
        $inbox_model->response_status = 0;
        $msg = Yii::$app->session->getFlash('success');
        $error_msg = $msg['message'];
        $inbox_model->transaction_error = 'Type:' . $transaction . ',' . 'Message' . ':' . $msg['type'] . '-' . $error_msg;
        $transaction = $this->generalModel->saveTransaction([$inbox_model], ['Inbox Error', 'create']);
        if ($transaction == 'customRedirect') {
            $saveErrorLog = true;
        }
    }

    public function actionGenerateTrip() {
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
                if ($model->validate()) {
                    $result = $model->setModel();
                    if ($result[0]) {
                        $transaction = $this->generalModel->saveTransaction($result[1], ['Vehicle Trip', 'create']);
                        if ($transaction == 'customRedirect') {
                            $this->response['data'] = $result[2];
                        }
                    } else if ($result[3]) {
                        $this->response['data'] = $result[2];
                    }
                }
            }
        }
        return $this->response;
    }

}
