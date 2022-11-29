<?php

namespace app\modules\configuration\models;

use Yii;
use app\modules\configuration\models\TblDeviceConfigMasterTxn;
use app\modules\configuration\models\TblDeviceConfigMaster;

/**
 * This is the model class for table "tbl_device_config_template_details".
 *
 * @property integer $device_config_template_details_code
 * @property integer $device_temp_code
 * @property integer $device_config_code
 * @property string $device_config_name
 * @property string $device_config_key
 * @property integer $device_config_txn_code
 * @property string $type_code
 * @property string $type_value
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_byO
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 */
class TblDeviceConfigTemplateDetails extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_device_config_template_details';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['device_temp_code', 'device_config_code', 'device_config_txn_code', 'originating_type'], 'safe'],
            [['created_at', 'updated_at', 'config_type_code', 'config_type_value'], 'safe'],
            [['device_config_name'], 'safe'],
            [['device_config_key'], 'safe'],
            [['union_code'], 'safe'],
            [['created_by', 'updated_by'], 'safe'],
            [['originating_org_code', 'originating_org_type'], 'safe'],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
//            [['config_type_code'], function ($attribute, $params) {
//                    if (!empty($this->configResultCode) && $this->configResultCode->config_type_code == 'time') {
//                        Yii::$app->general->validateTime($this, $attribute, $params);
//                    }
//                }, 'skipOnEmpty' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'device_config_template_details_code' => Yii::t('app', 'Device Config Template Details Code'),
            'device_temp_code' => Yii::t('app', 'Device Temp Code'),
            'device_config_code' => Yii::t('app', 'Device Config Code'),
            'device_config_name' => Yii::t('app', 'Device Config Name'),
            'device_config_key' => Yii::t('app', 'Device Config Key'),
            'device_config_txn_code' => Yii::t('app', 'Device Config Txn Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'x_col1' => Yii::t('app', 'X Col1'),
            'x_col2' => Yii::t('app', 'X Col2'),
            'x_col3' => Yii::t('app', 'X Col3'),
            'x_col4' => Yii::t('app', 'X Col4'),
            'x_col5' => Yii::t('app', 'X Col5'),
        ];
    }

    public function getType($code) {
        $model = new TblDeviceConfigMasterTxn();
        $data = $model->find()->where(['device_config_code' => $code])->all();
        return $data;
    }

    public function getConfigCode() {
        return $this->hasOne(TblDeviceConfigMaster::className(), ['device_config_code' => 'device_config_code']);
    }

    public function getConfigResultCode() {
        return $this->hasOne(TblDeviceConfigMasterTxn::className(), ['device_config_code' => 'device_config_code', 'config_type_code' => 'config_type_code']);
    }

    public function getConfigResult() {
        return $this->hasOne(TblDeviceConfigMasterTxn::className(), ['device_config_code' => 'device_config_code']);
    }

    public function getExistConfig() {
        return $this->find()->where(['device_config_code' => $this->device_config_code, 'device_temp_code' => $this->device_temp_code])->one();
    }

}
