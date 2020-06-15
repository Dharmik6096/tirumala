<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\dcsoperation\models\TblMemberProvisional;
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

/**
 * This is the model class for table "tbl_provisional_milk_collection".
 *
 * @property integer $provisional_provisional_milk_collection_code
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

 *
 * @property TblAnimalType $milkTypeCode
 * @property TblDcs $dcsCode
 * @property TblProvisionalMember $memberCode
 * @property TblPurchaseRate $rateCode
 * @property TblVillages $villageCode
 * @property TblProvisionalMilkCollection $milkCollectionCode
 * @property TblProvisionalMilkCollection $tblProvisionalMilkCollection
 * @property integer $data_post_id
 * @property string $data_post_status
 * @property string $picked_datetime
 * @property string $resp_status
 * @property string $resp_desc
 */
class TblProvisionalMilkCollection extends \app\models\ChildModel {

    public $collection_date, $member;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_provisional_milk_collection';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['rtpl', 'amount'], 'trim'],
            [['member_code', 'dcs_code', 'name', 'mobile_no', 'auto_flag', 'village_code', 'type_of_data_receive', 'purchase_rate_code', 'error_log', 'soc_bmc_flag'], 'string', 'except' => ['sendsms', 'androidsync']],
            [['milk_type_code', 'shift_code', 'dcs_code', 'milk_type_code', 'qty'], 'required', 'except' => ['portal_data_post', 'post_sap_data', 'sendsms', 'androidsync']],
            [['milk_quality_type_code', 'amount', 'rtpl', 'member_code'], 'required', 'except' => ['portal_data_post', 'post_sap_data', 'sendsms', 'androidsync']],
            [['milk_type_code', 'sample_no', 'ack'], 'integer', 'except' => ['sendsms', 'androidsync']],
            [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'clr', 'no_of_can'], 'number', 'except' => ['sendsms', 'androidsync']],
            //[['sms_status'],'default','n'],
            //[['sms_msgid','sms_mobile','sms_errorlog','sms_timestamp'],'default',NULL],
            [['date_time_of_collection', 'date_time_of_recieve', 'sms_msgid', 'sms_mobile', 'sms_errorlog', 'sms_timestamp', 'sms_status', 'data_post_status', 'clr', 'status', 'qty_mode', 'qlty_time', 'qty_time', 'no_of_can', 'milk_quality_type_code', 'qlty_auto', 'qty_auto', 'collection_date', 'is_approved', 'data_post_id', 'picked_datetime', 'resp_status', 'resp_desc', 'shift_code', 'own_bmc_code', 'own_mcc_plant_code', 'member', 'tag_1', 'tag_2', 'error_desc'], 'safe'],
            [['milk_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type_code' => 'animal_type_code'], 'except' => ['sendsms', 'androidsync']],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code'], 'except' => ['sendsms', 'androidsync']],
            //  [['member_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMember::className(), 'targetAttribute' => ['member_code' => 'member_code']],
            // [['purchase_rate_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblPurchaseRate::className(), 'targetAttribute' => ['purchase_rate_code' => 'purchase_rate_code'], 'except' => ['sendsms']],
//            [['village_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVillages::className(), 'targetAttribute' => ['village_code' => 'village_code']],
//            [['provisional_milk_collection_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblProvisionalMilkCollection::className(), 'targetAttribute' => ['provisional_milk_collection_code' => 'provisional_milk_collection_code']],
            [['fat', 'snf', 'clr', 'water', 'qty'], 'default', 'value' => '0'],
            [['is_approved'], 'default', 'value' => '1'],
            [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'originating_type', 'protein', 'density', 'lactose', 'dcs_payment_cycle_code', 'milk_analyser_type_code', 'ws_code'], 'safe'],
            [['member_code', 'dcs_code', 'name', 'mobile_no', 'milk_type_code', 'fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'auto_flag', 'shift_code', 'date_time_of_collection', 'date_time_of_recieve', 'village_code', 'sample_no', 'type_of_data_receive', 'purchase_rate_code', 'error_log', 'ack', 'soc_bmc_flag', 'dt_date', 'sms_status', 'sms_msgid', 'sms_mobile', 'sms_errorlog', 'sms_timestamp', 'data_post_status', 'clr', 'status', 'qty_mode', 'qlty_time', 'qty_time', 'no_of_can', 'milk_quality_type_code', 'qlty_auto', 'qty_auto', 'created_at', 'created_by', 'updated_at', 'updated_by', 'route_code', 'bmc_code', 'converted_qty', 'is_approved', 'data_post_id', 'resp_status', 'resp_desc', 'picked_datetime', 'ftp_txn_file_name', 'tag_1', 'tag_2', 'ftp_txn_log_id', 'error_desc', 'originating_type', 'last_edited_type', 'remarks', 'sync_status', 'union_code', 'plant_code', 'mcc_plant_code', 'version_no', 'originating_org_code', 'originating_org_type', 'protein', 'density', 'lactose', 'dcs_payment_cycle_code', 'milk_analyser_type_code', 'ws_code', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'converted_qty_mode', 'incentive', 'deduction', 'total_amount'], 'safe'],
            [['dcs_code'], 'setUuid', 'on' => ['androidsync']],
            [['tag_1'], 'default', 'value' => 'X'],
            [['adt_param', 'adt_value'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'provisional_provisional_milk_collection_code' => Yii::t('app', 'Provisional Milk Collection Code'),
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
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
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
        return $this->hasOne(TblMemberProvisional::className(), ['member_code' => 'member_code']);
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
        return $this->hasOne(TblProvisionalMilkCollection::className(), ['provisional_milk_collection_code' => 'provisional_milk_collection_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblProvisionalMilkCollection() {
        return $this->hasOne(TblProvisionalMilkCollection::className(), ['provisional_milk_collection_code' => 'provisional_milk_collection_code']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    /**
     * @inheritdoc
     * @return TblProvisionalMilkCollectionQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblProvisionalMilkCollectionQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkQualityCode() {
        return $this->hasOne(TblMilkQualityType::className(), ['milk_quality_type_code' => 'milk_quality_type_code']);
    }

    public function setUuid($attribute, $params) {
        $this->data_post_id = !empty($this->data_post_id) ? $this->data_post_id : $this->x_col1;
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

    public function getMilkCollectionData($member_code){
        return $this->find()->where(['member_code' => $member_code])->all();
    }

}
