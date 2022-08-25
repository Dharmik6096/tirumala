<?php

namespace app\modules\configuration\models;

use Yii;

/**
 * This is the model class for table "tbl_app_lock_config_result_history".
 *
 * @property integer $id
 * @property integer $config_result_code
 * @property string $device_id
 * @property integer $config_code
 * @property string $config_name
 * @property string $config_key
 * @property integer $config_detail_code
 * @property string $config_detail_key
 * @property string $config_detail
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
 * @property string $config_for
 * @property string $history_created_at
 * @property string $history_created_by
 * @property string $operation_type
 */
class TblAppLockConfigResultHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_app_lock_config_result_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_result_code', 'config_code', 'config_detail_code', 'originating_type'], 'safe'],
            [['created_at', 'updated_at', 'history_created_at'], 'safe'],
            [['device_id', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['config_name'], 'safe'],
            [['config_key', 'config_for'], 'safe'],
            [['config_detail_key', 'config_detail'], 'safe'],
            [['union_code'], 'safe'],
            [['created_by', 'updated_by', 'history_created_by'], 'safe'],
            [['originating_org_code', 'originating_org_type'], 'safe'],
            [['operation_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'config_result_code' => Yii::t('app', 'Config Result Code'),
            'device_id' => Yii::t('app', 'Device ID'),
            'config_code' => Yii::t('app', 'Config Code'),
            'config_name' => Yii::t('app', 'Config Name'),
            'config_key' => Yii::t('app', 'Config Key'),
            'config_detail_code' => Yii::t('app', 'Config Detail Code'),
            'config_detail_key' => Yii::t('app', 'Config Detail Key'),
            'config_detail' => Yii::t('app', 'Config Detail'),
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
            'config_for' => Yii::t('app', 'Config For'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'history_created_by' => Yii::t('app', 'History Created By'),
            'operation_type' => Yii::t('app', 'Operation Type'),
        ];
    }
}
