<?php

namespace app\modules\payment\models;

use Yii;
use app\modules\transporter\models\TblTransporter;
use yii\helpers\ArrayHelper;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\organisation\models\TblMccPlant;
use app\modules\payment\models\TblTransporterPaymentDetail;
use app\modules\organisation\models\TblUnions;
use app\modules\transporter\models\TblBillingType;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblPlant;
use app\modules\transporter\models\TblVehicleMaster;

/**
 * This is the model class for table "tbl_transporter_payment".
 *
 * @property integer $transporter_payment_code
 * @property string $union_code
 * @property string $transporter_code
 * @property integer $transporter_type
 * @property string $from_date
 * @property string $to_date
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
 * @property string $no_of_days
 * @property string $total_kms
 * @property string $avg_rate
 * @property string $total_qty
 * @property string $total_amount
 * @property string $total_deduction
 * @property string $total_addition
 * @property string $net_amount
 * @property string $previous_hold
 * @property string $previous_due
 * @property string $hold_amount
 * @property string $adjust_amount
 * @property string $adjust_remark
 * @property string $final_amount
 * @property string $bank_name
 * @property string $bank_code
 * @property string $branch_name
 * @property string $branch_code
 * @property string $ifsc
 * @property string $bank_account_no
 * @property integer $is_verified
 * @property string $payment_date
 * @property string $status
 * @property string $disburse_amount
 * @property string $disburse_date
 * @property string $utr_no
 * @property string $reference_no
 * @property string $route_code
 * @property string $process_date
 * @property string $reject_reason
 * @property string $bank_status
 * @property string $payment_transaction_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblTransporterPayment extends \app\models\ChildModel {

    public $vendor_code, $final_pay, $route_name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_transporter_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['adjust_amount', 'transporter_type'], 'default', 'value' => 0],
                [['union_code', 'plant_code', 'from_date', 'to_date'], 'required', 'on' => 'paymentprocess'],
                [['union_code', 'from_date', 'to_date', 'transporter_code'], 'required', 'on' => 'sec_paymentprocess'],
                [['union_code', 'transporter_code', 'adjust_remark', 'bank_name', 'bank_code', 'branch_name', 'branch_code', 'ifsc', 'bank_account_no', 'status', 'utr_no', 'reference_no', 'route_code', 'reject_reason', 'bank_status', 'payment_transaction_code', 'created_by', 'updated_by', 'bmc_code', 'basic_price', 'vehicle_code', 'tds_amount'], 'safe'],
                [['transporter_type', 'is_verified', 'bill_no', 'primary_tpt_cost', 'incentive_value', 'chilling_cost', 'billing_method', 'is_day_wise', 'qty_amount', 'total_vts_kms', 'total_rejected_qty', 'total_rejected_amount', 'rejected_kg_fat', 'rejected_kg_snf', 'billing_type_code'], 'safe'],
                [['from_date', 'to_date', 'payment_date', 'disburse_date', 'process_date', 'created_at', 'updated_at', 'mcc_plant_code', 'total_penalty_amount', 'total_least_kms', 'parsing_no', 'transporter_name'], 'safe'],
                [['coll_qty', 'coll_kg_fat', 'coll_kg_snf', 'disp_qty', 'disp_kg_fat', 'disp_kg_snf', 'rec_qty', 'rec_kg_fat', 'rec_kg_snf', 'cd_qty_diff', 'cd_kg_fat_diff', 'cd_kg_snf_diff', 'rd_qty_diff', 'rd_kg_fat_diff', 'rd_kg_snf_diff', 'no_of_days', 'total_kms', 'avg_rate', 'total_qty', 'total_amount', 'total_deduction', 'total_addition', 'net_amount', 'previous_hold', 'previous_due', 'hold_amount', 'adjust_amount', 'final_amount', 'disburse_amount'], 'number'],
                [['from_date',], function ($attribute, $params) {
                    Yii::$app->general->dateRangeValidate($this, $attribute, $params, 'from_date', 'to_date');
                }, 'on' => 'paymentprocess'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'transporter_payment_code' => Yii::t('app', 'Transporter Payment Code'),
            'union_code' => Yii::t('app', 'Union'),
            'transporter_code' => Yii::t('app', 'Transporter'),
            'transporter_name' => Yii::t('app', 'Transporter'),
            'transporter_type' => Yii::t('app', 'Billing Type'),
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
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
            'no_of_days' => Yii::t('app', 'No. Of Days'),
            'total_kms' => Yii::t('app', 'Total KM'),
            'avg_rate' => Yii::t('app', 'Avg.Rate'),
            'total_qty' => Yii::t('app', 'Milk Qty.'),
            'total_amount' => Yii::t('app', 'Variable Amount'),
            'total_deduction' => Yii::t('app', 'Total Deduction'),
            'total_addition' => Yii::t('app', 'Total Addition'),
            'net_amount' => Yii::t('app', 'Net Amount'),
            'previous_hold' => Yii::t('app', 'Previous Hold'),
            'previous_due' => Yii::t('app', 'Previous Due'),
            'hold_amount' => Yii::t('app', 'Hold Amount'),
            'adjust_amount' => Yii::t('app', 'Adjust Amount'),
            'adjust_remark' => Yii::t('app', 'Adjust Remarks'),
            'final_amount' => Yii::t('app', 'Final Amount'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'bank_code' => Yii::t('app', 'Bank Code'),
            'branch_name' => Yii::t('app', 'Branch Name'),
            'branch_code' => Yii::t('app', 'Branch Code'),
            'ifsc' => Yii::t('app', 'Ifsc'),
            'bank_account_no' => Yii::t('app', 'Bank Account No'),
            'is_verified' => Yii::t('app', 'Is Verified'),
            'payment_date' => Yii::t('app', 'Payment Date'),
            'status' => Yii::t('app', 'Status'),
            'disburse_amount' => Yii::t('app', 'Disburse Amount'),
            'disburse_date' => Yii::t('app', 'Disburse Date'),
            'utr_no' => Yii::t('app', 'Utr No'),
            'reference_no' => Yii::t('app', 'Reference No'),
            'route_code' => Yii::t('app', 'Route Name'),
            'process_date' => Yii::t('app', 'Process Date'),
            'reject_reason' => Yii::t('app', 'Reject Reason'),
            'bank_status' => Yii::t('app', 'Bank Status'),
            'payment_transaction_code' => Yii::t('app', 'Payment Transaction Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'vendor_code' => Yii::t('app', 'V.CODE'),
            'final_pay' => Yii::t('app', 'Final Amount'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bill_no' => Yii::t('app', 'Bill No.'),
            'primary_tpt_cost' => Yii::t('app', 'Primary Transportaion'),
            'incentive_value' => Yii::t('app', 'Incentive Value'),
            'chilling_cost' => Yii::t('app', 'Chilling Cost'),
            'billing_method' => Yii::t('app', 'Billing Method'),
            'is_day_wise' => Yii::t('app', 'KM Rate Method'),
            'qty_amount' => Yii::t('app', 'Cost Per Ltr.'),
            'total_vts_kms' => Yii::t('app', 'VTS KM'),
            'total_rejected_qty' => Yii::t('app', 'Rejected Qty'),
            'total_rejected_amount' => Yii::t('app', 'Rejection Amount'),
            'rejected_kg_fat' => Yii::t('app', 'Rejected Kg FAT'),
            'rejected_kg_snf' => Yii::t('app', 'Rejected Kg SNF'),
            'total_penalty_amount' => Yii::t('app', 'Penalty Amount'),
            'total_least_kms' => Yii::t('app', 'Least KM'),
            'vehicle_code' => Yii::t('app', 'Vehicle No.'),
            'fixed_rent' => Yii::t('app', 'Fixed Charge'),
            'fuel_consumption' => Yii::t('app', 'Consumption(Ltr.)'),
            'vehicle_average' => Yii::t('app', 'Mileage'),
            'fuel_rate' => Yii::t('app', 'Fuel Rate'),
            'fixed_amount' => Yii::t('app', 'Fixed Amount'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'plant_code' => Yii::t('app', 'Plant'),
            'billing_type_code' => Yii::t('app', 'Billing Method'),
            'parsing_no' => Yii::t('app', 'Vehicle No.'),
        ];
    }

    public function getTransporterCode() {
        return $this->hasOne(TblTransporter::className(), ['transporter_code' => 'transporter_code']);
    }

    public function getRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getPaymentDetail() {
        return $this->hasOne(TblTransporterPaymentDetail::className(), ['transporter_payment_code' => 'transporter_payment_code']);
    }

    public function getdatewiseBmcList($union_code, $plant_code, $mcc_plant_code, $from_date, $to_date, $check_condition = 'Yes') {
        $from_date = date('Y-m-d', strtotime($from_date));
        $to_date = date('Y-m-d', strtotime($to_date));
        if($check_condition == 'Yes'){
            $exclude = TblTransporterPayment::find()->select(['bmc_code'])
                    ->where(['transporter_type' => 0, 'union_code' => $union_code])
                    ->andWhere(['not in', 'status', ['processed']])
                    ->andWhere(['or',
                    ['or',
                        ['between', 'from_date', $from_date, $to_date],
                        ['between', 'to_date', $from_date, $to_date]
                ],
                    ['or',
                    "'$from_date' BETWEEN [from_date] AND [to_date]",
                    "'$to_date' BETWEEN [from_date] AND [to_date]"
            ]]);
        }

        $query = TblDcsBmc::find()->select(['bmc_code', 'bmc_name'])
                ->where(['union_code' => $union_code, 'is_active' => 1])
                ->andWhere(['plant_code' => $plant_code, 'mcc_plant_code' => $mcc_plant_code]);
        if($check_condition == 'Yes'){
            $query->andWhere(['not in', 'bmc_code', $exclude]);
        }

        if (Yii::$app->session->get('BMC') !== '') {
            $query->andWhere(['bmc_code' => explode(',', Yii::$app->session->get('BMC'))]);
        } else if (Yii::$app->session->get('MCC') !== '') {
            $query->andWhere(['mcc_plant_code' => explode(',', Yii::$app->session->get('MCC'))]);
        } else if (Yii::$app->session->get('Plant') !== '') {
            $query->andWhere(['plant_code' => explode(',', Yii::$app->session->get('Plant'))]);
        }

        $value = $query->all();
        $value = ArrayHelper::map($value, 'bmc_code', 'bmc_name');
        return $value;
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getVehicleCode() {
        return $this->hasOne(TblVehicleMaster::className(), ['vehicle_code' => 'vehicle_code']);
    }

    public function getBillingTypeCode() {
        return $this->hasOne(TblBillingType::className(), ['billing_type_code' => 'billing_type_code']);
    }

    public function getdatewiseTransportersList($union_code, $from_date, $to_date) {
        $from_date = date('Y-m-d', strtotime($from_date));
        $to_date = date('Y-m-d', strtotime($to_date));

        $exclude = TblTransporterPayment::find()->select(['transporter_code'])
                ->where(['transporter_type' => 1, 'union_code' => $union_code])
                ->andWhere(['not in', 'status', ['processed']])
                ->andWhere(['or',
                ['or',
                    ['between', 'from_date', $from_date, $to_date],
                    ['between', 'to_date', $from_date, $to_date]
            ],
                ['or',
                "'$from_date' BETWEEN [from_date] AND [to_date]",
                "'$to_date' BETWEEN [from_date] AND [to_date]"
        ]]);

        $query = TblTransporter::find()->select(['transporter_code', 'transporter_name', 'vendor_code'])
                ->where(['union_code' => $union_code, 'is_active' => 1])
                ->andWhere(['not in', 'transporter_code', $exclude]);

        $value = $query->all();
        $value = ArrayHelper::map($value, 'transporter_code', function ($value) {
                    return $value['transporter_name'] . '(' . $value['vendor_code'] . ')';
                });
        return $value;
    }

    public function getMccList($union_code, $plant_code) {

        $query = TblMccPlant::find()->select(['mcc_plant_code'])
                ->where(['union_code' => $union_code, 'is_active' => 1])
                ->andWhere(['plant_code' => $plant_code]);

        if (Yii::$app->session->get('MCC') !== '') {
            $query->andWhere(['mcc_plant_code' => explode(',', Yii::$app->session->get('MCC'))]);
        } else if (Yii::$app->session->get('Plant') !== '') {
            $query->andWhere(['plant_code' => explode(',', Yii::$app->session->get('Plant'))]);
        }

        $value = $query->all();
        $value = !empty($value) ? array_column($value, 'mcc_plant_code') : [];
        return $value;
    }

}
