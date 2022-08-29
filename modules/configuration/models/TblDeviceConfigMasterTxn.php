<?php

namespace app\modules\configuration\models;

use Yii;

/**
 * This is the model class for table "tbl_device_config_master_txn".
 *
 * @property integer $device_config_txn_code
 * @property integer $device_config_code
 * @property string $config_type_code
 * @property string $config_type_value
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 */
class TblDeviceConfigMasterTxn extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_device_config_master_txn';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['device_config_code', 'config_type_code', 'config_type_value', 'created_at', 'created_by', 'is_active'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'device_config_txn_code' => Yii::t('app', 'Device Config Txn Code'),
            'device_config_code' => Yii::t('app', 'Device Config Code'),
            'config_type_code' => Yii::t('app', 'Config Type Code'),
            'config_type_value' => Yii::t('app', 'Config Type Value'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

}
