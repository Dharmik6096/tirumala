<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_member_types".
 *
 * @property integer $member_type_code
 * @property integer $is_active
 * @property string $member_type
 *
 * @property TblMember[] $tblMembers
 * @property TblMemberHistory[] $tblMemberHistories
 * @property TblMemberTypeLocal[] $tblMemberTypeLocals
 */
class TblMemberTypes extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_member_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active'], 'integer'],
            [['member_type_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'member_type_code' => Yii::t('app', 'Member Type Code'),
            'is_active' => Yii::t('app', 'Is Active'),
            'member_type_name' => Yii::t('app', 'Member Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMembers()
    {
        return $this->hasMany(TblMember::className(), ['member_type_code' => 'member_type_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblMemberHistories()
    {
        return $this->hasMany(TblMemberHistory::className(), ['member_type_code' => 'member_type_code']);
    }

    public function getMemberType(){
        $data = $this->getRecords();
        $values = \yii\helpers\ArrayHelper::map($data, 'member_type_code', 'member_type_name');
        return $values;
    }
    
    public function getRecords(){
        $data = $this->find()->select(['member_type_name','member_type_code'])->where(['is_active'=>'1'])->all();
        
        return $data;
    }
}
