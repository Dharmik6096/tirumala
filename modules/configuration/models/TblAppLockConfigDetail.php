<?php

namespace app\modules\configuration\models;

use Yii;

/**
 * This is the model class for table "tbl_app_lock_config_detail".
 *
 * @property integer $config_detail_code
 * @property string $config_detail_key
 * @property string $config_detail
 * @property integer $config_code
 * @property integer $is_active
 */
class TblAppLockConfigDetail extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_app_lock_config_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['config_detail_key', 'config_detail', 'config_code', 'is_active'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'config_detail_code' => Yii::t('app', 'Config Detail Code'),
            'config_detail_key' => Yii::t('app', 'Config Detail Key'),
            'config_detail' => Yii::t('app', 'Config Detail'),
            'config_code' => Yii::t('app', 'Config Code'),
            'is_active' => Yii::t('app', 'Is Active'),
        ];
    }

}
