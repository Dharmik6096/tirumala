<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_dcs_milk_type_history".
 *
 * @property integer $id
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $dcs_code
 * @property integer $milk_type_code
 * @property integer $is_active
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 */
class TblDcsMilkTypeHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dcs_milk_type_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at','created_by', 'updated_by', 'operation_type', 'history_created_at', 'updated_at','is_active'], 'safe'],
            [['milk_type_code','operation_type','dcs_code'], 'safe'],
//            [['history_created_at', 'created_at', 'updated_at', 'is_active'], 'safe'],
//            [['milk_type_code'], 'integer'],
//            [['operation_type'], 'string', 'max' => 10],
//            [['dcs_code'], 'string', 'max' => 9],
//            [['created_by', 'updated_by'], 'string', 'max' => 14],
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
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'milk_type_code' => Yii::t('app', 'Milk Type Code'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblDcsMilkTypeHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblDcsMilkTypeHistoryQuery(get_called_class());
    }
}
