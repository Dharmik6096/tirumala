<?php

namespace app\modules\feedback\models;

use app\models\ChildModel;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\organisation\models\TblMccPlant;
use webvimark\modules\UserManagement\models\User;
use Yii;

/**
 * This is the model class for table "tbl_non_member_house_hold_visit".
 *
 * @property integer $house_hold_visit_id
 * @property string $house_hold_visit_code
 * @property string $surveyer_code
 * @property string $visit_date
 * @property string $mcc_plant_code
 * @property string $bmc_code
 * @property string $dcs_code
 * @property string $name
 * @property string $address_line
 * @property string $pincode
 * @property string $mobile_no
 * @property integer $milch_animal_cow_cnt
 * @property integer $milch_animal_buff_cnt
 * @property integer $milch_animal_country_cow_cnt
 * @property string $cow_milk_volume
 * @property string $buff_milk_volume
 * @property string $total_milk_volume
 * @property string $own_milk_consumption
 * @property string $balance_milk
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblNonMemberHouseHoldVisit extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_non_member_house_hold_visit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['house_hold_visit_id','house_hold_visit_code','surveyer_code','visit_date','mcc_plant_code','bmc_code','dcs_code','name','address_line','pincode','mobile_no','milch_animal_cow_cnt','milch_animal_buff_cnt','milch_animal_country_cow_cnt','cow_milk_volume','buff_milk_volume','total_milk_volume','own_milk_consumption','balance_milk','remarks','created_at','created_by','updated_at','updated_by','originating_type','originating_org_code','originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'house_hold_visit_id' => Yii::t('app', 'House Hold Visit ID'),
            'house_hold_visit_code' => Yii::t('app', 'House Hold Visit Code'),
            'surveyer_code' => Yii::t('app', 'Surveyer'),
            'visit_date' => Yii::t('app', 'Visit Date'),
            'mcc_plant_code' => Yii::t('app', 'Mcc'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'dcs_code' => Yii::t('app', 'DCS'),
            'name' => Yii::t('app', 'Name'),
            'address_line' => Yii::t('app', 'Address Line'),
            'pincode' => Yii::t('app', 'Pincode'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'milch_animal_cow_cnt' => Yii::t('app', 'Milch Animal Cow Cnt'),
            'milch_animal_buff_cnt' => Yii::t('app', 'Milch Animal Buff Cnt'),
            'milch_animal_country_cow_cnt' => Yii::t('app', 'Milch Animal Country Cow Cnt'),
            'cow_milk_volume' => Yii::t('app', 'Cow Milk Volume'),
            'buff_milk_volume' => Yii::t('app', 'Buff Milk Volume'),
            'total_milk_volume' => Yii::t('app', 'Total Milk Volume'),
            'own_milk_consumption' => Yii::t('app', 'Own Milk Consumption'),
            'balance_milk' => Yii::t('app', 'Balance Milk'),
            'remarks' => Yii::t('app', 'Remarks'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }

    public function getMccPlantCode() {
        return $this->hasOne(TblMccPlant::className(), ['mcc_plant_code' => 'mcc_plant_code']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getSurveyerCode() {
        return $this->hasOne(User::className(), ['id' => 'surveyer_code']);
    }
}
