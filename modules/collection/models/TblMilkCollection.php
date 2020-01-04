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

    public $collection_date;

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
            [['member_code', 'dcs_code', 'name', 'mobile_no', 'auto_flag', 'village_code', 'type_of_data_receive', 'purchase_rate_code', 'error_log', 'soc_bmc_flag'], 'string', 'except' => ['sendsms', 'androidsync']],
            [['milk_type_code', 'shift_code', 'dcs_code', 'member_code', 'milk_type_code', 'milk_quality_type_code', 'rtpl', 'qty', 'amount'], 'required', 'except' => ['portal_data_post', 'post_sap_data', 'sendsms', 'androidsync']],
            [['milk_type_code', 'sample_no', 'ack'], 'integer', 'except' => ['sendsms', 'androidsync']],
            [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'clr', 'no_of_can'], 'number', 'except' => ['sendsms', 'androidsync']],
            //[['sms_status'],'default','n'],
            //[['sms_msgid','sms_mobile','sms_errorlog','sms_timestamp'],'default',NULL],
            [['date_time_of_collection', 'date_time_of_recieve', 'sms_msgid', 'sms_mobile', 'sms_errorlog', 'sms_timestamp', 'sms_status', 'data_post_status', 'clr', 'status', 'qty_mode', 'qlty_time', 'qty_time', 'no_of_can', 'milk_quality_type_code', 'qlty_auto', 'qty_auto', 'collection_date', 'is_approved', 'data_post_id', 'picked_datetime', 'resp_status', 'resp_desc', 'shift_code', 'own_bmc_code', 'own_mcc_plant_code'], 'safe'],
            [['milk_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type_code' => 'animal_type_code'], 'except' => ['sendsms', 'androidsync']],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code'], 'except' => ['sendsms', 'androidsync']],
            //  [['member_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMember::className(), 'targetAttribute' => ['member_code' => 'member_code']],
            // [['purchase_rate_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblPurchaseRate::className(), 'targetAttribute' => ['purchase_rate_code' => 'purchase_rate_code'], 'except' => ['sendsms']],
//            [['village_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVillages::className(), 'targetAttribute' => ['village_code' => 'village_code']],
//            [['milk_collection_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMilkCollection::className(), 'targetAttribute' => ['milk_collection_code' => 'milk_collection_code']],
            [['fat', 'snf', 'clr', 'water', 'qty', 'rtpl', 'amount'], 'default', 'value' => '0'],
            [['is_approved'], 'default', 'value' => '1'],
            [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'originating_type', 'protein', 'density', 'lactose', 'dcs_payment_cycle_code', 'milk_analyser_type_code', 'ws_code'], 'safe'],
            [['member_code', 'dcs_code', 'name', 'mobile_no', 'milk_type_code', 'fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'auto_flag', 'shift_code', 'date_time_of_collection', 'date_time_of_recieve', 'village_code', 'sample_no', 'type_of_data_receive', 'purchase_rate_code', 'error_log', 'ack', 'soc_bmc_flag', 'dt_date', 'sms_status', 'sms_msgid', 'sms_mobile', 'sms_errorlog', 'sms_timestamp', 'data_post_status', 'clr', 'status', 'qty_mode', 'qlty_time', 'qty_time', 'no_of_can', 'milk_quality_type_code', 'qlty_auto', 'qty_auto', 'created_at', 'created_by', 'updated_at', 'updated_by', 'route_code', 'bmc_code', 'converted_qty', 'is_approved', 'data_post_id', 'resp_status', 'resp_desc', 'picked_datetime', 'ftp_txn_file_name', 'tag_1', 'tag_2', 'ftp_txn_log_id', 'error_desc', 'originating_type', 'last_edited_type', 'remarks', 'sync_status', 'union_code', 'plant_code', 'mcc_plant_code', 'version_no', 'originating_org_code', 'originating_org_type', 'protein', 'density', 'lactose', 'dcs_payment_cycle_code', 'milk_analyser_type_code', 'ws_code', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'converted_qty_mode', 'incentive', 'deduction', 'total_amount'], 'safe'],
            [['dcs_code'], 'setUuid', 'on' => ['androidsync']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'milk_collection_code' => Yii::t('app', 'Milk Collection Code'),
            'member_code' => Yii::t('app', 'Member'),
            'dcs_code' => Yii::t('app', 'Society Name'),
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
                ->where(['member_code' => $this->member_code, 'shift_code' => $this->shift_code, 'CONVERT(date,date_time_of_collection)' => date('Y-m-d')])
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

}
