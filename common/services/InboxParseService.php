<?php

namespace common\services;

use app\modules\syncutility\models\TblInbox;
use app\models\GeneralModel;
use app\modules\syncutility\models\TblSyncLog;
use app\modules\syncutility\models\TblInboxConstraint;
use app\modules\collection\models\TblBmcCollectionNotExist;
use app\modules\collection\models\TblMilkCollectionNotExists;
use app\modules\organisation\models\TblBmcMilkType;
use app\modules\syncutility\models\TblInboxParsingCount;
use Yii;
use yii\helpers\Json;

class InboxParseService {

    public function InboxParsing() {
        try {
            $successCount = 0;
            $errorCount = 0;
            $verifyCountModel = new TblInboxParsingCount();
            $verifyCountModel->total_count = 0;
            $verifyCountModel->success_count = 0;
            $verifyCountModel->error_count = 0;
            $pick_datetime = date('Y-m-d H:i:s');
            $verifyCountModel->created_at = $pick_datetime;

            $unique_key = 'x_col1';
            $model = new TblInbox();
            $modelData = $model->getData();
            $i = 1;
            if (!empty($modelData)) {
                $version_ignore_tables = ['tbl_product_sale', 'tbl_product_sale_transaction'];
                $ignore_tables = ['tbl_product_stock', 'tbl_product_stock_transaction', 'tbl_product_receipt', 'tbl_product_receipt_transaction'];
                $tableWiseUniqueKeys = [
                    'tbl_member' => 'member_code',
                ];
                $version_no = 0;
                $update_ids = array_column($modelData, 'uuid');
                $model->updateAll(['picked_datetime' => $pick_datetime], ['uuid' => $update_ids]);

                $verifyCountModel->total_count = count($modelData);
                $verifyCountModel->updated_at = date('Y-m-d H:i:s');
                $verifyCountModel->save();

                foreach ($modelData as $transaction_data) {
                    try {
                        $process_record = TRUE;
                        $delete = [];
                        $childModel = [];
                        $delete[] = $transaction_data;
                        $syncLogModel = new TblSyncLog();
                        $syncLogModel->setAttributes($transaction_data->attributes);
                        $childModel[] = $syncLogModel;
                        if (in_array($transaction_data->table_name, $ignore_tables)) {
                            $process_record = FALSE;
                        } else if (in_array($transaction_data->table_name, $version_ignore_tables)) {
                            $version_no = (int) str_replace('d_', '', $transaction_data->version_no);
                            if ($version_no <= 100) {
                                $process_record = FALSE;
                            }
                        }
                        if ($process_record) {
                            $model_name = str_replace(' ', '', ucwords(str_replace('_', ' ', $transaction_data->table_name)));
                            $model_name = Yii::$app->path->define($model_name);
                            $model = new $model_name();
                            $json = $transaction_data->json_text;
                            $json = (array) json_decode($json);
                            $json = Yii::$app->general->camelCaseToUnderscore($json);
                            $model->setAttributes($json);
                            $unique_key = isset($tableWiseUniqueKeys[$transaction_data->table_name]) ? $tableWiseUniqueKeys[$transaction_data->table_name] : $unique_key;

                            $is_delete = (strtoupper($transaction_data->operation) == 'DELETE');

                            /* find record based on operation type */
                            if ($model->hasAttribute($unique_key) && !empty($model->$unique_key)) {
                                $unique_value = $model->$unique_key;
                                $model_count = $model->find()->where([$unique_key => $unique_value])->count();
                                $model_count = (int) $model_count;
                                if ($model_count != 0) {
                                    $model_data = $model->find()->where([$unique_key => $unique_value])->one();
                                    if (!empty($model_data)) {
                                        $model = $model_data;
                                        $history = $model_name . 'History';
                                        $historyModel = new $history();
                                        Yii::$app->operation->history($model, $historyModel, $is_delete ? 'DELETE' : 'UPDATE');
                                        if (!empty($historyModel)) {
                                            $childModel[] = $historyModel;

                                            if ($is_delete) {
                                                $delete[] = $model;
                                            }
                                        }
                                        $model->setAttributes($json);
                                    }
                                } else if ($is_delete) {
                                    /* Record NOT found for delete */
                                    $errorCount++;
                                    $transaction_data->error_log = "Delete failed: Record not found for {$transaction_data->table_name} ({$unique_key} = {$unique_value})";
                                    $transaction_data->error_timestamp = date('Y-m-d H:i:s');
                                    $transaction_data->data_post_status = 3;
                                    $transaction_data->save();
                                    continue;
                                }
                            }

                            /* update record if already available */
                            $model->scenario = 'androidsync';
                            $model = Yii::$app->general->SetDataType($model);
                            if ($model->validate()) {

                                if (isset($model->is_sentbox)) {
                                    $model->is_sentbox = false;
                                }
                                if ($model->hasAttribute('originating_type')) {
                                    $model->originating_type = 23;
                                    if ($transaction_data->device_id == 'AMUL' . $transaction_data->source_org_id . 'AMCS') {
                                        $model->originating_type = 25;
                                    }
                                }

                                if (isset($model->saveChildRecords) && $model->saveChildRecords == true) {
                                    $model->setTransactionData($model, $json, $childModel);
                                    if ($model->hasErrors()) {
                                        $errorCount++;
                                        $errors = [];
                                        foreach ($model->getErrors() as $attr => $err) {
                                            $errors[] = implode(", ", $err);
                                        }
                                        $transaction_data->error_log = implode("; ", $errors);
                                        $transaction_data->error_timestamp = date('Y-m-d H:i:s');
                                        $transaction_data->data_post_status = 3;
                                        $transaction_data->save();
                                        continue;
                                    }
                                }
                                if (isset($model->saveDeleteChildRecords) && $model->saveDeleteChildRecords == true) {
                                    $model->setTransactionSaveDeleteData($model, $json, $childModel, $delete);
                                }

                                if ($transaction_data->table_name == 'tbl_bmc_collection' || $transaction_data->table_name == 'tbl_milk_collection') {
                                    $model->scenario = 'androidsync_coll';
                                    if (!$model->validate()) {
                                        if ($is_delete) {
                                            $errorCount++;
                                            $transaction_data->error_log = Json::encode($model->getErrors());
                                            $transaction_data->error_timestamp = date('Y-m-d H:i:s');
                                            $transaction_data->data_post_status = 3;
                                            $transaction_data->save();
                                            continue;
                                        }
                                        $setData = $model;
                                        if ($transaction_data->table_name == 'tbl_bmc_collection') {
                                            $model = new TblBmcCollectionNotExist();
                                            $model->attributes = $setData->attributes;
                                            $model->data_inserted_from = 'androidsync';
                                        } elseif ($transaction_data->table_name == 'tbl_milk_collection') {
                                            $model = new TblMilkCollectionNotExists();
                                            $model->attributes = $setData->attributes;
                                            $model->send_status = 0;
                                            $model->data_inserted_from = 'androidsync';
                                        }
                                    } else {
                                        if ($transaction_data->table_name == 'tbl_bmc_collection') {
                                            if ($model->hasAttribute('vehicle_no') && !empty($model->vehicle_no)) {
                                                $model->vehicle_no = str_replace('\n', '', $model->vehicle_no);
                                                $model->vehicle_no = trim(preg_replace('/\n/', '', $model->vehicle_no));
                                                $model->vehicle_no = trim(preg_replace('/\s/', '', $model->vehicle_no));
                                                $model->vehicle_no = trim(preg_replace('/\s+/', '', $model->vehicle_no));
                                            }
                                            $range = Yii::$app->general->getUnionConfiguration($model->union_code, 'buf_min_fat_range_bmc', 'PORTAL');
                                            $mapping = new TblBmcMilkType();
                                            $mapped = $mapping->find()->where(['bmc_code' => $model->bmc_code, 'is_active' => 1])->all();
                                            if (!empty($range) && !empty($mapped) && count($mapped) == 2) {
                                                $type = [];
                                                foreach ($mapped as $map) {
                                                    $type[] = $map->milk_type_code;
                                                }
                                                if (in_array(1, $type) && in_array(2, $type)) {
                                                    if ($range < $model->fat) {
                                                        $model->milk_type_code = 2;
                                                    } elseif ($range >= $model->fat) {
                                                        $model->milk_type_code = 1;
                                                    }
                                                }
                                                if (in_array(1, $type) && in_array(3, $type)) {
                                                    if ($range < $model->fat) {
                                                        $model->milk_type_code = 3;
                                                    } elseif ($range >= $model->fat) {
                                                        $model->milk_type_code = 1;
                                                    }
                                                }
                                                if (in_array(2, $type) && in_array(3, $type)) {
                                                    if ($range < $model->fat) {
                                                        $model->milk_type_code = 3;
                                                    } elseif ($range >= $model->fat) {
                                                        $model->milk_type_code = 2;
                                                    }
                                                }
                                            }
                                        } else if ($transaction_data->table_name == 'tbl_milk_collection') {
                                            if (!empty($model->other_reading)) {
                                                $model->other_reading = str_replace('\r\n', '#####', $model->other_reading);
                                                $model->other_reading = str_replace('\r', '#####', $model->other_reading);
                                                $model->other_reading = str_replace('\n', '#####', $model->other_reading);
                                                if (in_array(substr($model->member_code, -4), ['2097', '2098'])) {
                                                    $process_record = FALSE;
                                                    $model->setCleaningCalibration($model, $childModel);
                                                }
                                            }
                                        }
                                        $model->scenario = 'androidsync';
                                    }
                                }

                                if ($transaction_data->table_name == 'tbl_dcs_closing') {

                                    $model_data = $model->find()->where(['dcs_code' => $model->dcs_code, 'to_date' => $model->to_date, 'to_shift_code' => $model->to_shift_code, 'milk_type_code' => $model->milk_type_code])->one();
                                    if (!empty($model_data)) {
                                        $model = $model_data;
                                        $history = $model_name . 'History';
                                        $historyModel = new $history();
                                        Yii::$app->operation->history($model, $historyModel, 'UPDATE');
                                        $childModel[] = $historyModel;
                                        unset($json['dcs_closing_code']);
                                        $model->setAttributes($json);
                                        $model->dcs_closing_code = $model_data->dcs_closing_code;
                                    } else {
                                        $model->dcs_closing_code = (string) Yii::$app->general->getCodeAutoIncrement($model, $i);
                                    }
                                }
                                $generalModel = new GeneralModel();
                                $masterSave = [];
                                if ($process_record) {
                                    $masterSave[] = $model;
                                    if (!empty($transaction_data->syncPriority) && $transaction_data->syncPriority->is_sentbox_entry == 1 && $transaction_data->device_id != 'AMUL' . $transaction_data->source_org_id . 'AMCS') {
                                        $transaction_data->generateSentBox($masterSave);
                                    }
                                }
                                $msg = $is_delete ? ['transactional data', 'delete'] : ['transactional data', 'create'];
                                $transaction = $generalModel->saveDeleteTransaction($masterSave, $childModel, $delete, $msg, true);
                                if ($transaction != 'customRedirect') {
                                    $errorCount++;
                                    $transaction_data->error_log = !empty($transaction) ? (string) $transaction : 'error_occured';
                                    $transaction_data->error_timestamp = date('Y-m-d H:i:s');
                                    $transaction_data->data_post_status = 3;
                                    if (strstr($transaction_data->error_log, 'Cannot insert duplicate key')) {
                                        $inbox_constraint = new TblInboxConstraint();
                                        $inbox_constraint->attributes = $transaction_data->attributes;
                                        $inbox_constraint->processed_timestamp = date('Y-m-d H:i:s');
                                        $inbox_constraint->data_post_status = 3;
                                        $transaction = $generalModel->saveDeleteTransaction([$inbox_constraint], [], [$transaction_data], ['inbox constraint data', 'create']);
                                        if ($transaction != 'customRedirect') {
                                            $transaction_data->save();
                                        }
                                    } else {
                                        $transaction_data->save();
                                    }
                                } else {
                                    $successCount++;
                                }
                            } else {
                                $errorCount++;
                                $transaction_data->error_log = Json::encode($model->getErrors());
                                $transaction_data->error_timestamp = date('Y-m-d H:i:s');
                                $transaction_data->data_post_status = 3;
                                $transaction_data->save();
                            }
                        } else {
                            $errorCount++;
                            $generalModel = new GeneralModel();
                            $transaction = $generalModel->saveDeleteTransaction($childModel, [], $delete, ['transactional data', 'create'], true);
                            if ($transaction != 'customRedirect') {
                                $transaction_data->error_log = !empty($transaction) ? (string) $transaction : 'error_occured';
                                $transaction_data->error_timestamp = date('Y-m-d H:i:s');
                                $transaction_data->data_post_status = 3;
                                $transaction_data->save();
                            }
                        }
                    } catch (\Throwable $ex) {
                        $errorCount++;
                        try {
                            $transaction_data->error_log = 'Throwable Exception';
                            $transaction_data->error_timestamp = date('Y-m-d H:i:s');
                            $transaction_data->data_post_status = 3;
                            $transaction_data->save();
                        } catch (\Throwable $e) {
                            
                        }
                    }
                    $i++;
                }
                $verifyCountModel->response_datetime = date('Y-m-d H:i:s');
                $verifyCountModel->success_count = $successCount;
                $verifyCountModel->error_count = $errorCount;
                $verifyCountModel->save();
            } else {
                return false;
            }
        } catch (yii\base\Exception $e) {
            try {
                if (isset($verifyCountModel)) {
                    $verifyCountModel->x_col1 = substr($e->getMessage(), 0, 7900);
                    $verifyCountModel->response_datetime = date('Y-m-d H:i:s');
                    $verifyCountModel->success_count = isset($successCount) ? $successCount : 0;
                    $verifyCountModel->error_count = isset($errorCount) ? $errorCount : 0;
                    $verifyCountModel->save();
                }
            } catch (\Throwable $e) {
                
            }
        } catch (\Throwable $e) {
            try {
                if (isset($verifyCountModel)) {
                    $verifyCountModel->x_col1 = substr($e->getMessage(), 0, 7900);
                    $verifyCountModel->response_datetime = date('Y-m-d H:i:s');
                    $verifyCountModel->success_count = isset($successCount) ? $successCount : 0;
                    $verifyCountModel->error_count = isset($errorCount) ? $errorCount : 0;
                    $verifyCountModel->save();
                }
            } catch (\Throwable $e) {
                
            }
        }
        return true;
    }

}
