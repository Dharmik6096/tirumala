<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_relationships".
 *
 * @property integer $relationship_code
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $relationship
 *
 * @property TblMemberInformation[] $tblMemberInformations
 * @property TblMemberInformationHistory[] $tblMemberInformationHistories
 * @property TblRelationshipsLocal[] $tblRelationshipsLocals
 */
class TblRelationships extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_relationships';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active', 'is_delete'], 'integer'],
            [['relationship'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'relationship_code' => Yii::t('app', 'Relationship Code'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'relationship' => Yii::t('app', 'Relationship'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberInformations()
    {
        return $this->hasMany(TblMemberInformation::className(), ['relationship_code' => 'relationship_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberInformationHistories()
    {
        return $this->hasMany(TblMemberInformationHistory::className(), ['relationship_code' => 'relationship_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblRelationshipsLocals()
    {
        return $this->hasMany(TblRelationshipsLocal::className(), ['relationship_code' => 'relationship_code']);
    }

    /**
     * @inheritdoc
     * @return TblRelationshipsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblRelationshipsQuery(get_called_class());
    }
}
