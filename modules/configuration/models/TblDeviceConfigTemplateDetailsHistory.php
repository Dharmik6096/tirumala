<?php

namespace app\modules\configuration\models;

use Yii;

/**
 * This is the model class for table "tbl_device_config_template_details_history".
 *
 * @property integer $id
 * @property integer $device_config_template_details_code
 * @property integer $device_temp_code
 * @property integer $device_config_code
 * @property string $device_config_name
 * @property string $device_config_key
 * @property integer $device_config_txn_code
 * @property string $config_type_code
 * @property string $config_type_value
 * @property string $type_code
 * @property string $type_value
 * @property string $union_code
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property integer $originating_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblDeviceConfigTemplateDetailsHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_device_config_template_details_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_config_template_details_code', 'device_temp_code', 'device_config_code', 'device_config_txn_code', 'originating_type'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['device_config_name'], 'safe'],
            [['device_config_key'], 'string', 'max' => 50],
            [['config_type_code', 'config_type_value'], 'string', 'max' => 500],
            [['union_code'], 'string', 'max' => 3],
            [['created_by', 'updated_by', 'history_created_by'], 'string', 'max' => 14],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'string', 'max' => 255],
            [['operation_type'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'device_config_template_details_code' => Yii::t('app', 'Device Config Template Details Code'),
            'device_temp_code' => Yii::t('app', 'Device Temp Code'),
            'device_config_code' => Yii::t('app', 'Device Config Code'),
            'device_config_name' => Yii::t('app', 'Device Config Name'),
            'device_config_key' => Yii::t('app', 'Device Config Key'),
            'device_config_txn_code' => Yii::t('app', 'Device Config Txn Code'),
            'config_type_code' => Yii::t('app', 'Config Type Code'),
            'config_type_value' => Yii::t('app', 'Config Type Value'),
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
            'operation_type' => Yii::t('app', 'Operation Type'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
        ];
    }
}
