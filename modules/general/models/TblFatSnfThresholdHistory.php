<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_fat_snf_threshold_history".
 *
 * @property integer $id
 * @property integer $threshold_code
 * @property string $dcs_code
 * @property integer $shift_id
 * @property string $wef_date
 * @property string $minimum_fat
 * @property string $maximum_fat
 * @property string $minimum_snf
 * @property string $maximum_snf
 * @property string $created_at
 * @property string $created_by
 * @property integer $is_active
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $operation_type
 */
class TblFatSnfThresholdHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_fat_snf_threshold_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['minimum_fat', 'maximum_fat', 'minimum_snf', 'maximum_snf','dcs_code', 'created_by', 'updated_by','wef_date', 'created_at', 'updated_at', 'history_created_at','threshold_code', 'shift_id', 'is_active','operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'threshold_code' => Yii::t('app', 'Threshold Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'shift_id' => Yii::t('app', 'Shift ID'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'minimum_fat' => Yii::t('app', 'Minimum Fat'),
            'maximum_fat' => Yii::t('app', 'Maximum Fat'),
            'minimum_snf' => Yii::t('app', 'Minimum Snf'),
            'maximum_snf' => Yii::t('app', 'Maximum Snf'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
        ];
    }
}
