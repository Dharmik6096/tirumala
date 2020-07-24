<?php

namespace app\modules\usermanagement\models;

use Yii;

/**
 * This is the model class for table "tbl_amcs_app_menu".
 *
 * @property integer $action_code
 * @property string $action_name
 * @property string $description
 * @property integer $is_active
 * @property integer $sequence_no
 * @property integer $parent_code
 * @property string $parent_name
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class TblAmcsAppMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_amcs_app_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['action_name', 'description', 'parent_name', 'created_by', 'updated_by'], 'string'],
            [['is_active', 'sequence_no', 'parent_code'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
            'description' => Yii::t('app', 'Description'),
            'is_active' => Yii::t('app', 'Is Active'),
            'sequence_no' => Yii::t('app', 'Sequence No'),
            'parent_code' => Yii::t('app', 'Parent Code'),
            'parent_name' => Yii::t('app', 'Parent Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }
}
