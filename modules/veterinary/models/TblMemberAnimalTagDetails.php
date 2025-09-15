<?php

namespace app\modules\veterinary\models;

use app\models\ChildModel;
use app\modules\dcsoperation\models\TblMember;
use app\modules\dcsoperation\models\TblMemberAnimalType;
use app\modules\general\models\TblGender;
use app\modules\organisation\models\TblDcs;
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
class TblMemberAnimalTagDetails extends ChildModel
{
    public $animal_type, $gender, $breed;
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
            [['dcs_code','member_code','mobile_no','email','tag_no','animal_type_id','gender_id','breed_id','year','month','no_of_calving','last_date_of_calving','pregnancy_status','pregnancy_month','pregnancy_month_on_date','milking_status','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type'], 'safe'],
            [['dcs_code', 'member_code', 'tag_no', 'animal_type','gender','breed', 'year', 'month', 'no_of_calving', 'last_date_of_calving', 'pregnancy_status', 'milking_status'], 'required', 'on' => 'importCsv'],
            ['tag_no', 'match', 'pattern' => '/^[0-9]{12}$/', 'message' => 'Tag no must be integer and exactly 12 digits'],
            [['animal_type','gender','breed'], 'number', 'integerOnly' => true, 'on' => 'importCsv'],
            [['year', 'month'], 'number', 'integerOnly' => true,'min' => 1, 'on' => 'importCsv'],
            [['month'], 'in', 'range' => range(1, 12), 'message' => 'Month must be between 1 and 12.'],
            [['year'], 'match', 'pattern' => '/^\d{4}$/', 'message' => 'Year must be 4 digits.'],
            [['last_date_of_calving'], 'convertDateDot', 'on' => ['importCsv']],
            [['last_date_of_calving'], 'date', 'format' => 'php:d.m.Y', 'message' => Yii::t('app/validation', 'Please enter date in valid format e.g. 01.12.2018'), 'on' => ['importCsv']],
            [['last_date_of_calving'], 'convertDate', 'on' => ['importCsv']],
            [['pregnancy_status'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'pregnancy_status');
                }, 'on' => 'importCsv'],
            [['milking_status'], function ($attribute, $params) {
                    Yii::$app->general->validateGlobalStatic($this, $attribute, 'milking_status');
                }, 'on' => 'importCsv'],
            [['dcs_code'], 'setImport', 'on' => 'importCsv'],
            [['animal_type_id'], 'validateMemberAnimalType'],
            [['gender_id'], 'validateGender'],
            [['breed_id'], 'validateBreed'],
            // [['year','month'], 'match', 'pattern' => '/^[0-9]+$/', 'message' => Yii::t('app', '{attribute} must be integer.')],
            [['pregnancy_month', 'pregnancy_month_on_date'], 'required', 'when' => function ($model) {
                return $model->pregnancy_status == '1';
            }, 'on' => 'importCsv'],
            [['pregnancy_month'], 'in', 'range' => range(1, 12), 'message' => 'Pregnancy Month must be between 1 and 12.'],
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
            'animal_type_id' => Yii::t('app', 'Animal Type'),
            'gender_id' => Yii::t('app', 'Gender'),
            'breed_id' => Yii::t('app', 'Breed'),
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

    public function setImport($attribute, $params) {
        $dcs = new TblDcs();
        $this->dcs_code = $dcs->getValidDcs($this->dcs_code);
        if (empty($this->dcs_code)) {
            $this->addError('dcs_code', Yii::t('app/validation', Yii::t('app', 'DCS') . ' is invalid'));
            return false;
        }

        $member = $this->validateMember($this->dcs_code, $this->member_code);
        if (empty($member)) {
            $this->addError('member_code', Yii::t('app/validation', Yii::t('app', 'Member') . ' is invalid'));
            return false;
        } else {
            $this->member_code = $member->member_code;
            $this->mobile_no = $member->mobile_no;
            $this->email = $member->email;
        }
        $this->animal_type_id = $this->animal_type;
        $this->gender_id = $this->gender;
        $this->breed_id = $this->breed;
        if($this->pregnancy_status == 1){
            $this->pregnancy_month_on_date = date('Y-m-d H:i:s');
        }
    }

    public function validateMember($dcsCode, $memberCode) {
        return TblMember::find()->where(['dcs_code' => $dcsCode, 'is_active' => 1])
                        ->andWhere(['or', ['member_code' => $memberCode], ['ex_member_code' => $memberCode]])->one();
    }

    public function validateMemberAnimalType($attribute, $params) {
        if (!empty($this->animal_type_id)) {
            $result = TblMemberAnimalType::find()->where(['is_active' => 1])
                        ->andWhere(['animal_type_code' => $this->animal_type_id])->one();
            if (empty($result)) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . " Id '" . $this->animal_type_id . "'" . ' is invalid.'));
                return false;
            }
        }
    }

    public function validateBreed($attribute, $params) {
        if (!empty($this->breed_id)) {
            $result = TblBreedMaster::find()->where(['breed_id' => $this->breed_id])->one();
            if (empty($result)) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . " Id '" . $this->gender_id . "'" . ' is invalid.'));
                return false;
            } else {
                $this->breed_id = $result->breed_id;
            }
        }
    }

    public function validateGender($attribute, $params) {
        if (!empty($this->gender_id)) {
            $gender = new TblGender();
            if ($gender->getGender($this->gender_id) <= 0) {
                $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . " Id '" . $this->gender_id . "'" . ' is invalid.'));
                return false;
            } else {
                $this->gender_id = $gender->gender_code;
            }
        }
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'member_code']);
    }

    public function convertDateDot() {
        try {
            $this->last_date_of_calving = Yii::$app->controls->view_date($this->last_date_of_calving, 'php:d.m.Y');
        } catch (\Exception $e) {
            $this->last_date_of_calving = '-';
        }
    }

    public function convertDate() {
        if (empty($this->getErrors())) {
            $this->last_date_of_calving = !empty($this->last_date_of_calving) ? Yii::$app->controls->view_date($this->last_date_of_calving, 'php:Y-m-d') : NULL;
            $this->last_date_of_calving = $this->last_date_of_calving;
        }
    }
}
