<?php

namespace app\modules\veterinary\models;

use Yii;

/**
 * This is the model class for table "tbl_member_animal_tag_details".
 *
 * @property integer $member_animal_tag_id
 * @property string $dcs_code
 * @property string $member_code
 * @property string $mobile_no
 * @property string $email
 * @property string $tag_no
 * @property integer $animal_type_id
 * @property integer $gender_id
 * @property integer $breed_id
 * @property integer $year
 * @property integer $month
 * @property integer $no_of_calving
 * @property string $last_date_of_calving
 * @property integer $pregnancy_status
 * @property integer $pregnancy_month
 * @property string $pregnancy_month_on_date
 * @property integer $milking_status
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblMemberAnimalTagDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_member_animal_tag_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['animal_type_id', 'breed_id', 'year', 'month', 'no_of_calving'], 'required'],
            [['animal_type_id', 'gender_id', 'breed_id', 'year', 'month', 'no_of_calving', 'pregnancy_status', 'pregnancy_month', 'milking_status', 'originating_type'], 'integer'],
            [['last_date_of_calving', 'pregnancy_month_on_date', 'created_at', 'updated_at'], 'safe'],
            [['dcs_code', 'member_code', 'originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['mobile_no', 'email'], 'string', 'max' => 255],
            [['tag_no'], 'string', 'max' => 50],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'member_animal_tag_id' => Yii::t('app', 'Member Animal Tag ID'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'member_code' => Yii::t('app', 'Member Code'),
            'mobile_no' => Yii::t('app', 'Mobile No'),
            'email' => Yii::t('app', 'Email'),
            'tag_no' => Yii::t('app', 'Tag No'),
            'animal_type_id' => Yii::t('app', 'Animal Type ID'),
            'gender_id' => Yii::t('app', 'Gender ID'),
            'breed_id' => Yii::t('app', 'Breed ID'),
            'year' => Yii::t('app', 'Year'),
            'month' => Yii::t('app', 'Month'),
            'no_of_calving' => Yii::t('app', 'No Of Calving'),
            'last_date_of_calving' => Yii::t('app', 'Last Date Of Calving'),
            'pregnancy_status' => Yii::t('app', 'Pregnancy Status'),
            'pregnancy_month' => Yii::t('app', 'Pregnancy Month'),
            'pregnancy_month_on_date' => Yii::t('app', 'Pregnancy Month On Date'),
            'milking_status' => Yii::t('app', 'Milking Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
        ];
    }
}
