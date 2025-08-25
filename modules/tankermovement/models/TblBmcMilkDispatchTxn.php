<?php

namespace app\modules\tankermovement\models;

use app\modules\configuration\models\TblMilkQualityParamRange;
use Yii;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\organisation\models\TblBmcSilosInfo;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\tankermovement\models\TblQtyDiffType;
use app\modules\organisation\models\TblPlant;
use app\modules\transporter\models\TblVehicleCompartmentDetail;

/**
 * This is the model class for table "tbl_bmc_milk_dispatch_txn".
 *
 * @property string $bmc_milk_dispatch_txn_code
 * @property string $bmc_milk_dispatch_code
 * @property integer $milk_quality_type_code
 * @property integer $milk_type_code
 * @property string $dispatch_qty
 * @property string $qty_diff
 * @property string $balance_qty
 * @property integer $qty_diff_type_code
 * @property integer $qty_mode
 * @property string $converted_qty
 * @property integer $converted_qty_mode
 * @property string $fat
 * @property string $snf
 * @property string $clr
 * @property string $water
 * @property string $protein
 * @property string $density
 * @property string $lactose
 * @property string $rtpl
 * @property string $amount
 * @property string $freezing_point
 * @property string $temperature
 * @property string $hsn_code
 * @property string $seal_no_top
 * @property string $seal_no_bottom
 * @property string $seal_no_broken
 * @property integer $bmc_silos_info_code
 * @property integer $chamber_no
 * @property string $dip_open
 * @property string $dip_close
 * @property string $dip_diff
 * @property integer $qty_auto
 * @property integer $qlty_auto
 * @property string $milk_analyser_type_code
 * @property string $ws_code
 * @property string $qty_time
 * @property string $qlty_time
 * @property string $adt_param
 * @property string $adt_value
 * @property integer $is_rejected
 * @property string $union_code
 * @property string $plant_code
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblBmcMilkDispatchTxn extends \app\models\ChildModel {

    public $from_datetime, $to_datetime, $opening_bal, $purchase_qty, $current_dispatch_qty, $source_type, $source_code, $trip_code, $vehicle_code, $transaction_date, $physical_stock_only, $from_date_tr, $is_clr_input, $original_dispatch_qty;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bmc_milk_dispatch_txn';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        $main_rules = [
            [['milk_quality_type_code', 'milk_type_code', 'dispatch_qty', 'fat', 'snf', 'temperature', 'bmc_silos_info_code', 'chamber_no', 'qty_diff_type_code', 'qty_diff', 'balance_qty'], 'required', 'except' => ['androidsync', 'createPlantDispatch', 'createPlantDispatchUpdate']],
            [['bmc_milk_dispatch_txn_code', 'bmc_milk_dispatch_code', 'hsn_code', 'seal_no_top', 'seal_no_bottom', 'seal_no_broken', 'milk_analyser_type_code', 'ws_code', 'adt_param', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string'],
            [['milk_quality_type_code', 'milk_type_code', 'qty_diff_type_code', 'qty_mode', 'converted_qty_mode', 'bmc_silos_info_code', 'chamber_no', 'qty_auto', 'qlty_auto', 'is_rejected', 'originating_type'], 'integer'],
            [['dispatch_qty', 'qty_diff', 'balance_qty', 'converted_qty', 'fat', 'snf', 'clr', 'water', 'protein', 'density', 'lactose', 'freezing_point', 'temperature', 'dip_open', 'dip_close', 'dip_diff', 'adt_value'], 'number'],
            [['qty_time', 'qlty_time', 'created_at', 'updated_at', 'trip_code', 'vehicle_code', 'transaction_date', 'test_report_no', 'shift_of_milk', 'physical_stock_only', 'from_date_tr', 'is_clr_input', 'original_dispatch_qty', 'bmc_silos_info_code', 'milk_quality_type_code', 'animal_type_additional_code'], 'safe'],
            [['milk_type_code'], 'unique', 'targetAttribute' => ['milk_type_code', 'milk_quality_type_code', 'bmc_silos_info_code', 'chamber_no', 'bmc_milk_dispatch_code'], 'message' => Yii::t('app/validation', 'Chamber Entry for selected milk and silo has been already taken.'), 'on' => 'create'],
            [['qty_time', 'qlty_time'], 'default', 'value' => date('Y-m-d H:i:s')],
            [['qty_auto', 'qlty_auto', 'is_rejected', 'clr', 'protein', 'density', 'lactose', 'freezing_point', 'hsn_code', 'seal_no_top', 'seal_no_bottom', 'seal_no_broken', 'dip_open', 'dip_close', 'dip_diff', 'rtpl', 'amount',], 'default', 'value' => '0'],
            [['milk_type_code'], 'ValidateData', 'on' => 'create'],
            [['union_code'], 'required', 'except' => ['androidsync', 'update']],
            [['milk_quality_type_code', 'milk_type_code', 'dispatch_qty', 'fat', 'snf', 'temperature', 'chamber_no', 'animal_type_additional_code'], 'required', 'on' => 'createPlantDispatch'],
            [['dispatch_qty'], 'ValidateCapacity', 'on' => ['createPlantDispatch', 'create']],
            [['shift_of_milk'], 'string', 'max' => 25],
            [['fat'], 'validateQualityRange', 'except' => ['androidsync']],
        ];
        $client_rules = Yii::$app->customvalidation->getRules('TblBmcMilkDispatchTxn', $this->form_validation_type);
        $rules = array_merge($client_rules, $main_rules);
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'bmc_milk_dispatch_txn_code' => Yii::t('app', 'Bmc Milk Dispatch Txn Code'),
            'bmc_milk_dispatch_code' => Yii::t('app', 'Bmc Milk Dispatch Code'),
            'milk_quality_type_code' => Yii::t('app', 'Quality Type'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'dispatch_qty' => Yii::t('app', 'Dispatch Qty'),
            'qty_diff' => Yii::t('app', 'Qty Diff.'),
            'balance_qty' => Yii::t('app', 'Balance Qty'),
            'qty_diff_type_code' => Yii::t('app', 'Diff. Type'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'converted_qty' => Yii::t('app', 'Converted Qty'),
            'converted_qty_mode' => Yii::t('app', 'Converted Qty Mode'),
            'fat' => Yii::t('app', 'FAT(%)'),
            'snf' => Yii::t('app', 'SNF(%)'),
            'clr' => Yii::t('app', 'CLR'),
            'water' => Yii::t('app', 'Water'),
            'protein' => Yii::t('app', 'Protein'),
            'density' => Yii::t('app', 'Density'),
            'lactose' => Yii::t('app', 'Lactose'),
            'rtpl' => Yii::t('app', 'Rate'),
            'amount' => Yii::t('app', 'Amount'),
            'freezing_point' => Yii::t('app', 'Freez. Point'),
            'temperature' => Yii::t('app', 'Temp.'),
            'hsn_code' => Yii::t('app', 'HSN Code'),
            'seal_no_top' => Yii::t('app', 'Seal No. Top'),
            'seal_no_bottom' => Yii::t('app', 'Seal No. Bott.'),
            'seal_no_broken' => Yii::t('app', 'Seal No. Bro.'),
            'bmc_silos_info_code' => Yii::t('app', 'Silo No.'),
            'chamber_no' => Yii::t('app', 'Chamb. No.'),
            'dip_open' => Yii::t('app', 'Dip. Open'),
            'dip_close' => Yii::t('app', 'Dip. Close'),
            'dip_diff' => Yii::t('app', 'Dip. Diff.'),
            'qty_auto' => Yii::t('app', 'Qty Auto'),
            'qlty_auto' => Yii::t('app', 'Qlty Auto'),
            'milk_analyser_type_code' => Yii::t('app', 'Milk Analyser Type Code'),
            'ws_code' => Yii::t('app', 'Ws Code'),
            'qty_time' => Yii::t('app', 'Qty Time'),
            'qlty_time' => Yii::t('app', 'Qlty Time'),
            'adt_param' => Yii::t('app', 'Adt Param'),
            'adt_value' => Yii::t('app', 'Adt Value'),
            'is_rejected' => Yii::t('app', 'Is Rejected'),
            'union_code' => Yii::t('app', 'Union Code'),
            'plant_code' => Yii::t('app', 'Plant Code'),
            'mcc_plant_code' => Yii::t('app', 'Mcc Plant Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'shift_of_milk' => Yii::t('app', 'Shift Of Milk'),
            'original_dispatch_qty' => Yii::t('app', 'Original Qty'),
        ];
    }

    public function getSilosInfoCode() {
        return $this->hasOne(TblBmcSilosInfo::className(), ['bmc_silos_info_code' => 'bmc_silos_info_code']);
    }

    public function getMilkType() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public function getMilkQualityType() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
    }

    public function getQtyDiffType() {
        return $this->hasOne(TblQtyDiffType::className(), ['qty_diff_type_code' => 'qty_diff_type_code']);
    }

    public function getBmcMilkDispatchCode() {
        return $this->hasOne(TblBmcMilkDispatch::className(), ['bmc_milk_dispatch_code' => 'bmc_milk_dispatch_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getSampleBottleNo() {
        return $this->hasOne(TblConfigTxnResult::className(), ['ref_code' => 'bmc_milk_dispatch_txn_code'])
                        ->join('inner join', 'tbl_config c', "c.config_code=tbl_config_txn_result.config_code and c.config_key='sample_bottle_no' and c.config_for in ('BMC','PLANT') and c.process_name in ('BMC_DISPATCH','PLANT_DISPATCH')");
    }

    public function ValidateData() {
        $dispatch_with_milk_type = isset(Yii::$app->session->get('unionConfig')[$this->union_code]['bmc_dispatch_with_milk_type']) ? Yii::$app->session->get('unionConfig')[$this->union_code]['bmc_dispatch_with_milk_type'] : '0';
        if ($dispatch_with_milk_type == '1' && $this->milk_type_code == '3') {
            $this->addError('milk_type_code', Yii::t('app/validation', 'Milk Type must not be Mix.'));
            return;
        }
        if ($dispatch_with_milk_type != '1' && $this->milk_type_code != '3') {
            $this->addError('milk_type_code', Yii::t('app/validation', 'Milk Type must be Mix.'));
            return;
        }
        // $current_stock_date = date('Y-m-d H:i:s', strtotime('+12 hours', strtotime($this->to_datetime)));
        $query = \Yii::$app->db->createCommand("{CALL sp_portal_bmcsilomilk_stock_detail (:bmc_code,:silo_code,:milk_type,:quality_type,:with_milk_type,:from_datetime,:to_datetime,:physical_stock_only)}")
                ->bindValue(':from_datetime', $this->from_datetime)
                ->bindValue(':to_datetime', $this->to_datetime)
                ->bindValue(':bmc_code', $this->bmc_code)
                ->bindValue(':quality_type', $this->milk_quality_type_code)
                ->bindValue(':silo_code', $this->bmc_silos_info_code)
                ->bindValue(':milk_type', $this->milk_type_code)
                ->bindValue(':with_milk_type', $dispatch_with_milk_type)
                ->bindValue(':physical_stock_only', $this->physical_stock_only);
        $result = $query->queryAll();
        if (empty($result)) {
            $this->addError('bmc_silos_info_code', Yii::t('app/validation', 'Silo is empty.'));
        } else {
            $this->opening_bal = $result[0]['opening_bal'];
            $this->purchase_qty = $result[0]['purchase_qty'];
            $this->current_dispatch_qty = $result[0]['current_dispatch_qty'];
            $this->from_date_tr = $result[0]['from_date_tr'];
            $bal = ($this->opening_bal + $this->purchase_qty) - ($this->current_dispatch_qty + $this->dispatch_qty);
            // $balance = abs($bal);
            $balance = number_format((float) abs($bal), 2, '.', '');
            $this->balance_qty = number_format((float) $this->balance_qty, 2, '.', '');
            if ($this->qty_diff_type_code == 1) {
                if ($bal < 0) {
                    $this->addError('qty_diff_type_code', Yii::t('app/validation', 'Diff Type is must be Flush.'));
                } else if ($this->balance_qty != $balance) {
                    $this->addError('balance_qty', Yii::t('app/validation', 'Balance qty must be ' . $balance . '.'));
                } else if ($this->qty_diff != 0) {
                    $this->addError('qty_diff', Yii::t('app/validation', 'Diff. Qty must be 0.'));
                }
            } else if ($this->qty_diff_type_code == 4) {
                if ($this->qty_diff != $balance) {
                    $this->addError('qty_diff', Yii::t('app/validation', 'Diff. Qty must be ' . $balance . '.'));
                } else {
                    $flush_limit = (float) Yii::$app->general->getCheckBmcConfiguration($this->union_code, 'bmc_dispatch_flush_limit', $this->bmc_code, 'BMC', 'BMC_DISPATCH_CONFIG');
                    $flush_limit = $flush_limit ?: 0;
                    $bmcDispatchFlushWithStock = Yii::$app->general->getUnionConfiguration(explode(',', Yii::$app->session->get('Unions')), 'bmc_dispatch_flush_with_stock', 'BMC') == 1 ? TRUE : FALSE;
                    $stock_model = new TblBmcDispatchStock();
                    $stock_model->bmc_code = $this->bmc_code;
                    $stock_model->to_date = $this->to_datetime;
                    $stock_model->milk_type_code = $this->milk_type_code;
                    $stock_model->bmc_silos_info_code = $this->bmc_silos_info_code;
                    $stock_model->milk_quality_type_code = $this->milk_quality_type_code;
                    $stock_data = $stock_model->getStockEntry();
                    if (!empty($stock_data)) {
                        $act_milk = $bmcDispatchFlushWithStock ? ($stock_data->opening_bal + $stock_data->purchase_qty) : $stock_data->purchase_qty;
                    } else {
                        $act_milk = $bmcDispatchFlushWithStock ? ($this->opening_bal + $this->purchase_qty) : $this->purchase_qty;
                    }
                    $dispatch_milk = ($this->current_dispatch_qty + $this->dispatch_qty);
                    $allow_flush = ($act_milk * $flush_limit) / 100;
                    $total_flush = $this->balance_qty + $this->qty_diff;
                    if ($total_flush > $allow_flush) {
                        $this->addError('balance_qty', Yii::t('app/validation', 'Adjust Balance qty as Flush Limit is ' . $flush_limit . '%'));
                    }
                }
            } else {
                if ($this->balance_qty != 0) {
                    $this->addError('balance_qty', Yii::t('app/validation', 'Balance must be 0.'));
                }
                if ($this->qty_diff != $balance) {
                    $this->addError('qty_diff', Yii::t('app/validation', 'Diff. Qty must be ' . $balance . '.'));
                }
            }
        }
        $this->amount = $this->dispatch_qty * $this->rtpl;
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function ValidateCapacity() {
        $capacityLimit = TblVehicleCompartmentDetail::find()->select('capacity')->where(['vehicle_code' => $this->vehicle_code, 'compartment_no' => $this->chamber_no])->scalar();
        if (!empty($capacityLimit)) {
            $existingDispatchQty = TblBmcMilkDispatchtxn::find()
                    ->where(['bmc_milk_dispatch_code' => TblBmcMilkDispatch::find()->select('bmc_milk_dispatch_code')->where(['trip_code' => $this->trip_code])->column(), 'chamber_no' => $this->chamber_no])
                    ->sum('dispatch_qty');

            $existingDispatchQty = $existingDispatchQty ?: 0;
            $totalDispatchQty = $this->dispatch_qty + $existingDispatchQty;

            if ($totalDispatchQty > $capacityLimit) {
                $this->addError('dispatch_qty', Yii::t('app/validation', "Total dispatched quantity exceeds allowed capacity of $capacityLimit."));
            }
        } else {
            $this->addError('dispatch_qty', Yii::t('app/validation', "Chamber Capacity Not Found"));
        }
    }

    public function getCompartmentWiseDispatchData() {
        $dispatchData = TblBmcMilkDispatchtxn::find()
                ->select(['chamber_no', 'SUM(dispatch_qty) as total_qty'])
                ->where(['bmc_milk_dispatch_code' => TblBmcMilkDispatch::find()
                    ->select('bmc_milk_dispatch_code')
                    ->where(['trip_code' => $this->trip_code])
                ])
                ->groupBy('chamber_no')
                ->asArray()
                ->all();

        $chamberWiseQty = [];
        foreach ($dispatchData as $data) {
            $chamberWiseQty[$data['chamber_no']] = $data['total_qty'];
        }

        $compartmentCapacities = TblVehicleCompartmentDetail::find()
                ->select(['compartment_no', 'capacity'])
                ->where(['vehicle_code' => $this->vehicle_code])
                ->asArray()
                ->all();

        $result = [];
        foreach ($compartmentCapacities as $compartment) {
            $compartmentNo = $compartment['compartment_no'];
            $result[$compartmentNo] = [
                'total_qty' => isset($chamberWiseQty[$compartmentNo]) ? $chamberWiseQty[$compartmentNo] : 0,
                'capacity' => $compartment['capacity']
            ];
        }

        return $result;
    }

    public function afterSave($insert, $changedAttributes) {
        if ($insert) {
            $tripModel = new TblVehicleTrip();
            $bmcMilkDispatchCode = $this->bmcMilkDispatchCode;
            $tripModel->attributes = $bmcMilkDispatchCode->attributes;
            $tripModel->transaction_date = date('Y-m-d');
            $tripModel->trip_status = 'open';
            $tripModel->trip_sub_status = $bmcMilkDispatchCode['source_org_type'] . '_dispatch_C' . $this->chamber_no;
            $tripModel->sub_status_time = date('Y-m-d H:i:s', strtotime($this->created_at . ' +1 second'));
            $remarks = $this->dispatch_qty . '-' . Yii::$app->general->getforeignkey($this->milkType, 'animal_type_name');  
            $tripDetail = TblVehicleTripDetail::find()->where([
                    'challan_no' => $bmcMilkDispatchCode['challan_no'],
                    'trip_code' => $bmcMilkDispatchCode['trip_code'],
                ])->one();
            $tripDetailCode = '';
            if ($tripDetail !== null) {
                $tripDetailCode = $tripDetail->vehicle_trip_detail_code;
            }
            $trackingDetail = ['visibility_status' => 0, 'module_code' => $tripDetailCode, 'module_type' => 'tbl_vehicle_trip_detail'];
            Yii::$app->general->setVehicleTripTrackingDetail($tripModel, $trackingDetail, $remarks);
        }
    }

    public function generateTestReportNo() {
        $isBmc = !empty($this->bmc_code);
        $prefix = $isBmc ? 'B' : 'P';
        $refCode = $isBmc ? $this->bmcCode->ref_code : $this->plantCode->ref_code;
        $year = date('Y', strtotime($this->transaction_date));
        $pattern = "{$prefix}/{$refCode}/{$year}/";

        $testReportNo = $this->find()
                ->select(['test_report_no'])
                ->where(['like', 'test_report_no', $pattern . '%', false])
                ->orderBy(['test_report_no' => SORT_DESC])
                ->limit(1)
                ->scalar();

        if (!empty($testReportNo) && preg_match('/(\d{4})$/', $testReportNo, $matches)) {
            $lastIncrement = (int) $matches[1];
            $nextIncrement = $lastIncrement + 1;
        } else {
            $nextIncrement = 1;
        }

        $autoInc = str_pad($nextIncrement, 4, '0', STR_PAD_LEFT);
        return "{$prefix}/{$refCode}/{$year}/{$autoInc}";
    }

    public function validateQualityRange($attribute, $params) {
        if ($this->is_clr_input != '') {
            $for = $this->bmc_code ? 'BMC' : 'PLANT';
            $processName = $this->bmc_code ? 'BMC_MILK_DISPATCH' : 'PLANT_MILK_DISPATCH';
            $orgCode = $this->bmc_code ? $this->bmc_code : $this->plant_code;
            $milkQualityParamRangeModel = new TblMilkQualityParamRange();
            $milkQualityParamRangeModel->union_code = $this->union_code;
            $milkQualityParamRangeModel->process_name = $processName;
            $milkQualityParamRangeModel->org_type = $for;
            $milkQualityParamRangeModel->org_code = $orgCode;
            $milkQualityParamRangeModel->animal_type_code = $this->milk_type_code;
            $range = $milkQualityParamRangeModel->getQualityRange();
            if (!empty($range)) {
                if ($this->fat < $range->min_fat || $this->fat > $range->max_fat) {
                    $this->addError('fat', "FAT should be between " . $range->min_fat . " and " . $range->max_fat);
                }
                if (($this->snf < $range->min_snf || $this->snf > $range->max_snf) && $this->is_clr_input == 0) {
                    $this->addError('snf', "SNF should be between " . $range->min_snf . " and " . $range->max_snf);
                }
                if (($this->clr < $range->min_clr || $this->clr > $range->max_clr) && $this->is_clr_input == 1) {
                    $this->addError('clr', "CLR should be between " . $range->min_clr . " and " . $range->max_clr);
                }
            }
        }
    }

}
