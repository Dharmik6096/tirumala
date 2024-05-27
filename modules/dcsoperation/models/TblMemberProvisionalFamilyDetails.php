<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\models\ChildModel;
use app\modules\general\models\TblRelationship;
use app\modules\general\models\TblGender;

/**
 * This is the model class for table "tbl_member_provisional_family_details".
 *
 * @property integer $member_provisional_family_detail_code
 * @property string $union_code
 * @property string $provisional_member_code
 * @property string $family_member_name
 * @property string $local_family_member_name
 * @property string $dob
 * @property integer $age
 * @property integer $gender_code
 * @property integer $relationship_code
 * @property integer $is_nominee
 * @property string $nominee_address
 * @property string $local_nominee_address
 * @property string $guardian_name
 * @property string $local_guardian_name
 * @property string $remarks
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 */
class TblMemberProvisionalFamilyDetails extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_provisional_family_details';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['age', 'union_code', 'local_family_member_name', 'dob', 'remarks', 'nominee_address', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'local_nominee_address', 'provisional_member_code', 'gender_code', 'family_member_name', 'guardian_name', 'local_guardian_name', 'relationship_code', 'is_nominee', 'originating_type', 'created_at', 'updated_at'], 'safe'],
                [['age', 'dob', 'nominee_address', 'gender_code', 'family_member_name', 'guardian_name', 'relationship_code'], 'required', 'on' => ['member_family_detail']],
                [['is_nominee'], 'validateIsNominee', 'on' => ['member_family_detail']],
                [['dob'], 'validateAge', 'on' => ['member_family_detail']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'member_provisional_family_detail_code' => Yii::t('app', 'Member Provisional Family Detail Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'provisional_member_code' => Yii::t('app', 'Provisional Member Code'),
            'family_member_name' => Yii::t('app', 'Family Member Name'),
            'local_family_member_name' => Yii::t('app', 'Local Family Member Name'),
            'dob' => Yii::t('app', 'Date of Birth'),
            'age' => Yii::t('app', 'Age'),
            'gender_code' => Yii::t('app', 'Gender'),
            'relationship_code' => Yii::t('app', 'Relation With Nominee'),
            'is_nominee' => Yii::t('app', 'Is Nominee'),
            'nominee_address' => Yii::t('app', 'Nominee Address'),
            'local_nominee_address' => Yii::t('app', 'Local Nominee Address'),
            'guardian_name' => Yii::t('app', 'Guardian Name'),
            'local_guardian_name' => Yii::t('app', 'Local Guardian Name'),
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

    public function getRelationship() {
        return $this->hasOne(TblRelationship::className(), ['relationship_code' => 'relationship_code']);
    }

    public function getGenderCode() {
        return $this->hasOne(TblGender::className(), ['gender_code' => 'gender_code']);
    }

    public function getFamilyData($pro_member_code) {
        return $this->find()->where(['provisional_member_code' => $pro_member_code])->all();
    }

    public function validateAge($attribute, $params) {
        $dob = $this->dob;
        $age = $this->age;

        if (!empty($dob) && !empty($age)) {
            $dobDateTime = new \DateTime($dob);
            $today = new \DateTime();
            $ageFromDate = $dobDateTime->diff($today)->y;

            if ($age != $ageFromDate) {
                $this->addError($attribute, 'Age does not match the provided date of birth.');
            }
        }
    }

    public function validateIsNominee($attribute, $params) {
        if ($this->$attribute == 1) {
            $existingNomineeCount = $this::find()
                    ->where(['provisional_member_code' => $this->provisional_member_code, 'is_nominee' => 1])
                    ->count();

            if ($existingNomineeCount > 0) {
                $this->addError($attribute, 'Only one nominee is allowed per provisional member.');
            }
        }
    }

}
