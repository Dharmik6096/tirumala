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

class RealtimeServicesController extends RestController {

    public function actionPurchaseRate() {
        $res_data = [];
        $res_data['purchaseRate'] = '';
        $res_data['purchaseRateBased'] = [];
        $res_data['purchaseRateApplicability'] = '';
        $data = $this->post_data;
        $org_code = $data['organization_code'];
        $org_type = $data['organization_type'];
        $model = new TblPurchaseRate();
        $model->purchase_rate_code = $data['content']['purchase_rate_code'];
        $rate = $model->getRateRecord();
        if (!empty($rate)) {
            $res_data['purchaseRate'] = $rate->attributes;
            $based_date = [];
            $base_record = $rate->purchaseRateBased;
            foreach ($base_record as $b) {
                $based_date[] = $b->attributes;
            }
            $res_data['purchaseRateBased'] = $based_date;
            $rate->app_org_code = $org_code;
            $res_data['purchaseRateApplicability'] = !empty($rate->purchaseRateApplicability) ? $rate->purchaseRateApplicability[0]->attributes : "";
        }
        $this->response['data'] = $res_data;
        return $this->response;
    }

    public function actionPurchaseRateDetail() {
        $data = $this->post_data;
        $model = new TblPurchaseRateDetails();
        $model->attributes = $data['content'];
        $res_data = Yii::$app->general->getSpData('sp_app_amcs_v2_purchase_rate_detail', [$model->purchase_rate_code, $model->milk_quality_type_code, $model->milk_type_code]);
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
                $model->updateAll(['updated_at' => date('Y-m-d H:i:s'), 'rate_flag' => 0], ['dcs_code' => $model->dcs_code]);
            } else if ($data['ack_type'] == 'MEMBER') {
                $model = new TblDcs();
                $model->attributes = $data;
                $model->updateAll(['updated_at' => date('Y-m-d H:i:s'), 'is_name_request' => 0], ['dcs_code' => $model->dcs_code]);
            }
        }
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
                    if (isset($transaction_data['operation_type']) && $transaction_data['operation_type'] == 'UPDATE') {
                        $primaryKey = empty($unique_key) ? $model->tableSchema->primaryKey[0] : $unique_key;
                        $key = $model->$primaryKey;
                        $model_data = $model->findOne($key);
                        if (!empty($model_data)) {
                            $model = $model_data;
                            $this->setHistoryModel($model, $model_name, $masterModel);
                            $model->setAttributes($json);
                        }
                    }
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
        $childModel[] = $historyModel;
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

}
