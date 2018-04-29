<?php

namespace app\modules\general\models;

use Yii;

/**
 * This is the model class for table "tbl_shift_time_history".
 *
 * @property integer $id
 * @property string $history_created_at
 * @property string $operation_type
 * @property integer $shift_time_code
 * @property string $dcs_code
 * @property integer $shift_code
 * @property string $start_time
 * @property string $end_time
 * @property string $wef_date
 * @property integer $allow_after_collection
 * @property string $created_at
 * @property string $created_by
 * @property integer $is_active
 * @property string $updated_at
 * @property string $updated_by
 */
class TblShiftTimeHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_shift_time_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['history_created_at', 'start_time', 'end_time', 'wef_date', 'created_at', 'updated_at', 'operation_type', 'dcs_code', 'created_by', 'updated_by', 'shift_time_code', 'shift_code', 'allow_after_collection', 'is_active'], 'safe'],
//            [['operation_type', 'dcs_code', 'created_by', 'updated_by'], 'string'],
//            [['shift_time_code', 'shift_code', 'allow_after_collection', 'is_active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'shift_time_code' => Yii::t('app', 'Shift Time Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'shift_code' => Yii::t('app', 'Shift Code'),
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'wef_date' => Yii::t('app', 'Wef Date'),
            'allow_after_collection' => Yii::t('app', 'Allow After Collection'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'is_active' => Yii::t('app', 'Is Active'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
}
