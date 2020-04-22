<?php

namespace app\modules\usermanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_eipl_app_widget".
 *
 * @property integer $widget_id
 * @property string $widget_name
 * @property string $widget_icon
 * @property string $widget_api
 * @property string $redirect_path
 * @property string $widget_type
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $widget_for
 */
class TblEiplAppWidget extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_eipl_app_widget';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['widget_name', 'widget_icon', 'widget_api', 'redirect_path', 'widget_type', 'created_by', 'updated_by'], 'string'],
            [['is_active', 'widget_for'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'widget_id' => Yii::t('app', 'Widget ID'),
            'widget_name' => Yii::t('app', 'Widget Name'),
            'widget_icon' => Yii::t('app', 'Widget Icon'),
            'widget_api' => Yii::t('app', 'Widget Api'),
            'redirect_path' => Yii::t('app', 'Redirect Path'),
            'widget_type' => Yii::t('app', 'Widget Type'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'widget_for' => Yii::t('app', 'Widget For'),
        ];
    }
}
