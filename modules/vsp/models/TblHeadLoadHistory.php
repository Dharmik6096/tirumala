<?php

namespace app\modules\vsp\models;

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
 * @property string $head_load_code
 * @property string $history_created_at
 * @property integer $is_active
 * @property string $operation_type
 * @property string $updated_at
 * @property string $created_by
 * @property integer $criteria_type_code
 * @property string $dcs_code
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
class TblHeadLoadHistory extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_head_load_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['created_at', 'history_created_at', 'criteria_type_code', 'updated_at'], 'safe'],
            [['is_active'], 'safe'],
            [['criteria_description'], 'safe'],
            [['head_load_code'], 'safe'],
            [['operation_type'], 'safe'],
            [['created_by', 'updated_by', 'min_km'], 'safe'],
            [['dcs_code'], 'safe'],
            [['union_code', 'entry_type', 'criteria_type_code', 'updated_by',], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'criteria_description' => Yii::t('app', 'Criteria Description'),
            'head_load_code' => Yii::t('app', 'Head Load Code'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'criteria_type_code' => Yii::t('app', 'Criteria Type Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy() {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCriteriaTypeCode() {
        return $this->hasOne(TblHeadLoadCriteria::className(), ['code' => 'criteria_type_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblHeadLoadHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblHeadLoadHistoryQuery(get_called_class());
    }

}
