<?php

namespace app\modules\vsp\models;

use Yii;

/**
 * This is the model class for table "tbl_head_load_applicability_history".
 *
 * @property integer $id
 * @property string $code
 * @property string $created_at
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $updated_at
 * @property string $wef_date
 * @property string $created_by
 * @property string $dcs_code
 * @property string $head_load_code
 * @property string $updated_by
 *
 * @property User $createdBy
 * @property TblDcs $dcsCode
 * @property TblHeadLoad $headLoadCode
 * @property TblSubCenter $subCenterCode
 * @property User $updatedBy
 */
class TblHeadLoadApplicabilityHistory extends \yii\db\ActiveRecord {

    public $product_group_id;
    public $language_code;
    public $local_code;
    public $local_name;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_head_load_applicability_history';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_code', 'head_load_code', 'code', 'created_at', 'operation_type', 'created_by', 'updated_by', 'history_created_at', 'updated_at', 'wef_date', 'shift_code', 'shift_for', 'union_code'], 'safe'],
            [['applicable_code', 'applicable_for'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'created_by' => Yii::t('app', 'Created By'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'head_load_code' => Yii::t('app', 'Head Load Code'),
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
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeadLoadCode() {
        return $this->hasOne(TblHeadLoad::className(), ['head_load_code' => 'head_load_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy() {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TblHeadLoadApplicabilityHistoryQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblHeadLoadApplicabilityHistoryQuery(get_called_class());
    }

}
