<?php

namespace app\modules\setting\models;

use Yii;

/**
 * This is the model class for table "tbl_general_config_history".
 *
 * @property integer $id
 * @property integer $config_code
 * @property string $module_name
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $history_created_at
 * @property string $operation_type
 */
class TblGeneralConfigHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_general_config_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_code', 'is_active'], 'integer'],
            [['module_name', 'created_by', 'updated_by', 'deleted_by', 'operation_type'], 'string'],
            [['created_at', 'updated_at', 'deleted_at', 'history_created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'config_code' => Yii::t('app', 'Config Code'),
            'module_name' => Yii::t('app', 'Module Name'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }
}
