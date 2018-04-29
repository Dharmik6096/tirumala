<?php

namespace app\modules\dcsoperation\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblUnions;
use webvimark\modules\UserManagement\models\User;
/**
 * This is the model class for table "tbl_head_load_history".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $criteria_description
 * @property string $deleted_at
 * @property string $head_load_code
 * @property string $history_created_at
 * @property integer $is_active
 * @property integer $is_delete
 * @property string $operation_type
 * @property string $updated_at
 * @property string $created_by
 * @property integer $criteria_type_code
 * @property string $dcs_code
 * @property string $deleted_by
 * @property string $union_code
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property TblHeadLoadCriteria $criteriaTypeCode
 * @property TblDcs $dcsCode
 * @property User $deletedBy
 * @property TblUnions $unionCode
 * @property User $updatedBy
 */
class TblHeadLoadHistory extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_head_load_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['criteria_type_code', 'dcs_code','union_code', 'head_load_code', 'criteria_description', 'created_at', 'deleted_at','operation_type','created_by', 'deleted_by', 'updated_by', 'is_active', 'history_created_at', 'is_delete','flg_sentbox_entry', 'sync_status', 'criteria_type_code', 'sync_timestamp', 'updated_at'], 'safe'],
//            [['is_active'], 'integer'],
//            [['criteria_description'], 'string', 'max' => 100],
//            [['flg_sentbox_entry', 'sync_status'], 'string', 'max' => 1],
//            [['head_load_code'], 'string', 'max' => 20],
//            [['operation_type'], 'string', 'max' => 10],
//            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 14],
//            [['dcs_code'], 'string', 'max' => 9],
//            [['union_code'], 'string', 'max' => 3],
//            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
//            [['criteria_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblHeadLoadCriteria::className(), 'targetAttribute' => ['criteria_type_code' => 'code']],
//            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
//            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['deleted_by' => 'id']],
//            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
//            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
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
            'criteria_description' => Yii::t('app', 'Criteria Description'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'head_load_code' => Yii::t('app', 'Head Load Code'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'criteria_type_code' => Yii::t('app', 'Criteria Type Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'union_code' => Yii::t('app', 'Union Code'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
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
    public function getCriteriaTypeCode()
    {
        return $this->hasOne(TblHeadLoadCriteria::className(), ['code' => 'criteria_type_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
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
    public function getUnionCode()
    {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblHeadLoadHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblHeadLoadHistoryQuery(get_called_class());
    }
}
