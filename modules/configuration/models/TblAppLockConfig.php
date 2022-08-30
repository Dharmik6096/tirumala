<?php

namespace app\modules\configuration\models;

use Yii;

/**
 * This is the model class for table "tbl_app_lock_config".
 *
 * @property integer $config_code
 * @property string $config_name
 * @property string $config_key
 * @property string $config_for
 */
class TblAppLockConfig extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_app_lock_config';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['config_key', 'config_for', 'config_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'config_code' => Yii::t('app', 'Config Code'),
            'config_name' => Yii::t('app', 'Config Name'),
            'config_key' => Yii::t('app', 'Config Key'),
            'config_for' => Yii::t('app', 'Config For'),
        ];
    }

    public function getConfigData() {
        $data = $this->find()
                ->all();
        return $data;
    }

}
