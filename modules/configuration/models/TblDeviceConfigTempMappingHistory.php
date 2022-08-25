<?php

namespace app\modules\configuration\models;

use Yii;

/**
 * This is the model class for table "tbl_device_config_temp_mapping_history".
 *
 * @property integer $id
 * @property integer $config_temp_mapping_code
 * @property integer $device_temp_code
 * @property string $applicable_for
 * @property string $applicable_code
 * @property integer $is_active
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $originating_type
 * @property string $originating_org_code
 * @property string $originating_org_type
 * @property string $x_col1
 * @property string $x_col2
 * @property string $x_col3
 * @property string $x_col4
 * @property string $x_col5
 * @property string $operation_type
 * @property string $history_created_at
 * @property string $history_created_by
 */
class TblDeviceConfigTempMappingHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_device_config_temp_mapping_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_temp_mapping_code', 'device_temp_code', 'is_active', 'originating_type'], 'integer'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['applicable_for', 'applicable_code'], 'string', 'max' => 20],
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
            'config_temp_mapping_code' => Yii::t('app', 'Config Temp Mapping Code'),
            'device_temp_code' => Yii::t('app', 'Device Temp Code'),
            'applicable_for' => Yii::t('app', 'Applicable Type'),
            'applicable_code' => Yii::t('app', 'Applicable Code'),
            'is_active' => Yii::t('app', 'Is Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'originating_type' => Yii::t('app', 'Originating Type'),
            'originating_org_code' => Yii::t('app', 'Originating Org Code'),
            'originating_org_type' => Yii::t('app', 'Originating Org Type'),
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
