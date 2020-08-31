<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblPlant;
use app\modules\materialmanagement\models\TblCustomerMaster;

/**
 * This is the model class for table "tbl_transporter_payment_detail".
 *
 * @property integer $payment_detail_code
 * @property integer $transporter_payment_code
 * @property string $vehicle_code
 * @property string $parsing_no
 * @property string $from_type
 * @property string $from_dest
 * @property string $to_type
 * @property string $to_dest
 * @property string $coll_qty
 * @property string $coll_kg_fat
 * @property string $coll_kg_snf
 * @property string $disp_qty
 * @property string $disp_kg_fat
 * @property string $disp_kg_snf
 * @property string $rec_qty
 * @property string $rec_kg_fat
 * @property string $rec_kg_snf
 * @property string $cd_qty_diff
 * @property string $cd_kg_fat_diff
 * @property string $cd_kg_snf_diff
 * @property string $rd_qty_diff
 * @property string $rd_kg_fat_diff
 * @property string $rd_kg_snf_diff
 * @property string $avg_rate
 * @property string $morning_qty
 * @property string $evening_qty
 * @property string $qty
 * @property string $morning_kms
 * @property string $evening_kms
 * @property string $extra_kms
 * @property string $total_kms
 * @property string $km_rate
 * @property string $fuel_rate
 * @property string $fuel_consumption
 * @property string $amount
 * @property string $toll_amount
 * @property string $fastag_amount
 * @property string $fixed_amount
 * @property string $other_amount
 * @property string $total_amount
 * @property string $dispatch_date
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblTransporterPaymentDetail extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_transporter_payment_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['transporter_payment_code'], 'integer'],
            [['vehicle_code', 'parsing_no', 'from_type', 'from_dest', 'to_type', 'to_dest', 'created_by', 'updated_by'], 'string'],
            [['coll_qty', 'coll_kg_fat', 'coll_kg_snf', 'disp_qty', 'disp_kg_fat', 'disp_kg_snf', 'rec_qty', 'rec_kg_fat', 'rec_kg_snf', 'cd_qty_diff', 'cd_kg_fat_diff', 'cd_kg_snf_diff', 'rd_qty_diff', 'rd_kg_fat_diff', 'rd_kg_snf_diff', 'avg_rate', 'morning_qty', 'evening_qty', 'qty', 'morning_kms', 'evening_kms', 'extra_kms', 'total_kms', 'km_rate', 'fuel_rate', 'fuel_consumption', 'amount', 'toll_amount', 'fastag_amount', 'fixed_amount', 'other_amount', 'total_amount', 'weighing_cost'], 'number'],
            [['dispatch_date', 'created_at', 'updated_at', 'weighing_cost'], 'safe'],
            [['rejected_kg_fat', 'rejected_kg_snf', 'morning_rejected_qty', 'evening_rejected_qty', 'rejected_qty', 'rejected_amount', 'qty_amount', 'morning_vts_kms', 'evening_vts_kms', 'total_vts_kms', 'primary_tpt_cost', 'morning_late_minute', 'morning_applicable_penalty', 'morning_penalty_amount', 'evening_late_minute', 'evening_applicable_penalty', 'evening_penalty_amount', 'penalty_amount', 'morning_least_kms', 'evening_least_kms', 'total_least_kms', 'incentive_value', 'e_basic_price', 'm_basic_price', 'chilling_cost', 'basic_price'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'payment_detail_code' => Yii::t('app', 'Payment Detail Code'),
            'transporter_payment_code' => Yii::t('app', 'Transporter Payment Code'),
            'vehicle_code' => Yii::t('app', 'TANKER'),
            'parsing_no' => Yii::t('app', 'TANKER'),
            'from_type' => Yii::t('app', 'From Type'),
            'from_dest' => Yii::t('app', 'FROM'),
            'to_type' => Yii::t('app', 'To Type'),
            'to_dest' => Yii::t('app', 'TO'),
            'coll_qty' => Yii::t('app', 'Coll Qty'),
            'coll_kg_fat' => Yii::t('app', 'Coll Kg Fat'),
            'coll_kg_snf' => Yii::t('app', 'Coll Kg Snf'),
            'disp_qty' => Yii::t('app', 'Disp Qty'),
            'disp_kg_fat' => Yii::t('app', 'Disp Kg Fat'),
            'disp_kg_snf' => Yii::t('app', 'Disp Kg Snf'),
            'rec_qty' => Yii::t('app', 'Rec Qty'),
            'rec_kg_fat' => Yii::t('app', 'FAT Kg'),
            'rec_kg_snf' => Yii::t('app', 'SNF Kg'),
            'cd_qty_diff' => Yii::t('app', 'Cd Qty Diff'),
            'cd_kg_fat_diff' => Yii::t('app', 'Cd Kg Fat Diff'),
            'cd_kg_snf_diff' => Yii::t('app', 'Cd Kg Snf Diff'),
            'rd_qty_diff' => Yii::t('app', 'Rd Qty Diff'),
            'rd_kg_fat_diff' => Yii::t('app', 'Rd Kg Fat Diff'),
            'rd_kg_snf_diff' => Yii::t('app', 'Rd Kg Snf Diff'),
            'avg_rate' => Yii::t('app', 'Avg Rate'),
            'morning_qty' => Yii::t('app', 'Qty(M)'),
            'evening_qty' => Yii::t('app', 'Qty(E)'),
            'qty' => Yii::t('app', 'QTY.'),
            'morning_kms' => Yii::t('app', 'Morning(KM)'),
            'evening_kms' => Yii::t('app', 'Evening(KM)'),
            'extra_kms' => Yii::t('app', 'Extra KM'),
            'total_kms' => Yii::t('app', 'KM'),
            'km_rate' => Yii::t('app', 'RATE'),
            'fuel_rate' => Yii::t('app', 'Rate/Liter'),
            'fuel_consumption' => Yii::t('app', 'Consumption(Liter)'),
            'amount' => Yii::t('app', 'HSD AMOUNT'),
            'toll_amount' => Yii::t('app', 'TOLL'),
            'fastag_amount' => Yii::t('app', 'FASTAG'),
            'fixed_amount' => Yii::t('app', 'Fixed Amount'),
            'other_amount' => Yii::t('app', 'Other Amount'),
            'total_amount' => Yii::t('app', 'TOTAL'),
            'dispatch_date' => Yii::t('app', 'DATE'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'rejected_kg_fat' => Yii::t('app', 'Rejected Kg FAT'),
            'rejected_kg_snf' => Yii::t('app', 'Rejected Kg SNF'),
            'morning_rejected_qty' => Yii::t('app', 'Rejected Qty(M)'),
            'evening_rejected_qty' => Yii::t('app', 'Rejected Qty(E)'),
            'rejected_qty' => Yii::t('app', 'Rejected Qty'),
            'rejected_amount' => Yii::t('app', 'Rejection Amount'),
            'qty_amount' => Yii::t('app', 'Amount as per Qty'),
            'morning_vts_kms' => Yii::t('app', 'VTS KM(M)'),
            'evening_vts_kms' => Yii::t('app', 'VTS KM(E)'),
            'total_vts_kms' => Yii::t('app', 'VTS KM'),
            'primary_tpt_cost' => Yii::t('app', 'Primary Transportaion'),
            'morning_late_minute' => Yii::t('app', 'Late Min.(M)'),
            'morning_applicable_penalty' => Yii::t('app', 'Penalty(M)'),
            'morning_penalty_amount' => Yii::t('app', 'Pen.Amt(M)'),
            'evening_late_minute' => Yii::t('app', 'Late Min.(E)'),
            'evening_applicable_penalty' => Yii::t('app', 'Penalty(E)'),
            'evening_penalty_amount' => Yii::t('app', 'Pen.Amt(E)'),
            'penalty_amount' => Yii::t('app', 'Total Pen.Amt'),
            'morning_least_kms' => Yii::t('app', 'Least KM(M)'),
            'evening_least_kms' => Yii::t('app', 'Least KM(E)'),
            'total_least_kms' => Yii::t('app', 'Least KM'),
            'incentive_value' => Yii::t('app', 'Agent Incentive(%)'),
            'e_basic_price' => Yii::t('app', 'Basic Rate(M)'),
            'm_basic_price' => Yii::t('app', 'Basic Rate(E)'),
        ];
    }

    public function getBmcCodeDest() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'to_dest']);
    }

    public function getMccPlantCodeDest() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'to_dest']);
    }

    public function getPlantCodeDest() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'to_dest']);
    }

    public function getCustomerCodeDest() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'to_dest']);
    }

    public function getBmcCodeSource() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'from_dest']);
    }

    public function getMccPlantCodeSource() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'from_dest']);
    }

    public function getPlantCodeSource() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'from_dest']);
    }

    public function getCustomerCodeSource() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'from_dest']);
    }

}
