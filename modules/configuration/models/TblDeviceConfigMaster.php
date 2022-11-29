<?php

namespace app\modules\configuration\models;

use Yii;

/**
 * This is the model class for table "tbl_device_config_master".
 *
 * @property integer $device_config_code
 * @property string $device_config_name
 * @property string $device_config_key
 * @property string $created_at
 * @property string $created_by
 */
class TblDeviceConfigMaster extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_device_config_master';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['created_at', 'created_by', 'device_config_name', 'device_config_key'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'device_config_code' => Yii::t('app', 'Device Config Code'),
            'device_config_name' => Yii::t('app', 'Device Config Name'),
            'device_config_key' => Yii::t('app', 'Device Config Key'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    public function getConfigData() {
        $data = $this->find()
                ->all();
        return $data;
    }

}
