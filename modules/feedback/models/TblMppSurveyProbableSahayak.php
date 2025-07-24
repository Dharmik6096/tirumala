<?php

namespace app\modules\feedback\models;

use app\models\ChildModel;
use Yii;

/**
 * This is the model class for table "tbl_mpp_survey_probable_sahayak".
 *
 * @property integer $mpp_survey_sahayak_id
 * @property integer $mpp_survey_id
 * @property string $name
 * @property string $mobile_no
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblMppSurveyProbableSahayak extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_mpp_survey_probable_sahayak';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mpp_survey_sahayak_id','mpp_survey_id','name','mobile_no','remarks','created_at','created_by','updated_at','updated_by','originating_type','originating_org_code','originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mpp_survey_sahayak_id' => Yii::t('app', 'Mpp Survey Sahayak ID'),
            'mpp_survey_id' => Yii::t('app', 'Mpp Survey ID'),
            'name' => Yii::t('app', 'Name'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
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
