<?php

namespace app\modules\veterinary\models;

use Yii;

/**
 * This is the model class for table "tbl_animal_treatment_request".
 *
 * @property integer $animal_treatment_request_id
 * @property string $case_no
 * @property string $dcs_code
 * @property string $member_code
 * @property string $member_name
 * @property string $member_type
 * @property string $mobile_number
 * @property string $address
 * @property integer $case_type_id
 * @property integer $member_animal_tag_id
 * @property integer $disease_id
 * @property integer $animal_type_id
 * @property integer $breed_id
 * @property integer $gender_id
 * @property integer $year
 * @property integer $month
 * @property string $tran_datetime
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 */
class TblAnimalTreatmentRequest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_animal_treatment_request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['case_type_id', 'member_animal_tag_id', 'disease_id', 'animal_type_id', 'breed_id', 'year', 'month'], 'required'],
            [['case_type_id', 'member_animal_tag_id', 'disease_id', 'animal_type_id', 'breed_id', 'gender_id', 'year', 'month', 'originating_type'], 'integer'],
            [['tran_datetime', 'created_at', 'updated_at'], 'safe'],
            [['case_no', 'dcs_code', 'member_code', 'member_name', 'member_type', 'originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['mobile_number', 'address'], 'string', 'max' => 255],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'animal_treatment_request_id' => Yii::t('app', 'Animal Treatment Request ID'),
            'case_no' => Yii::t('app', 'Case No'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'member_code' => Yii::t('app', 'Member Code'),
            'member_name' => Yii::t('app', 'Member Name'),
            'member_type' => Yii::t('app', 'Member Type'),
            'mobile_number' => Yii::t('app', 'Mobile Number'),
            'address' => Yii::t('app', 'Address'),
            'case_type_id' => Yii::t('app', 'Case Type ID'),
            'member_animal_tag_id' => Yii::t('app', 'Member Animal Tag ID'),
            'disease_id' => Yii::t('app', 'Disease ID'),
            'animal_type_id' => Yii::t('app', 'Animal Type ID'),
            'breed_id' => Yii::t('app', 'Breed ID'),
            'gender_id' => Yii::t('app', 'Gender ID'),
            'year' => Yii::t('app', 'Year'),
            'month' => Yii::t('app', 'Month'),
            'tran_datetime' => Yii::t('app', 'Tran Datetime'),
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
