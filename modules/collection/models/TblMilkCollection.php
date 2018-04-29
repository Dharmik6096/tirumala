<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\dcsoperation\models\TblMember;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\organisation\models\TblDcs;
use app\modules\dcsoperation\models\TblPurchaseRate;
use app\modules\geo\models\TblVillages;
use app\modules\dcsoperation\models\TblShift;

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
 * @property string $sms_status
 * @property string $sms_msgid
 * @property string $sms_mobile
 * @property string $sms_errorlog
 * @property string $sms_timestamp

 *
 * @property TblAnimalType $milkTypeCode
 * @property TblDcs $dcsCode
 * @property TblMember $memberCode
 * @property TblPurchaseRate $rateCode
 * @property TblVillages $villageCode
 * @property TblMilkCollection $milkCollectionCode
 * @property TblMilkCollection $tblMilkCollection
 */
class TblMilkCollection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_milk_collection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_code', 'dcs_code', 'name', 'mobile_no', 'auto_flag', 'shift', 'village_code', 'type_of_data_receive', 'rate_code', 'error_log', 'soc_bmc_flag'], 'string'],
            [['milk_type_code'], 'required'],
            [['milk_type_code', 'sample_no', 'ack'], 'integer'],
            [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount'], 'number'],
            //[['sms_status'],'default','n'],
            //[['sms_msgid','sms_mobile','sms_errorlog','sms_timestamp'],'default',NULL],
            [['date_time_of_collection', 'date_time_of_recieve','sms_msgid','sms_mobile','sms_errorlog','sms_timestamp','sms_status'], 'safe'],
            [['milk_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblAnimalType::className(), 'targetAttribute' => ['milk_type_code' => 'animal_type_code']],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            [['member_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMember::className(), 'targetAttribute' => ['member_code' => 'member_code']],
            [['rate_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblPurchaseRate::className(), 'targetAttribute' => ['rate_code' => 'purchase_rate_code']],
            [['village_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVillages::className(), 'targetAttribute' => ['village_code' => 'village_code']],
            [['milk_collection_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblMilkCollection::className(), 'targetAttribute' => ['milk_collection_code' => 'milk_collection_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
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
            'rtpl' => Yii::t('app', 'Rtpl'),
            'amount' => Yii::t('app', 'Amount'),
            'auto_flag' => Yii::t('app', 'Auto Flag'),
            'shift' => Yii::t('app', 'Shift'),
            'date_time_of_collection' => Yii::t('app', 'Date Time Of Collection'),
            'date_time_of_recieve' => Yii::t('app', 'Date Time Of Recieve'),
            'village_code' => Yii::t('app', 'Village Name'),
            'sample_no' => Yii::t('app', 'Sample No'),
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkTypeCode()
    {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMemberCode()
    {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRateCode()
    {
        return $this->hasOne(TblPurchaseRate::className(), ['purchase_rate_code' => 'rate_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVillageCode()
    {
        return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMilkCollectionCode()
    {
        return $this->hasOne(TblMilkCollection::className(), ['milk_collection_code' => 'milk_collection_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMilkCollection()
    {
        return $this->hasOne(TblMilkCollection::className(), ['milk_collection_code' => 'milk_collection_code']);
    }
    
    public function getShiftCode()
    {
        return $this->hasOne(TblShift::className(), ['id' => 'shift']);
    }
    
    /**
     * @inheritdoc
     * @return TblMilkCollectionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblMilkCollectionQuery(get_called_class());
    }
}
