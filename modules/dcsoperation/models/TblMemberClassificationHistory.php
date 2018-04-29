<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\organisation\models\TblUnions;
/**
 * This is the model class for table "tbl_member_classification_history".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $created_by
 * @property string $flg_sentbox_entry
 * @property string $history_created_at
 * @property boolean $is_active
 * @property string $member_classification_code
 * @property string $member_classification_name
 * @property string $operation_type
 * @property double $range_from
 * @property double $range_to
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $member_classification_type
 * @property string $union_code
 *
 * @property TblMemberClassificationType $memberClassificationTypeCode
 * @property TblUnions $unionCode
 */
class TblMemberClassificationHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_member_classification_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_type','union_code','created_by', 'updated_by','created_at', 'history_created_at', 'updated_at','is_active','member_classification_code', 'member_classification_name', 'member_classification_type','range_from', 'range_to'], 'safe'],
//            [['is_active'], 'boolean'],
//            [['member_classification_name', 'member_classification_type'], 'required'],
//            [['range_from', 'range_to'], 'number'],
//            [['member_classification_type'], 'integer'],
//            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 14],
//            [['member_classification_code'], 'string', 'max' => 11],
//            [['member_classification_name'], 'string', 'max' => 25],
//            [['union_code'], 'string', 'max' => 3],
//            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'member_classification_code' => Yii::t('app', 'Member Classification Code'),
            'member_classification_name' => Yii::t('app', 'Member Classification Name'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'range_from' => Yii::t('app', 'Range From'),
            'range_to' => Yii::t('app', 'Range To'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'member_classification_type' => Yii::t('app', 'Member Classification Type Code'),
            'union_code' => Yii::t('app', 'Union Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode()
    {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @inheritdoc
     * @return TblMemberClassificationHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblMemberClassificationHistoryQuery(get_called_class());
    }
}
