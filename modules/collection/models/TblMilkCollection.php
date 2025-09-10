<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\dcsoperation\models\TblMember;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblPurchaseRate;
use app\modules\geo\models\TblVillages;
use app\modules\dcsoperation\models\TblShift;
use app\modules\globalmaster\models\TblMilkQualityType;
use app\modules\general\models\TblSocietyVendor;
use app\modules\organisation\models\TblMccPlant;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblRouteMapping;
use app\modules\organisation\models\TblPlant;
use app\modules\organisation\models\TblUnions;
use app\modules\dcsoperation\models\TblPurchaseRateApplicability;
use app\modules\dcsoperation\models\TblPurchaseRateDetails;
use app\modules\collection\models\TblCollectionDataAlias;
use app\modules\configuration\models\TblUnionRatechartRange;
use app\modules\dcsoperation\models\TblMemberProvisional;
use app\modules\collection\models\TblProvisionalMilkCollection;
use app\modules\collection\models\TblProvisionalMilkCollectionHistory;
use app\modules\collection\models\TblMccShiftLock;
use app\modules\collection\models\TblAnalyzerCleaning;
use app\modules\collection\models\TblAnalyzerCalibration;
use app\modules\collection\models\TblAnalyzerSerialNo;
use app\modules\organisation\models\TblBmcMilkType;
use app\modules\general\models\TblApprovalStagesDetail;

/**
 * This is the model class for table "tbl_milk_collection".
 *
 * @property integer $milk_collection_code
 * @property string $member_code
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
 * @property string $purchase_rate_code
 * @property string $error_log
 * @property integer $ack
 * @property string $soc_bmc_flag
 * @property string $sms_status
 * @property string $sms_msgid
 * @property string $sms_mobile
 * @property string $sms_errorlog
 * @property string $sms_timestamp
 * @property integer $is_approved
 * @property integer $is_provisional

 *
 * @property TblAnimalType $milkTypeCode
 * @property TblDcs $dcsCode
 * @property TblMember $memberCode
 * @property TblPurchaseRate $rateCode
 * @property TblVillages $villageCode
 * @property TblMilkCollection $milkCollectionCode
 * @property TblMilkCollection $tblMilkCollection
 * @property integer $data_post_id
 * @property string $data_post_status
 * @property string $picked_datetime
 * @property string $resp_status
 * @property string $resp_desc
 */
class TblMilkCollection extends \app\models\ChildModel {

    public $collection_date, $member, $dcs_name, $org_type;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_collection';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['milk_type_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'milk_type_code');
                }, 'on' => 'importCsv'],
                [['shift_code'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalData($this, $attribute, 'shift');
                }, 'on' => 'importCsv'],
                [['fat', 'snf', 'bmc_code'], 'required', 'except' => ['create', 'create_allow', 'ho_sync_create']],
                [['union_code', 'plant_code', 'mcc_plant_code'], 'required', 'except' => ['create', 'importCsv', 'create_allow']],
                [['shift_code'], 'integer', 'message' => Yii::t('app/validation', '{attribute} is invalid.'), 'on' => ['importCsv']],
                [['milk_type_code'], 'integer', 'message' => Yii::t('app/validation', '{attribute} is invalid.'), 'on' => ['importCsv']],
                [['rtpl', 'amount'], 'trim'],
                [['member_code', 'dcs_code', 'name', 'mobile_no', 'auto_flag', 'village_code', 'type_of_data_receive', 'purchase_rate_code', 'error_log', 'soc_bmc_flag'], 'string', 'except' => ['sendsms', 'androidsync', 'androidsync_coll']],
                [['milk_type_code', 'shift_code', 'dcs_code', 'milk_type_code', 'qty'], 'required', 'except' => ['portal_data_post', 'post_sap_data', 'sendsms', 'androidsync', 'androidsync_coll']],
                [['milk_quality_type_code', 'amount', 'rtpl', 'member_code'], 'required', 'except' => ['importCsv', 'portal_data_post', 'post_sap_data', 'sendsms', 'androidsync', 'androidsync_coll', 'ho_sync_create', 'ho_sync_update']],
                [['member', 'sample_no'], 'required', 'on' => ['importCsv']],
                [['sample_no'], 'number', 'min' => 0, 'on' => ['importCsv']],
                [['milk_type_code', 'sample_no', 'ack'], 'integer', 'except' => ['sendsms', 'androidsync', 'androidsync_coll']],
                [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'clr', 'no_of_can'], 'number', 'except' => ['sendsms', 'androidsync', 'androidsync_coll']],
            //[['sms_status'],'default','n'],
//[['sms_msgid','sms_mobile','sms_errorlog','sms_timestamp'],'default',NULL],
            [['date_time_of_collection', 'date_time_of_recieve', 'sms_msgid', 'sms_mobile', 'sms_errorlog', 'sms_timestamp', 'sms_status', 'data_post_status', 'clr', 'status', 'qty_mode', 'qlty_time', 'qty_time', 'no_of_can', 'milk_quality_type_code', 'qlty_auto', 'qty_auto', 'collection_date', 'is_approved', 'data_post_id', 'picked_datetime', 'resp_status', 'resp_desc', 'shift_code', 'own_bmc_code', 'own_mcc_plant_code', 'member', 'tag_1', 'tag_2', 'error_desc', 'device_lat', 'device_long', 'mob_lat', 'mob_long', 'is_sms_sent', 'dcs_name', 'org_type'], 'safe'],
                [['milk_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type_code' => 'animal_type_code'], 'except' => ['sendsms', 'androidsync', 'androidsync_coll']],
                [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code'], 'except' => ['sendsms', 'androidsync', 'importCsv', 'androidsync_coll']],
                [['shift_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblShift::className(), 'targetAttribute' => ['shift_code' => 'id'], 'on' => ['importCsv']],
            //  [['member_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMember::className(), 'targetAttribute' => ['member_code' => 'member_code']],
// [['purchase_rate_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblPurchaseRate::className(), 'targetAttribute' => ['purchase_rate_code' => 'purchase_rate_code'], 'except' => ['sendsms']],
//            [['village_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVillages::className(), 'targetAttribute' => ['village_code' => 'village_code']],
//            [['milk_collection_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMilkCollection::className(), 'targetAttribute' => ['milk_collection_code' => 'milk_collection_code']],
            [['fat', 'snf', 'clr', 'water', 'qty', 'is_sms_sent'], 'default', 'value' => '0'],
                [['rtpl', 'amount'], 'default', 'value' => '0', 'except' => ['importCsv']],
                [['is_approved'], 'default', 'value' => '1'],
                [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'originating_type', 'protein', 'density', 'lactose', 'dcs_payment_cycle_code', 'milk_analyser_type_code', 'ws_code'], 'safe'],
                [['member_code', 'dcs_code', 'name', 'mobile_no', 'milk_type_code', 'fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'auto_flag', 'shift_code', 'date_time_of_collection', 'date_time_of_recieve', 'village_code', 'sample_no', 'type_of_data_receive', 'purchase_rate_code', 'error_log', 'ack', 'soc_bmc_flag', 'dt_date', 'sms_status', 'sms_msgid', 'sms_mobile', 'sms_errorlog', 'sms_timestamp', 'data_post_status', 'clr', 'status', 'qty_mode', 'qlty_time', 'qty_time', 'no_of_can', 'milk_quality_type_code', 'qlty_auto', 'qty_auto', 'created_at', 'created_by', 'updated_at', 'updated_by', 'route_code', 'bmc_code', 'converted_qty', 'is_approved', 'data_post_id', 'resp_status', 'resp_desc', 'picked_datetime', 'ftp_txn_file_name', 'tag_1', 'tag_2', 'ftp_txn_log_id', 'error_desc', 'originating_type', 'last_edited_type', 'remarks', 'sync_status', 'union_code', 'plant_code', 'mcc_plant_code', 'version_no', 'originating_org_code', 'originating_org_type', 'protein', 'density', 'lactose', 'dcs_payment_cycle_code', 'milk_analyser_type_code', 'ws_code', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'converted_qty_mode', 'incentive', 'deduction', 'total_amount', 'is_provisional', 'dpu_rtpl', 'dpu_amount', 'dpu_incentive', 'dpu_deduction', 'dpu_total_amount'], 'safe'],
                [['dcs_code'], 'setUuid', 'on' => ['androidsync']],
                [['bmc_code'], function ($attribute, $params) {
                    Yii::$app->general->validateBMC($this, $attribute, 'bmc_code');
                }, 'on' => ['importCsv']],
                [['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code'], 'on' => ['importCsv']],
                [['snf', 'rtpl'], 'double', 'min' => 0, 'on' => ['importCsv']],
                [['fat', 'qty'], 'double', 'min' => 0.01, 'message' => Yii::t('app/validation', '{attribute} must be greater than 0'), 'on' => ['importCsv']],
                [['date_time_of_collection'], 'convertDateDot', 'on' => ['importCsv']],
                [['date_time_of_collection'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
                [['date_time_of_collection'], 'convertDate', 'on' => ['importCsv']],
//            [['dcs_code'], 'unique', 'targetAttribute' => ['member_code', 'dcs_code', 'qty', 'fat', 'snf', 'milk_type_code', 'shift_code', 'date_time_of_collection', 'bmc_code'], 'message' => Yii::t('app/validation', 'Record is Already Exist.'), 'skipOnEmpty' => TRUE, 'when' => function($model) {
//                    return empty($this->getErrors());
//                }, 'on' => ['importCsv']],
            [['dcs_code'], 'pastDateValidate', 'on' => ['importCsv', 'create', 'androidsync_coll']],
                [['shift_code', 'milk_type_code'], 'ImportfieldSet', 'skipOnError' => true, 'on' => 'importCsv'],
                ['shift_code', 'in', 'range' => [1, 2], 'on' => ['importCsv'], 'skipOnEmpty' => TRUE, 'message' => Yii::t('app/validation', '{attribute} is invalid')],
                [['tag_1'], 'default', 'value' => 'X'],
                [['adt_param', 'adt_value', 'received_timestamp', 'is_rate_recalc', 'purchase_rate_code_old'], 'safe'],
                [['member_code'], 'validateUnique', 'on' => ['create', 'create_allow', 'ho_sync_create', 'importApproval']],
                [['milk_type_code'], 'validateUpdate', 'on' => ['update', 'update_allow', 'ho_sync_update']],
                [['bmc_code'], function ($attribute, $params) {
                    if (empty($this->getErrors())) {
                        Yii::$app->general->paymentCycleLock($this, 'date_time_of_collection', 'bmc_code', 'BMC', 'DCS', ['data_lock_member', 'billing_lock_member']);
                    }
                }, 'skipOnEmpty' => TRUE, 'on' => ['create', 'importCsv']],
                [['bmc_code'], function ($attribute, $params) {
                    if (empty($this->getErrors())) {
                        Yii::$app->general->paymentCycleLock($this, 'date_time_of_collection', 'bmc_code', 'BMC', 'DCS', ['data_lock_member', 'billing_lock_member', 'sync_lock_member']);
                    }
                }, 'skipOnEmpty' => TRUE, 'on' => ['androidsync_coll', 'importApproval', 'ho_sync_create', 'ho_sync_update', 'ho_sync_delete']],
                [['date_time_of_collection'], function ($attribute, $params) {
                    $this->data_post_status = 0;
                }, 'skipOnEmpty' => false, 'except' => ['post_sap_data']],
                [['is_rate_recalc'], 'default', 'value' => 0],
                [['bmc_code'], function ($attribute, $params) {
                    if (empty($this->getErrors())) {
                        Yii::$app->general->shiftLock($this, 'date_time_of_collection', 'mcc_plant_code', 'qty', 'member_lock');
                    }
                }, 'skipOnEmpty' => TRUE, 'on' => ['create', 'update', 'androidsync_coll', 'ho_sync_create', 'importApproval', 'ho_sync_update', 'ho_sync_delete']],
                [['antibiotic_sms_sent', 'antibiotic', 'is_antibiotic'], 'safe'],
                [['antibiotic_sms_sent'], 'default', 'value' => 0],
                [['scheme_rate', 'scheme_rate_code', 'actual_rate', 'other_reading'], 'safe'],
                [['qty'], 'qtyValidate', 'on' => ['create', 'update', 'ho_sync_create', 'ho_sync_update']],
                [['union_code', 'plant_code', 'mcc_plant_code', 'date_time_of_collection', 'milk_type_code', 'shift_code', 'dcs_code', 'fat', 'snf', 'bmc_code', 'qty', 'milk_quality_type_code', 'amount', 'member_code'], 'required', 'on' => ['ho_sync_create']],
                [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'clr', 'no_of_can'], 'number', 'on' => ['ho_sync_create']],
                [['antibiotic_sms_sent', 'water', 'is_sms_sent'], 'default', 'value' => '0', 'on' => ['ho_sync_create']],
                [['milk_type_code'], 'validateMilkType', 'on' => ['ho_sync_create', 'ho_sync_update']],
                [['rtpl'], 'validateRtpl', 'on' => ['ho_sync_create', 'ho_sync_update']],
                [['member_code'], 'validateDelete', 'on' => ['ho_sync_delete']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milk_collection_code' => Yii::t('app', 'Milk Collection Code'),
            'member_code' => Yii::t('app', 'Member'),
            'dcs_code' => Yii::t('app', 'Society'),
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
            'date_time_of_collection' => Yii::t('app', 'Collection Date'),
            'date_time_of_recieve' => Yii::t('app', 'Date Time Of Recieve'),
            'village_code' => Yii::t('app', 'Village Name'),
            'sample_no' => Yii::t('app', 'Sample No'),
            'type_of_data_receive' => Yii::t('app', 'Type Of Data Receive'),
            'purchase_rate_code' => Yii::t('app', 'Purchase Rate'),
            'error_log' => Yii::t('app', 'Error Log'),
            'ack' => Yii::t('app', 'Ack'),
            'soc_bmc_flag' => Yii::t('app', 'Soc Bmc Flag'),
            'sms_status' => Yii::t('app', 'SMS Status'),
            'sms_msgid' => Yii::t('app', 'SMS Msg id'),
            'sms_mobile' => Yii::t('app', 'SMS Mobile'),
            'sms_errorlog' => Yii::t('app', 'SMS Error Log'),
            'sms_timestamp' => Yii::t('app', 'SMS Timestamp'),
            'union_code' => Yii::t('app', 'Union'),
            'clr' => Yii::t('app', 'CLR'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'route_code' => Yii::t('app', 'Route'),
            'plant_code' => Yii::t('app', 'Plant'),
            'society_code' => Yii::t('app', 'Society Code'),
            'dcs_name' => Yii::t('app', 'Society Name'),
            'tag_1' => Yii::t('app', 'SAP Status'),
            'error_desc' => Yii::t('app', 'Status Desc.'),
            'originating_org_type' => Yii::t('app', 'Orignated Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'adt_param' => Yii::t('app', 'Adultration Param'),
            'adt_value' => Yii::t('app', 'Adultration Value'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type'),
            'device_lat' => Yii::t('app', 'Device Latitude'),
            'device_long' => Yii::t('app', 'Device Longitude'),
            'mob_lat' => Yii::t('app', 'Application Latitude'),
            'mob_long' => Yii::t('app', 'Application Longitude'),
            'antibiotic' => Yii::t('app', 'Antibiotic'),
            'scheme_rate' => Yii::t('app', 'Scheme Rate'),
            'scheme_rate_code' => Yii::t('app', 'Scheme Rate'),
            'actual_rate' => Yii::t('app', 'Actual Rate'),
            'org_type' => Yii::t('app', 'Originated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRateCode() {
        return $this->hasOne(TblPurchaseRate::className(), ['purchase_rate_code' => 'purchase_rate_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVillageCode() {
        return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkCollectionCode() {
        return $this->hasOne(TblMilkCollection::className(), ['milk_collection_code' => 'milk_collection_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMilkCollection() {
        return $this->hasOne(TblMilkCollection::className(), ['milk_collection_code' => 'milk_collection_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    /**
     * @inheritdoc
     * @return TblMilkCollectionQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblMilkCollectionQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkQualityCode() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
    }

    public function getMilkCollData() {
        return $this->find()
                        ->joinWith(['societyVendorCode'])
                        ->where(['or', ['data_post_status' => [0, 3]], ['data_post_status' => NULL]])
                        ->andWhere(['tbl_society_vendor.vendor_code' => 'STELLAPPS'])
                        ->limit(200)
                        ->orderby('date_time_of_collection ASC')
                        ->all();
    }

    public function updateMilkColl($value) {
        return $this->updateAll(['data_post_status' => 1], ['milk_collection_code' => $value]);
    }

    public function getSocietyVendorCode() {
        return $this->hasOne(TblSocietyVendor::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getCollection($data) {
        $from_date = $data['from_date'];
        $to_date = $data['to_date'];
        return $this->find()->select(['member_code', 'fat', 'snf', 'qty', 'amount', 'date_time_of_collection', 'sample_no', 'shift_code', 'clr', 'rtpl', 'qlty_auto', 'qty_auto', 'milk_type_code'])
                        ->where(['member_code' => $this->member_code])->andWhere("date_time_of_collection between '$from_date' and '$to_date' ")->orderBy(['date_time_of_collection' => SORT_ASC, 'shift_code' => SORT_ASC, 'sample_no' => SORT_DESC])->all();
    }

    public function getCollectionDatewise() {
        return $this->find()->select(['member_code', 'fat', 'snf', 'qty', 'amount', 'date_time_of_collection', 'sample_no', 'shift_code', 'clr', 'rtpl', 'qlty_auto', 'qty_auto', 'milk_type_code'])
                        ->where(['member_code' => $this->member_code, 'cast(date_time_of_collection as date)' => $this->collection_date])->orderBy(['date_time_of_collection' => SORT_ASC, 'shift_code' => SORT_ASC, 'sample_no' => SORT_DESC])->all();
    }

    public function memberCollectionData($from_date, $to_date, $society_code) {
        return $this->find()->select(['member_code', 'name', 'milk_type_code', 'fat', 'snf', 'qty', 'rtpl', 'amount', 'shift_code', 'date_time_of_collection', 'sample_no'])
                        ->where(['dcs_code' => $society_code])
                        ->andFilterWhere(['>=', 'date_time_of_collection', $from_date])
                        ->andFilterWhere(['<=', 'date_time_of_collection', $to_date])
                        ->all();
    }

    public function getSampleNo() {
        $data = $this->find()
                ->select('max(sample_no) as sample_no')
                ->where(['dcs_code' => $this->dcs_code, 'shift_code' => $this->shift_code, 'CONVERT(date,date_time_of_collection)' => Yii::$app->controls->view_date($this->date_time_of_collection, 'php:Y-m-d')])
                ->one();
        $sample_no = (int) $data['sample_no'] + 1;
        return $sample_no;
    }

    public function getExistingData() {
        return $this->find()
                        ->where(['dcs_code' => $this->dcs_code, 'sample_no' => $this->sample_no, 'milk_type_code' => $this->milk_type_code, 'date_time_of_collection' => $this->date_time_of_collection, 'shift_code' => $this->shift_code])
                        ->one();
    }

    public function setUuid($attribute, $params) {
        $this->data_post_id = !empty($this->data_post_id) ? $this->data_post_id : $this->x_col1;
        $this->dpu_rtpl = $this->rtpl;
        $this->dpu_amount = $this->amount;
        $this->dpu_incentive = $this->incentive;
        $this->dpu_deduction = $this->deduction;
        $this->dpu_total_amount = $this->total_amount;
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getRouteCode() {
        return $this->hasOne(TblRouteMapping::className(), ['route_code' => 'route_code']);
    }

    public function getPlantCode() {
        return $this->hasOne(TblPlant::className(), ['plant_code' => 'plant_code']);
    }

    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    public function getRateRange() {
        return $this->hasOne(TblUnionRatechartRange::className(), ['union_code' => 'union_code', 'animal_type_code' => 'milk_type_code'])->where(['config_for' => 'VLC']);
    }

    public function ImportfieldSet($attribute, $params) {
        if (empty($this->getErrors())) {
            $dcs = new TblDcs();
            $this->dcs_code = $dcs->validDcs($this->dcs_code, $this->bmc_code);
            $this->date_time_of_collection = !empty($this->date_time_of_collection) ? date('Y-m-d', strtotime($this->date_time_of_collection)) : '';
            $this->date_time_of_collection = $this->date_time_of_collection . ' ' . \Yii::$app->general->getshift($this->shift_code);
            if (empty($this->dcs_code)) {
                $this->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'DCS') . ' is invalid'));
            } else {
                $bmc = Yii::$app->general->getforeignkey($this->dcsCode, 'bmc_code');
                if (!empty($this->bmc_code) && ($this->bmc_code != $bmc)) {
                    $this->addError('bmc_code', Yii::t('app/validation', $this->getAttributeLabel('bmc_code') . ' is invalid'));
                }
                $this->union_code = Yii::$app->general->getforeignkey($this->dcsCode, 'union_code');
                $this->member_code = $this->dcs_code . str_pad(substr($this->member, -4), 4, '0', STR_PAD_LEFT);
                $config = isset(Yii::$app->session->get('unionConfig')[$this->union_code]['member_collection_check']) ? Yii::$app->session->get('unionConfig')[$this->union_code]['member_collection_check'] : '';
                if (empty($this->memberCode) && !empty($this->member) && empty($config)) {
                    $this->addError('member_code', Yii::t('app/validation', $this->getAttributeLabel('member_code') . ' invalid '));
                }
                $this->mcc_plant_code = Yii::$app->general->getforeignkey($this->dcsCode, 'mcc_plant_code');
                $this->plant_code = Yii::$app->general->getforeignkey($this->dcsCode, 'plant_code');
                $this->milk_quality_type_code = 1;
                Yii::$app->general->validateDeactivateDcs($this, $this->date_time_of_collection, '', TRUE);
                $this->mobile_no = Yii::$app->general->getforeignkey($this->memberCode, 'mobile_no');
                $this->name = Yii::$app->general->getforeignkey($this->memberCode, 'member_name');
                $this->village_code = Yii::$app->general->getforeignkey($this->dcsCode, 'village_code');
                $datetime = date('Y-m-d H:i:s');
                $this->date_time_of_recieve = $datetime;
                $this->dt_date = Yii::$app->formatter->asDate($datetime, DATE_FORMAT) . ' ' . \Yii::$app->general->getshift($this->shift_code);
                $this->qlty_time = $datetime;
                $this->qty_time = $datetime;
                $this->type_of_data_receive = 'import';
                $this->status = 'Accept';
                $this->qty_mode = 0;
                $this->qlty_auto = 0;
                $this->qty_auto = 0;
                $this->sms_status = 'n';
                $this->route_code = Yii::$app->general->getforeignkey($this->dcsCode, 'route_code');
//                $this->sample_no = $this->getSampleNo();
                $this->last_edited_type = 'P';
                $this->own_mcc_plant_code = $this->mcc_plant_code;
                $this->own_bmc_code = $this->bmc_code;
//                $this->milkTypeWiseUnique($this, $this, FALSE, FALSE);
                Yii::$app->general->validateRateRange($this);
//set rtpl,rate_code and amount
                if (empty($this->getErrors()) && $this->amount === '' && $this->rtpl === '') {
                    $data['milk_type'] = $this->milk_type_code;
                    $data['milk_quality_type'] = $this->milk_quality_type_code;
                    $data['fat'] = $this->fat;
                    $data['snf'] = $this->snf;
                    $data['shift'] = $this->shift_code;
                    $model = new TblPurchaseRateApplicability();
                    $model->dcs_code = $this->dcs_code;
                    $model->wef_date = $this->dt_date;
                    $rateClass = Yii::$app->general->getforeignkey($this->memberCode, 'rate_class');
                    $data['rate_class'] = empty($rateClass) ? 0 : $rateClass;
                    $model_data = $model->getPurchaseRateApplicableData($data);

                    if (!empty($model_data)) {
                        $detail_model = new TblPurchaseRateDetails();
                        $detail_model->rate_type_code = $model_data->rate_app_code;
                        $detail_model->purchase_rate_code = $model_data->purchase_rate_code;
                        $rate_type = $detail_model->rateTypeCode->rate_type;
                        $detail_data = $detail_model->getPurchasseRateDetailData($data, $rate_type);
                        if (!empty($detail_data)) {
                            $this->purchase_rate_code = (string) $detail_data->purchase_rate_code;
                            $this->rtpl = $detail_data->rtpl;
                            $this->amount = $detail_data->rtpl * $this->qty;
                        } else {
                            $this->addError('rtpl', Yii::t('app/validation', $this->getAttributeLabel('rtpl') . ' not available'));
                        }
                    } else {
                        $this->addError('rtpl', Yii::t('app/validation', $this->getAttributeLabel('rtpl') . ' not available'));
                    }
                } else if (empty(floatval($this->rtpl)) && !empty(floatval($this->amount))) {
                    $this->rtpl = $this->amount / $this->qty;
                } else if (!empty(floatval($this->rtpl)) && empty(floatval($this->amount))) {
                    $this->amount = $this->rtpl * $this->qty;
                } else if (empty(floatval($this->rtpl)) || empty(floatval($this->amount))) {
                    $this->amount = 0;
                    $this->rtpl = 0;
                }
            }
        }
    }

    public function pastDateValidate($attribute, $params) {
        $date_time_of_collection = !empty($this->date_time_of_collection) ? date('Y-m-d', strtotime($this->date_time_of_collection)) : NULL;
        if (!empty($date_time_of_collection) && ($date_time_of_collection > date('Y-m-d'))) {
            $this->addError('date_time_of_collection', Yii::t('app/validation', $this->getAttributeLabel('date_time_of_collection') . ' Must be smaller than ' . date('d.m.Y')));
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

    public function getApprovalData() {
        return $this->hasOne(TblCollectionDataAlias::className(), ['member_code' => 'member_code', 'old_milk_type_code' => 'milk_type_code', 'shift_code' => 'shift_code', 'date_time_of_collection' => 'date_time_of_collection', 'amount' => 'amount'])->andOnCondition(['tbl_collection_data_alias.table_name' => 'tbl_milk_collection', 'action_perform' => 'DELETE']);
    }

    public function validateUnique($attribute, $params) {
        $flag = Yii::$app->general->getUnionConfiguration($this->union_code, 'collection_approval', 'PORTAL');
        $qtyWiseCollConfig = Yii::$app->general->getUnionConfiguration($this->union_code, 'qty_wise_collection', 'VLC');

        $model = new TblMember();
        $data = $model->validMember($this->member_code);
        if (empty($data)) {
            $this->addError('member_code', Yii::t('app/validation', $this->getAttributeLabel('member_code') . ' is Invalid'));
        }

        Yii::$app->general->validateDeactivateDcs($this, $this->date_time_of_collection, '', TRUE);
        if ($qtyWiseCollConfig != 1) {
            Yii::$app->general->validateRateRange($this);
        }
        $ApprovalModel = new TblCollectionDataAlias();
        $this->milkTypeWiseUnique($ApprovalModel, $this, TRUE);
        $this->milkTypeWiseUnique($this, $this);
    }

    public function validateUpdate($attribute, $params) {
        $flag = Yii::$app->general->getUnionConfiguration($this->union_code, 'collection_approval', 'PORTAL');
        $ApprovalModel = new TblCollectionDataAlias();
        $oldMilktype = $this->oldAttributes['milk_type_code'];
        if (!empty($this->oldAttributes) && ($this->fat != $this->oldAttributes['fat'] || $this->snf != $this->oldAttributes['snf'] || $this->qty != $this->oldAttributes['qty'] || $this->milk_type_code != $this->oldAttributes['milk_type_code'] || $this->milk_quality_type_code != $this->oldAttributes['milk_quality_type_code'])) {
            $existTableData = $ApprovalModel->find()->where(['dcs_code' => $this->dcs_code, 'member_code' => $this->member_code, 'cast(date_time_of_collection as date)' => $this->date_time_of_collection, 'old_milk_type_code' => $this->oldAttributes['milk_type_code'], 'shift_code' => $this->shift_code, 'old_qty' => $this->oldAttributes['qty'], 'old_fat' => $this->oldAttributes['fat'], 'old_snf' => $this->oldAttributes['snf'], 'table_name' => 'tbl_milk_collection', 'old_milk_quality_type_code' => $this->oldAttributes['milk_quality_type_code']])->one();
            if (($flag == 1 || $flag == 2) && !empty($existTableData)) {
                $this->addError($attribute, "Record is Already Exist For Approval.");
            }
            $this->milkTypeWiseUnique($ApprovalModel, $this, TRUE);
            $this->milkTypeWiseUnique($this, $this, FALSE, TRUE);
            Yii::$app->general->validateRateRange($this);
            if ($flag != 1 && $this->scenario != 'update_allow') {
                Yii::$app->general->paymentCycleLock($this, 'date_time_of_collection', 'bmc_code', 'BMC', 'DCS', ['data_lock_member', 'billing_lock_member'], 'milk_type_code');
            }
        }
    }

    public function getExistingCollection($data) {
        return $this->find()->where(['dcs_code' => $data->dcs_code, 'member_code' => $data->member_code, 'date_time_of_collection' => $data->date_time_of_collection, 'milk_type_code' => $data->old_milk_type_code, 'shift_code' => $data->shift_code, 'qty' => $data->old_qty, 'fat' => $data->old_fat, 'snf' => $data->old_snf, 'milk_quality_type_code' => $data->old_milk_quality_type_code])->one();
    }

    public function setModel(&$model) {
        $datetime = date('Y-m-d H:i:s');
        $model->status = 'Accept';
        $model->sms_status = 'n';
        $model->mobile_no = Yii::$app->general->getforeignkey($model->memberCode, 'mobile_no');
        $model->name = Yii::$app->general->getforeignkey($model->memberCode, 'member_name');
        $model->village_code = Yii::$app->general->getforeignkey($model->dcsCode, 'village_code');
        $model->dt_date = empty($datetime) ? NULL : Yii::$app->controls->view_date($datetime, 'php:Y-m-d') . ' ' . \Yii::$app->general->getshift($model->shift_code);
        $model->route_code = Yii::$app->general->getforeignkey($model->dcsCode, 'route_code');
        $model->last_edited_type = 'P';
        $model->own_mcc_plant_code = $model->mcc_plant_code;
        $model->own_bmc_code = $model->bmc_code;
        $model->sample_no = $model->getSampleNo();
    }

    public function milkTypeWiseUnique($model, &$modelData, $approval = false, $update = false, $approvalUpdate = false, $importUpdate = false) {
        if ($modelData->antibiotic === 'AB+') {
            $modelData->is_antibiotic = 1;
        } else {
            $modelData->is_antibiotic = 0;
        }
        $flag = Yii::$app->general->getUnionConfiguration($modelData->union_code, 'collection_approval', 'PORTAL');
        $sameMilkType = Yii::$app->general->getUnionConfiguration($modelData->union_code, 'multi_entry_same_milk', 'VLC');
        $diffMilkType = Yii::$app->general->getUnionConfiguration($modelData->union_code, 'multi_entry_other_milk', 'VLC');
        $uniqueCheckAntibiotic = Yii::$app->general->getUnionConfiguration($modelData->union_code, 'unique_check_with_antibiotic', 'VLC');
        $oldMilktype = !empty($model->oldAttributes['milk_type_code']) ? $model->oldAttributes['milk_type_code'] : '';
        $is_antibiotic = $modelData->is_antibiotic;
        if ($approvalUpdate) {
            $oldMilktype = $modelData->old_milk_type_code;
        }
        if ($sameMilkType != 1 && $diffMilkType != 1) {
            $returnModel = $model->find()->where(['dcs_code' => $modelData->dcs_code,
                'member_code' => $modelData->member_code,
                'cast(date_time_of_collection as date)' => date('Y-m-d', strtotime($modelData->date_time_of_collection)),
                'shift_code' => $modelData->shift_code]);
            if ($uniqueCheckAntibiotic == 1) {
                $returnModel->andWhere(['=', 'is_antibiotic', $is_antibiotic]);
            }
            if ($approval) {
                $returnModel->andWhere(['table_name' => 'tbl_milk_collection']);
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
            $returnModel = $model->find()->where(['dcs_code' => $modelData->dcs_code,
                'member_code' => $modelData->member_code,
                'cast(date_time_of_collection as date)' => date('Y-m-d', strtotime($modelData->date_time_of_collection)),
                'shift_code' => $modelData->shift_code,
                'milk_type_code' => $modelData->milk_type_code]);
            if ($uniqueCheckAntibiotic == 1) {
                $returnModel->andWhere(['=', 'is_antibiotic', $is_antibiotic]);
            }
            if ($approval) {
                $returnModel->andWhere(['table_name' => 'tbl_milk_collection']);
            }
            if ($update || $approvalUpdate || $importUpdate) {
                $returnModel->andWhere(['!=', 'milk_type_code', $oldMilktype]);
            }
            $returnModel = $returnModel->one();
            if ((!empty($returnModel))) {
                $modelData->addError('milk_type_code', "Milk Type Must Not Same.");
                return FALSE;
            }
        } else if ($sameMilkType == 1 && $diffMilkType != 1) {
            $returnModel = $model->find()->where(['dcs_code' => $modelData->dcs_code,
                        'member_code' => $modelData->member_code,
                        'cast(date_time_of_collection as date)' => date('Y-m-d', strtotime($modelData->date_time_of_collection)),
                        'shift_code' => $modelData->shift_code])
                    ->andWhere(['!=', 'milk_type_code', $modelData->milk_type_code]);
            if ($uniqueCheckAntibiotic == 1) {
                $returnModel->andWhere(['=', 'is_antibiotic', $is_antibiotic]);
            }
            if ($approval) {
                $returnModel->andWhere(['table_name' => 'tbl_milk_collection']);
            }
            $returnModel = $returnModel->one();
            if (!empty($returnModel)) {
                $modelData->addError('milk_type_code', "Milk Type Must Same.");
                return FALSE;
            }
            if (empty($returnModel)) {
                $returnModel = $model->find()->where([
                    'dcs_code' => $modelData->dcs_code,
                    'member_code' => $modelData->member_code,
                    'cast(date_time_of_collection as date)' => date('Y-m-d', strtotime($modelData->date_time_of_collection)),
                    'shift_code' => $modelData->shift_code,
                    'milk_type_code' => $modelData->milk_type_code,
                    'qty' => $modelData->qty, 'fat' => $modelData->fat, 'snf' => $modelData->snf]);
                if ($uniqueCheckAntibiotic == 1) {
                    $returnModel->andWhere(['=', 'is_antibiotic', $is_antibiotic]);
                }
                if ($approval) {
                    $returnModel->andWhere(['table_name' => 'tbl_milk_collection']);
                }
                if ($importUpdate) {
                    $returnModel->andWhere(['!=', 'milk_collection_code', $modelData->milk_collection_code]);
                }
                $returnModel = $returnModel->one();
            }
        } else if ($sameMilkType == 1 && $diffMilkType == 1) {
            $returnModel = $model->find()->where(['dcs_code' => $modelData->dcs_code,
                'member_code' => $modelData->member_code,
                'cast(date_time_of_collection as date)' => date('Y-m-d', strtotime($modelData->date_time_of_collection)),
                'milk_type_code' => $modelData->milk_type_code,
                'shift_code' => $modelData->shift_code,
                'qty' => $modelData->qty, 'fat' => $modelData->fat, 'snf' => $modelData->snf]);
            if ($uniqueCheckAntibiotic == 1) {
                $returnModel->andWhere(['=', 'is_antibiotic', $is_antibiotic]);
            }
            if ($approval) {
                $returnModel->andWhere(['table_name' => 'tbl_milk_collection']);
            }
            if ($importUpdate) {
                $returnModel->andWhere(['!=', 'milk_collection_code', $modelData->milk_collection_code]);
            }
            $returnModel = $returnModel->one();
        }
        if (($approval && ($flag == 1 || $flag == 2) && !empty($returnModel))) {
            $modelData->addError('milk_type_code', "Record is Already Exist In Approval.");
            return FALSE;
        }
        if (!$approval && !empty($returnModel)) {
            $modelData->addError('milk_type_code', "Record is Already Exist.");
            return FALSE;
        }
    }

    public function setChildTable(&$model, &$modelSave, &$errors) {
        $config = isset(Yii::$app->session->get('unionConfig')[$model->union_code]['member_collection_check']) ? Yii::$app->session->get('unionConfig')[$model->union_code]['member_collection_check'] : '';
        if (empty($model->memberCode) && !empty($model->member) && !empty($config)) {
            if ($config == 1) {
                $memberModel = new TblMember();
                $memberModel->member_code = $memberModel->getCode();
                $this->setMemberModel($model, $memberModel);
                if (!$memberModel->validate()) {
                    $errors[] = $memberModel->getErrors();
                }
                array_push($modelSave, $memberModel);
            } elseif ($config == 2) {
                $memberPrModel = new TblMemberProvisional();
                $this->setMemberModel($model, $memberPrModel);
                $existData = $memberPrModel::find()->where(['member_code' => $memberPrModel->member_code])->one();
                if (empty($existData)) {
                    $memberPrModel->pro_ex_member_code = $memberPrModel->ex_member_code;
                    $memberPrModel->provisional_member_code = Yii::$app->general->getPrimaryCode($memberPrModel);
                    $memberPrModel->is_approved = 0;
                    if (!$memberPrModel->validate()) {
                        $errors[] = $memberPrModel->getErrors();
                    }
                    array_push($modelSave, $memberPrModel);
                }
                $updateModel = TblProvisionalMilkCollection::find()->where(['dcs_code' => $model->dcs_code, 'member_code' => $model->member_code, 'sample_no' => $model->sample_no, 'date_time_of_collection' => $model->date_time_of_collection, 'shift_code' => $model->shift_code])->one();
                if (!empty($updateModel)) {
                    $HistoryModel = new TblProvisionalMilkCollectionHistory();
                    Yii::$app->operation->history($updateModel, $HistoryModel, UPDATE);
                    array_push($modelSave, $HistoryModel);
                } else {
                    $updateModel = new TblProvisionalMilkCollection();
//                    $updateModel->provisional_milk_collection_code = Yii::$app->general->getCodeAutoIncrement($updateModel);
                }
                $updateModel->attributes = $model->attributes;
                $updateModel->send_status = 0;
                $model = $updateModel;
            }
        } else {
            $existData = $model::find()->where(['dcs_code' => $model->dcs_code, 'member_code' => $model->member_code, 'sample_no' => $model->sample_no, 'date_time_of_collection' => $model->date_time_of_collection, 'shift_code' => $model->shift_code])->one();
            if (!empty($existData)) {
                $model->milkTypeWiseUnique($model, $model, FALSE, FALSE, FALSE, TRUE);
            } else {
//                $model->sample_no = $this->getSampleNo();
                $model->milkTypeWiseUnique($model, $model, FALSE, FALSE);
            }
        }
    }

    public function setMemberModel($model, &$memberModel) {
        $memberModel->attributes = $model->attributes;
        $memberModel->scenario = 'collection';
        $memberModel->ex_member_code = str_pad($model->member, 4, '0', STR_PAD_LEFT);
        $memberModel->animal_type_code = $model->milk_type_code;
        $memberModel->address = 'No Address';
        $memberModel->no_of_buffalo = $memberModel->no_of_cow_cross = $memberModel->no_of_cow_ind = $memberModel->total_animals = 0;
        $memberModel->member_type_code = '1';
        $memberModel->member_name = 'No Name';
        $memberModel->state_code = !empty(Yii::$app->general->getforeignkey($model->dcsCode, 'state_code')) ? Yii::$app->general->getforeignkey($model->dcsCode, 'state_code') : NULL;
        $memberModel->district_code = !empty(Yii::$app->general->getforeignkey($model->dcsCode, 'district_code')) ? Yii::$app->general->getforeignkey($model->dcsCode, 'district_code') : NULL;
        $memberModel->sub_district_code = !empty(Yii::$app->general->getforeignkey($model->dcsCode, 'sub_district_code')) ? Yii::$app->general->getforeignkey($model->dcsCode, 'sub_district_code') : NULL;
        $memberModel->hamlet_code = !empty(Yii::$app->general->getforeignkey($model->dcsCode, 'hamlet_code')) ? Yii::$app->general->getforeignkey($model->dcsCode, 'hamlet_code') : NULL;
        $memberModel->village_code = !empty(Yii::$app->general->getforeignkey($model->dcsCode, 'village_code')) ? Yii::$app->general->getforeignkey($model->dcsCode, 'village_code') : NULL;
    }

    public function getCollectionData($data) {
        $fromDate = $data->date_time_of_dispatch;
        $toDate = $data->date_time_of_dispatch;

        if ($data->shift_code == 2) {
            $fromDate = date('Y-m-d', strtotime($data->date_time_of_dispatch)) . '' . ' 06:00:00';
        } else
        if ($data->shift_code == 1) {
            $fromDate = date('Y-m-d', strtotime($data->date_time_of_dispatch . ' -1 day')) . '' . ' 18:00:00';
        }
        return $this->find()
                        ->where(['dcs_code' => $data->dcs_code, 'bmc_code' => $data->bmc_code, 'mcc_plant_code' => $data->mcc_plant_code])
                        ->andWhere(['>=', 'date_time_of_collection', $fromDate])
                        ->andWhere(['<=', 'date_time_of_collection', $toDate])
                        ->andWhere(['or', ['IS', 'antibiotic_sms_sent', NULL], ['antibiotic_sms_sent' => ''], ['antibiotic_sms_sent' => '0']])
                        ->all();
    }

    public function getShiftLock() {
        return $this->hasOne(TblMccShiftLock::className(), ['mcc_plant_code' => 'mcc_plant_code', 'cast(date_time_of_collection as date)' => 'cast(date_time_of_collection as date)', 'shift_code' => 'shift_code'])->andOnCondition(['member_lock' => 1])->select('tset');
    }

    public function qtyValidate($attribute, $params) {
        $config = Yii::$app->general->getUnionConfigResult($this->union_code, 'max_qty_limit_member');
        if ($config > 0 && $this->qty > $config) {
            $this->addError('qty', Yii::t('app/validation', $this->getAttributeLabel('qty') . ' must not be greater than ' . $config));
        }
    }

    public function setCleaningCalibration($model, &$modelSave) {
        /*
          //FATSCAN Format Clening Calibration
          //  $model->other_reading = '{"CLE":"##EIPL-MA##\u0003dw04/06/24 14:20 01 006,04/06/24 12:33 01 000,04/06/24 12:23 01 005,12/03/24 15:48 01000,12/03/24 16:04 01 000,2","CAL":"##EIPL-MA##\u0003cc 0.00, 0.00,  0.0, 0.00,  0.0,2#####cb 0.00, 0.00,  0.0, 0.00,  0.0,2#####cm 0.00, 0.00,  0.0, 0.00,  0.0,2"}';

          //BIPL Format Clening
          //  $model->other_reading = '{"CLE":"\u001b@#####----------------------------------------##########          Cleaning Log Report ##########CP Name: Benny Impex Private Limited #####CP Code: CPOINT_1 #####Date / Time: 03/06/24 18:04:35#####Record :10/10##########RNO  Date  Time OP SR CY   Results   Mea###############MLMS SN:020021040387#####  1 270224 1143 CL WA  1  N/A   N/A    0#####  2 270224 1129 CL WA  1  N/A   N/A    0#####  3 270224 1121 CL WA  5  N/A   N/A    2#####  4 200224 1214 CL WA  2  N/A   N/A    2#####  5 200224 1211 RT NA NA  N/A   N/A    2#####  6 200224 1209 RT NA NA  N/A   N/A    2#####  7 200224 1207 RT NA NA  N/A   N/A    2#####  8 090124 1218 CL WA  2  N/A   N/A   12#####  9 090124 1216 RT NA NA  N/A   N/A   12##### 10 090124 1119 RT NA NA  N/A   N/A   12#####Cleaning Log End"}';

          // BIPL Format Calibration
          //  $model->other_reading = '{"CAL":"\u001b@#####----------------------------------------##########        Calibration Log Report ##########CP Name: Benny Impex Private Limited #####CP Code: CPOINT_1 #####Date / Time: 03/06/24 18:01:18#####Record :6/6##########RNO  Date   Time  M C P Input Total User###############MLMS SN:020021040387#####  1 280524 163530 M M S +2.00 +2.00 ADMIN#####  2 280524 163525 M M F +1.00 +1.00 ADMIN#####  3 280524 163517 M B S +0.00 +0.00 ADMIN#####  4 280524 163503 M B F +1.00 +1.00 ADMIN#####  5 280524 163257 M C S +0.20 +1.10 ADMIN#####  6 280524 163248 M C F +0.10 -0.60 ADMIN#####Calibration Log End"}';
         */
        $otherReading = json_decode($model->other_reading);
        $cal = !empty($otherReading->CAL) ? preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $otherReading->CAL) : null;
        $cle = !empty($otherReading->CLE) ? preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $otherReading->CLE) : null;
        if (!empty($cal) && substr($model->member_code, -4) == '2097') {
            $this->setCalibrationData($model, $modelSave, $cal);
        } else if (!empty($cle) && substr($model->member_code, -4) == '2098') {
            $this->setCleaningData($model, $modelSave, $cle);
        }
    }

    public function setCleaningData($model, &$modelSave, $cle) {
        $cleaning = new TblAnalyzerCleaning();
        $cleaning->date_time_of_cleaning = $model->date_time_of_collection;
        $cleaning->shift_code = $model->shift_code;
        $cleaning->union_code = $model->union_code;
        $cleaning->plant_code = $model->plant_code;
        $cleaning->mcc_plant_code = $model->mcc_plant_code;
        $cleaning->bmc_code = $model->bmc_code;
        $cleaning->dcs_code = $model->dcs_code;
        $cleaning->created_at = $model->created_at;
        $cleaning->created_by = $model->created_by;
        $cleaning->originating_org_code = $model->union_code;
        $cleaning->originating_org_type = $model->originating_org_type;
        $cleaning->originating_type = $model->originating_type;
        $cleaning->analyzer_cleaning_code = Yii::$app->general->getPrimaryCode($cleaning);
        $cleaning->originating_org_code = $model->originating_org_code;
        $cleaning->counter = 1;
        $cleaning->measuring = 0;
        $cleaning->milk_analyser_type_code = $model->milk_analyser_type_code;
        $cleaning->x_col1 = $model->x_col1;

        $pk_data = explode('-', $cleaning->analyzer_cleaning_code);
        $pk_code = $pk_data[2];
        $auto_inc = 0;
        $cle_bipl = strstr($cle, 'MLMS SN');
        $cle_eipl = strstr($cle, 'dw');
        if ($cle_bipl) {
            /* BIPL Cleaning Parsing */
            $strArr = explode('#####', $cle_bipl);
            $count = count($strArr);
            if (is_array($strArr) && $count > 2) {
                unset($strArr[0]);
                unset($strArr[$count - 1]);

                foreach ($strArr as $strVal) {
                    $strVal = ltrim($strVal);
                    $strVal = rtrim($strVal);
                    $strVal = preg_replace('!\s+!', ' ', $strVal);
                    $data = explode(' ', $strVal);
                    if (count($data) > 8) {
                        if (in_array($data[3], ['CL'])) {
                            $cel_model = new TblAnalyzerCleaning();
                            $cel_model->attributes = $cleaning->attributes;
                            $pk_data[2] = $pk_code + $auto_inc;
                            $cel_model->analyzer_cleaning_code = implode('-', $pk_data);
                            $datetime = \DateTime::createFromFormat('dmyHi', $data[1] . $data[2])->format('Y-m-d H:i:s');
                            $cel_model->date_time_of_actual_cleaning = $datetime;
                            $cel_model->cycle = $data[5];
                            $cel_model->measuring = $data[8];
                            array_push($modelSave, $cel_model);
                            $auto_inc++;
                        }
                    }
                }
            }
            /* BIPL Cleaning Parsing */
        } else if ($cle_eipl) {
            /* FATSCAN Cleaning Parsing */
            $cle_eipl = substr($cle_eipl, 2);
            $strArr = explode(',', $cle_eipl);
            foreach ($strArr as $strVal) {
                $data = explode(' ', $strVal);
                if (count($data) > 3) {
                    $cel_model = new TblAnalyzerCleaning();
                    $cel_model->attributes = $cleaning->attributes;
                    $pk_data[2] = $pk_code + $auto_inc;
                    $cel_model->analyzer_cleaning_code = implode('-', $pk_data);
                    $datetime = \DateTime::createFromFormat('d/m/yH:i', $data[0] . $data[1])->format('Y-m-d H:i:s');
                    $cel_model->date_time_of_actual_cleaning = $datetime;
                    $cel_model->cycle = $data[2];
                    $cel_model->counter = $data[3];
                    array_push($modelSave, $cel_model);
                    $auto_inc++;
                }
            }
            /* FATSCAN Cleaning Parsing */
        }
    }

    public function setCalibrationData($model, &$modelSave, $cal) {
        $calibration = new TblAnalyzerCalibration();
        $calibration->date_time_of_calibration = $calibration->date_time_of_actual_calibration = $model->date_time_of_collection;
        $calibration->shift_code = $model->shift_code;
        $calibration->union_code = $model->union_code;
        $calibration->plant_code = $model->plant_code;
        $calibration->mcc_plant_code = $model->mcc_plant_code;
        $calibration->bmc_code = $model->bmc_code;
        $calibration->dcs_code = $model->dcs_code;
        $calibration->created_at = $model->created_at;
        $calibration->created_by = $model->created_by;
        $calibration->originating_org_code = $model->union_code;
        $calibration->originating_org_type = $model->originating_org_type;
        $calibration->originating_type = $model->originating_type;
        $calibration->analyzer_calibration_code = Yii::$app->general->getPrimaryCode($calibration);
        $calibration->originating_org_code = $model->originating_org_code;
        $calibration->water_offset = $calibration->fat_offset = $calibration->snf_offset = 0.00;
        $calibration->milk_analyser_type_code = $model->milk_analyser_type_code;
        $calibration->x_col1 = $model->x_col1;
        $pk_data = explode('-', $calibration->analyzer_calibration_code);
        $pk_code = $pk_data[2];
        $auto_inc = 0;

        $cal_bipl = strstr($cal, 'MLMS SN');
        $cal_eipl = strstr($cal, 'cc');

        if ($cal_bipl) {
            /* BIPL Calibration Parsing */
            $strArr = explode('#####', $cal_bipl);
            $count = count($strArr);
            if (is_array($strArr) && $count > 2) {
                $srno_data = explode(':', $strArr[0]);
                /* BIPL Sr No Parsing */
                if (isset($srno_data[1])) {
                    $sr_model = new TblAnalyzerSerialNo();
                    $sr_model->attributes = $calibration->attributes;
                    $sr_model->originating_org_code = $model->union_code;
                    $sr_model->analyzer_serial_no_code = Yii::$app->general->getPrimaryCode($sr_model);
                    $sr_model->originating_org_code = $model->originating_org_code;
                    $sr_model->date_time_of_serial_no = $model->date_time_of_collection;
                    $sr_model->serial_no = $srno_data[1];
                    array_push($modelSave, $sr_model);
                }
                /* BIPL Sr No Parsing */
                unset($strArr[0]);
                unset($strArr[$count - 1]);
                $strArr = array_reverse($strArr);
                $cal_data = [];
                foreach ($strArr as $strVal) {
                    $strVal = ltrim($strVal);
                    $strVal = rtrim($strVal);
                    $data = explode(' ', $strVal);
                    if (count($data) > 8) {
                        if (in_array($data[4], ['C', 'B', 'M'])) {
                            $key = $data[4] . $data[1];
                            if (!isset($cal_data[$key])) {
                                $cal_data[$key] = [];
                            }
                            if ($data[5] == 'F') {
                                $cal_data[$key]['fat_offset'] = $data[6];
                            } elseif ($data[5] == 'S') {
                                $cal_data[$key]['snf_offset'] = $data[6];
                            }
                            $cal_data[$key]['date'] = $data[1];
                            $cal_data[$key]['time'] = $data[2];
                        }
                    }
                }
                foreach ($cal_data as $m => $c) {
                    $cal_model = new TblAnalyzerCalibration();
                    $cal_model->attributes = $calibration->attributes;
                    $pk_data[2] = $pk_code + $auto_inc;
                    $cal_model->analyzer_calibration_code = implode('-', $pk_data);
                    $datetime = \DateTime::createFromFormat('dmyHis', $c['date'] . $c['time'])->format('Y-m-d H:i:s');
                    $cal_model->date_time_of_actual_calibration = $datetime;
                    $cal_model->milk_type_code = substr($m, 0, 1);
                    Yii::$app->general->validateGlobalData($cal_model, 'milk_type_code', 'milk_type_code');
                    $cal_model->fat_offset = !empty($c['fat_offset']) ? $c['fat_offset'] : $cal_model->fat_offset;
                    $cal_model->snf_offset = !empty($c['snf_offset']) ? $c['snf_offset'] : $cal_model->snf_offset;
                    array_push($modelSave, $cal_model);
                    $auto_inc++;
                }
            }

            /* BIPL Calibration Parsing */
        } else if ($cal_eipl) {
            /* FATSCAN Calibration Parsing */
            $strArr = explode('#####', $cal_eipl);
            $auto_inc = 0;
            foreach ($strArr as $strVal) {
                $milk_type = substr($strVal, 1, 1);
                $strVal = substr($strVal, 3);
                $data = explode(',', $strVal);
                if (count($data) > 4) {
                    $cal_model = new TblAnalyzerCalibration();
                    $cal_model->attributes = $calibration->attributes;
                    $pk_data[2] = $pk_code + $auto_inc;
                    $cal_model->analyzer_calibration_code = implode('-', $pk_data);
                    $cal_model->milk_type_code = $milk_type;
                    Yii::$app->general->validateGlobalData($cal_model, 'milk_type_code', 'milk_type_code');
                    $cal_model->fat_offset = $data[0];
                    $cal_model->snf_offset = $data[1];
                    $cal_model->water_offset = $data[3];
                    array_push($modelSave, $cal_model);
                    $auto_inc++;
                }
            }
            /* FATSCAN Calibration Parsing */
        }
    }

    public function updateProcessStatus($resp_desc, $resp_status, $data_post_status, $data_status, $file_name) {
        return $this->updateAll(['resp_desc' => $resp_desc, 'resp_status' => $resp_status, 'data_post_status' => $data_post_status, 'response_datetime' => date('Y-m-d H:i:s')], ['data_post_status' => $data_status, 'ftp_txn_file_name' => $file_name]);
    }

    public function setChildTableOther(&$model, &$transaction_data, &$childModel, &$auto_key_config) {
        $collectionCode = $transaction_data['content']['milk_collection_code'];
        $i = 0;
        $opType = strtoupper($transaction_data['operation_type']);
        if (!empty($opType) && $opType == 'CREATE') {
            $qtyWiseCollConfig = Yii::$app->general->getUnionConfiguration($model->union_code, 'qty_wise_collection', 'VLC');
            if ($qtyWiseCollConfig == 1) {
                $this->claculateFatSnf($model, -7, -1);
            }
            $this->setCollectionData($model, 'api_create');
            $this->applyCalculations($model);
            $this->postDataSet($model, 'api_create', $childModel, $auto_key_config);
            $collmodel = new TblMilkCollection();
            $collmodel->attributes = $model->attributes;
            $collmodel->scenario = 'ho_sync_create';
            if (!$collmodel->validate()) {
                $childModel[0]->addErrors($collmodel->errors);
            }
        } else if (!empty($opType) && ($opType == 'UPDATE') && !empty($collectionCode)) {
            $existingData = $this->find()->where(['milk_collection_code' => $collectionCode])->one();
            $login_data = Yii::$app->eiplapp->identity;
            $created_by = !empty($login_data['module_code']) ? $login_data['module_code'] : '';
            $model->originating_org_type = 'MOBILE';
            $model->originating_org_code = $model->union_code;
            if ($existingData) {
                $collectionApprovalConfig = Yii::$app->general->getUnionConfiguration($model->union_code, 'collection_approval', 'PORTAL');
                $qtyWiseCollConfig = Yii::$app->general->getUnionConfiguration($model->union_code, 'qty_wise_collection', 'VLC');
                if ($qtyWiseCollConfig == 1) {
                    $this->claculateFatSnf($model, -7, -1);
                }
                $model->own_mcc_plant_code = $model->mcc_plant_code;
                $model->own_bmc_code = $model->bmc_code;
                $model->date_time_of_recieve = date('Y-m-d H:i:s');
                $model->qty_mode = Yii::$app->general->getUnionConfiguration($model->union_code, 'collection_qty_mode', 'VLC');
                $model->converted_qty_mode = $model->qty_mode == 1 ? 0 : 1;
                $conversion_const = Yii::$app->general->getUnionConfiguration($model->union_code, 'ltr_to_kg_constant', 'VLC');
                $conversion_const = empty($conversion_const) ? 1 : $conversion_const;
                $model->converted_qty = $model->qty_mode == 1 ? $model->qty / $conversion_const : $model->qty * $conversion_const;
                if (!empty($model->attributes) && ($model->fat != $existingData->attributes['fat'] || $model->snf != $existingData->attributes['snf'] || $model->rtpl != $existingData->attributes['rtpl'] || $model->qty != $existingData->attributes['qty'] )) {
                    $this->applyCalculations($model);

                    $collectionData = clone $existingData;
                    $fieldsToUpdate = ['clr', 'qty', 'fat', 'snf', 'milk_collection_code', 'actual_rate', 'rtpl', 'scheme_rate', 'scheme_rate_code', 'amount', 'converted_qty'];
                    $updateData = array_intersect_key($model->attributes, array_flip($fieldsToUpdate));
                    $existingData->attributes = array_merge($existingData->attributes, $updateData);
                    $existingData->created_by = $created_by;

                    if (in_array($collectionApprovalConfig, [1, 2])) {
                        $approvalModel = new TblCollectionDataAlias();
                        $approvalModel->attributes = $collectionData->attributes;
                        $approvalModel->setOldAttributesValues($approvalModel);
                        $approvalModel->attributes = $existingData->attributes;
                        $approvalModel->table_name = 'tbl_milk_collection';
                        $approvalModel->action_perform = 'UPDATE';
                        if ($collectionApprovalConfig == 2) {
                            $modelStages = new TblApprovalStagesDetail();
                            $modelStages->setProcessWiseApprovalData($approvalModel, $approvalModel->union_code, 'tbl_milk_collection', $childModel, $auto_key_config, $i, TRUE, 'collection_data_alias_code', $created_by);
                            $i++;
                        } else {
                            $childModel[] = $approvalModel;
                        }
                    } else {
                        $historyModel = new TblMilkCollectionHistory();
                        Yii::$app->operation->history($collectionData, $historyModel, 'UPDATE');
                        $childModel[] = $historyModel;
                        $collectionData->attributes = $existingData->attributes;
                        $childModel[] = $collectionData;
                    }
                }
                $collmodel = new TblMilkCollection();
                $collmodel->attributes = $existingData->attributes;
                $collmodel->oldattributes = $existingData->oldattributes;
                $collmodel->scenario = 'ho_sync_update';
                if (!$collmodel->validate()) {
                    $childModel[0]->addErrors($collmodel->errors);
                }
            }
        } else if (!empty($opType) && ($opType == 'DELETE') && !empty($collectionCode)) {
            $requestData = $transaction_data['content'];
            $approval_status = $requestData['approval_status'];
            $collection_type_status = $requestData['collection_type_status'];
            if (strtolower($approval_status) == 'approve' && (strtolower($collection_type_status) == 'collection')) {
                $existingData = $this->find()->where(['milk_collection_code' => $collectionCode])->one();
                $login_data = Yii::$app->eiplapp->identity;
                $created_by = !empty($login_data['module_code']) ? $login_data['module_code'] : '';
                if ($existingData) {
                    $collectionApprovalConfig = Yii::$app->general->getUnionConfiguration($model->union_code, 'collection_approval', 'PORTAL');
                    if (in_array($collectionApprovalConfig, [1, 2])) {
                        $ApprovalModel = new TblCollectionDataAlias();
                        $ApprovalModel->attributes = $existingData->attributes;
                        $ApprovalModel->setOldAttributesValues($ApprovalModel);
                        $ApprovalModel->table_name = 'tbl_milk_collection';
                        $ApprovalModel->action_perform = 'DELETE';
                        if ($collectionApprovalConfig == 2) {
                            $modelStages = new TblApprovalStagesDetail();
                            $modelStages->setProcessWiseApprovalData($ApprovalModel, $model->union_code, 'tbl_milk_collection', $childModel, $auto_key_config, $i, TRUE, 'collection_data_alias_code', $created_by);
                            $i++;
                        } else {
                            $childModel[] = $ApprovalModel;
                        }
                        $collmodel = new TblMilkCollection();
                        $collmodel->attributes = $existingData->attributes;
                        $collmodel->scenario = 'ho_sync_delete';
                        if (!$collmodel->validate()) {
                            $childModel[0]->addErrors($collmodel->errors);
                        }
                    } else {
                        $historyModel = new TblMilkCollectionHistory();
                        Yii::$app->operation->history($existingData, $historyModel, 'DELETE');
                        $childModel[] = $historyModel;
//                    $deleteModel[] = $existingData;
                        $collmodel = new TblMilkCollection();
                        $collmodel->attributes = $existingData->attributes;
                        $collmodel->scenario = 'ho_sync_delete';
                        if (!$collmodel->validate()) {
                            $childModel[0]->addErrors($collmodel->errors);
                        } else {
                            $existingData->delete();
                        }
                    }
                }
            } else if (strtolower($approval_status) != 'approve' && (strtolower($collection_type_status) == 'alias')) {
                $aliasData = TblCollectionDataAlias::find()->where(['collection_data_alias_code' => $collectionCode])->one();
                $historyModel = new TblCollectionDataAliasHistory();
                Yii::$app->operation->history($aliasData, $historyModel, 'DELETE');
                $childModel[] = $historyModel;
                $aliasData->delete();
            }
        }
    }

    public function claculateFatSnf(&$model, $startDay, $endDay) {
        $startDate = date('Y-m-d', strtotime("$startDay days", strtotime($model->date_time_of_collection)));
        $endDate = date('Y-m-d', strtotime("$endDay days", strtotime($model->date_time_of_collection)));

        $result = $this->find()->select([
                    'CAST(ROUND(SUM(qty * fat/100)/SUM(qty) * 100,1) AS DECIMAL(18,2)) AS avg_fat',
                    'CAST(ROUND(SUM(qty * snf/100)/SUM(qty) * 100,1) AS DECIMAL(18,2)) AS avg_snf',
                    'member_code'
                ])
                ->where(['between', 'date_time_of_collection', $startDate, $endDate])
                ->andWhere(['shift_code' => $model->shift_code])
                ->andWhere(['member_code' => $model->member_code, 'dcs_code' => $model->dcs_code])
                ->groupBy('member_code')
                ->asArray()
                ->one();

        $model->fat = !empty($result['avg_fat']) ? $result['avg_fat'] : '0.00';
        $model->snf = !empty($result['avg_snf']) ? $result['avg_snf'] : '0.00';
    }

    public function applyCalculations(&$model) {
        $flag = ['calculate_clr', 'rtpl_calculate'];
        $data = [];
        $data['dcs_code'] = $model->dcs_code;
        $data['milk_type'] = $model->milk_type_code;
        $data['milk_quality_type'] = $model->milk_quality_type_code;
        $data['shift'] = $model->shift_code;
        $data['dt_date'] = empty($model->date_time_of_collection) ? NULL : Yii::$app->controls->view_date($model->date_time_of_collection, 'php:Y-m-d') . ' ' . \Yii::$app->general->getshift($data['shift']);
        $data['fat'] = $model->fat;
        $data['snf'] = $model->snf;
        $resdata = $this->calculateData($flag, $model->union_code, $model->bmc_code, $model->fat, $model->snf, $model->milk_type_code, $data, $model->member_code);
        $model->clr = isset($resdata['clr']) ? $resdata['clr'] : 0;
        $responseData = isset($resdata['data']['list']) ? $resdata['data']['list'] : '';
        $rtpl = isset($responseData['rtpl']) ? $responseData['rtpl'] : '0';
        $model->actual_rate = !empty($rtpl) ? number_format($rtpl, 2) : 0;
        $model->purchase_rate_code = isset($responseData['purchase_rate_code']) ? $responseData['purchase_rate_code'] : '';
        if (isset($responseData['scheme_rate_rtpl']) && $responseData['scheme_rate_rtpl'] != '' && $responseData['scheme_rate_rtpl'] != null) {
            $rtpl = $rtpl + $responseData['scheme_rate_rtpl'];
            $model->scheme_rate_code = $responseData['scheme_rate_code'];
            $model->scheme_rate = $responseData['scheme_rate_rtpl'];
        }
        $model->rtpl = $rtpl;
        $rate = is_numeric($model->rtpl) ? (float) $model->rtpl : 0;
        $qty = is_numeric($model->qty) ? (float) $model->qty : 0;
        $amount = $rate * $qty;
        $model->amount = number_format($amount, 2, '.', '');
    }

    public function calculateData($flag, $union = '', $bmcCode = '', $fat = '', $snf = '', $milk_type = '', $data = [], $member = '') {
        $response = [];
        $flagArray = [];
        if (!is_array($flag)) {
            $flagArray[] = $flag;
        } else {
            $flagArray = $flag;
        }
//calculate clr
        if (in_array('calculate_clr', $flagArray)) {
            $lr1 = Yii::$app->general->getCheckBmcConfiguration($union, 'clr_constant1', $bmcCode, 'BMC', 'MEMBER_COLLECTION');
            $lr2 = Yii::$app->general->getCheckBmcConfiguration($union, 'clr_constant2', $bmcCode, 'BMC', 'MEMBER_COLLECTION');

            if ($lr1 == '' or $lr2 == '') {
                $lr1 = Yii::$app->general->getUnionConfiguration($union, 'clr_constant1', 'VLC');
                $lr2 = Yii::$app->general->getUnionConfiguration($union, 'clr_constant2', 'VLC');
            }
// $lr1 = Yii::$app->general->getUnionConfiguration($union, 'clr_constant1', 'VLC');
// $lr2 = Yii::$app->general->getUnionConfiguration($union, 'clr_constant2', 'VLC');
            (float) $lr1 = empty($lr1) ? 1 : $lr1;
            (float) $lr2 = empty($lr2) ? 0 : $lr2;
            $clr = ($snf - ($fat * $lr1) - $lr2) * 4;
            $response['clr'] = $clr;
        }

        if (in_array('check_fat_range', $flagArray)) {
//calculate fat range
            $range = Yii::$app->general->getUnionConfiguration($union, 'buf_min_fat_range_member', 'PORTAL');
            $mapping = new TblBmcMilkType();
            $mapped = $mapping->find()->where(['bmc_code' => $bmcCode, 'is_active' => 1])->all();

            if (!empty($range) && !empty($mapped) && count($mapped) == 2) {
                $type = [];
                foreach ($mapped as $map) {
                    $type[] = $map->milk_type_code;
                }
//Cow and Buffalo
                if (in_array(1, $type) && in_array(2, $type)) {
                    if ($range < $fat && $milk_type != 2) {
                        $response['status'] = 'success';
                        $response['data'] = 2;
                        $response['msg'] = Yii::t('app', 'Milk Type Must Buffalo');
                    } elseif ($range >= $fat && $milk_type != 1) {
                        $response['status'] = 'success';
                        $response['data'] = 1;
                        $response['msg'] = Yii::t('app', 'Milk Type Must Cow');
                    }
                }
//Cow and Mix
                if (in_array(1, $type) && in_array(3, $type)) {
                    if ($range < $fat && $milk_type != 3) {
                        $response['status'] = 'success';
                        $response['data'] = 3;
                        $response['msg'] = Yii::t('app', 'Milk Type Must Mix');
                    } elseif ($range >= $fat && $milk_type != 1) {
                        $response['status'] = 'success';
                        $response['data'] = 1;
                        $response['msg'] = Yii::t('app', 'Milk Type Must Cow');
                    }
                }
//Buffalo and Mix
                if (in_array(2, $type) && in_array(3, $type)) {
                    if ($range < $fat && $milk_type != 3) {
                        $response['status'] = 'success';
                        $response['data'] = 3;
                        $response['msg'] = Yii::t('app', 'Milk Type Must Mix');
                    } elseif ($range >= $fat && $milk_type != 2) {
                        $response['status'] = 'success';
                        $response['data'] = 2;
                        $response['msg'] = Yii::t('app', 'Milk Type Must Buffalo');
                    }
                }
            }
        }

        if (in_array('rtpl_calculate', $flagArray)) {
//calculate rtpl 
            $this->member_code = $member;
            $rateClass = Yii::$app->general->getforeignkey($this->memberCode, 'rate_class');

            $data['rate_class'] = empty($rateClass) ? 0 : $rateClass;
            $model = new TblPurchaseRateApplicability();
            $model->dcs_code = $data['dcs_code'];
            $model->wef_date = $data['dt_date'];
            $model_data = $model->getPurchaseRateApplicableData($data);
            if (!empty($model_data)) {
                $detail_model = new TblPurchaseRateDetails();
                $detail_model->rate_type_code = $model_data->rate_app_code;
                $detail_model->purchase_rate_code = $model_data->purchase_rate_code;
                $rate_type = !empty($detail_model->rateTypeCode) ? $detail_model->rateTypeCode->rate_type : '';
                $detail_data = $detail_model->getPurchasseRateDetailData($data, $rate_type);
                if (!empty($detail_data)) {
                    $response['status'] = 'success';
                    $rtpl_data['list'] = $detail_data;
                    $response['data'] = $rtpl_data;
                }
            }
        }
        return $response;
    }

    public function setCollectionData(&$model, $flag) {
        $datetime = date('Y-m-d H:i:s');
        $model->date_time_of_collection = empty($model->date_time_of_collection) ? NULL : Yii::$app->controls->view_date($model->date_time_of_collection, 'php:Y-m-d') . ' ' . Yii::$app->general->getshift($model->shift_code);
        $model->date_time_of_recieve = $datetime;
        $model->qlty_time = $datetime;
        $model->qty_time = $datetime;
        $model->type_of_data_receive = 'Manual';
        $model->qty_mode = 0;
        $model->qlty_auto = 0;
        $model->qty_auto = 0;
        $model->setModel($model);
        $model->scenario = $flag == 'create' ? 'create' : 'ho_sync_create';
        $model->qty_mode = Yii::$app->general->getUnionConfiguration($model->union_code, 'collection_qty_mode', 'VLC');
        $conversion_const = Yii::$app->general->getUnionConfiguration($model->union_code, 'ltr_to_kg_constant', 'VLC');
        $model->converted_qty_mode = $model->qty_mode == 1 ? 0 : 1;
        $conversion_const = empty($conversion_const) ? 1 : $conversion_const;
        $model->converted_qty = $model->qty_mode == 1 ? $model->qty / $conversion_const : $model->qty * $conversion_const;
    }

    public function postDataSet(&$model, $flag, &$modelSave, &$auto_key_config, &$message = '', &$type = '') {
        $collectionApprovalConfig = Yii::$app->general->getUnionConfiguration($model->union_code, 'collection_approval', 'PORTAL');

        if ($flag == 'api_create') {
            $login_data = Yii::$app->eiplapp->identity;
            $created_by = !empty($login_data['module_code']) ? $login_data['module_code'] : '';
            $model->originating_org_type = 'MOBILE';
            $model->originating_org_code = $model->union_code;
        }
        if (in_array($collectionApprovalConfig, [1, 2])) {
            $approvalModel = new TblCollectionDataAlias();
            $approvalModel->attributes = $model->attributes;
            $approvalModel->table_name = 'tbl_milk_collection';
            $approvalModel->action_perform = 'CREATE';
            $approvalModel->setOldAttributesValues($approvalModel);
            if ($collectionApprovalConfig == 2) {
                $i = 0;
                $modelStages = new TblApprovalStagesDetail();
                if ($flag == 'api_create') {
                    $modelStages->setProcessWiseApprovalData($approvalModel, $model->union_code, 'tbl_milk_collection', $modelSave, $auto_key_config, $i, TRUE, 'collection_data_alias_code', $created_by);
                } else {
                    $modelStages->setProcessWiseApprovalData($approvalModel, $model->union_code, 'tbl_milk_collection', $modelSave, $auto_key_config, $i, TRUE, 'collection_data_alias_code');
                }
            } else {
                $modelSave[] = $approvalModel;
            }
            $message = 'Data For Approval';
            $type = 'create';
        } else {
            $modelSave[] = $model;
        }
    }

    public function validateMilkType($attribute, $params) {
        $resdata = $this->calculateData('check_fat_range', $this->union_code, $this->bmc_code, $this->fat, '', $this->milk_type_code);
        if (!empty($resdata['msg'])) {
            $this->addError('milk_type_code', $resdata['msg']);
            return FALSE;
        }
    }

    public function validateRtpl($attribute, $params) {
        $qtyWiseCollConfig = Yii::$app->general->getUnionConfiguration($this->union_code, 'qty_wise_collection', 'VLC');
        if ($qtyWiseCollConfig != 1 && empty($this->rtpl)) {
            $this->addError('rtpl', Yii::t('app/validation', $this->getAttributeLabel('rtpl') . ' can not blank.'));
        }
    }

    public function validateDelete($attribute) {
        $flag = Yii::$app->general->getUnionConfiguration($this->union_code, 'collection_approval', 'PORTAL');
        $ApprovalModel = new TblCollectionDataAlias();
        $existTableData = $ApprovalModel->find()->where(['dcs_code' => $this->dcs_code, 'member_code' => $this->member_code, 'cast(date_time_of_collection as date)' => $this->date_time_of_collection, 'milk_type_code' => $this->milk_type_code, 'shift_code' => $this->shift_code, 'qty' => $this->qty, 'fat' => $this->fat, 'snf' => $this->snf, 'table_name' => 'tbl_milk_collection', 'milk_quality_type_code' => $this->milk_quality_type_code, 'action_perform' => 'DELETE'])->one();
        if (($flag == 1 || $flag == 2) && !empty($existTableData)) {
            $this->addError($attribute, "Record is Already Exist For Approval.");
        }
    }
    
    public function getCollectionSummaryData($dcs_code, $shift, $collection_datetime) {
        return $this->find()
                        ->where([
                            'dcs_code' => $dcs_code,
                            'date_time_of_collection' => $collection_datetime,
                            'shift_code' => $shift
                        ])
                        ->andWhere(['<', 'right(member_code, 4)', 900])
                        ->all();
    }

}
