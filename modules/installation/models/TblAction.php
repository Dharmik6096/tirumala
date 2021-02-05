<?php

namespace app\modules\installation\models;

use Yii;

/**
 * This is the model class for table "tbl_action".
 *
 * @property integer $action_code
 * @property string $action_name
 * @property integer $menu_level
 * @property string $description
 * @property integer $parent_code
 * @property integer $action_type
 * @property integer $action_for
 */
class TblAction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_action';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_level', 'parent_code', 'action_type', 'action_for'], 'integer'],
            [['action_name', 'description'], 'string', 'max' => 255],
            [['action_name','menu_level','parent_code','description'],'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'action_code' => Yii::t('app', 'Action Code'),
            'action_name' => Yii::t('app', 'Action Name'),
            'menu_level' => Yii::t('app', 'Menu Level'),
            'description' => Yii::t('app', 'Description'),
            'parent_code' => Yii::t('app', 'Parent Code'),
            'action_type' => Yii::t('app', 'Action Type'),
            'action_for' => Yii::t('app', 'Action For'),
        ];
    }
}
