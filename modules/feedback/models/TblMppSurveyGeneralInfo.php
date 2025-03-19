<?php

namespace app\modules\feedback\models;

use app\models\ChildModel;
use Yii;

/**
 * This is the model class for table "tbl_mpp_survey_general_info".
 *
 * @property integer $mpp_survey_gn_info_id
 * @property integer $mpp_survey_id
 * @property integer $no_male
 * @property integer $no_female
 * @property integer $gender_total
 * @property integer $total_voters
 * @property integer $total_family
 * @property integer $total_family_engageding_dairy_business
 * @property string $name_of_visiting_officer
 * @property string $number_of_visiting_officer
 * @property string $pradhan_name
 * @property string $pradhan_number
 * @property integer $power_status
 * @property integer $post_office
 * @property integer $health_center
 * @property integer $artificial_insemination_center
 * @property integer $animal_health_center
 * @property integer $status_of_advance_amount_in_thevillage
 * @property string $status_of_education_invillage
 * @property string $main_crops_of_thevillage
 * @property string $green_foddercrops
 * @property string $irrigation_facility
 * @property string $cowmilkvolume
 * @property string $buffmilkvolume
 * @property string $totalmilkvolume
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblMppSurveyGeneralInfo extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_mpp_survey_general_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mpp_survey_gn_info_id', 'mpp_survey_id', 'no_male', 'no_female', 'gender_total', 'total_voters', 'total_family', 'total_family_engageding_dairy_business', 'name_of_visiting_officer', 'number_of_visiting_officer', 'pradhan_name', 'pradhan_number', 'power_status', 'post_office', 'health_center', 'artificial_insemination_center', 'animal_health_center', 'status_of_advance_amount_in_thevillage', 'status_of_education_invillage', 'main_crops_of_thevillage', 'green_foddercrops', 'irrigation_facility', 'cowmilkvolume', 'buffmilkvolume', 'totalmilkvolume', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_type', 'originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mpp_survey_gn_info_id' => Yii::t('app', 'Mpp Survey Gn Info ID'),
            'mpp_survey_id' => Yii::t('app', 'Mpp Survey ID'),
            'no_male' => Yii::t('app', 'No. of Males'),
            'no_female' => Yii::t('app', 'No. of females'),
            'gender_total' => Yii::t('app', 'Total Gender Count'),
            'total_voters' => Yii::t('app', 'Total Voters'),
            'total_family' => Yii::t('app', 'Total Families'),
            'total_family_engageding_dairy_business' => Yii::t('app', 'Total Families Engaged in Dairy Business'),
            'name_of_visiting_officer' => Yii::t('app', 'Name Of Visiting Officer'),
            'number_of_visiting_officer' => Yii::t('app', 'Number Of Visiting Officers'),
            'pradhan_name' => Yii::t('app', 'Pradhan Name'),
            'pradhan_number' => Yii::t('app', 'Pradhan Contact Number'),
            'power_status' => Yii::t('app', 'Power Status'),
            'post_office' => Yii::t('app', 'Post Office'),
            'health_center' => Yii::t('app', 'Health Center'),
            'artificial_insemination_center' => Yii::t('app', 'Artificial Insemination Center'),
            'animal_health_center' => Yii::t('app', 'Animal Health Center'),
            'status_of_advance_amount_in_thevillage' => Yii::t('app', 'Status Of Advance Amount In The Village'),
            'status_of_education_invillage' => Yii::t('app', 'Status Of Education In village'),
            'main_crops_of_thevillage' => Yii::t('app', 'Main Crops Of The village'),
            'green_foddercrops' => Yii::t('app', 'Green Fodder Crops'),
            'irrigation_facility' => Yii::t('app', 'Irrigation Facility'),
            'cowmilkvolume' => Yii::t('app', 'Cow Milk Volume'),
            'buffmilkvolume' => Yii::t('app', 'Buff Milk Volume'),
            'totalmilkvolume' => Yii::t('app', 'Total Milk Volume'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }
}
