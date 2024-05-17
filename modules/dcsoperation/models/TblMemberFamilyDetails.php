<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_member_family_details".
 *
 * @property integer $member_family_detail_code
 * @property string $union_code
 * @property string $member_code
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
class TblMemberFamilyDetails extends ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_member_family_details';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['age', 'union_code', 'member_code', 'dob', 'remarks', 'gender_code', 'family_member_name', 'local_family_member_name', 'nominee_address', 'local_nominee_address', 'guardian_name', 'local_guardian_name', 'relationship_code', 'is_nominee', 'originating_type', 'created_at', 'updated_at', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'member_family_detail_code' => Yii::t('app', 'Member Family Detail Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'member_code' => Yii::t('app', 'Member Code'),
            'family_member_name' => Yii::t('app', 'Family Member Name'),
            'local_family_member_name' => Yii::t('app', 'Local Family Member Name'),
            'dob' => Yii::t('app', 'Dob'),
            'age' => Yii::t('app', 'Age'),
            'gender_code' => Yii::t('app', 'Gender Code'),
            'relationship_code' => Yii::t('app', 'Relationship Code'),
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

}
