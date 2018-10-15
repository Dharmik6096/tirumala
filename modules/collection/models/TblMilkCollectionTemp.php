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

/**
 * This is the model class for table "tbl_milk_collection_temp".
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
 * @property string $shift
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
 * @property integer $data_post_status
 * @property string $clr
 * @property string $status
 * @property integer $qty_mode
 * @property string $qlty_time
 * @property string $qty_time
 * @property integer $no_of_can
 * @property integer $milk_quality_type_code
 * @property integer $qlty_auto
 * @property integer $qty_auto
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $route_code
 * @property string $bmc_code
 * @property string $converted_qty
 * @property integer $is_approved
 * @property integer $is_updated
 */
class TblMilkCollectionTemp extends \app\models\ChildModel {

    public $collection_date, $union_code, $plant_code, $mcc_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_milk_collection_temp';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['member_code', 'dcs_code', 'name', 'mobile_no', 'auto_flag', 'shift', 'village_code', 'type_of_data_receive', 'rate_code', 'error_log', 'soc_bmc_flag'], 'string'],
            [['milk_type_code', 'shift', 'dcs_code', 'member_code', 'milk_type_code', 'milk_quality_type_code', 'rtpl', 'qty', 'amount', 'date_time_of_collection'], 'required', 'except' => ['portal_data_post']],
            [['milk_type_code', 'sample_no', 'ack', 'is_approved'], 'integer'],
            [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'clr', 'no_of_can'], 'number'],
            [['member_code'], 'validateMemberCode'],
            [['date_time_of_collection', 'date_time_of_recieve', 'sms_msgid', 'sms_mobile', 'sms_errorlog', 'sms_timestamp', 'sms_status', 'data_post_status', 'clr', 'status', 'qty_mode', 'qlty_time', 'qty_time', 'no_of_can', 'milk_quality_type_code', 'qlty_auto', 'qty_auto', 'collection_date', 'plant_code', 'bmc_code', 'mcc_code', 'is_updated'], 'safe'],
            [['milk_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type_code' => 'animal_type_code']],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            [['member_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMember::className(), 'targetAttribute' => ['member_code' => 'member_code']],
            [['rate_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblPurchaseRate::className(), 'targetAttribute' => ['rate_code' => 'purchase_rate_code']],
            [['fat', 'snf', 'clr', 'water', 'qty', 'rtpl', 'amount'], 'default', 'value' => '0'],
            [['is_approved', 'is_updated'], 'default', 'value' => '0'],
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
            'mobile_no' => Yii::t('app', 'Mobile No.'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'fat' => Yii::t('app', 'FAT'),
            'snf' => Yii::t('app', 'SNF'),
            'water' => Yii::t('app', 'Water'),
            'qty' => Yii::t('app', 'Qty'),
            'rtpl' => Yii::t('app', 'RTPL'),
            'amount' => Yii::t('app', 'Amount'),
            'auto_flag' => Yii::t('app', 'Auto Flag'),
            'shift' => Yii::t('app', 'Shift'),
            'date_time_of_collection' => Yii::t('app', 'Date'),
            'date_time_of_recieve' => Yii::t('app', 'Recieve Date'),
            'village_code' => Yii::t('app', 'Village Name'),
            'sample_no' => Yii::t('app', 'Sample No.'),
            'type_of_data_receive' => Yii::t('app', 'Type Of Data Receive'),
            'rate_code' => Yii::t('app', 'Purchase Rate'),
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
            'is_approved' => Yii::t('app', 'Is Approved'),
            'plant_code' => Yii::t('app', 'Plant'),
            'mcc_code' => Yii::t('app', 'MCC'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'is_approved' => Yii::t('app', 'Status'),
            'is_updated' => Yii::t('app', 'Record Type'),
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
        return $this->hasOne(TblPurchaseRate::className(), ['purchase_rate_code' => 'rate_code']);
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
        return $this->hasOne(TblShift::className(), ['id' => 'shift']);
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

    public function getSocietyVendorCode() {
        return $this->hasOne(TblSocietyVendor::className(), ['dcs_code' => 'dcs_code']);
    }

    public function validateMemberCode($attribute, $params) {
        if (strlen($this->member_code) <= 4) {
            $this->member_code = $this->dcs_code . str_pad($this->member_code, 4, '0', STR_PAD_LEFT);
        }
    }

    public function getSampleNo() {
        $data = $this->find()
                ->select('max(sample_no) as sample_no')
                ->where(['member_code' => $this->member_code, 'shift' => $this->shift, 'CONVERT(date,date_time_of_collection)' => date('Y-m-d', strtotime($this->date_time_of_collection))])
                ->one();
        $sample_no = (int) $data['sample_no'] + 1;
        return $sample_no;
    }

    public function search($params) {
        
    }

    public function getData() {
        return $this->find()
                        ->where(['dcs_code' => $this->dcs_code, 'date_time_of_collection' => $this->date_time_of_collection, 'shift' => $this->shift, 'is_approved' => $this->is_approved, 'is_updated' => $this->is_updated])
                        ->all();
    }

}
