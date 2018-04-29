<?php

namespace app\modules\staffmanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_bloodgroup".
 *
 * @property integer $blood_group_id
 * @property string $blood_group
 * @property integer $is_active
 * @property integer $is_delete
 *
 * @property TblBloodgroupLocal[] $tblBloodgroupLocals
 * @property TblMemberInformation[] $tblMemberInformations
 * @property TblMemberInformationHistory[] $tblMemberInformationHistories
 * @property TblStaffMember[] $tblStaffMembers
 * @property TblStaffMemberHistory[] $tblStaffMemberHistories
 */
class TblBloodgroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_bloodgroup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active', 'is_delete'], 'integer'],
            [['blood_group'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'blood_group_id' => Yii::t('app', 'Blood Group ID'),
            'blood_group' => Yii::t('app', 'Blood Group'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBloodgroupLocals()
    {
        return $this->hasMany(TblBloodgroupLocal::className(), ['blood_group_id' => 'blood_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberInformations()
    {
        return $this->hasMany(TblMemberInformation::className(), ['blood_group_id' => 'blood_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberInformationHistories()
    {
        return $this->hasMany(TblMemberInformationHistory::className(), ['blood_group_id' => 'blood_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffMembers()
    {
        return $this->hasMany(TblStaffMember::className(), ['blood_group_id' => 'blood_group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblStaffMemberHistories()
    {
        return $this->hasMany(TblStaffMemberHistory::className(), ['blood_group_id' => 'blood_group_id']);
    }

    /**
     * @inheritdoc
     * @return TblBloodgroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblBloodgroupQuery(get_called_class());
    }
}
