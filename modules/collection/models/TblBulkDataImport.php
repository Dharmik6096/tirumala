<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_bulk_data_import".
 *
 * @property integer $data_import_code
 * @property string $member_code
 * @property string $dcs_code
 * @property string $customer_type
 * @property string $customer_code
 * @property string $bmc_code
 * @property integer $bmc_silos_info_code
 * @property string $date_time_of_collection
 * @property string $shift_code
 * @property integer $sample_no
 * @property integer $milk_type_code
 * @property integer $milk_quality_type_code
 * @property string $fat
 * @property string $snf
 * @property string $qty
 * @property string $rtpl
 * @property string $amount
 * @property integer $collection_type
 * @property string $vehicle_code
 * @property string $route_arrival_time
 * @property string $union_code
 * @property integer $status
 * @property string $entry_datetime
 * @property string $pick_datetime
 * @property string $response_datetime
 * @property string $response_msg
 * @property string $uuid
 */
class TblBulkDataImport extends \yii\db\ActiveRecord {

    public $calibration_value_fat, $calibration_value_snf, $clr;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_bulk_data_import';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['customer_type'], 'default', 'value' => 'DCS'],
                [['shift_code', 'sample_no', 'date_time_of_collection'], 'required', 'except' => ['sample_milk_collection']],
                [['bmc_code'], 'required', 'except' => ['milk_collection_dpu_data', 'milk_collection_other_data']],
                [['qlty_auto', 'qty_auto'], 'default', 'value' => 0],
                [['fat', 'snf'], 'required', 'on' => ['bmc_collection', 'bmc_collection_antibiotic', 'milk_collection', 'milk_collection_qlty', 'bmc_collection_mapped', 'milk_collection_allow', 'bmc_collection_allow', 'milk_collection_qlty_allow', 'bmc_collection_mapped_allow', 'bmc_collection_route', 'bmc_collection_can', 'bmc_collection_bmc_route', 'bmc_collection_bmc_can', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can', 'milk_collection_dpu_data', 'milk_collection_other_data', 'sample_milk_collection']],
                [['milk_type_code'], 'required', 'on' => ['bmc_collection', 'bmc_collection_antibiotic', 'milk_collection', 'milk_collection_qlty', 'bmc_collection_mapped', 'milk_collection_allow', 'bmc_collection_allow', 'milk_collection_qlty_allow', 'bmc_collection_mapped_allow', 'bmc_collection_route', 'bmc_collection_can', 'bmc_collection_bmc_route', 'bmc_collection_bmc_can', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can', 'milk_collection_dpu_data', 'milk_collection_other_data', 'milk_collection_qty', 'sample_milk_collection']],
                [['qty'], 'required', 'on' => ['bmc_collection', 'bmc_collection_antibiotic', 'milk_collection', 'milk_collection_qlty', 'bmc_collection_mapped', 'milk_collection_allow', 'bmc_collection_allow', 'milk_collection_qlty_allow', 'bmc_collection_mapped_allow', 'bmc_collection_route', 'bmc_collection_can', 'bmc_collection_bmc_route', 'bmc_collection_bmc_can', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can', 'bmc_weight_collection', 'milk_collection_dpu_data', 'milk_collection_other_data', 'milk_collection_qty', 'sample_milk_collection']],
//          [['qty'], 'required','except' => ['bmc_quality_test']],
            [['union_code', 'doc_no'], 'required', 'on' => ['bmc_quality_test', 'bmc_weight_collection']],
                [['route_arrival_time'], 'required', 'on' => ['bmc_quality_test']],
                [['member_code', 'dcs_code'], 'required', 'on' => ['milk_collection', 'milk_collection_qlty', 'milk_collection_qlty_allow', 'milk_collection_allow', 'milk_collection_dpu_data', 'milk_collection_other_data', 'milk_collection_qty']],
                [['milk_quality_type_code'], 'required', 'on' => ['bmc_collection', 'bmc_collection_mapped', 'bmc_collection_allow', 'bmc_collection_mapped_allow', 'bmc_collection_route', 'bmc_collection_can', 'bmc_collection_bmc_route', 'bmc_collection_bmc_can', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can', 'bmc_collection_antibiotic', 'bmc_weight_collection']],
                [['customer_code', 'route_arrival_time'], 'required', 'on' => ['bmc_collection', 'bmc_collection_mapped', 'bmc_collection_allow', 'bmc_collection_mapped_allow', 'bmc_collection_route', 'bmc_collection_can', 'bmc_collection_bmc_route', 'bmc_collection_bmc_can', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can', 'bmc_collection_antibiotic', 'bmc_weight_collection']],
                [['member_code', 'dcs_code', 'customer_type', 'customer_code', 'bmc_code', 'shift_code', 'vehicle_code', 'union_code', 'response_msg', 'uuid', 'own_bmc_code', 'can_no', 'route_code', 'antibiotic', 'doc_no', 'qlty_auto', 'qty_auto', 'calibration_value_fat', 'calibration_value_snf'], 'safe'],
                [['bmc_silos_info_code', 'sample_no', 'milk_type_code', 'milk_quality_type_code', 'collection_type', 'status'], 'safe'],
                [['date_time_of_collection', 'route_arrival_time', 'entry_datetime', 'pick_datetime', 'response_datetime', 'clr', 'qlty_time', 'qty_time'], 'safe'],
                [['fat', 'snf', 'qty', 'rtpl', 'amount', 'sample_no', 'clr'], 'number'],
                ['shift_code', 'in', 'range' => ['M', 'E', 'm', 'e'], 'on' => ['bmc_collection', 'milk_collection', 'bmc_collection_allow', 'milk_collection_allow', 'milk_collection_qlty', 'milk_collection_qlty_allow', 'bmc_collection_mapped', 'bmc_collection_mapped_allow', 'bmc_collection_route', 'bmc_collection_can', 'bmc_collection_bmc_route', 'bmc_collection_bmc_can', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can', 'bmc_collection_antibiotic', 'bmc_quality_test', 'bmc_weight_collection', 'milk_collection_qty', 'sample_milk_collection']],
                ['milk_type_code', 'in', 'range' => ['C', 'B', 'M', 'c', 'b', 'm'], 'on' => ['bmc_collection', 'milk_collection', 'bmc_collection_allow', 'milk_collection_allow', 'milk_collection_qlty', 'milk_collection_qlty_allow', 'milk_collection_allow', 'bmc_collection_mapped', 'bmc_collection_mapped_allow', 'bmc_collection_route', 'bmc_collection_can', 'bmc_collection_bmc_route', 'bmc_collection_bmc_can', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can', 'bmc_collection_antibiotic', 'milk_collection_qty', 'sample_milk_collection']],
                [['milk_type_code', 'shift_code'], function ($attribute) {
                    $this->$attribute = strtoupper($this->$attribute);
                }, 'on' => ['milk_collection_dpu_data', 'milk_collection_other_data']],
                ['shift_code', 'in', 'range' => ['M', 'E', '06:00', '18:00', '06:00:00', '18:00:00', 'MORNING', 'EVENING'], 'on' => ['milk_collection_dpu_data', 'milk_collection_other_data']],
                ['milk_type_code', 'in', 'range' => ['C', 'COW', 'B', 'BUF', 'BUFFALO', 'M', 'MIXED', 'MIX'], 'on' => ['milk_collection_dpu_data', 'milk_collection_other_data']],
                [['milk_quality_type_code', 'shift_code'], function ($attribute) {
                    $this->$attribute = strtolower($this->$attribute);
                }, 'on' => ['milk_collection_other_data']],
                ['milk_quality_type_code', 'in', 'range' => ['Good', 'Curd', 'Sour', 'Drain', 'good', 'curd', 'sour', 'drain'], 'on' => ['bmc_collection', 'milk_collection', 'milk_collection_qlty', 'milk_collection_qlty_allow', 'bmc_collection_allow', 'milk_collection_allow', 'bmc_collection_mapped', 'bmc_collection_mapped_allow', 'bmc_collection_route', 'bmc_collection_can', 'bmc_collection_bmc_route', 'bmc_collection_bmc_can', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can', 'bmc_collection_antibiotic', 'milk_collection_dpu_data', 'milk_collection_other_data', 'sample_milk_collection']],
                [['route_arrival_time'], 'match', 'pattern' => '/^(0[0-9]|1[0-9]|2[0-3]|[0-9]):[0-5][0-9]$/'],
                [['date_time_of_collection'], 'date', 'format' => 'php:d.m.Y', 'strictDateFormat' => true, 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['bmc_collection', 'milk_collection', 'bmc_collection_allow', 'milk_collection_allow', 'milk_collection_qlty', 'milk_collection_qlty_allow', 'bmc_collection_mapped', 'bmc_collection_mapped_allow', 'bmc_collection_route', 'bmc_collection_can', 'bmc_collection_bmc_route', 'bmc_collection_bmc_can', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can', 'bmc_collection_antibiotic', 'bmc_weight_collection', 'bmc_quality_test', 'milk_collection_dpu_data', 'milk_collection_other_data', 'milk_collection_qty', 'sample_milk_collection']],
                [['collection_type'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'collection_type');
                }],
                [['own_bmc_code'], 'required', 'on' => ['bmc_collection_mapped', 'bmc_collection_mapped_allow', 'bmc_collection_bmc_route', 'bmc_collection_bmc_can', 'bmc_collection_bmc_route_can']],
                [['milk_quality_type_code'], 'required', 'on' => ['milk_collection_qlty', 'milk_collection_qlty_allow']],
                [['can_no'], 'required', 'on' => ['bmc_collection_can', 'bmc_collection_bmc_can', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can']],
                [['route_code'], 'required', 'on' => ['bmc_collection_route', 'bmc_collection_bmc_route', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can']],
                [['antibiotic'], 'required', 'when' => function ($model) {
                    return \Yii::$app->session->get('eiplCode') == 'PRABHAT';
                }, 'on' => ['bmc_collection_route', 'bmc_collection_can', 'bmc_collection_bmc_route', 'bmc_collection_bmc_can', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can', 'bmc_collection_mapped', 'bmc_collection', 'bmc_collection_antibiotic']],
                [['antibiotic'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'antibiotic');
                }, 'on' => ['bmc_collection_route', 'bmc_collection_can', 'bmc_collection_bmc_route', 'bmc_collection_bmc_can', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can', 'bmc_collection_mapped', 'bmc_collection', 'bmc_collection_antibiotic', 'milk_collection', 'milk_collection_qlty']],
                [['qlty_time', 'qty_time'], 'match', 'pattern' => '/^(0[0-9]|1[0-9]|2[0-3]|[0-9]):[0-5][0-9]:[0-5][0-9]$/', 'on' => ['milk_collection_other_data']],
                [['source_of_milk'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'source_of_milk');
                }, 'on' => ['sample_milk_collection']],
                [['dcs_code', 'shift_code', 'date_time_of_collection'], 'required', 'on' => ['sample_milk_collection']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'data_import_code' => 'Data Import Code',
            'member_code' => 'Member Code',
            'dcs_code' => 'Dcs Code',
            'customer_type' => 'Customer Type',
            'customer_code' => 'Customer Code',
            'bmc_code' => 'Bmc Code',
            'bmc_silos_info_code' => 'Bmc Silos Info Code',
            'date_time_of_collection' => 'Date Time Of Collection',
            'shift_code' => 'Shift Code',
            'sample_no' => 'Sample No',
            'milk_type_code' => 'Milk Type Code',
            'milk_quality_type_code' => 'Milk Quality Type Code',
            'fat' => 'Fat',
            'snf' => 'Snf',
            'clr' => 'Clr',
            'qty' => 'Qty',
            'rtpl' => 'Rtpl',
            'amount' => 'Amount',
            'collection_type' => 'Collection Type',
            'vehicle_code' => 'Vehicle Code',
            'route_arrival_time' => 'Route Arrival Time',
            'union_code' => 'Union Code',
            'status' => 'Status',
            'entry_datetime' => 'Entry Datetime',
            'pick_datetime' => 'Pick Datetime',
            'response_datetime' => 'Response Datetime',
            'response_msg' => 'Response Msg',
            'uuid' => 'Uuid',
        ];
    }

    public function SetDataForShagunDPU() {
        $cValues = ['C', 'COW'];
        $bValues = ['B', 'BUF', 'BUFFALO'];
        $mValues = ['M', 'MIXED', 'MIX'];
        $shiftCode = ['M', '06:00', '06:00:00', 'MORNING'];

        if (in_array($this->milk_type_code, $cValues)) {
            $this->milk_type_code = 'C';
        } elseif (in_array($this->milk_type_code, $bValues)) {
            $this->milk_type_code = 'B';
        } elseif (in_array($this->milk_type_code, $mValues)) {
            $this->milk_type_code = 'M';
        }

        $this->shift_code = in_array($this->shift_code, $shiftCode) ? 1 : 2;
        $this->bmc_code = 0;
        $this->own_bmc_code = $this->bmc_code;
        $this->qlty_auto = (strtoupper($this->qlty_auto) == 'AUTOMATIC') ? 1 : 0;
        $this->qty_auto = (strtoupper($this->qty_auto) == 'AUTOMATIC') ? 1 : 0;
    }

}
