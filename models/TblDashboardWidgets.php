<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_dashboard_widgets".
 *
 * @property integer $dashboard_widget_id
 * @property string $widget_id
 * @property string $widget_type
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string widget_label
 */
class TblDashboardWidgets extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dashboard_widgets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['widget_id', 'widget_type', 'is_active'], 'required'],
            [['is_active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['widget_id','widget_label'], 'string', 'max' => 255],
            [['widget_type'], 'string', 'max' => 10],
            [['created_by', 'updated_by'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dashboard_widget_id' => 'Dashboard Widget ID',
            'widget_id' => 'Widget ID',
            'widget_type' => 'Widget Type',
            'is_active' => 'Is Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'widget_label' => 'Label',
        ];
    }

    public function getDashboardWidgets(){
        return $this->find()->all();
    }

    public function getWidgetLabel($id,$type='') {
        $data = $this->find()->where(['widget_id' => $id, 'widget_type' => $type])->one();
        return $data->widget_label;
    }
}
