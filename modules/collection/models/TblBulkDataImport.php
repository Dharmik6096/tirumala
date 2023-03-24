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
            [['bmc_code', 'shift_code', 'sample_no', 'milk_type_code', 'date_time_of_collection', 'fat', 'snf', 'qty'], 'required'],
            [['member_code', 'dcs_code'], 'required', 'on' => ['milk_collection', 'milk_collection_qlty', 'milk_collection_qlty_allow', 'milk_collection_allow']],
            [['customer_code', 'milk_quality_type_code', 'route_arrival_time'], 'required', 'on' => ['bmc_collection', 'bmc_collection_mapped', 'bmc_collection_allow', 'bmc_collection_mapped_allow', 'bmc_collection_route', 'bmc_collection_can', 'bmc_collection_bmc_route', 'bmc_collection_bmc_can', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can']],
            [['member_code', 'dcs_code', 'customer_type', 'customer_code', 'bmc_code', 'shift_code', 'vehicle_code', 'union_code', 'response_msg', 'uuid', 'own_bmc_code', 'can_no', 'route_code', 'antibiotic'], 'safe'],
            [['bmc_silos_info_code', 'sample_no', 'milk_type_code', 'milk_quality_type_code', 'collection_type', 'status'], 'safe'],
            [['date_time_of_collection', 'route_arrival_time', 'entry_datetime', 'pick_datetime', 'response_datetime'], 'safe'],
            [['fat', 'snf', 'qty', 'rtpl', 'amount', 'sample_no'], 'number'],
            ['shift_code', 'in', 'range' => ['M', 'E', 'm', 'e'], 'on' => ['bmc_collection', 'milk_collection', 'bmc_collection_allow', 'milk_collection_allow', 'milk_collection_qlty', 'milk_collection_qlty_allow', 'bmc_collection_mapped', 'bmc_collection_mapped_allow', 'bmc_collection_route', 'bmc_collection_can', 'bmc_collection_bmc_route', 'bmc_collection_bmc_can', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can']],
            ['milk_type_code', 'in', 'range' => ['C', 'B', 'M', 'c', 'b', 'm'], 'on' => ['bmc_collection', 'milk_collection', 'bmc_collection_allow', 'milk_collection_allow', 'milk_collection_qlty', 'milk_collection_qlty_allow', 'milk_collection_allow', 'bmc_collection_mapped', 'bmc_collection_mapped_allow', 'bmc_collection_route', 'bmc_collection_can', 'bmc_collection_bmc_route', 'bmc_collection_bmc_can', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can']],
            ['milk_quality_type_code', 'in', 'range' => ['Good', 'Curd', 'Sour', 'Drain', 'good', 'curd', 'sour', 'drain'], 'on' => ['bmc_collection', 'milk_collection', 'milk_collection_qlty', 'milk_collection_qlty_allow', 'bmc_collection_allow', 'milk_collection_allow', 'bmc_collection_mapped', 'bmc_collection_mapped_allow', 'bmc_collection_route', 'bmc_collection_can', 'bmc_collection_bmc_route', 'bmc_collection_bmc_can', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can']],
            [['route_arrival_time'], 'match', 'pattern' => '/^(0[0-9]|1[0-9]|2[0-3]|[0-9]):[0-5][0-9]$/'],
            [['date_time_of_collection'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['bmc_collection', 'milk_collection', 'bmc_collection_allow', 'milk_collection_allow', 'milk_collection_qlty', 'milk_collection_qlty_allow', 'bmc_collection_mapped', 'bmc_collection_mapped_allow', 'bmc_collection_route', 'bmc_collection_can', 'bmc_collection_bmc_route', 'bmc_collection_bmc_can', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can']],
            [['collection_type'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'collection_type');
                }],
            [['own_bmc_code'], 'required', 'on' => ['bmc_collection_mapped', 'bmc_collection_mapped_allow', 'bmc_collection_bmc_route', 'bmc_collection_bmc_can', 'bmc_collection_bmc_route_can']],
            [['milk_quality_type_code'], 'required', 'on' => ['milk_collection_qlty', 'milk_collection_qlty_allow',]],
            [['can_no'], 'required', 'on' => ['bmc_collection_can', 'bmc_collection_bmc_can', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can']],
            [['route_code'], 'required', 'on' => ['bmc_collection_route', 'bmc_collection_bmc_route', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can']],
            [['antibiotic'], 'required', 'when' => function ($model) {
		return \Yii::$app->session->get('eiplCode') == 'PRABHAT';
            }, 'on' => ['bmc_collection_route', 'bmc_collection_can', 'bmc_collection_bmc_route', 'bmc_collection_bmc_can', 'bmc_collection_route_can', 'bmc_collection_bmc_route_can', 'bmc_collection_mapped', 'bmc_collection']]
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

}
