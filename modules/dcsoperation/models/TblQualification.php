<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_qualification".
 *
 * @property integer $qualification_code
 * @property string $created_at
 * @property string $deleted_at
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $qualification_name
 * @property integer $sequences_no
 * @property string $updated_at
 * @property string $created_by
 * @property string $deleted_by
 * @property string $updated_by
 *
 * @property TblMemberInformation[] $tblMemberInformations
 * @property TblMemberInformationHistory[] $tblMemberInformationHistories
 * @property User $createdBy
 * @property User $deletedBy
 * @property User $updatedBy
 * @property TblQualificationLocal[] $tblQualificationLocals
 */
class TblQualification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_qualification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'deleted_at', 'updated_at'], 'safe'],
            [['is_active', 'is_delete', 'sequences_no'], 'integer'],
            [['qualification_name'], 'required'],
            [['qualification_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'qualification_code' => Yii::t('app', 'Qualification Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'qualification_name' => Yii::t('app', 'Qualification Name'),
            'sequences_no' => Yii::t('app', 'Sequences No'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberInformations()
    {
        return $this->hasMany(TblMemberInformation::className(), ['qualification_code' => 'qualification_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberInformationHistories()
    {
        return $this->hasMany(TblMemberInformationHistory::className(), ['qualification_code' => 'qualification_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'deleted_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblQualificationLocals()
    {
        return $this->hasMany(TblQualificationLocal::className(), ['qualification_code' => 'qualification_code']);
    }

    /**
     * @inheritdoc
     * @return TblQualificationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblQualificationQuery(get_called_class());
    }
}
