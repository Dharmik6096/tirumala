<?php

namespace app\modules\dcsoperation\models;

use Yii;

/**
 * This is the model class for table "tbl_head_load_applicability_history".
 *
 * @property integer $id
 * @property string $code
 * @property string $created_at
 * @property string $deleted_at
 * @property string $history_created_at
 * @property integer $is_delete
 * @property string $operation_type
 * @property string $updated_at
 * @property string $wef_date
 * @property string $created_by
 * @property string $dcs_code
 * @property string $deleted_by
 * @property string $head_load_code
 * @property string $sub_center_code
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property TblDcs $dcsCode
 * @property User $deletedBy
 * @property TblHeadLoad $headLoadCode
 * @property TblSubCenter $subCenterCode
 * @property User $updatedBy
 */
class TblHeadLoadApplicabilityHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_head_load_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dcs_code', 'sub_center_code', 'code', 'head_load_code', 'operation_type', 'created_by', 'deleted_by', 'updated_by', 'created_at', 'deleted_at', 'history_created_at',  'updated_at', 'wef_date', 'is_delete'], 'safe'],
//            [['is_delete'], 'integer'],
//            [['code', 'head_load_code'], 'string', 'max' => 20],
//            [['operation_type'], 'string', 'max' => 10],
//            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 14],
//            [['dcs_code', 'sub_center_code'], 'string', 'max' => 9],
//            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
//            [['head_load_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblHeadLoad::className(), 'targetAttribute' => ['head_load_code' => 'head_load_code']],
//            [['sub_center_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblSubCenter::className(), 'targetAttribute' => ['sub_center_code' => 'sub_center_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'created_by' => Yii::t('app', 'Created By'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'head_load_code' => Yii::t('app', 'Head Load Code'),
            'sub_center_code' => Yii::t('app', 'Sub Center Code'),
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
    public function getHeadLoadCode()
    {
        return $this->hasOne(TblHeadLoad::className(), ['head_load_code' => 'head_load_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubCenterCode()
    {
        return $this->hasOne(TblSubCenter::className(), ['sub_center_code' => 'sub_center_code']);
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
     * @return TblHeadLoadApplicabilityHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblHeadLoadApplicabilityHistoryQuery(get_called_class());
    }
}
