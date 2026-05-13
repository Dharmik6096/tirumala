<?php

namespace app\modules\webservice\eipl\v1\controllers;

use app\modules\webservice\eipl\controllers\MasterController;
use Yii;
use yii\web\Response;
use app\modules\tankermovement\models\TblBmcMilkDispatch;
use app\modules\tankermovement\models\TblBmcMilkDispatchTxn;
use app\modules\tankermovement\models\TblBmcDispatchStock;
use app\modules\tankermovement\models\TblVehicleTripDetail;
use app\modules\configuration\models\TblConfig;
use app\modules\configuration\models\TblMilkQualityParamRange;
use app\modules\tankermovement\models\TblBmcMilkDispatchSearch;
use app\modules\tankermovement\models\TblBmcMilkDispatchTxnSearch;
use app\modules\tankermovement\models\TblConfigTxnResult;
use app\modules\tankermovement\models\TblVehicleTrip;
use app\modules\organisation\models\TblBmcSilosInfo;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\tankermovement\models\TblBmcDispatchStockHistory;
use app\modules\tankermovement\models\TblQtyDiffType;
use app\modules\tankermovement\models\TblVehicleTripHistory;
use app\modules\transporter\models\TblVehicleCompartmentDetail;
use app\modules\configuration\models\TblUnionRatechartRange;
use yii\helpers\ArrayHelper;

class BmcMilkDispatchController extends MasterController {

    public function actionLoadData() {
        $req_data = Yii::$app->request->getRawBody();
        $trip_code = !empty($req_data['trip_code']) ? $req_data['trip_code'] : null;
        $bmc_code = !empty($req_data['bmc_code']) ? $req_data['bmc_code'] : null;

        if (empty($trip_code) || empty($bmc_code)) {
            $this->response->setStatusCode($this->eiplResponseCode->statusError);
            $this->response->setMessage(['Trip code and Bmc code are required.']);
            return $this->response;
        }

        $tripCombined = TblVehicleTripDetail::getTripDispatchDetail($trip_code, $bmc_code);

        if (empty($tripCombined)) {
            $this->response->setStatusCode($this->eiplResponseCode->statusError);
            $this->response->setMessage(['Trip or Dispatch details not found.']);
            return $this->response;
        }

        $union_code = $tripCombined['union_code'];

        $dispatchDetail = [
            'destination_type' => $tripCombined['destination_type'],
            'destination_code' => $tripCombined['destination_code'],
            'destination_name' => '',
            'trip_code' => $tripCombined['trip_code'],
            'vehicle_code' => $tripCombined['vehicle_code'],
            'transaction_date' => $tripCombined['transaction_date'],
            'union_code' => $tripCombined['union_code'],
        ];

        if (!empty($dispatchDetail['destination_type'])) {
            $destCacheKey = 'bmc_dispatch_dest_name_' . $dispatchDetail['destination_type'] . '_' . $dispatchDetail['destination_code'];
            $destName = null;
            if (Yii::$app->has('redis')) {
                $redis = Yii::$app->get('redis');
                $destName = $redis->get($destCacheKey);
            }
            if ($destName === null) {
                $columnData = Yii::$app->general->getColumnName($dispatchDetail['destination_type']);
                $relName = $columnData['rel'] . 'Dest';
                $vtdModel = new TblVehicleTripDetail();
                $vtdModel->destination_code = $dispatchDetail['destination_code'];
                $destName = !empty($vtdModel->$relName) ? $vtdModel->$relName->{$columnData['name']} : '';
                if (Yii::$app->has('redis')) {
                    $redis->setex($destCacheKey, 86400, $destName);
                }
            }
            $dispatchDetail['destination_name'] = $destName;
        }

        $txn = new TblBmcMilkDispatchTxn();
        $txn->trip_code = $tripCombined['trip_code'];
        $txn->vehicle_code = $tripCombined['vehicle_code'];
        $dispatchDetail['chamber_capacities'] = $txn->getCompartmentWiseDispatchData();
        
        $vehicleCacheKey = 'bmc_dispatch_vehicle_capacity_' . $tripCombined['vehicle_code'];
        $totalVehicleCapacity = null;
        if (Yii::$app->has('redis')) {
            $redis = Yii::$app->get('redis');
            $totalVehicleCapacity = $redis->get($vehicleCacheKey);
        }
        if ($totalVehicleCapacity === null) {
            $totalVehicleCapacity = TblVehicleCompartmentDetail::find()->where(['vehicle_code' => $tripCombined['vehicle_code']])->sum('capacity');
            if (Yii::$app->has('redis')) {
                $redis->setex($vehicleCacheKey, 86400, $totalVehicleCapacity);
            }
        }
        $dispatchDetail['totalVehicleCapacity'] = $totalVehicleCapacity ?? 0;

        $bmcMilkDispatch = new TblBmcMilkDispatch();
        $bmcMilkDispatch->bmc_code = $bmc_code;
        $stock_detail = $bmcMilkDispatch->getFromDateToDate();
        
        $stockDetail = [
            'fromDateTime' => $stock_detail['from_datetime'] ?? null,
            'toDateTime' => $stock_detail['to_datetime'] ?? null,
            'physicalStockOnly' => $stock_detail['physical_stock_only'] ?? 0,
            'stockData' => $stock_detail['stock_data'] ?? [],
            'mappedStockData' => $stock_detail['stock_detail'] ?? [],
        ];

        $masterCacheKey = 'bmc_dispatch_master_union_' . $union_code . '_bmc_' . $bmc_code;
        $configCacheKey = 'bmc_dispatch_config_union_' . $union_code;

        $master = null;
        $configData = null;
        if (Yii::$app->has('redis')) {
            $redis = Yii::$app->get('redis');
            $master = json_decode($redis->get($masterCacheKey), true);
            $configData = json_decode($redis->get($configCacheKey), true);
        }

        if (empty($master)) {
            $animal_types = TblAnimalType::find()->select(['animal_type_code', 'animal_type_name'])->where(['is_active' => 1])->asArray()->all();
            $atCodes = ArrayHelper::getColumn($animal_types, 'animal_type_code');

            $primaryRanges = TblMilkQualityParamRange::find()
                ->select(['min_fat', 'max_fat', 'min_snf', 'max_snf', 'min_clr', 'max_clr', 'animal_type_code'])
                ->where(['union_code' => $union_code, 'process_name' => 'BMC_MILK_DISPATCH', 'org_type' => 'BMC', 'org_code' => $bmc_code, 'animal_type_code' => $atCodes])
                ->asArray()->all();
            $primaryRangeMap = ArrayHelper::index($primaryRanges, 'animal_type_code');

            $fallbackRanges = TblUnionRatechartRange::find()
                ->select(['min_fat', 'max_fat', 'min_snf', 'max_snf', 'min_clr', 'max_clr', 'animal_type_code'])
                ->where(['union_code' => $union_code, 'config_for' => 'BMC', 'animal_type_code' => $atCodes])
                ->asArray()->all();
            $fallbackRangeMap = ArrayHelper::index($fallbackRanges, 'animal_type_code');

            foreach ($animal_types as $key => $mt) {
                $atCode = $mt['animal_type_code'];
                $range = $primaryRangeMap[$atCode] ?? $fallbackRangeMap[$atCode] ?? null;
                if ($range) {
                    $animal_types[$key]['quality_ranges'] = [
                        'min_fat' => (float)$range['min_fat'],
                        'max_fat' => (float)$range['max_fat'],
                        'min_snf' => (float)$range['min_snf'],
                        'max_snf' => (float)$range['max_snf'],
                        'min_clr' => (float)$range['min_clr'],
                        'max_clr' => (float)$range['max_clr'],
                    ];
                }
            }

            $is_clr_input_master = Yii::$app->general->getCheckBmcConfiguration($union_code, 'is_clr_input', $bmc_code, 'BMC', 'BMC_DISPATCH_CONFIG');
            if ($is_clr_input_master == '') {
                $is_clr_input_master = Yii::$app->general->getUnionConfiguration($union_code, 'is_clr_input', 'PORTAL');
            }

            $master = [
                'silos' => TblBmcSilosInfo::find()->select(['bmc_silos_info_code', 'silo_no', 'description'])->where(['bmc_code' => $bmc_code, 'is_active' => 1])->asArray()->all(),
                'animal_types' => $animal_types,
                'milk_quality_types' => TblMilkQualityType::find()->select(['milk_quality_type_code', 'milk_quality_type_name'])->where(['is_active' => 1])->asArray()->all(),
                'qty_diff_types' => TblQtyDiffType::find()->select(['qty_diff_type_code', 'qty_diff_type_name'])->where(['is_active' => 1])->asArray()->all(),
                'is_clr_input' => ($is_clr_input_master != '') ? (int)$is_clr_input_master : 0,
            ];
            if (Yii::$app->has('redis')) {
                $redis = Yii::$app->get('redis');
                $redis->setex($masterCacheKey, 3600, json_encode($master));
            }
        }

        if (empty($configData)) {
            $config = new TblConfig();
            $config->config_for = 'BMC';
            $config->process_name = 'BMC_DISPATCH';
            $config->config_type = 'CONTROL';
            $config_list = $config->getOrgConfigList($config->config_for, $bmc_code);
            $dynamicConfig = [];
            if (!empty($config_list)) {
                foreach ($config_list as $model) {
                    $item = $model->toArray(['config_code', 'config_name', 'control_type', 'is_adulteration']);
                    $item['config_result'] = [];
                    if ($model->control_type != 'TEXT' && !empty($model->configResult)) {
                        foreach ($model->configResult as $res) {
                            $item['config_result'][] = $res->toArray(['config_result_key', 'config_result']);
                        }
                    }
                    $dynamicConfig[] = $item;
                }
            }

            $unionConfigs = ArrayHelper::map(Yii::$app->general->getAllUnionWiseConfig($union_code, 'PORTAL'), 'config_key', 'config_result_key');
            $bmcConfigs = ArrayHelper::map(Yii::$app->general->getAllUnionWiseConfig($union_code, 'BMC'), 'config_key', 'config_result_key');

            $confige = [];
            $is_clr_input = Yii::$app->general->getCheckBmcConfiguration($union_code, 'is_clr_input', $bmc_code, '', 'BMC');
            if ($is_clr_input == '') {
                $is_clr_input = $unionConfigs['is_clr_input'] ?? 0;
            }
            $confige['clr_config'] = $is_clr_input;

            $clr_constant1 = Yii::$app->general->getCheckBmcConfiguration($union_code, 'clr_constant1', $bmc_code, 'BMC', 'BMC_MILK_DISPATCH');
            $clr_constant2 = Yii::$app->general->getCheckBmcConfiguration($union_code, 'clr_constant2', $bmc_code, 'BMC', 'BMC_MILK_DISPATCH');
            if ($clr_constant1 == '' || $clr_constant2 == '') {
                $clr_constant1 = $unionConfigs['clr_constant1'] ?? 1;
                $clr_constant2 = $unionConfigs['clr_constant2'] ?? 0;
            }
            $confige['clr_constant1'] = (float)$clr_constant1;
            $confige['clr_constant2'] = (float)$clr_constant2;
            $confige['auto_reject'] = $bmcConfigs['bmc_dispatch_auto_reject'] ?? '0';

            $extraConfigs = [
                'bmc_dispatch_with_milk_type' => $unionConfigs['bmc_dispatch_with_milk_type'] ?? '0',
                'bmc_dispatch_flush_limit' => (float) Yii::$app->general->getCheckBmcConfiguration($union_code, 'bmc_dispatch_flush_limit', $bmc_code, 'BMC', 'BMC_DISPATCH_CONFIG') ?: 0,
                'bmc_dispatch_flush_with_stock' => (($bmcConfigs['bmc_dispatch_flush_with_stock'] ?? 0) == 1),
                'dispatch_qty_mode' => $bmcConfigs['dispatch_qty_mode'] ?? null,
                'ltr_to_kg_constant' => (float) ($bmcConfigs['ltr_to_kg_constant'] ?? 1),
            ];

            $configData = [
                'confige' => $confige,
                'dynamicConfig' => $dynamicConfig,
                'extraConfigs' => $extraConfigs
            ];
            if (Yii::$app->has('redis')) {
                $redis = Yii::$app->get('redis');
                $redis->setex($configCacheKey, 86400, json_encode($configData));
            }
        }

        $this->response->setData([
            'masterDetails' => $master,
            'dispatchDetail' => $dispatchDetail,
            'stockDetail' => $stockDetail,
            'confige' => $configData['confige'],
            'dynamicConfig' => $configData['dynamicConfig'],
        ]);
        return $this->response;
    }

    public function actionSaveData() {
        $reqData = Yii::$app->request->getRawBody();

        $model = new TblBmcMilkDispatch();
        $model->scenario = 'createApi';
        $model->attributes = $reqData;
        $model->transaction_date = $reqData['transaction_date'] ?? date('Y-m-d');
        
        $bmcData = $model->bmcCode;
        if (empty($bmcData)) {
            return $this->errorResponse('Invalid BMC code.');
        }

        $model->union_code = $bmcData->union_code;
        $model->plant_code = $bmcData->plant_code;
        $model->mcc_plant_code = $bmcData->mcc_plant_code;

        if (!$model->validate()) {
            return $this->errorResponse('Validation failed for dispatch data.', $model->getErrors());
        }

        $general = Yii::$app->general;
        $model->from_date = date('Y-m-d', strtotime($model->from_date)) . ' ' . $general->getshift($model->from_shift_code);
        $model->to_date = date('Y-m-d', strtotime($model->to_date)) . ' ' . $general->getshift($model->to_shift_code);
        $model->transaction_date = date('Y-m-d', strtotime($model->transaction_date));
        $model->source_org_code = $model->bmc_code;
        $model->source_org_type = 'bmc';
        $model->originating_org_code = $model->union_code;
        $model->bmc_milk_dispatch_code = $general->getUuid();
        $model->challan_no = $model->getChallanNo();
        $model->vehicle_in_time = $model->transaction_date . ' ' . $model->vehicle_in_time;
        $model->vehicle_out_time = !empty($model->vehicle_out_time) ? $model->transaction_date . ' ' . $model->vehicle_out_time : date('Y-m-d H:i:s');
        $model->x_col1 = $general->getUuid();
        $saveModels = [];
        $saveModels[] = $model;

        $tripModel = new TblVehicleTrip();
        $tripModel->trip_code = $model->trip_code;
        $tripModel = $tripModel->getTripData();
        if ($tripModel) {
            if($model->is_last_destination == 1){
                $tripModelHistory = new TblVehicleTripHistory();
                Yii::$app->operation->history($tripModel, $tripModelHistory, 'UPDATE');
                $saveModels[] = $tripModelHistory;
                $tripModel->trip_status = 'tankerfull';
                $saveModels[] = $tripModel;
            }
            $model->driver_name = $tripModel->driver_name;
            $model->driver_contact_no = $tripModel->mobile_no;
        }

        $dispatchTxnData = !empty($reqData['dispatch_txn']) ? $reqData['dispatch_txn'] : [$reqData];
        $txnModels = [];
        $trackedStocks = [];
        $stockModelsMap = [];
        $validationFailed = false;

        $unionCode = $model->union_code;
        $bmcCode = $model->bmc_code;
        
        $configCacheKey = 'bmc_dispatch_config_union_' . $unionCode;
        $configCache = null;
        if (Yii::$app->has('redis')) {
            $redis = Yii::$app->get('redis');
            $configCache = json_decode($redis->get($configCacheKey), true);
        }
        if ($configCache && isset($configCache['extraConfigs'])) {
            $extra = $configCache['extraConfigs'];
            $dispatchWithMilkType = $extra['bmc_dispatch_with_milk_type'];
            $flushLimit = $extra['bmc_dispatch_flush_limit'];
            $flushWithStock = $extra['bmc_dispatch_flush_with_stock'];
            $qtyMode = $extra['dispatch_qty_mode'];
            $conversionConst = $extra['ltr_to_kg_constant'];
            $autoReject = $configCache['confige']['auto_reject'];
        } else {
            $dispatchWithMilkType = $general->getUnionConfiguration($unionCode,'bmc_dispatch_with_milk_type','PORTAL') ?: '0';
            $flushLimit = (float) $general->getCheckBmcConfiguration($unionCode, 'bmc_dispatch_flush_limit', $bmcCode, 'BMC', 'BMC_DISPATCH_CONFIG') ?: 0;
            $flushWithStock = $general->getUnionConfiguration($unionCode, 'bmc_dispatch_flush_with_stock', 'BMC') == 1;

            $qtyMode = $general->getUnionConfiguration($unionCode, 'dispatch_qty_mode', 'BMC');
            $conversionConst = (float) $general->getUnionConfiguration($unionCode, 'ltr_to_kg_constant', 'BMC') ?: 1;
            $autoReject = $general->getUnionConfiguration($unionCode, 'bmc_dispatch_auto_reject', 'BMC') ?: '0';
        }
        $cnt = 1;
        $cIdx = 1;
        foreach ($dispatchTxnData as $index => $txnItem) {
            $txnModel = new TblBmcMilkDispatchTxn();
            $txnModel->scenario = 'createApi';
            $txnModel->attributes = $model->attributes;
            $txnModel->attributes = $txnItem;
            $txnModel->from_datetime = $model->from_date;
            $txnModel->to_datetime = $model->to_date;
            $txnModel->bmc_code = $model->bmc_code;
            $txnModel->union_code = $model->union_code;
            $txnModel->bmc_milk_dispatch_code = $model->bmc_milk_dispatch_code;
            $txnModel->trip_code = $model->trip_code;
            $txnModel->vehicle_code = $model->vehicle_code;
            $txnModel->transaction_date = $model->transaction_date;

            $isValid = $txnModel->validate();
            if (!$isValid) $validationFailed = true;

            if ($isValid && !$validationFailed) {
                if ($dispatchWithMilkType == '1' && $txnModel->milk_type_code == '3') {
                    $txnModel->addError('milk_type_code', Yii::t('app/validation', 'Milk Type must not be Mix.'));
                    $isValid = false;
                    $validationFailed = true;
                } elseif ($dispatchWithMilkType != '1' && $txnModel->milk_type_code != '3') {
                    $txnModel->addError('milk_type_code', Yii::t('app/validation', 'Milk Type must be Mix.'));
                    $isValid = false;
                    $validationFailed = true;
                }
            }

            $stockKey = "{$txnModel->bmc_code}_{$txnModel->bmc_silos_info_code}_{$txnModel->milk_type_code}_{$txnModel->milk_quality_type_code}";

            if ($isValid && !$validationFailed) {
                if (!isset($trackedStocks[$stockKey])) {
                    $stockResult = Yii::$app->db->createCommand("{CALL sp_portal_bmcsilomilk_stock_detail (:bmc_code,:silo_code,:milk_type,:quality_type,:with_milk_type,:from_datetime,:to_datetime,:physical_stock_only)}")
                        ->bindValues([
                            ':bmc_code' => $txnModel->bmc_code,
                            ':silo_code' => $txnModel->bmc_silos_info_code,
                            ':milk_type' => $txnModel->milk_type_code,
                            ':quality_type' => $txnModel->milk_quality_type_code,
                            ':with_milk_type' => $dispatchWithMilkType,
                            ':from_datetime' => $txnModel->from_datetime,
                            ':to_datetime' => $txnModel->to_datetime,
                            ':physical_stock_only' => $txnModel->physical_stock_only,
                        ])->queryOne();

                    if (empty($stockResult)) {
                        $txnModel->addError('bmc_silos_info_code', Yii::t('app/validation', 'Silo is empty.'));
                        $isValid = false;
                        $validationFailed = true;
                    } else {
                        $trackedStocks[$stockKey] = [
                            'opening_bal' => (float)$stockResult['opening_bal'],
                            'purchase_qty' => (float)$stockResult['purchase_qty'],
                            'current_dispatch_qty' => (float)$stockResult['current_dispatch_qty'],
                            'from_date_tr' => $stockResult['from_date_tr'],
                            'current_stock' => (float)($stockResult['opening_bal'] + $stockResult['purchase_qty'] - $stockResult['current_dispatch_qty']),
                        ];
                    }
                }
            }

            if ($isValid && !$validationFailed) {
                $txnModel->opening_bal = $trackedStocks[$stockKey]['opening_bal'];
                $txnModel->purchase_qty = $trackedStocks[$stockKey]['purchase_qty'];
                $txnModel->current_dispatch_qty = ($txnModel->opening_bal + $txnModel->purchase_qty) - $trackedStocks[$stockKey]['current_stock'];
                $txnModel->from_date_tr = $trackedStocks[$stockKey]['from_date_tr'];

                $calculatedBal = $trackedStocks[$stockKey]['current_stock'] - $txnModel->dispatch_qty;
                $absCalculatedBal = number_format(abs($calculatedBal), 2, '.', '');
                $txnModel->balance_qty = number_format((float) $txnModel->balance_qty, 2, '.', '');

                if ($txnModel->qty_diff_type_code == 1) {
                    if ($calculatedBal < 0) {
                        $txnModel->addError('qty_diff_type_code', Yii::t('app/validation', 'Diff Type must be Flush.'));
                    } elseif ($txnModel->balance_qty != $absCalculatedBal) {
                        $txnModel->addError('balance_qty', Yii::t('app/validation', 'Balance qty must be ' . $absCalculatedBal . '.'));
                    } elseif ($txnModel->qty_diff != 0) {
                        $txnModel->addError('qty_diff', Yii::t('app/validation', 'Diff. Qty must be 0.'));
                    }
                } elseif ($txnModel->qty_diff_type_code == 4) {
                    if ($txnModel->qty_diff != $absCalculatedBal) {
                        $txnModel->addError('qty_diff', Yii::t('app/validation', 'Diff. Qty must be ' . $absCalculatedBal . '.'));
                    } else {
                        $actMilk = $flushWithStock ? ($txnModel->opening_bal + $txnModel->purchase_qty) : $txnModel->purchase_qty;
                        $allowFlush = ($actMilk * $flushLimit) / 100;
                        if (($txnModel->balance_qty + $txnModel->qty_diff) > $allowFlush) {
                            $txnModel->addError('balance_qty', Yii::t('app/validation', 'Adjust Balance qty as Flush Limit is ' . $flushLimit . '%'));
                        }
                    }
                } else {
                    if ($txnModel->balance_qty != 0) {
                        $txnModel->addError('balance_qty', Yii::t('app/validation', 'Balance must be 0.'));
                    }
                    if ($txnModel->qty_diff != $absCalculatedBal) {
                        $txnModel->addError('qty_diff', Yii::t('app/validation', 'Diff. Qty must be ' . $absCalculatedBal . '.'));
                    }
                }

                if ($txnModel->hasErrors()) {
                    $isValid = false;
                    $validationFailed = true;
                }
            }

            if ($isValid && !$validationFailed) {
                $trackedStocks[$stockKey]['current_stock'] = (float)$txnModel->balance_qty;
                
                $txnModel->test_report_no = $txnModel->generateTestReportNo();
                $txnModel->x_col1 = $general->getUuid();
                $txnModel->bmc_milk_dispatch_txn_code = $general->getTransactionCode($txnModel, $model->bmc_milk_dispatch_code, $cnt);
                $txnModel->qty_mode = $qtyMode;
                $txnModel->converted_qty_mode = ($qtyMode == 1) ? 0 : 1;
                $txnModel->converted_qty = ($qtyMode == 1) ? $txnModel->dispatch_qty / $conversionConst : $txnModel->dispatch_qty * $conversionConst;
                $txnModel->amount = $txnModel->dispatch_qty * $txnModel->rtpl;
                $cnt++;

                if (isset($stockModelsMap[$stockKey])) {
                    $stockModel = $stockModelsMap[$stockKey];
                    $stockModel->closing_bal += $txnModel->dispatch_qty;
                    $stockModel->qty_diff += $txnModel->qty_diff;
                    $stockModel->balance_qty = $txnModel->balance_qty;
                    $stockModel->qty_diff_type_code = $txnModel->qty_diff_type_code;
                } else {
                    $stockModel = new TblBmcDispatchStock();
                    $stockModel->attributes = $txnModel->attributes;
                    $stockModel->to_date = $model->to_date;
                    $stockModel->to_shift_code = $model->to_shift_code;
                    $stockModel->from_date = $model->from_date;
                    $stockModel->from_shift_code = $model->from_shift_code;
                    $stockModel->from_date_tr = !empty($txnModel->from_date_tr) ? date('Y-m-d H:i:s', strtotime($txnModel->from_date_tr)) : null;
                    $stockModel->transaction_date = $model->transaction_date;
                    $stockModel->closing_bal = $txnModel->dispatch_qty;
                    $existingStock = $stockModel->getStockEntry();
                    if ($existingStock && strtolower($existingStock->type) == 'dispatch') {
                        $stockModelHistory = new TblBmcDispatchStockHistory();
                        Yii::$app->operation->history($existingStock, $stockModelHistory, 'UPDATE');
                        $saveModels[] = $stockModelHistory;
                        $stockModel = $existingStock;
                        $stockModel->qty_diff = $txnModel->qty_diff;
                        $stockModel->qty_diff_type_code = $txnModel->qty_diff_type_code;
                        $stockModel->balance_qty = $txnModel->balance_qty;
                        $stockModel->closing_bal += $txnModel->dispatch_qty;
                        if (!empty($txnModel->from_date_tr)) $stockModel->from_date_tr = date('Y-m-d H:i:s', strtotime($txnModel->from_date_tr));
                    } else {
                        $stockModel->x_col1 = $general->getUuid();
                        $stockModel->bmc_dispatch_stock_code = $general->getPrimaryCode($stockModel);
                        $stockModel->purchase_qty = $txnModel->purchase_qty;
                        $stockModel->opening_bal = $txnModel->opening_bal;
                    }
                    $stockModelsMap[$stockKey] = $stockModel;
                    $saveModels[] = $stockModel;
                }

                $saveModels[] = $txnModel;

                $configDataList = !empty($txnItem['config_data']) ? $txnItem['config_data'] : (!empty($reqData['config_data']) ? $reqData['config_data'] : []);
                foreach ($configDataList as $cData) {
                    $configResult = new TblConfigTxnResult(['attributes' => $txnModel->attributes]);
                    $configResult->attributes = $cData;
                    if (isset($cData['config_result_key'])) $configResult->config_result = $cData['config_result_key'];
                    $configResult->config_for = 'BMC_DISPATCH';
                    $configResult->originating_org_code = $txnModel->bmc_code;
                    $configResult->config_txn_result_code = $general->getPrimaryCode($configResult, $cIdx, 'MOBILE');
                    $configResult->ref_code = $txnModel->bmc_milk_dispatch_txn_code;
                    $cIdx++;

                    if ($autoReject == '1') {
                        $configDetail = $configResult->configCode;
                        if ($configDetail && $configDetail->is_adulteration == 1 && $configDetail->check_value != '') {
                            $isRejected = false;
                            if (in_array($configDetail->control_type, ['RADIO', 'DROPDOWN'])) {
                                if ((int)$configDetail->check_value != (int)$configResult->config_result) $isRejected = true;
                            } elseif ($configDetail->control_type == 'TEXT' && (int)$configResult->config_result > (int)$configDetail->check_value) {
                                $isRejected = true;
                            }
                            if ($isRejected) $txnModel->is_rejected = 1;
                        }
                    }
                    $saveModels[] = $configResult;
                }
            }
            $txnModels[$index] = $txnModel;
        }

        if ($validationFailed) {
            $txnErrors = [];
            foreach ($txnModels as $index => $txn) {
                if ($txn->hasErrors()) $txnErrors["transaction_$index"] = $txn->getErrors();
            }
            return $this->errorResponse('Validation failed for transaction data.', $txnErrors);
        }
        $transaction = $this->generalModel->saveTransaction($saveModels, ['BMC Milk Dispatch', 'create']);
        if ($transaction === 'customRedirect') {
            $savedTxns = [];
            foreach ($txnModels as $txn) {
                $savedTxns[] = $txn->attributes;
            }

            $this->response->setData([
                'message' => 'Dispatch saved successfully.',
                'dispatch_data' => $model->attributes,
                'transaction_data' => $savedTxns
            ]);
            return $this->response;
        }

        $errors = [];
        foreach ($saveModels as $sModel) {
            if ($sModel->hasErrors()) $errors = array_merge($errors, $sModel->getErrors());
        }
        return $this->errorResponse('Failed to save data.', $errors);
    }

    private function errorResponse($message, $data = []) {
        $this->response->setStatusCode($this->eiplResponseCode->statusError);
        $this->response->setMessage([$message]);
        $this->response->setData($data);
        return $this->response;
    }
}