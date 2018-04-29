<?php

namespace app\modules\globalmaster\models;

use Yii;

/**
 * This is the model class for table "tbl_capacity_history".
 *
 * @property integer $id
 * @property integer $capacity_code
 * @property integer $value
 * @property string $unit
 * @property string $created_at
 * @property string $created_by
 * @property string $history_created_at
 * @property integer $is_active
 * @property string $operation_type
 * @property string $updated_at
 * @property string $updated_by
 */
class TblCapacityHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_capacity_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [         
            [['id', 'capacity_code', 'value', 'is_active'], 'safe'],
            [['unit', 'created_by', 'operation_type', 'updated_by'], 'safe'],
            [['created_at', 'history_created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'capacity_code' => Yii::t('app', 'Capacity Code'),
            'value' => Yii::t('app', 'Value'),
            'unit' => Yii::t('app', 'Unit'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblCapacityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblCapacityQuery(get_called_class());
    }
}
