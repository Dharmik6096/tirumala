<?php

namespace app\modules\feedback\models;

use app\models\ChildModel;
use Yii;

/**
 * This is the model class for table "tbl_mpp_survey_competitors".
 *
 * @property integer $mpp_survey_competitors_id
 * @property integer $mpp_survey_id
 * @property integer $competitor_Id
 * @property integer $producter_count
 * @property string $milk_volume
 * @property string $milk_rate
 * @property string $other_input_services
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblMppSurveyCompetitors extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_mpp_survey_competitors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mpp_survey_competitors_id','mpp_survey_id','competitor_id','producter_count','milk_volume','milk_rate','other_input_services','created_at','created_by','updated_at','updated_by','originating_type','originating_org_code','originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mpp_survey_competitors_id' => Yii::t('app', 'Mpp Survey Competitors ID'),
            'mpp_survey_id' => Yii::t('app', 'Mpp Survey ID'),
            'competitor_id' => Yii::t('app', 'Competitor'),
            'producter_count' => Yii::t('app', 'Producter Count'),
            'milk_volume' => Yii::t('app', 'Milk Volume'),
            'milk_rate' => Yii::t('app', 'Milk Rate'),
            'other_input_services' => Yii::t('app', 'Other Input Services'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
        ];
    }

    public function getCompetitorCode() {
        return $this->hasOne(TblCompetitors::className(), ['competitor_id' => 'competitor_id']);
    }
}
