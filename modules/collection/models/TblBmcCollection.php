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
use app\modules\dcsoperation\models\TblDcsPurchaseRateDetails;
use app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabitity;
use app\modules\organisation\models\TblBmcSilosInfo;
use app\modules\collection\models\TblCollectionDataAlias;
use app\modules\configuration\models\TblUnionRatechartRange;
use app\modules\dcsoperation\models\TblMember;

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
    public $dcs_name, $bmc_name, $route_name, $dcs_incharge_name, $customer_name, $ex_code, $allow_rate_zero, $status, $bmc_ref_code, $ref_code;

    public static function tableName() {
        return 'tbl_bmc_collection';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['milk_type_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'milk_type_code');
                }, 'on' => 'importCsv'],
                [['milk_quality_type_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'milk_quality_type_code');
                }, 'on' => 'importCsv'],
                [['shift_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'shift');
                }, 'on' => 'importCsv'],
                [['collection_type'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'collection_type');
                }, 'on' => 'importCsv'],
                [['bmc_silos_info_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'bmc_silos', FALSE, TRUE, ['module_name' => 'BMC', 'module_code' => $this->bmc_code], TRUE);
                }, 'on' => ['importCsv']],
                [['milk_type_code', 'shift_code', 'milk_quality_type_code'], 'integer', 'message' => Yii::t('app/validation', '{attribute} is invalid.'), 'on' => ['importCsv']],
                [['dcs_code', 'name', 'mobile_no', 'auto_flag', 'village_code', 'type_of_data_receive', 'error_log', 'soc_bmc_flag', 'sms_status', 'sms_msgid', 'sms_mobile', 'sms_errorlog', 'remarks'], 'string', 'except' => ['androidsync']],
                [['rate_code'], 'string', 'except' => ['androidsync', 'saveCreamyData']],
                [['sample_no'], 'number', 'min' => 0, 'on' => ['importCsv']],
                [['milk_type_code', 'sample_no', 'ack'], 'integer', 'except' => ['androidsync']],
                [['rtpl', 'amount'], 'trim'],
                [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'clr'], 'number', 'except' => ['androidsync']],
                [['transporter_code', 'vehicle_code'], 'required', 'when' => function ($model) {
                    return $model->collection_type == '2';
                }, 'whenClient' => "function (attribute, value) { 
              return $('#tblbmccollection-collection_type').val() == '2'; 
          }", 'except' => ['post_sap_data', 'androidsync', 'importCsv', 'update']],
                [['dcs_code'], 'validateDcs', 'except' => ['post_sap_data', 'androidsync', 'create', 'create_allow']],
//            [['customer_code'], 'unique', 'targetAttribute' => ['date_time_of_collection', 'shift_code', 'customer_code', 'sample_no'], 'skipOnEmpty' => true, 'message' => Yii::t('app/validation', '{attribute} has already been taken.'), 'when' => function() {
//                    return $this->shift_code;
//                }, 'except' => ['androidsync']],
            [['collection_type'], 'default', 'value' => 1, 'on' => ['saveCreamyData', 'saveSapData', 'androidsync', 'importCsv']],
                [['fat', 'snf', 'qty', 'shift_code', 'milk_type_code', 'date_time_of_collection', 'milk_quality_type_code'], 'required', 'except' => ['saveSapData', 'post_sap_data', 'androidsync']],
                [['date_time_of_collection', 'date_time_of_recieve', 'dt_date', 'sms_timestamp', 'transporter_code', 'vehicle_code', 'collection_type', 'date', 'weigh_time', 'testing_time', 'bmc_code', 'data_post_id', 'data_post_status', 'picked_datetime', 'resp_status', 'resp_desc', 'purchase_rate_code', 'bmc_silos_info_code', 'response_datetime'], 'safe'],
                [['density', 'clr', 'lactose', 'protein', 'qlty_auto', 'qty_mode', 'qty_auto', 'no_of_can', 'avg_qlty_param', 'qlty_time', 'qlty_times_no', 'qty_time', 'date_time_of_testing', 'converted_qty', 'doc_no', 'RouteArivalTime', 'allow_rate_zero', 'originating_org_code', 'originating_org_type'], 'safe'],
                [['own_mcc_plant_code', 'own_bmc_code', 'converted_qty_mode', 'milk_analyser_type_code', 'ws_code', 'vehicle_no', 'route_arrival_time', 'customer_type', 'customer_code', 'union_code', 'plant_code', 'mcc_plant_code', 'route_code', 'dcs_code', 'village_code', 'tag_1', 'tag_2', 'error_desc'], 'safe'],
                [['mcc_plant_code', 'plant_code', 'union_code', 'customer_code', 'customer_type', 'bmc_code', 'own_bmc_code'], 'required', 'on' => ['create', 'update', 'create_allow', 'update_allow']],
                [['bmc_silos_info_code'], 'required', 'on' => ['create', 'update', 'create_allow', 'update_allow']],
                [['customer_code', 'bmc_code', 'sample_no'], 'required', 'on' => ['importCsv']],
                [['clr'], 'number', 'min' => 0, 'on' => ['create', 'update', 'importCsv', 'create_allow', 'update_allow']],
                [['date_time_of_collection'], 'convertDateDot', 'on' => ['importCsv']],
                [['date_time_of_collection'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
                [['date_time_of_collection'], 'convertDate', 'on' => ['importCsv']],
//            [['customer_code'], 'unique', 'targetAttribute' => ['customer_code', 'qty', 'fat', 'snf', 'milk_type_code', 'shift_code', 'date_time_of_collection', 'bmc_code', 'milk_quality_type_code', 'customer_type'], 'message' => Yii::t('app/validation', 'Record is Already Exist.'), 'skipOnError' => true, 'when' => function($model) {
//                    return empty($this->getErrors());
//                }, 'on' => ['create', 'update', 'importCsv']],
            [['rtpl'], function ($attribute, $params) {
                    Yii::$app->general->validateOnUnionConfig($this, 'rtpl', 'bmc_collection_allow_on_zero_rate', 0);
                }, 'skipOnEmpty' => false, 'on' => ['create', 'update', 'create_allow', 'update_allow']],
                [['rtpl', 'amount'], 'default', 'value' => 0, 'except' => ['importCsv']],
                [['water'], 'default', 'value' => 0],
                [['doc_no'], 'default', 'value' => 1],
                [['rtpl'], 'number', 'min' => 0],
                [['route_arrival_time'], 'match', 'pattern' => '/^(0[0-9]|1[0-9]|2[0-3]|[0-9]):[0-5][0-9]$/', 'on' => ['create', 'importCsv', 'create_allow']],
                [['customer_code'], 'setUuid', 'on' => ['create', 'update', 'androidsync', 'importCsv', 'create_allow', 'update_allow']],
                [['shift_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblShift::className(), 'targetAttribute' => ['shift_code' => 'id'], 'on' => ['importCsv']],
                [['milk_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type_code' => 'animal_type_code'], 'on' => ['importCsv']],
                [['milk_quality_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMilkQualityType::className(), 'targetAttribute' => ['milk_quality_type_code' => 'milk_quality_type_code'], 'on' => ['importCsv']],
                [['bmc_code'], function ($attribute, $params) {
                    Yii::$app->general->validateBMC($this, $attribute, 'bmc_code');
                }, 'on' => ['importCsv']],
                [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code'], 'on' => ['importCsv']],
                [['bmc_silos_info_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblBmcSilosInfo::className(), 'targetAttribute' => ['bmc_silos_info_code' => 'bmc_silos_info_code'], 'on' => ['importCsv']],
                [['customer_type'], function ($attribute, $params) {
                    $this->union_code = Yii::$app->general->getforeignkey($this->bmcCode, 'union_code');
                    Yii::$app->general->validateGlobalData($this, $attribute, 'customer_type', FALSE, TRUE, ['union_code' => $this->union_code]);
                }, 'on' => ['importCsv']],
                [['customer_type'], 'exist', 'skipOnError' => true, 'targetClass' => TblCustomerType::className(), 'targetAttribute' => ['customer_type' => 'customer_type'], 'on' => ['importCsv']],
                [['bmc_code'], 'pastDateValidate', 'on' => 'importCsv'],
                [['bmc_code'], 'importData', 'skipOnError' => true, 'on' => ['importCsv']],
                [['tag_1'], 'default', 'value' => 'X'],
                [['adt_param', 'adt_value', 'received_timestamp', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'is_rate_recalc', 'purchase_rate_code_old'], 'safe'],
                [['date_time_of_recieve'], 'default', 'value' => date('Y-m-d H:i:s'), 'on' => 'androidsync'],
                [['bmc_code'], function ($attribute, $params) {
                    if (empty($this->getErrors())) {
                        Yii::$app->general->paymentCycleLock($this, 'date_time_of_collection', 'bmc_code', 'BMC', $this->customer_type, ['data_lock_bmc', 'billing_lock_bmc']);
                    }
                }, 'skipOnEmpty' => TRUE, 'on' => ['create', 'importCsv']],
                [['bmc_code'], function ($attribute, $params) {
                    if (empty($this->getErrors())) {
                        Yii::$app->general->paymentCycleLock($this, 'date_time_of_collection', 'bmc_code', 'BMC', $this->customer_type, ['data_lock_bmc', 'billing_lock_bmc', 'sync_lock_bmc']);
                    }
                }, 'skipOnEmpty' => TRUE, 'on' => ['androidsync_coll']],
                [['customer_code'], 'validateUnique', 'on' => ['create', 'create_allow']],
                [['milk_type_code'], 'validateUpdate', 'on' => ['update', 'update_allow']],
                [['date_time_of_collection'], function ($attribute, $params) {
                    $this->data_post_status = 0;
                }, 'skipOnEmpty' => false, 'except' => ['post_sap_data']],
                [['is_rate_recalc'], 'default', 'value' => 0],
                [['bmc_code'], function ($attribute, $params) {
                    if (empty($this->getErrors())) {
                        Yii::$app->general->shiftLock($this, 'date_time_of_collection', 'mcc_plant_code', 'qty', 'bmc_lock');
                    }
                }, 'skipOnEmpty' => TRUE, 'on' => ['create', 'update', 'androidsync_coll']],
                [['qty'], 'validateMinLimit', 'on' => ['create', 'update']],
                [['antibiotic'], 'safe'],
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
            'qlty_time' => Yii::t('app', 'Qlty. Time'),
            'tag_1' => Yii::t('app', 'SAP Status'),
            'error_desc' => Yii::t('app', 'Status Desc.'),
            'originating_org_type' => Yii::t('app', 'Originated At'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'adt_param' => Yii::t('app', 'Adultration Param'),
            'adt_value' => Yii::t('app', 'Adultration Value'),
            'bmc_silos_info_code' => Yii::t('app', 'Silos'),
            'qty_time' => Yii::t('app', 'Qty. Time'),
            'created_at' => Yii::t('app', 'Receive Time'),
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
        return $this->hasOne(TblCustomerMaster::className(), ['customer_type' => 'customer_type'])->andwhere(['union_code' => $this->union_code, 'bmc_code' => $this->bmc_code, 'customer_code_ex' => $this->ex_code]);
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

    public function validateCustomer($union, $code, $type, $bmc) {
        if (!empty($code) && strtolower($type) != 'dcs') {
            $this->union_code = $union;
            $this->bmc_code = $bmc;
            $this->customer_type = $type;
            $prefix = Yii::$app->general->getforeignkey($this->customerType, 'code_prefix');
            $length = Yii::$app->general->getforeignkey($this->customerType, 'code_length');
            $Code = '';
            if (!empty($prefix) && is_numeric($this->customer_code)) {
                $customerModel = new TblCustomerMaster();
                $customerModel->customer_type = $this->customer_type;
                $customerModelData = $customerModel->find()
                        ->where(['customer_type' => $this->customer_type, 'bmc_code' => $bmc])
                        ->andWhere(['CAST(REPLACE(customer_code_ex,\'' . $prefix . '\', \'\') as int)' => (int) $this->customer_code])
                        ->all();
                if (count($customerModelData) == 1) {
                    $Code = $customerModelData[0]->customer_code;
                    $this->ex_code = $customerModelData[0]->customer_code_ex;
                }
            } else {
                $this->ex_code = !empty($length) ? $prefix . str_pad($code, $length, '0', STR_PAD_LEFT) : '';
                $Code = Yii::$app->general->getforeignkey($this->customerCode, 'customer_code');
            }
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
                ->where(['own_bmc_code' => $this->own_bmc_code, 'shift_code' => $this->shift_code, 'CONVERT(date,date_time_of_collection)' => Yii::$app->formatter->asDate($this->date_time_of_collection, DATE_FORMAT)])
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

    public function importData($attribute, $params) {
        if (empty($this->getErrors())) {
            if (empty($this->bmc_silos_info_code)) {
                $this->bmc_silos_info_code = Yii::$app->general->getforeignkey($this->bmcSilosCode, 'bmc_silos_info_code');
                if (empty($this->bmc_silos_info_code)) {
                    $this->addError($attribute, Yii::t('app/validation', 'Silo No. is not Available.'));
                }
            } else {
                $bmc = Yii::$app->general->getforeignkey($this->silosCode, 'module_code');
                if ($bmc != $this->bmc_code) {
                    $this->addError($attribute, Yii::t('app/validation', 'Silo No. is invalid'));
                }
            }
            $this->union_code = Yii::$app->general->getforeignkey($this->mainBmcCode, 'union_code');
            $this->plant_code = Yii::$app->general->getforeignkey($this->mainBmcCode, 'plant_code');
            $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->mainBmcCode, 'mcc_plant_code');
            $this->date_time_of_collection = !empty($this->date_time_of_collection) ? date('Y-m-d', strtotime($this->date_time_of_collection)) : '';
            $this->date_time_of_collection = $this->date_time_of_collection . ' ' . \Yii::$app->general->getshift($this->shift_code);
            Yii::$app->general->validateCustomer($this);
            if (strtoupper($this->customer_type) == 'DCS') {
                $this->dcs_code = $this->customer_code;
                $this->village_code = Yii::$app->general->getforeignkey($this->dcsCode, 'village_code');
                $this->route_code = Yii::$app->general->getforeignkey($this->dcsCode, 'route_code');
                Yii::$app->general->validateDeactivateDcs($this, $this->date_time_of_collection);
            } else {
                $this->dcs_code = NULL;
                $this->village_code = Yii::$app->general->getforeignkey($this->mainCustomerCode, 'village_code');
                $this->route_code = Yii::$app->general->getforeignkey($this->mainCustomerCode, 'route_code');
            }
            $datetime = date('Y-m-d H:i:s');
            $this->qlty_auto = 0;
            $this->qty_auto = 0;
            $this->dt_date = $datetime;
//            $this->sample_no = $this->getSampleNo();
            $this->date_time_of_recieve = $datetime;
            $this->type_of_data_receive = 'Manual';
            $this->sms_status = 'n';
            $this->qlty_time = $datetime;
            $this->qty_time = $datetime;
            $this->date_time_of_testing = $datetime;
            $this->own_mcc_plant_code = $this->mcc_plant_code;
//            $this->own_bmc_code = $this->bmc_code;
            //set converted_qty
            $this->qty_mode = Yii::$app->general->getUnionConfiguration($this->union_code, 'collection_qty_mode', 'BMC');
            (float) $conversion_const = Yii::$app->general->getUnionConfiguration($this->union_code, 'ltr_to_kg_constant', 'BMC');
            $this->converted_qty_mode = $this->qty_mode == 1 ? 0 : 1;
            $this->converted_qty = $this->qty_mode == 1 ? $this->qty / $conversion_const : $this->qty * $conversion_const;

            // set clr
            (float) $fat = $this->fat;
            (float) $snf = $this->snf;
            $union = $this->union_code;
            (float) $lr1 = Yii::$app->general->getUnionConfiguration($union, 'clr_constant1', 'BMC');
            (float) $lr2 = Yii::$app->general->getUnionConfiguration($union, 'clr_constant2', 'BMC');
            $this->clr = ($snf - ($fat * $lr1) - $lr2) * 4;
            Yii::$app->general->validateRateRange($this);
            //set rtpl,rate_code and amount
            if (empty($this->getErrors()) && $this->amount === '' && $this->rtpl === '') {
                $data['milk_type'] = $this->milk_type_code;
                $data['milk_quality_type'] = $this->milk_quality_type_code;
                $data['dt_date'] = Yii::$app->formatter->asDate($this->dt_date, DATE_FORMAT);
                $data['dt_date'] = $data['dt_date'] . ' ' . \Yii::$app->general->getshift($this->shift_code);
                $data['fat'] = $this->fat;
                $data['clr'] = $this->clr;
                $data['snf'] = $this->snf;
                $data['shift'] = $this->shift_code;
                $data['union'] = $this->union_code;
                $model = new TblDcsPurchaseRateApplicabitity();
                $data['milk_quality_type_code'] = $data['milk_quality_type'];
                $data['appl_for'] = $this->customer_type;
                $data['appl_code'] = $this->customer_code;
                $model->wef_date = $data['dt_date'];
                $model_data = $model->getDcsPurchaseRateApplicableData($data);
                if (!empty($model_data)) {
                    $detail_model = new TblDcsPurchaseRateDetails();
                    $detail_model->rate_type_code = $model_data->rate_app_code;
                    $detail_model->purchase_rate_code = $model_data->purchase_rate_code;
                    $rate_type = $detail_model->rateTypeCode->rate_type;
                    $detail_data = $detail_model->getDcsPurchasseRateDetailData($data, $rate_type);
                    if (!empty($detail_data)) {
                        $this->rate_code = (string) $detail_data->purchase_rate_code;
                        $this->rtpl = $detail_data->rtpl;
                        $this->amount = $detail_data->rtpl * $this->qty;
                    } else {
                        Yii::$app->general->validateOnUnionConfig($this, 'rtpl', 'bmc_collection_allow_on_zero_rate', 0);
                        $this->rtpl = 0;
                        $this->amount = 0;
                    }
                } else {
                    Yii::$app->general->validateOnUnionConfig($this, 'rtpl', 'bmc_collection_allow_on_zero_rate', 0);
                    $this->rtpl = 0;
                    $this->amount = 0;
                }
            } else if (empty(floatval($this->rtpl)) && !empty(floatval($this->amount))) {
                $this->rtpl = $this->amount / $this->qty;
            } else if (!empty(floatval($this->rtpl)) && empty(floatval($this->amount))) {
                $this->amount = $this->rtpl * $this->qty;
            } else if (empty(floatval($this->rtpl)) || empty(floatval($this->amount))) {
                $this->amount = 0;
                $this->rtpl = 0;
            }
            $existData = $this::find()->where(['bmc_code' => $this->bmc_code, 'customer_code' => $this->customer_code, 'customer_type' => $this->customer_type, 'date_time_of_collection' => $this->date_time_of_collection, 'sample_no' => $this->sample_no, 'shift_code' => $this->shift_code])->one();
            if (!empty($existData)) {
                $this->milkTypeWiseUnique($this, $this, FALSE, FALSE, FALSE, TRUE);
            } else {
                $this->milkTypeWiseUnique($this, $this, FALSE, FALSE);
            }
        }
    }

    public function convertDateDot() {
        try {
            $this->date_time_of_collection = Yii::$app->controls->view_date($this->date_time_of_collection, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->date_time_of_collection = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->date_time_of_collection = !empty($this->date_time_of_collection) ? Yii::$app->controls->view_date($this->date_time_of_collection, 'php:Y-m-d') : NULL;
            $this->date_time_of_collection = $this->date_time_of_collection . ' ' . \Yii::$app->general->getshift($this->shift_code);
        }
    }

    public function getSilosCode() {
        return $this->hasOne(TblBmcSilosInfo::className(), ['bmc_silos_info_code' => 'bmc_silos_info_code'])->andOnCondition(['module_name' => 'BMC']);
    }

    public function getBmcSilosCode() {
        return $this->hasOne(TblBmcSilosInfo::className(), ['module_code' => 'bmc_code'])->andOnCondition(['module_name' => 'BMC']);
    }

    public function getApprovalData() {
        return $this->hasOne(TblCollectionDataAlias::className(), ['bmc_code' => 'bmc_code', 'customer_code' => 'customer_code', 'customer_type' => 'customer_type', 'old_milk_type_code' => 'milk_type_code', 'old_milk_quality_type_code' => 'milk_quality_type_code', 'shift_code' => 'shift_code', 'date_time_of_collection' => 'date_time_of_collection'])->andOnCondition(['tbl_collection_data_alias.table_name' => 'tbl_bmc_collection', 'action_perform' => 'DELETE']);
    }

    public function validateUnique($attribute, $params) {
        $flag = Yii::$app->general->getUnionConfiguration($this->union_code, 'collection_approval', 'PORTAL');

        Yii::$app->general->validateRateRange($this);

        $ApprovalModel = new TblCollectionDataAlias();
        $this->milkTypeWiseUnique($ApprovalModel, $this, TRUE);
        $this->milkTypeWiseUnique($this, $this);
    }

    public function validateUpdate($attribute, $params) {
        $flag = Yii::$app->general->getUnionConfiguration($this->union_code, 'collection_approval', 'PORTAL');

        $ApprovalModel = new TblCollectionDataAlias();
        if (!empty($this->oldAttributes) && ($this->fat != $this->oldAttributes['fat'] || $this->snf != $this->oldAttributes['snf'] || $this->qty != $this->oldAttributes['qty'] || $this->milk_type_code != $this->oldAttributes['milk_type_code'] || $this->milk_quality_type_code != $this->oldAttributes['milk_quality_type_code'] || $this->no_of_can != $this->oldAttributes['no_of_can'])) {

            $existTableData = $ApprovalModel->find()->where(['bmc_code' => $this->bmc_code, 'customer_code' => $this->customer_code, 'customer_type' => $this->customer_type, 'cast(date_time_of_collection as date)' => $this->date_time_of_collection, 'shift_code' => $this->shift_code, 'old_milk_type_code' => $this->oldAttributes['milk_type_code'], 'old_milk_quality_type_code' => $this->oldAttributes['milk_quality_type_code'], 'old_qty' => $this->oldAttributes['qty'], 'old_fat' => $this->oldAttributes['fat'], 'old_snf' => $this->oldAttributes['snf'], 'table_name' => 'tbl_bmc_collection'])->one();
            if ($flag == 1 && !empty($existTableData)) {
                $this->addError($attribute, "Record is Already Exist For Approval");
            }
            $this->milkTypeWiseUnique($ApprovalModel, $this, TRUE);
            $this->milkTypeWiseUnique($this, $this, FALSE, TRUE);

            Yii::$app->general->validateRateRange($this);
            if ($flag != 1 && $this->scenario != 'update_allow') {
                Yii::$app->general->paymentCycleLock($this, 'date_time_of_collection', 'bmc_code', 'BMC', $this->customer_type, ['data_lock_bmc', 'billing_lock_bmc'], 'milk_type_code');
            }
        }
    }

    public function getExistingCollection($data) {
        return $this->find()->where(['bmc_code' => $data->bmc_code, 'customer_code' => $data->customer_code, 'customer_type' => $data->customer_type, 'date_time_of_collection' => $data->date_time_of_collection, 'shift_code' => $data->shift_code, 'milk_type_code' => $data->old_milk_type_code, 'milk_quality_type_code' => $data->old_milk_quality_type_code, 'qty' => $data->old_qty, 'fat' => $data->old_fat, 'snf' => $data->old_snf])->one();
    }

    public function getRateRange() {
        return $this->hasOne(TblUnionRatechartRange::className(), ['union_code' => 'union_code', 'animal_type_code' => 'milk_type_code']);
    }

    public function setModel(&$model) {
        $datetime = date('Y-m-d H:i:s');
        $model->status = 'Accept';
        $model->sms_status = 'n';
        if (strtolower($model->customer_type) == 'dcs') {
            $model->village_code = Yii::$app->general->getforeignkey($model->dcsCode, 'village_code');
            $model->route_code = Yii::$app->general->getforeignkey($model->dcsCode, 'route_code');
        } else {
            $model->village_code = Yii::$app->general->getforeignkey($model->mainCustomerCode, 'village_code');
            $model->route_code = Yii::$app->general->getforeignkey($model->mainCustomerCode, 'route_code');
        }
        $model->own_mcc_plant_code = $model->mcc_plant_code;
//        $model->own_bmc_code = $model->bmc_code;
        $model->dt_date = Yii::$app->formatter->asDate($datetime, DATE_FORMAT) . ' ' . \Yii::$app->general->getshift($model->shift_code);
        $model->last_edited_type = 'P';
        $model->sample_no = $model->getSampleNo();
    }

    public function milkTypeWiseUnique($model, $modelData, $approval = false, $update = false, $approvalUpdate = false, $importUpdate = false) {
        $flag = Yii::$app->general->getUnionConfiguration($modelData->union_code, 'collection_approval', 'PORTAL');
        if (strtolower($modelData->customer_type) == 'dcs') {
            $modelData->dcs_code = !empty($modelData->dcs_code) ? $modelData->dcs_code : $modelData->customer_code;
            $xclol = Yii::$app->general->getforeignkey($modelData->dcsCode, 'x_col1');
        } else {
            $xclol = Yii::$app->general->getforeignkey($modelData->mainCustomerCode, 'x_col1');
        }
        if (!empty($xclol)) {
            $value = explode('#', $xclol);
            $sameMilkType = !empty($value[0]) ? $value[0] : 0;
            $diffMilkType = !empty($value[1]) ? $value[1] : 0;
        } else {
            $sameMilkType = 0;
            $diffMilkType = 0;
        }
        $oldMilktype = !empty($model->oldAttributes['milk_type_code']) ? $model->oldAttributes['milk_type_code'] : '';
        $oldMilkqlttype = !empty($model->oldAttributes['milk_quality_type_code']) ? $model->oldAttributes['milk_quality_type_code'] : '';
        if ($approvalUpdate) {
            $oldMilktype = $modelData->old_milk_type_code;
            $oldMilkqlttype = $modelData->old_milk_quality_type_code;
        }
        if ($sameMilkType != 1 && $diffMilkType != 1) {
            $returnModel = $model->find()->where(['bmc_code' => $modelData->bmc_code,
                'customer_code' => $modelData->customer_code,
                'customer_type' => $modelData->customer_type,
                'cast(date_time_of_collection as date)' => date('Y-m-d', strtotime($modelData->date_time_of_collection)),
                'shift_code' => $modelData->shift_code]);
            if ($approval) {
                $returnModel->andWhere(['table_name' => 'tbl_bmc_collection']);
            }
            if ($update || $importUpdate) {
                $returnModel->andWhere(['!=', 'milk_collection_code', $modelData->milk_collection_code]);
            }
            if ($approvalUpdate) {
                $returnModel = $returnModel->count();
                if ($returnModel > 1) {
                    $modelData->addError('milk_type_code', "Record is Already Exist.");
                    return FALSE;
                } else {
                    $returnModel = '';
                }
            } else {
                $returnModel = $returnModel->one();
            }
        } else if ($sameMilkType != 1 && $diffMilkType == 1) {
            $returnModel = $model->find()->where(['bmc_code' => $modelData->bmc_code,
                'customer_code' => $modelData->customer_code,
                'customer_type' => $modelData->customer_type,
                'cast(date_time_of_collection as date)' => date('Y-m-d', strtotime($modelData->date_time_of_collection)),
                'shift_code' => $modelData->shift_code,
                'milk_type_code' => $modelData->milk_type_code]);
            if ($approval) {
                $returnModel->andWhere(['table_name' => 'tbl_bmc_collection']);
            }
            if (($update || $approvalUpdate || $importUpdate) && $modelData->milk_type_code == $oldMilktype) {
                $returnModel->andWhere(['!=', 'milk_type_code', $oldMilktype]);
            }
            $returnModel = $returnModel->one();
            if (($approval && $flag == 1 && !empty($returnModel)) || (!$approval && !empty($returnModel))) {
                $modelData->addError('milk_type_code', "Milk Type Must Not Same.");
                return FALSE;
            }
        } else if ($sameMilkType == 1 && $diffMilkType != 1) {
            $returnModel = $model->find()->where(['bmc_code' => $modelData->bmc_code,
                        'customer_code' => $modelData->customer_code,
                        'customer_type' => $modelData->customer_type,
                        'cast(date_time_of_collection as date)' => date('Y-m-d', strtotime($modelData->date_time_of_collection)),
                        'shift_code' => $modelData->shift_code])
                    ->andWhere(['!=', 'milk_type_code', $modelData->milk_type_code]);
            if ($approval) {
                $returnModel->andWhere(['table_name' => 'tbl_bmc_collection']);
            }
            $returnModel = $returnModel->one();
            if (!empty($returnModel)) {
                $modelData->addError('milk_type_code', "Milk Type Must Same.");
                return FALSE;
            }
            if (empty($returnModel)) {
                $returnModel = $model->find()->where([
                    'bmc_code' => $modelData->bmc_code,
                    'customer_code' => $modelData->customer_code,
                    'customer_type' => $modelData->customer_type,
                    'cast(date_time_of_collection as date)' => date('Y-m-d', strtotime($modelData->date_time_of_collection)),
                    'shift_code' => $modelData->shift_code,
                    'milk_type_code' => $modelData->milk_type_code,
                    'milk_quality_type_code' => $modelData->milk_quality_type_code,
                    'qty' => $modelData->qty, 'fat' => $modelData->fat, 'snf' => $modelData->snf]);
                if ($approval) {
                    $returnModel->andWhere(['table_name' => 'tbl_bmc_collection']);
                }
                if ($importUpdate) {
                    $returnModel->andWhere(['!=', 'milk_collection_code', $modelData->milk_collection_code]);
                }
                $returnModel = $returnModel->one();
            }
        } else if ($sameMilkType == 1 && $diffMilkType == 1) {
            $returnModel = $model->find()->where([
                'bmc_code' => $this->bmc_code,
                'customer_code' => $this->customer_code,
                'customer_type' => $this->customer_type,
                'cast(date_time_of_collection as date)' => date('Y-m-d', strtotime($this->date_time_of_collection)),
                'milk_type_code' => $this->milk_type_code,
                'milk_quality_type_code' => $this->milk_quality_type_code,
                'shift_code' => $this->shift_code,
                'qty' => $this->qty, 'fat' => $this->fat, 'snf' => $this->snf]);
            if ($approval) {
                $returnModel->andWhere(['table_name' => 'tbl_bmc_collection']);
            }
            if ($importUpdate) {
                $returnModel->andWhere(['!=', 'milk_collection_code', $modelData->milk_collection_code]);
            }
            $returnModel = $returnModel->one();
        }
        if (($approval && $flag == 1 && !empty($returnModel))) {
            $modelData->addError('milk_type_code', "Record is Already Exist In Approval.");
            return FALSE;
        }
        if (!$approval && !empty($returnModel)) {
            $modelData->addError('milk_type_code', "Record is Already Exist.");
            return FALSE;
        }
    }

    public function pastDateValidate($attribute, $params) {
        $this->date_time_of_collection = ($this->date_time_of_collection == '') ? null : date('Y-m-d', strtotime($this->date_time_of_collection));
        if (!empty($this->date_time_of_collection) && ($this->date_time_of_collection > date('Y-m-d'))) {
            $this->addError('date_time_of_collection', Yii::t('app/validation', $this->getAttributeLabel('date_time_of_collection') . ' Must be smaller than ' . date('d.m.Y')));
        }
    }

    public function validateMinLimit($attribute, $params) {
        $flag = Yii::$app->general->getforeignkey($this->mccPlantCode, 'has_min_qty_limit');
        $value = Yii::$app->general->getforeignkey($this->mccPlantCode, 'min_qty_limit');
        if (strtoupper($this->customer_type) == 'DCS' && $flag == 1 && $value > $this->qty) {
            $this->addError('qty', Yii::t('app/validation', $this->getAttributeLabel('qty') . ' Must be greater than ' . $value));
        }
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'customer_code']);
    }

}
