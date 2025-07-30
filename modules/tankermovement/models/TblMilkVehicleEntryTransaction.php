<?php

namespace app\modules\tankermovement\models;

use app\modules\configuration\models\TblMilkQualityParamRange;
use Yii;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;

/**
 * This is the model class for table "tbl_milk_vehicle_entry_transaction".
 *
 * @property string $milk_vehicle_entry_transaction_code
 * @property string $milk_vehicle_entry_code
 * @property string $vehicle_entry_chamber_date
 * @property string $chamber_quantity
 * @property string $grn_no
 * @property string $chamber_no
 * @property string $challan_no
 * @property integer $milk_quality_type_code
 * @property integer $milk_type_code
 * @property string $source_org_code
 * @property string $source_org_type
 * @property string $destination_code
 * @property string $destination_type
 * @property string $entry_type
 * @property string $fat
 * @property string $snf
 * @property string $clr
 * @property string $water
 * @property string $density
 * @property string $protein
 * @property string $lactose
 * @property string $freezing_point
 * @property string $mbrt
 * @property string $temp
 * @property string $acidity
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblMilkVehicleEntryTransaction extends \app\models\ChildModel {

    public $source, $destination, $process_approval_code;
    public $vehicle_entry_date, $trip_code, $receipt_at, $receipt_at_code, $dispatch_from, $dispatch_from_code, $receipt_datetime, $union_code, $is_clr_input;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_vehicle_entry_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['chamber_quantity', 'fat', 'snf', 'water', 'temp', 'milk_quality_type_code', 'milk_type_code', 'entry_type', 'gross_weight', 'tare_weight', 'gross_weight_time', 'tare_weight_time'], 'required', 'except' => ['androidsync', 'qltySubmit']],
            [['milk_vehicle_entry_transaction_code', 'milk_vehicle_entry_code', 'grn_no', 'chamber_no', 'challan_no', 'source_org_code', 'source_org_type', 'destination_code', 'destination_type', 'entry_type', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'string'],
            [['vehicle_entry_chamber_date', 'created_at', 'updated_at', 'is_qty_only', 'is_pending_merge', 'record_status', 'process_approval_code', 'trip_code', 'union_code', 'is_clr_input'], 'safe'],
            [['chamber_no', 'chamber_quantity', 'fat', 'snf', 'clr', 'water', 'density', 'protein', 'lactose', 'freezing_point', 'mbrt', 'temp', 'acidity'], 'number'],
            [['milk_quality_type_code', 'milk_type_code', 'originating_type'], 'integer'],
            [['challan_no', 'source_org_code', 'source_org_type', 'destination_type', 'destination_code'], 'required', 'when' => function ($model) {
                    return $model->entry_type == 'INDIVIDUAL';
                }, 'whenClient' => "function (attribute, value) {
                return $('#tblmilkvehicleentrytransaction-entry_type').val() == 'INDIVIDUAL';
            }", 'except' => ['androidsync', 'qltySubmit']],
            [['entry_type'], 'validateCreate', 'except' => ['androidsync', 'qltySubmit']],
            [['fat', 'snf', 'water', 'clr', 'protein', 'density', 'lactose', 'freezing_point', 'mbrt', 'temp', 'acidity'], 'default', 'value' => '0'],
            [['status', 'cron_pick_datetime', 'pick_datetime', 'response_datetime', 'response_msg', 'gross_weight', 'tare_weight', 'gross_weight_time', 'tare_weight_time'], 'safe'],
            [['status'], 'default', 'value' => 0],
            [['chamber_no'], 'default', 'value' => '1', 'when' => function($model) {
                return empty($model->trip_code);
            }, 'except' => ['androidsync']],
            [['chamber_no'], 'required', 'except' => ['androidsync']],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['gross_weight'], 'validateGrossWeight', 'except' => ['androidsync', 'qltySubmit']],
            [['tare_weight_time'], 'validateGrossTareTime', 'except' => ['androidsync', 'qltySubmit']],
            [['fat'], 'validateQualityRange', 'except' => ['androidsync', 'qltySubmit']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milk_vehicle_entry_transaction_code' => Yii::t('app', 'Milk Vehicle Entry Transaction Code'),
            'milk_vehicle_entry_code' => Yii::t('app', 'Milk Vehicle Entry Code'),
            'vehicle_entry_chamber_date' => Yii::t('app', 'Vehicle Entry Chamber Date'),
            'chamber_quantity' => Yii::t('app', 'Chamber Qty.'),
            'grn_no' => Yii::t('app', 'Grn No'),
            'chamber_no' => Yii::t('app', 'Chamber No'),
            'challan_no' => Yii::t('app', 'Challan No'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Qlty Type'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'source_org_code' => Yii::t('app', 'Source Code'),
            'source_org_type' => Yii::t('app', 'Source Type'),
            'destination_code' => Yii::t('app', 'Dest. Code'),
            'destination_type' => Yii::t('app', 'Dest. Type'),
            'entry_type' => Yii::t('app', 'Entry Type'),
            'fat' => Yii::t('app', 'FAT(%)'),
            'snf' => Yii::t('app', 'SNF(%)'),
            'clr' => Yii::t('app', 'CLR'),
            'water' => Yii::t('app', 'Water'),
            'protein' => Yii::t('app', 'Protein'),
            'density' => Yii::t('app', 'Density'),
            'lactose' => Yii::t('app', 'Lactose'),
            'freezing_point' => Yii::t('app', 'Freezing Point'),
            'mbrt' => Yii::t('app', 'Mbrt'),
            'temp' => Yii::t('app', 'Temp'),
            'acidity' => Yii::t('app', 'Acidity'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
            'record_status' => Yii::t('app', 'Record Status'),
        ];
    }

    public function getMilkType() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public function getMilkQualityType() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
    }

    public function getCustomerCodeDest() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'destination_code']);
    }

    public function getBmcCodeDest() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'destination_code']);
    }

    public function getMccPlantCodeDest() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'destination_code']);
    }

    public function getPlantCodeDest() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'destination_code']);
    }

    public function getBmcCodeSource() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'source_org_code']);
    }

    public function getMilkVehicleEntryCode() {
        return $this->hasOne(TblMilkVehicleEntry::className(), ['milk_vehicle_entry_code' => 'milk_vehicle_entry_code']);
    }

    public function validateCreate($attribute, $params) {
        if (!empty($this->milk_vehicle_entry_code) && $this->entry_type == 'CONSOLIDATED') {
            $count = $this->find()
                    ->where(['milk_vehicle_entry_code' => $this->milk_vehicle_entry_code, 'entry_type' => $this->entry_type, 'chamber_no' => $this->chamber_no, 'milk_type_code' => $this->milk_type_code, 'milk_quality_type_code' => $this->milk_quality_type_code])
                    ->andfilterWhere(['!=', 'milk_vehicle_entry_transaction_code', $this->milk_vehicle_entry_transaction_code])
                    ->count();
            if ($count > 0) {
                $this->addError('chamber_no', Yii::t('app/validation', 'Chamber Entry is already exist.'));
                return false;
            }
        } else if (!empty($this->milk_vehicle_entry_code) && $this->entry_type == 'INDIVIDUAL') {
            $count = $this->find()
                    ->where(['milk_vehicle_entry_code' => $this->milk_vehicle_entry_code, 'entry_type' => $this->entry_type, 'chamber_no' => $this->chamber_no, 'challan_no' => $this->challan_no])
                    ->andfilterWhere(['!=', 'milk_vehicle_entry_transaction_code', $this->milk_vehicle_entry_transaction_code])
                    ->count();
            if ($count > 0) {
                $this->addError('chamber_no', Yii::t('app/validation', 'Chamber Entry is already exist.'));
                return false;
            }
        }
    }

    public function validateGrossWeight($attribute, $params) {
        if (!empty($this->milk_vehicle_entry_code)) {
            $mainGrossWeight = TblMilkVehicleEntry::find()
                    ->select('gross_weight')
                    ->where(['milk_vehicle_entry_code' => $this->milk_vehicle_entry_code])
                    ->scalar();
            if (!empty($mainGrossWeight) && $mainGrossWeight < $this->gross_weight) {
                $this->addError('gross_weight', Yii::t('app/validation', 'Gross weight should not be more than first gross weight.'));
                return false;
            }
        }
    }

    // public function updateStatus($updateData, $ids) {
    //     return $this->updateAll($updateData, ['milk_vehicle_entry_transaction_code' => $ids]);
    // }
    public function updateStatus(array $updateData, $ids) {
        $idList = is_array($ids) ? $ids : [$ids];
        $setParts = [];
        $params = [];

        foreach ($updateData as $field => $value) {
            $setParts[] = "t.$field = :$field";
            $params[":$field"] = $value;
        }

        $setClause = implode(', ', $setParts);

        $whereParts = [];
        foreach ($idList as $index => $id) {
            $parts = explode('_', $id);
            if (count($parts) !== 3) {
                continue;
            }

            $params[":org_code_$index"] = $parts[0];
            $params[":org_type_$index"] = $parts[1];
            $params[":trip_code_$index"] = $parts[2];

            $whereParts[] = "(t.source_org_code = :org_code_$index AND t.source_org_type = :org_type_$index AND mve.trip_code = :trip_code_$index)";
        }

        if (empty($whereParts)) {
            return false;
        }

        $whereClause = implode(' OR ', $whereParts);

        $sql = "
            UPDATE t
            SET $setClause
            FROM tbl_milk_vehicle_entry_transaction t
            INNER JOIN tbl_milk_vehicle_entry mve ON t.milk_vehicle_entry_code = mve.milk_vehicle_entry_code
            WHERE $whereClause
        ";
        return Yii::$app->db->createCommand($sql)->bindValues($params)->execute();
    }

    public function updateMilkVehicleEntryTxnWithHistory($milkVehicleEntryQltyData, &$saveModel) {
        $milkVehicleEntryTxnDataList = $this->find()
                ->alias('mvet')
                ->joinWith(['milkVehicleEntryCode mve'])
                ->where(['mvet.chamber_no' => $milkVehicleEntryQltyData->chamber_no, 'mve.trip_code' => $milkVehicleEntryQltyData->trip_code])
                ->all();

        if (!empty($milkVehicleEntryTxnDataList)) {
            foreach ($milkVehicleEntryTxnDataList as $milkVehicleEntryTxnData) {
                $milkVehicleEntryTxnData->scenario = 'qltySubmit';
                $milkVehicleEntryTxnHistoryModel = new TblMilkVehicleEntryTransactionHistory();
                Yii::$app->operation->history($milkVehicleEntryTxnData, $milkVehicleEntryTxnHistoryModel, UPDATE);
                $milkVehicleEntryTxnData->record_status = $milkVehicleEntryQltyData->record_status;
                $saveModel[] = $milkVehicleEntryTxnHistoryModel;
                $saveModel[] = $milkVehicleEntryTxnData;
            }
        }
    }

    public function getPartyMasterCodeSource() {
        return $this->hasOne(TblPartyMaster::className(), ['party_master_code' => 'source_org_code']);
    }

    public function getPlantCodeSource() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'source_org_code']);
    }

    public function setData($model) {
        $this->destination_code = $model->receipt_at_code;
        $this->destination_type = $model->receipt_at;
        $this->source_org_code = $model->dispatch_from_code;
        $this->source_org_type = $model->dispatch_from;
    }

    public function validateGrossTareTime($attribute, $params) {
        if ($this->receipt_at == 'PLANT') {
            $milkVehicleEntryQlty = new TblMilkVehicleEntryQlty();
            $milkVehicleEntryQlty->union_code = $this->union_code;
            $milkVehicleEntryQlty->trip_code = $this->trip_code;
            $milkVehicleEntryQlty->plant_code = $this->receipt_at_code;
            $milkVehicleEntryQltyData = $milkVehicleEntryQlty->getMilkVehicleEntryQlty(TRUE);

            if ($milkVehicleEntryQltyData['lotQltyValidate'] && !empty($milkVehicleEntryQltyData['lotQltyData'])) {
                $chamberData = null;
                foreach ($milkVehicleEntryQltyData['lotQltyData'] as $lot) {
                    if ($lot['chamber_no'] == $this->chamber_no) {
                        $chamberData = $lot;
                        break;
                    }
                }
                if ($chamberData !== null) {
                    $sampleDateTime = $chamberData['sample_datetime'];
                    $sampleDateTimeStr = date('H:i', strtotime($sampleDateTime));
                    if (!preg_match('/^\d{4}-\d{2}-\d{2}/', $this->gross_weight_time) || !preg_match('/^\d{4}-\d{2}-\d{2}/', $this->tare_weight_time)) {
                        $gross_weight_time = date('Y-m-d') . ' ' . $this->gross_weight_time . ':00';
                        $tare_weight_time = date('Y-m-d') . ' ' . $this->tare_weight_time . ':00';
                    } else {
                        $gross_weight_time = $this->gross_weight_time;
                        $tare_weight_time = $this->tare_weight_time;
                    }
                    if (strtotime($gross_weight_time) >= strtotime($sampleDateTime)) {
                        $this->addError('gross_weight_time', "Gross Time must be less than sample time ($sampleDateTimeStr).");
                    }
                    if (strtotime($tare_weight_time) <= strtotime($sampleDateTime)) {
                        $this->addError('tare_weight_time', "Tare Time must be greater than sample time ($sampleDateTimeStr).");
                    }
                }
            }
        }
    }

    public function validateQualityRange($attribute, $params) {
        if (!empty($this->receipt_at) && strtolower($this->receipt_at) == 'plant' && $this->is_clr_input != '') {
            $milkQualityParamRangeModel = new TblMilkQualityParamRange();
            $milkQualityParamRangeModel->union_code = $this->union_code;
            $milkQualityParamRangeModel->process_name = 'PLANT_MILK_RECEIPT';
            $milkQualityParamRangeModel->org_type = 'PLANT';
            $milkQualityParamRangeModel->org_code = $this->receipt_at_code;
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

    public function afterSave($insert, $changedAttributes) {
        if ($insert && !empty($this->tare_weight)) {
            $tripModel = TblVehicleTrip::findOne(['trip_code' => $this->trip_code]);
            $remarks = '';
            if(!empty($tripModel)){
                $tripModel->trip_sub_status = 'milk_receipt_C' . $this->chamber_no;
                $tripModel->sub_status_time = date('Y-m-d H:i:s', strtotime($this->created_at . ' +1 second'));
                $remarks = $this->chamber_quantity . '-' . Yii::$app->general->getforeignkey($this->milkType, 'animal_type_name');
            }
            Yii::$app->general->setVehicleTripTrackingDetail($tripModel, $remarks);
        }
    }

}
