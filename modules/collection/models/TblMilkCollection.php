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

    public $collection_date, $member;

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
            [['fat', 'snf', 'bmc_code'], 'required', 'except' => ['create']],
            [['union_code', 'plant_code', 'mcc_plant_code'], 'required', 'except' => ['create', 'importCsv']],
            [['shift_code'], 'integer', 'message' => Yii::t('app/validation', '{attribute} is invalid.'), 'on' => ['importCsv']],
            [['milk_type_code'], 'integer', 'message' => Yii::t('app/validation', '{attribute} is invalid.'), 'on' => ['importCsv']],
            [['rtpl', 'amount'], 'trim'],
            [['member_code', 'dcs_code', 'name', 'mobile_no', 'auto_flag', 'village_code', 'type_of_data_receive', 'purchase_rate_code', 'error_log', 'soc_bmc_flag'], 'string', 'except' => ['sendsms', 'androidsync']],
            [['milk_type_code', 'shift_code', 'dcs_code', 'milk_type_code', 'qty'], 'required', 'except' => ['portal_data_post', 'post_sap_data', 'sendsms', 'androidsync']],
            [['milk_quality_type_code', 'amount', 'rtpl', 'member_code'], 'required', 'except' => ['importCsv', 'portal_data_post', 'post_sap_data', 'sendsms', 'androidsync']],
            [['member', 'sample_no'], 'required', 'on' => ['importCsv']],
            [['sample_no'], 'number', 'min' => 0, 'on' => ['importCsv']],
            [['milk_type_code', 'sample_no', 'ack'], 'integer', 'except' => ['sendsms', 'androidsync']],
            [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'clr', 'no_of_can'], 'number', 'except' => ['sendsms', 'androidsync']],
            //[['sms_status'],'default','n'],
            //[['sms_msgid','sms_mobile','sms_errorlog','sms_timestamp'],'default',NULL],
            [['date_time_of_collection', 'date_time_of_recieve', 'sms_msgid', 'sms_mobile', 'sms_errorlog', 'sms_timestamp', 'sms_status', 'data_post_status', 'clr', 'status', 'qty_mode', 'qlty_time', 'qty_time', 'no_of_can', 'milk_quality_type_code', 'qlty_auto', 'qty_auto', 'collection_date', 'is_approved', 'data_post_id', 'picked_datetime', 'resp_status', 'resp_desc', 'shift_code', 'own_bmc_code', 'own_mcc_plant_code', 'member', 'tag_1', 'tag_2', 'error_desc', 'device_lat', 'device_long', 'mob_lat', 'mob_long'], 'safe'],
            [['milk_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type_code' => 'animal_type_code'], 'except' => ['sendsms', 'androidsync']],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code'], 'except' => ['sendsms', 'androidsync', 'importCsv']],
            [['shift_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblShift::className(), 'targetAttribute' => ['shift_code' => 'id'], 'on' => ['importCsv']],
            //  [['member_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMember::className(), 'targetAttribute' => ['member_code' => 'member_code']],
            // [['purchase_rate_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblPurchaseRate::className(), 'targetAttribute' => ['purchase_rate_code' => 'purchase_rate_code'], 'except' => ['sendsms']],
//            [['village_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVillages::className(), 'targetAttribute' => ['village_code' => 'village_code']],
//            [['milk_collection_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMilkCollection::className(), 'targetAttribute' => ['milk_collection_code' => 'milk_collection_code']],
            [['fat', 'snf', 'clr', 'water', 'qty'], 'default', 'value' => '0'],
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
            [['dcs_code'], 'pastDateValidate', 'on' => 'importCsv'],
            [['shift_code', 'milk_type_code'], 'ImportfieldSet', 'skipOnError' => true, 'on' => 'importCsv'],
            ['shift_code', 'in', 'range' => [1, 2], 'on' => ['importCsv'], 'skipOnEmpty' => TRUE, 'message' => Yii::t('app/validation', '{attribute} is invalid')],
            [['tag_1'], 'default', 'value' => 'X'],
            [['adt_param', 'adt_value', 'received_timestamp', 'is_rate_recalc', 'purchase_rate_code_old'], 'safe'],
            [['member_code'], 'validateUnique', 'on' => ['create']],
            [['milk_type_code'], 'validateUpdate', 'on' => ['update']],
            [['bmc_code'], function ($attribute, $params) {
                    if (empty($this->getErrors())) {
                        Yii::$app->general->paymentCycleLock($this, 'date_time_of_collection', 'bmc_code', 'BMC', 'DCS', ['data_lock_member', 'billing_lock_member']);
                    }
                }, 'skipOnEmpty' => TRUE, 'on' => ['create', 'importCsv']],
            [['bmc_code'], function ($attribute, $params) {
                    if (empty($this->getErrors())) {
                        Yii::$app->general->paymentCycleLock($this, 'date_time_of_collection', 'bmc_code', 'BMC', 'DCS', ['data_lock_member', 'billing_lock_member', 'sync_lock_member']);
                    }
                }, 'skipOnEmpty' => TRUE, 'on' => ['androidsync_coll']],
            [['date_time_of_collection'], function ($attribute, $params) {
                    $this->data_post_status = 0;
                }, 'skipOnEmpty' => false, 'except' => ['post_sap_data']],
            [['is_rate_recalc'], 'default', 'value' => 0]
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
            'originating_org_type' => Yii::t('app', 'Originated At'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'adt_param' => Yii::t('app', 'Adultration Param'),
            'adt_value' => Yii::t('app', 'Adultration Value'),
            'milk_quality_type_code' => Yii::t('app', 'Milk Quality Type'),
            'device_lat' => Yii::t('app', 'Device Latitude'),
            'device_long' => Yii::t('app', 'Device Longitude'),
            'mob_lat' => Yii::t('app', 'Application Latitude'),
            'mob_long' => Yii::t('app', 'Application Longitude'),
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
                ->where(['dcs_code' => $this->dcs_code, 'shift_code' => $this->shift_code, 'CONVERT(date,date_time_of_collection)' => Yii::$app->formatter->asDate($this->date_time_of_collection, DATE_FORMAT)])
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
        return $this->hasOne(TblUnionRatechartRange::className(), ['union_code' => 'union_code', 'animal_type_code' => 'milk_type_code']);
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
        $this->date_time_of_collection = ($this->date_time_of_collection == '') ? null : date('Y-m-d', strtotime($this->date_time_of_collection));
        if (!empty($this->date_time_of_collection) && ($this->date_time_of_collection > date('Y-m-d'))) {
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
        $model = new TblMember();
        $data = $model->validMember($this->member_code);
        if (empty($data)) {
            $this->addError('member_code', Yii::t('app/validation', $this->getAttributeLabel('member_code') . ' is Invalid'));
        }

        Yii::$app->general->validateDeactivateDcs($this, $this->date_time_of_collection, '', TRUE);
        Yii::$app->general->validateRateRange($this);

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
            if ($flag == 1 && !empty($existTableData)) {
                $this->addError($attribute, "Record is Already Exist For Approval");
            }
            $this->milkTypeWiseUnique($ApprovalModel, $this, TRUE);
            $this->milkTypeWiseUnique($this, $this, FALSE, TRUE);
            Yii::$app->general->validateRateRange($this);
            if ($flag != 1) {
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
        $model->dt_date = Yii::$app->formatter->asDate($datetime, DATE_FORMAT) . ' ' . \Yii::$app->general->getshift($model->shift_code);
        $model->route_code = Yii::$app->general->getforeignkey($model->dcsCode, 'route_code');
        $model->last_edited_type = 'P';
        $model->own_mcc_plant_code = $model->mcc_plant_code;
        $model->own_bmc_code = $model->bmc_code;
        $model->sample_no = $model->getSampleNo();
    }

    public function milkTypeWiseUnique($model, $modelData, $approval = false, $update = false, $approvalUpdate = false, $importUpdate = false) {
        $flag = Yii::$app->general->getUnionConfiguration($modelData->union_code, 'collection_approval', 'PORTAL');
        $sameMilkType = Yii::$app->general->getUnionConfiguration($modelData->union_code, 'multi_entry_same_milk', 'VLC');
        $diffMilkType = Yii::$app->general->getUnionConfiguration($modelData->union_code, 'multi_entry_other_milk', 'VLC');
        $oldMilktype = !empty($model->oldAttributes['milk_type_code']) ? $model->oldAttributes['milk_type_code'] : '';
        if ($approvalUpdate) {
            $oldMilktype = $modelData->old_milk_type_code;
        }
        if ($sameMilkType != 1 && $diffMilkType != 1) {
            $returnModel = $model->find()->where(['dcs_code' => $modelData->dcs_code,
                'member_code' => $modelData->member_code,
                'cast(date_time_of_collection as date)' => date('Y-m-d', strtotime($modelData->date_time_of_collection)),
                'shift_code' => $modelData->shift_code]);
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
            if ($approval) {
                $returnModel->andWhere(['table_name' => 'tbl_milk_collection']);
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

}
