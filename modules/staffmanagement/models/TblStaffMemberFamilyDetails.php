<?php

namespace app\modules\staffmanagement\models;

use Yii;
use app\modules\general\models\TblRelationship;
use app\modules\general\models\TblGender;
use app\modules\staffmanagement\models\TblStaffMember;

/**
 * This is the model class for table "tbl_staff_member_family_details".
 *
 * @property integer $staff_family_details_code
 * @property string $staff_member_code
 * @property string $family_member_name
 * @property string $relation_code
 * @property string $birth_date
 * @property string $gender_code
 * @property string $union_code
 * @property string $local_family_member_name
 * @property string $guardian_member_code
 * @property integer $is_nominee
 * @property integer $is_minor
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblStaffMemberFamilyDetails extends \app\models\ChildModel {

    public $age;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_staff_member_family_details';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['staff_member_code', 'family_member_name', 'relation_code', 'birth_date', 'gender_code', 'union_code', 'local_family_member_name', 'guardian_member_code', 'created_by', 'updated_by'], 'string'],
            [['is_nominee', 'is_minor', 'is_active'], 'integer'],
            [['created_at', 'updated_at', 'age', 'is_nominee'], 'safe'],
            [['family_member_name', 'relation_code', 'gender_code'], 'required'],
            [['is_active'], 'default', 'value' => 1],
            [['family_member_name'], function ($attribute, $params) {
                    Yii::$app->general->validateAlphaNumber($this, $attribute, $params);
                }, 'skipOnEmpty' => false,],
            [['local_family_member_name'], function ($attribute, $params) {
                    Yii::$app->general->vaildateLocalField($this, $attribute, $params);
                }, 'skipOnEmpty' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'staff_family_details_code' => Yii::t('app', 'Staff Family Details Code'),
            'staff_member_code' => Yii::t('app', 'Staff Member Code'),
            'family_member_name' => Yii::t('app', 'Family Member Name'),
            'relation_code' => Yii::t('app', 'Relation'),
            'birth_date' => Yii::t('app', 'Birth Date'),
            'gender_code' => Yii::t('app', 'Gender'),
            'union_code' => Yii::t('app', 'Union'),
            'local_family_member_name' => Yii::t('app', 'Local Family Member Name'),
            'guardian_member_code' => Yii::t('app', 'Guardian Member Code'),
            'is_nominee' => Yii::t('app', 'Is Nominee'),
            'is_minor' => Yii::t('app', 'Is Minor'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function getRelationship() {
        return $this->hasOne(TblRelationship::className(), ['relationship_code' => 'relation_code']);
    }

    public function getGenderCode() {
        return $this->hasOne(TblGender::className(), ['gender_code' => 'gender_code']);
    }

    public function getStaffMemberCode() {
        return $this->hasOne(TblStaffMember::className(), ['staff_member_code' => 'staff_member_code']);
    }

}
