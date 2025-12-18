<?php

namespace app\modules\usermanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_eipl_app_widget_mapping_history".
 *
 * @property integer $id
 * @property integer $mapping_id
 * @property integer $widget_id
 * @property string $login_type
 * @property string $department
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $history_created_at
 * @property string $operation_type
 * @property string $history_created_by
 */
class TblEiplAppWidgetMappingHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_eipl_app_widget_mapping_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mapping_id', 'widget_id', 'union_code'], 'safe'],
            [['login_type', 'department', 'created_by', 'updated_by', 'operation_type', 'history_created_by'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at', 'app_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'mapping_id' => Yii::t('app', 'Mapping ID'),
            'widget_id' => Yii::t('app', 'Widget ID'),
            'login_type' => Yii::t('app', 'Login Type'),
            'department' => Yii::t('app', 'Department'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'app_type' => Yii::t('app', 'App Type'),
        ];
    }
}
