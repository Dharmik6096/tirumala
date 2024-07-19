<?php

namespace app\modules\feedback\models;

use app\models\ChildModel;
use app\modules\geo\models\TblDistricts;
use app\modules\geo\models\TblHamlets;
use app\modules\geo\models\TblStates;
use app\modules\geo\models\TblSubDistricts;
use app\modules\geo\models\TblVillages;
use app\modules\organisation\models\TblMccPlant;
use webvimark\modules\UserManagement\models\User;
use Yii;

/**
 * This is the model class for table "tbl_mpp_survey".
 *
 * @property integer $mpp_survey_id
 * @property string $mpp_survey_code
 * @property string $survey_person_code
 * @property string $survey_date
 * @property string $mcc_plant_code
 * @property string $state_code
 * @property string $district_code
 * @property string $sub_district_code
 * @property string $village_code
 * @property string $hamlet_code
 * @property string $mpp_name
 * @property string $pincode
 * @property integer $no_of_family_gen
 * @property integer $no_of_family_obc
 * @property integer $no_of_family_st
 * @property integer $no_of_family_sc
 * @property integer $no_of_family_other
 * @property integer $no_of_family_total
 * @property integer $milch_animal_cow_cnt
 * @property integer $milch_animal_buff_cnt
 * @property integer $milch_animal_country_cow_cnt
 * @property integer $milch_animal_cnt_total
 * @property integer $non_milch_animal_cow_cnt
 * @property integer $non_milch_animal_buff_cnt
 * @property integer $non_milch_animal_country_cow_cnt
 * @property integer $non_milch_animal_cnt_total
 * @property string $cow_milk_volume
 * @property string $buff_milk_volume
 * @property string $total_milk_volume
 * @property integer $nos_of_pouring_members
 * @property string $per_day_milk_sales_volume
 * @property string $expected_pourer_count
 * @property string $expected_milk_volume
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblMppSurvey extends ChildModel
{
    public $f_union_code, $f_plant_code, $f_mcc_code, $from_date, $to_date;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_mpp_survey';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mpp_survey_id','mpp_survey_code','survey_person_code','survey_date','mcc_plant_code','state_code','district_code','sub_district_code','village_code','hamlet_code','mpp_name','pincode','no_of_family_gen','no_of_family_obc','no_of_family_st','no_of_family_sc','no_of_family_other','no_of_family_total','milch_animal_cow_cnt','milch_animal_buff_cnt','milch_animal_country_cow_cnt','milch_animal_cnt_total','non_milch_animal_cow_cnt','non_milch_animal_buff_cnt','non_milch_animal_country_cow_cnt','non_milch_animal_cnt_total','cow_milk_volume','buff_milk_volume','total_milk_volume','nos_of_pouring_members','per_day_milk_sales_volume','expected_pourer_count','expected_milk_volume','created_at','created_by','updated_at','updated_by','originating_type','originating_org_code','originating_org_type'], 'safe'],
            [['f_union_code', 'f_plant_code', 'f_mcc_code', 'from_date', 'to_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mpp_survey_id' => Yii::t('app', 'Mpp Survey ID'),
            'mpp_survey_code' => Yii::t('app', 'Mpp Survey'),
            'survey_person_code' => Yii::t('app', 'Survey Person'),
            'survey_date' => Yii::t('app', 'Survey Date'),
            'mcc_plant_code' => Yii::t('app', 'MCC'),
            'state_code' => Yii::t('app', 'State'),
            'district_code' => Yii::t('app', 'District'),
            'sub_district_code' => Yii::t('app', 'Sub District'),
            'village_code' => Yii::t('app', 'Village'),
            'hamlet_code' => Yii::t('app', 'Hamlet'),
            'mpp_name' => Yii::t('app', 'MPP Name'),
            'pincode' => Yii::t('app', 'Pincode'),
            'no_of_family_gen' => Yii::t('app', 'No Of Family Gen'),
            'no_of_family_obc' => Yii::t('app', 'No Of Family Obc'),
            'no_of_family_st' => Yii::t('app', 'No Of Family St'),
            'no_of_family_sc' => Yii::t('app', 'No Of Family Sc'),
            'no_of_family_other' => Yii::t('app', 'No Of Family Other'),
            'no_of_family_total' => Yii::t('app', 'No Of Family Total'),
            'milch_animal_cow_cnt' => Yii::t('app', 'Milch Animal Cow Cnt'),
            'milch_animal_buff_cnt' => Yii::t('app', 'Milch Animal Buff Cnt'),
            'milch_animal_country_cow_cnt' => Yii::t('app', 'Milch Animal Country Cow Cnt'),
            'milch_animal_cnt_total' => Yii::t('app', 'Milch Animal Cnt Total'),
            'non_milch_animal_cow_cnt' => Yii::t('app', 'Non Milch Animal Cow Cnt'),
            'non_milch_animal_buff_cnt' => Yii::t('app', 'Non Milch Animal Buff Cnt'),
            'non_milch_animal_country_cow_cnt' => Yii::t('app', 'Non Milch Animal Country Cow Cnt'),
            'non_milch_animal_cnt_total' => Yii::t('app', 'Non Milch Animal Cnt Total'),
            'cow_milk_volume' => Yii::t('app', 'Cow Milk Volume'),
            'buff_milk_volume' => Yii::t('app', 'Buff Milk Volume'),
            'total_milk_volume' => Yii::t('app', 'Total Milk Volume'),
            'nos_of_pouring_members' => Yii::t('app', 'Nos Of Pouring Members'),
            'per_day_milk_sales_volume' => Yii::t('app', 'Per Day Milk Sales Volume'),
            'expected_pourer_count' => Yii::t('app', 'Expected Pourer Count'),
            'expected_milk_volume' => Yii::t('app', 'Expected Milk Volume'),
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
    public function getStateCode() {
        return $this->hasOne(TblStates::className(), ['state_code' => 'state_code']);
    }
    public function getDistrictCode() {
        return $this->hasOne(TblDistricts::className(), ['district_code' => 'district_code']);
    }
    public function getVillageCode() {
        return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
    }
    public function getHamletCode() {
        return $this->hasOne(TblHamlets::className(), ['hamlet_code' => 'hamlet_code']);
    }
    public function getSubDistrictCode() {
        return $this->hasOne(TblSubDistricts::className(), ['sub_district_code' => 'sub_district_code']);
    }
    public function getSurveyPersonCode() {
        return $this->hasOne(User::className(), ['id' => 'survey_person_code']);
    }
}
