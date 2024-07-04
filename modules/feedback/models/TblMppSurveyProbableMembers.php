<?php

namespace app\modules\feedback\models;

use app\models\ChildModel;
use Yii;

/**
 * This is the model class for table "tbl_mpp_survey_probable_members".
 *
 * @property integer $mpp_survey_members_id
 * @property integer $mpp_survey_id
 * @property string $name
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
class TblMppSurveyProbableMembers extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_mpp_survey_probable_members';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mpp_survey_members_id','mpp_survey_id','name','mobile_no','milch_animal_cow_cnt','milch_animal_buff_cnt','milch_animal_country_cow_cnt','cow_milk_volume','buff_milk_volume','total_milk_volume','own_milk_consumption','balance_milk','remarks','created_at','created_by','updated_at','updated_by','originating_type','originating_org_code','originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mpp_survey_members_id' => Yii::t('app', 'Mpp Survey Members ID'),
            'mpp_survey_id' => Yii::t('app', 'Mpp Survey ID'),
            'name' => Yii::t('app', 'Name'),
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
}
