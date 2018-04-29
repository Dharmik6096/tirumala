<?php

namespace app\modules\staffmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_gender".
 *
 * @property integer $gender_code
 * @property string $gender
 * @property integer $is_active
 * @property integer $is_delete
 *
 * @property TblGenderLocal[] $tblGenderLocals
 * @property TblMember[] $tblMembers
 * @property TblMemberHistory[] $tblMemberHistories
 * @property TblStaffMember[] $tblStaffMembers
 * @property TblStaffMemberHistory[] $tblStaffMemberHistories
 */
class TblGender extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_gender';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active', 'is_delete'], 'integer'],
            [['gender'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gender_code' => Yii::t('app', 'Gender ID'),
            'gender' => Yii::t('app', 'Gender'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblGenderLocals()
    {
        return $this->hasMany(TblGenderLocal::className(), ['gender_id' => 'gender_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMembers()
    {
        return $this->hasMany(TblMember::className(), ['gender_code' => 'gender_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberHistories()
    {
        return $this->hasMany(TblMemberHistory::className(), ['gender_code' => 'gender_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffMembers()
    {
        return $this->hasMany(TblStaffMember::className(), ['gender_code' => 'gender_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffMemberHistories()
    {
        return $this->hasMany(TblStaffMemberHistory::className(), ['gender_code' => 'gender_code']);
    }

    /**
     * @inheritdoc
     * @return TblGenderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblGenderQuery(get_called_class());
    }
}
