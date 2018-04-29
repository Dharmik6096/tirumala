<?php

namespace app\modules\hardwareconfigutation\models;

use Yii;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_interfacing_device_history".
 *
 * @property integer $id
 * @property string $baud_rate
 * @property integer $bit_rate
 * @property string $created_at
 * @property string $created_by
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $device_code
 * @property string $device_name
 * @property integer $device_type
 * @property string $discard_char
 * @property string $end_char
 * @property string $flg_sentbox_entry
 * @property string $history_created_at
 * @property integer $incoming_data_type
 * @property boolean $is_active
 * @property boolean $is_delete
 * @property boolean $is_snf
 * @property integer $length
 * @property string $operation_type
 * @property integer $parity
 * @property integer $reading_type
 * @property string $reg_expression
 * @property string $split_char
 * @property string $start_char
 * @property integer $stop_bit
 * @property string $sync_status
 * @property string $sync_timestamp
 * @property string $tare
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $device_manufacturer_id
 * @property string $union_code
 *
 * @property TblDeviceManufacturer $deviceManufacturer
 * @property TblUnions $unionCode
 */
class TblInterfacingDeviceHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_interfacing_device_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['bit_rate', 'device_type', 'incoming_data_type', 'length', 'parity', 'reading_type', 'stop_bit', 'device_manufacturer_id'], 'integer'],
            [['flg_sentbox_entry', 'end_char', 'start_char', 'reg_expression', 'device_manufacturer_id', 'union_code',  'flg_sentbox_entry', 'sync_status', 'discard_char', 'split_char', 'device_code', 'device_name', 'is_active', 'is_delete', 'is_snf','baud_rate', 'operation_type', 'tare', 'bit_rate', 'device_type', 'incoming_data_type', 'length', 'parity', 'reading_type', 'stop_bit', 'device_manufacturer_id', 'created_at', 'deleted_at', 'history_created_at', 'sync_timestamp', 'updated_at','created_by', 'deleted_by', 'updated_by'], 'safe'],
//            [['device_name'], 'required'],
//            [['is_active', 'is_delete', 'is_snf'], 'boolean'],
//            [['baud_rate', 'operation_type', 'tare'], 'string', 'max' => 10],
//            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 14],
//            [['device_code'], 'string', 'max' => 9],
//            [['device_name'], 'string', 'max' => 255],
//            [['discard_char', 'split_char'], 'string', 'max' => 15],
//            [['end_char', 'start_char'], 'string', 'max' => 2],
//            [['flg_sentbox_entry', 'sync_status'], 'string', 'max' => 1],
//            [['reg_expression'], 'string', 'max' => 100],
//            [['union_code'], 'string', 'max' => 3],
//            [['device_manufacturer_id'], 'exist', 'skipOnError' => true, 'targetClass' => TblDeviceManufacturer::className(), 'targetAttribute' => ['device_manufacturer_id' => 'id']],
//            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'baud_rate' => Yii::t('app', 'Baud Rate'),
            'bit_rate' => Yii::t('app', 'Bit Rate'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'device_code' => Yii::t('app', 'Device Code'),
            'device_name' => Yii::t('app', 'Device Name'),
            'device_type' => Yii::t('app', 'Device Type'),
            'discard_char' => Yii::t('app', 'Discard Char'),
            'end_char' => Yii::t('app', 'End Char'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'history_created_at' => Yii::t('app', 'History Created At'),
            'incoming_data_type' => Yii::t('app', 'Incoming Data Type'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'is_snf' => Yii::t('app', 'Is Snf'),
            'length' => Yii::t('app', 'Length'),
            'operation_type' => Yii::t('app', 'Operation Type'),
            'parity' => Yii::t('app', 'Parity'),
            'reading_type' => Yii::t('app', 'Reading Type'),
            'reg_expression' => Yii::t('app', 'Reg Expression'),
            'split_char' => Yii::t('app', 'Split Char'),
            'start_char' => Yii::t('app', 'Start Char'),
            'stop_bit' => Yii::t('app', 'Stop Bit'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'tare' => Yii::t('app', 'Tare'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'device_manufacturer_id' => Yii::t('app', 'Device Manufacturer ID'),
            'union_code' => Yii::t('app', 'Union Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeviceManufacturer()
    {
        return $this->hasOne(TblDeviceManufacturer::className(), ['id' => 'device_manufacturer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode()
    {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @inheritdoc
     * @return TblInterfacingDeviceHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblInterfacingDeviceHistoryQuery(get_called_class());
    }
}
