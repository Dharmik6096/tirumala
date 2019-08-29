<?php

namespace app\modules\configuration\models;

use Yii;

/**
 * This is the model class for table "tbl_config_result".
 *
 * @property integer $config_result_code
 * @property string $config_result_key
 * @property string $config_result
 * @property integer $config_code
 * @property integer $is_active
 */
class TblConfigResult extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_config_result';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_result_key', 'config_result'], 'string'],
            [['config_code', 'is_active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'config_result_code' => Yii::t('app', 'Config Result Code'),
            'config_result_key' => Yii::t('app', 'Config Result Key'),
            'config_result' => Yii::t('app', 'Config Result'),
            'config_code' => Yii::t('app', 'Config Code'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }
}
