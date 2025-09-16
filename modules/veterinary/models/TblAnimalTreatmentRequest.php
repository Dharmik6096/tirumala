<?php

namespace app\modules\veterinary\models;

use app\models\ChildModel;
use app\modules\dcsoperation\models\TblMember;
use app\modules\dcsoperation\models\TblMemberAnimalType;
use app\modules\general\models\TblGender;
use app\modules\organisation\models\TblDcs;
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
class TblAnimalTreatmentRequest extends ChildModel
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
            [['animal_treatment_request_id','case_no','dcs_code','member_code','member_name','member_type','mobile_number','address','case_type_id','member_animal_tag_id','disease_id','animal_type_id','breed_id','gender_id','year','month','tran_datetime','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type'], 'safe'],
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
            'dcs_code' => Yii::t('app', 'DCS'),
            'member_code' => Yii::t('app', 'Member'),
            'member_name' => Yii::t('app', 'Member Name'),
            'member_type' => Yii::t('app', 'Member Type'),
            'mobile_number' => Yii::t('app', 'Mobile Number'),
            'address' => Yii::t('app', 'Address'),
            'case_type_id' => Yii::t('app', 'Case Type'),
            'member_animal_tag_id' => Yii::t('app', 'Member Animal Tag'),
            'disease_id' => Yii::t('app', 'Disease'),
            'animal_type_id' => Yii::t('app', 'Animal Type'),
            'breed_id' => Yii::t('app', 'Breed'),
            'gender_id' => Yii::t('app', 'Gender'),
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

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    public function getAnimalTypeId() {
        return $this->hasOne(TblMemberAnimalType::className(), ['animal_type_code' => 'animal_type_id']);
    }

    public function getBreedId() {
        return $this->hasOne(TblBreedMaster::className(), ['breed_id' => 'breed_id']);
    }

    public function getGenderId() {
        return $this->hasOne(TblGender::className(), ['gender_code' => 'gender_id']);
    }
    
    public function getCaseTypeId() {
        return $this->hasOne(TblCaseType::className(), ['case_type_id' => 'case_type_id']);
    }

    public function getMemberAnimalTagId() {
        return $this->hasOne(TblMemberAnimalTagDetails::className(), ['member_animal_tag_id' => 'member_animal_tag_id']);
    }

    public function getDiseaseId() {
        return $this->hasOne(TblDiseaseMaster::className(), ['disease_id' => 'disease_id']);
    }
}
