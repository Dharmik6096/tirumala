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
 * @property integer $provisional_milk_collection_code
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

            [['member_code','dcs_code','name','mobile_no','milk_type_code','fat','snf','water','qty','rtpl','amount','auto_flag','shift_code','date_time_of_collection','date_time_of_recieve','village_code','sample_no','type_of_data_receive','purchase_rate_code','error_log','ack','soc_bmc_flag','dt_date','sms_status','sms_msgid','sms_mobile','sms_errorlog','sms_timestamp','data_post_status','clr','status','qty_mode','qlty_time','qty_time','no_of_can','milk_quality_type_code','qlty_auto','qty_auto','created_at','created_by','updated_at','updated_by','route_code','bmc_code','converted_qty','is_approved','data_post_id','resp_status','resp_desc','picked_datetime','ftp_txn_file_name','tag_1','tag_2','ftp_txn_log_id','error_desc','originating_type','last_edited_type','remarks','sync_status','union_code','plant_code','mcc_plant_code','version_no','originating_org_code','originating_org_type','converted_qty_mode','protein','density','lactose','dcs_payment_cycle_code','milk_analyser_type_code','ws_code','x_col1','x_col2','x_col3','x_col4','x_col5','incentive','deduction','total_amount','own_bmc_code','own_mcc_plant_code','send_status','response_datetime','adt_param','adt_value','txfarmer_id','data_inserted_from'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'provisional_milk_collection_code' => Yii::t('app', 'Provisional Milk Collection Code'),
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

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift_code']);
    }

    public function getMilkCollectionData($member_code){
        return $this->find()->where(['member_code' => $member_code])->all();
    }

}
