<?php

namespace app\modules\changelog\models;

use Yii;

/**
 * This is the model class for table "tbl_change_log_history".
 *
 * @property integer $id
 * @property integer $log_code
 * @property string $description
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblChangeLogHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_change_log_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['log_code', 'is_active'], 'integer'],
            [['description', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at','history_created_at','operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'log_code' => Yii::t('app', 'Log Code'),
            'description' => Yii::t('app', 'Description'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }
}
