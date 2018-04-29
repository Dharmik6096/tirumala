<?php

namespace app\modules\globalmaster\models;

use Yii;

/**
 * This is the model class for table "tbl_unit_conversions_history".
 *
 * @property integer $id
 * @property string $conversion_factor
 * @property string $created_at
 * @property resource $created_by
 * @property string $history_created_at
 * @property string $operation_type
 * @property integer $is_active
 * @property integer $unit_conversion_code
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $from_unit
 * @property integer $to_unit
 */
class TblUnitConversionsHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_unit_conversions_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['conversion_factor'], 'number'],
            [['created_by', 'operation_type', 'updated_by', 'is_active', 'unit_conversion_code', 'from_unit', 'to_unit', 'conversion_factor', 'created_at', 'history_created_at', 'updated_at'], 'safe'],
//            [['created_by', 'operation_type', 'updated_by'], 'string'],
//            [['is_active', 'unit_conversion_code', 'from_unit', 'to_unit'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'conversion_factor' => Yii::t('app', 'Conversion Factor'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'is_active' => Yii::t('app', 'Is Active'),
            'unit_conversion_code' => Yii::t('app', 'Unit Conversion Code'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'from_unit' => Yii::t('app', 'From Unit'),
            'to_unit' => Yii::t('app', 'To Unit'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblUnitConversionsHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblUnitConversionsHistoryQuery(get_called_class());
    }
}
