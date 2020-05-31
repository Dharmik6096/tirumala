<?php

namespace app\modules\staffmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_staff_member_family_details_history".
 *
 * @property integer $id
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
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblStaffMemberFamilyDetailsHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_staff_member_family_details_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_family_details_code', 'is_nominee', 'is_minor', 'is_active'], 'integer'],
            [['staff_member_code', 'family_member_name', 'relation_code', 'birth_date', 'gender_code', 'union_code', 'local_family_member_name', 'guardian_member_code', 'created_by', 'updated_by', 'operation_type', 'history_created_by'], 'string'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'staff_family_details_code' => Yii::t('app', 'Staff Family Details Code'),
            'staff_member_code' => Yii::t('app', 'Staff Member Code'),
            'family_member_name' => Yii::t('app', 'Family Member Name'),
            'relation_code' => Yii::t('app', 'Relation Code'),
            'birth_date' => Yii::t('app', 'Birth Date'),
            'gender_code' => Yii::t('app', 'Gender Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'local_family_member_name' => Yii::t('app', 'Local Family Member Name'),
            'guardian_member_code' => Yii::t('app', 'Guardian Member Code'),
            'is_nominee' => Yii::t('app', 'Is Nominee'),
            'is_minor' => Yii::t('app', 'Is Minor'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }
}
