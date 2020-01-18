<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\transporter\models\TblTransporter;
use app\modules\transporter\models\TblVehicleMaster;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\globalmaster\models\TblCustomerType;
use app\modules\organisation\models\TblCustomerMaster;
use app\modules\organisation\models\TblUnions;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblMccPlant;

/**
 * This is the model class for table "tbl_bmc_collection".
 *
 * @property integer $milk_collection_code
 * @property string $dcs_code
 * @property string $name
 * @property string $mobile_no
 * @property integer $milk_type_code
 * @property string $fat
 * @property string $snf
 * @property string $water
 * @property string $qty
 * @property string $rtpl
 * @property string $amount
 * @property string $auto_flag
 * @property string $shift_code
 * @property string $date_time_of_collection
 * @property string $date_time_of_recieve
 * @property string $village_code
 * @property integer $sample_no
 * @property string $type_of_data_receive
 * @property string $rate_code
 * @property string $error_log
 * @property integer $ack
 * @property string $soc_bmc_flag
 * @property string $dt_date
 * @property string $sms_status
 * @property string $sms_msgid
 * @property string $sms_mobile
 * @property string $sms_errorlog
 * @property string $sms_timestamp
 * @property string $remarks
 * @property integer $data_post_id
 * @property string $data_post_status
 * @property string $picked_datetime
 * @property string $resp_status
 * @property string $resp_desc
 */
class TblBmcCollection extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public $date, $weigh_time, $testing_time;
    public $dcs_name, $bmc_name, $route_name, $dcs_incharge_name, $customer_name, $value, $allow_rate_zero;

    public static function tableName() {
        return 'tbl_bmc_collection';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_code', 'name', 'mobile_no', 'auto_flag', 'village_code', 'type_of_data_receive', 'error_log', 'soc_bmc_flag', 'sms_status', 'sms_msgid', 'sms_mobile', 'sms_errorlog', 'remarks'], 'string', 'except' => ['androidsync']],
            [['rate_code'], 'string', 'except' => ['androidsync', 'saveCreamyData']],
            [['milk_type_code', 'sample_no', 'ack'], 'integer', 'except' => ['androidsync']],
            [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'clr'], 'number', 'except' => ['androidsync']],
            [['transporter_code', 'vehicle_code'], 'required', 'when' => function ($model) {
            return $model->collection_type == '2';
        }, 'whenClient' => "function (attribute, value) { 
              return $('#tblbmccollection-collection_type').val() == '2'; 
          }", 'except' => ['post_sap_data', 'androidsync']],
            [['dcs_code'], 'validateDcs', 'except' => ['post_sap_data', 'androidsync', 'create']],
//            [['customer_code'], 'unique', 'targetAttribute' => ['date_time_of_collection', 'shift_code', 'customer_code', 'sample_no'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
//                    return $this->shift_code;
//                }, 'except' => ['androidsync']],
            [['collection_type'], 'default', 'value' => 1, 'on' => ['saveCreamyData', 'saveSapData', 'androidsync']],
            [['fat', 'snf', 'qty', 'shift_code', 'milk_type_code', 'date_time_of_collection', 'milk_quality_type_code'], 'required', 'except' => ['saveSapData', 'post_sap_data', 'androidsync']],
            [['date_time_of_collection', 'date_time_of_recieve', 'dt_date', 'sms_timestamp', 'transporter_code', 'vehicle_code', 'collection_type', 'date', 'weigh_time', 'testing_time', 'bmc_code', 'data_post_id', 'data_post_status', 'picked_datetime', 'resp_status', 'resp_desc'], 'safe'],
            [['density', 'clr', 'lactose', 'protein', 'qlty_auto', 'qty_mode', 'qty_auto', 'no_of_can', 'avg_qlty_param', 'qlty_time', 'qlty_times_no', 'qty_time', 'date_time_of_testing', 'converted_qty', 'doc_no', 'RouteArivalTime', 'allow_rate_zero', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['own_mcc_plant_code', 'own_bmc_code', 'converted_qty_mode', 'milk_analyser_type_code', 'ws_code', 'vehicle_no', 'route_arrival_time', 'customer_type', 'customer_code', 'union_code', 'plant_code', 'mcc_plant_code', 'route_code', 'dcs_code', 'village_code'], 'safe'],
            [['mcc_plant_code', 'plant_code', 'union_code', 'customer_code', 'customer_type', 'bmc_code'], 'required', 'on' => ['create']],
            [['clr'], 'number', 'min' => 0, 'on' => ['create', 'update']],
            [['customer_code'], 'unique', 'targetAttribute' => ['customer_code', 'qty', 'fat', 'snf', 'milk_type_code', 'shift_code', 'date_time_of_collection', 'bmc_code', 'milk_quality_type_code', 'customer_type'], 'message' => Yii::t('app/validation', 'Record is Already Exist.'), 'skipOnError' => true, 'when' => function($model) {
            return empty($this->getErrors());
        }, 'on' => ['create', 'update']],
            [['rtpl'], 'required', 'when' => function ($model) {
            return $model->allow_rate_zero == 0;
        }, 'whenClient' => "function (attribute, value) { 
              return $('#tblbmccollection-allow_rate_zero').val() == '0'; 
          }", 'except' => ['post_sap_data', 'androidsync']],
            [['rtpl', 'water'], 'default', 'value' => 0],
            [['doc_no'], 'default', 'value' => 1],
            [['rtpl'], 'number', 'min' => 0],
            [['route_arrival_time'], 'match', 'pattern' => '/^(0[0-9]|1[0-9]|2[0-3]|[0-9]):[0-5][0-9]$/', 'on' => ['create', 'update']],
            [['customer_code'], 'setUuid', 'on' => ['create', 'update', 'androidsync']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milk_collection_code' => Yii::t('app', 'Milk Collection'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type'),
            'dcs_code' => Yii::t('app', 'Society Code'),
            'transporter_code' => Yii::t('app', 'Transporter'),
            'vehicle_code' => Yii::t('app', 'Vehicle'),
            'name' => Yii::t('app', 'Name'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'fat' => Yii::t('app', 'FAT'),
            'snf' => Yii::t('app', 'SNF'),
            'water' => Yii::t('app', 'Water'),
            'qty' => Yii::t('app', 'Qty'),
            'rtpl' => Yii::t('app', 'RTPL'),
            'amount' => Yii::t('app', 'Amount'),
            'auto_flag' => Yii::t('app', 'Auto Flag'),
            'shift_code' => Yii::t('app', 'Shift'),
            'date_time_of_collection' => Yii::t('app', 'Date Of Collection'),
            'date_time_of_recieve' => Yii::t('app', 'Date Time Of Recieve'),
            'village_code' => Yii::t('app', 'Village'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'type_of_data_receive' => Yii::t('app', 'Type Of Data Receive'),
            'rate_code' => Yii::t('app', 'Rate'),
            'error_log' => Yii::t('app', 'Error Log'),
            'ack' => Yii::t('app', 'Ack'),
            'soc_bmc_flag' => Yii::t('app', 'Soc Bmc Flag'),
            'dt_date' => Yii::t('app', 'Dt Date'),
            'sms_status' => Yii::t('app', 'Sms Status'),
            'sms_msgid' => Yii::t('app', 'Sms Msgid'),
            'sms_mobile' => Yii::t('app', 'Sms Mobile'),
            'sms_errorlog' => Yii::t('app', 'Sms Errorlog'),
            'sms_timestamp' => Yii::t('app', 'Sms Timestamp'),
            'remarks' => Yii::t('app', 'Remarks'),
            'dcs_name' => Yii::t('app', 'DCS Name'),
            'bmc_code' => Yii::t('app', 'BMC Code'),
            'bmc_name' => Yii::t('app', 'BMC Name'),
            'converted_qty' => Yii::t('app', 'Converted Qty'),
            'dcs_incharge_name' => Yii::t('app', 'DCS Incharge'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'plant_code' => Yii::t('app', 'Plant'),
            'customer_type' => Yii::t('app', 'Type'),
            'customer_code' => Yii::t('app', 'Code'),
            'union_code' => Yii::t('app', 'Union'),
            'clr' => Yii::t('app', 'CLR'),
            'route_arrival_time' => Yii::t('app', 'Arrival Time'),
        ];
    }

    public function validateDcs($attribute, $params) {
        $dcs_model = new TblDcs();
        $data = $dcs_model->find()->where(['dcs_code' => $this->dcs_code, 'is_active' => 1])->one();
        if (empty($data)) {
            $this->addError($attribute, "Please Enter Valid Society Code");
        }
    }

    public function getMilkType() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    public function getMilkQualityType() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
    }

    public function getTransporter() {
        return $this->hasOne(TblTransporter::className(), ['transporter_code' => 'transporter_code']);
    }

    public function getVehicle() {
        return $this->hasOne(TblVehicleMaster::className(), ['vehicle_code' => 'vehicle_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'customer_code']);
    }

    public function collectionData($from_date, $to_date, $bmc_code) {
        return $this->find()
                        ->select(['dcs_code', 'milk_type_code', 'fat', 'snf', 'qty', 'rtpl', 'amount', 'shift_code', 'date_time_of_collection', 'sample_no'])
                        ->andFilterWhere(['>=', 'date_time_of_collection', $from_date])
                        ->andFilterWhere(['<=', 'date_time_of_collection', $to_date])
                        ->where(['bmc_code' => $bmc_code])->all();
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code'])->andwhere(['is_active' => 1]);
    }

    public function getRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

    public function getBmcData() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getCustomerType() {
        return $this->hasOne(TblCustomerType::className(), ['customer_type' => 'customer_type', 'union_code' => 'union_code']);
    }

    public function getCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_type' => 'customer_type'])->andwhere(['union_code' => $this->union_code, 'customer_code_ex' => $this->value]);
    }

    public function getMainCustomerCode() {
        return $this->hasOne(TblCustomerMaster::className(), ['customer_code' => 'customer_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function validateCustomer($union, $code, $type) {
        if (!empty($code) && strtolower($type) != 'dcs') {
            $this->union_code = $union;
            $this->customer_type = $type;
            $prefix = Yii::$app->general->getforeignkey($this->customerType, 'code_prefix');
            $length = Yii::$app->general->getforeignkey($this->customerType, 'code_length');
            $this->value = $prefix . str_pad($code, $length, '0', STR_PAD_LEFT);
            $Code = Yii::$app->general->getforeignkey($this->customerCode, 'customer_code');
            return $data = empty($Code) ? '' : $Code;
        }
    }

    public function getCustomerCodeVal() {
        if ($this->customerType != 'DCS') {
            $customerCode = $this->mainCustomerCode;
            $customerType = $this->customerType;
            if (!empty($customerCode) && !empty($customerType)) {
                $prefix = $customerType->code_prefix;
                $this->customer_code = (int) str_replace($prefix, '', $customerCode->customer_code_ex);
            }
        }
    }

    public function getSampleNo() {
        $data = $this->find()
                ->select('max(sample_no) as sample_no')
                ->where(['bmc_code' => $this->bmc_code, 'shift_code' => $this->shift_code, 'CONVERT(date,date_time_of_collection)' => Yii::$app->formatter->asDate($this->date_time_of_collection, DATE_FORMAT)])
                ->one();
        $sample_no = (int) $data['sample_no'] + 1;
        return $sample_no;
    }

    public function getMainBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function setUuid($attribute, $params) {
        $this->data_post_id = !empty($this->data_post_id) ? $this->data_post_id : Yii::$app->general->getUuid();
        $this->data_post_status = 0;
    }

}
