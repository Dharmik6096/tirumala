<?php

namespace app\modules\configuration\models;

use Yii;

/**
 * This is the model class for table "tbl_device_config_template".
 *
 * @property string $device_temp_code
 * @property string $device_temp_name
 * @property string $created_at
 * @property string $created_by
 */
class TblDeviceConfigTemplate extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_device_config_template';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['device_temp_code', 'device_temp_name', 'created_by', 'created_at', 'union_code'], 'safe'],
            [['device_temp_name'], 'unique'],
            [['device_temp_name'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'device_temp_code' => Yii::t('app', 'Device Temp Code'),
            'device_temp_name' => Yii::t('app', 'Device Template Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

}
