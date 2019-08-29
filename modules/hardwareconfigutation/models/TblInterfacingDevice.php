<?php

namespace app\modules\hardwareconfigutation\models;

use Yii;
use app\modules\organisation\models\TblUnions;

/**
 * This is the model class for table "tbl_interfacing_device".
 *
 * @property string $interfacing_device_code
 * @property string $baud_rate
 * @property integer $bit_rate
 * @property string $created_at
 * @property string $created_by
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $device_name
 * @property integer $device_type
 * @property string $discard_char
 * @property string $end_char
 * @property string $flg_sentbox_entry
 * @property integer $incoming_data_type
 * @property boolean $is_active
 * @property boolean $is_delete
 * @property boolean $is_snf
 * @property integer $length
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
 * @property TblHardwareConfig[] $tblHardwareConfigs
 * @property TblHardwareConfig[] $tblHardwareConfigs0
 * @property TblHardwareConfig[] $tblHardwareConfigs1
 * @property TblHardwareConfig[] $tblHardwareConfigs2
 * @property TblHardwareConfig[] $tblHardwareConfigs3
 * @property TblHardwareConfigHistory[] $tblHardwareConfigHistories
 * @property TblHardwareConfigHistory[] $tblHardwareConfigHistories0
 * @property TblHardwareConfigHistory[] $tblHardwareConfigHistories1
 * @property TblHardwareConfigHistory[] $tblHardwareConfigHistories2
 * @property TblHardwareConfigHistory[] $tblHardwareConfigHistories3
 * @property TblDeviceManufacturer $deviceManufacturer
 * @property TblUnions $unionCode
 */
class TblInterfacingDevice extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_interfacing_device';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['interfacing_device_code', 'device_name'], 'required'],
            [['bit_rate', 'device_type', 'incoming_data_type', 'length', 'parity', 'reading_type', 'stop_bit', 'device_manufacturer_id'], 'integer'],
            [['created_at', 'deleted_at', 'sync_timestamp', 'updated_at'], 'safe'],
            [['is_active', 'is_delete', 'is_snf'], 'boolean'],
            [['interfacing_device_code'], 'string', 'max' => 9],
            [['bit_rate'], 'validateBitRate'],
            [['baud_rate'], 'validateBaudRate'],
            [['device_type'], 'validateDeviceType'],
            [['is_snf'], 'validateIsSnf'],
            [['incoming_data_type'], 'validateIncomingType'],
            [['parity'], 'validateParity'],
            [['reading_type'], 'validateReadingType'],
            [['baud_rate', 'tare'], 'string', 'max' => 10],
            [['created_by', 'deleted_by', 'updated_by'], 'string', 'max' => 14],
            [['device_name'], 'string', 'max' => 255],
            [['discard_char', 'split_char'], 'string', 'max' => 15],
            [['end_char', 'start_char'], 'string', 'max' => 2],
            [['flg_sentbox_entry', 'sync_status'], 'string', 'max' => 1],
            [['reg_expression'], 'string', 'max' => 100],
            [['union_code'], 'string', 'max' => 3],
            [['device_manufacturer_id'], 'exist', 'skipOnError' => true, 'targetClass' => TblDeviceManufacturer::className(), 'targetAttribute' => ['device_manufacturer_id' => 'id']],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
            [['x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    public function validateReadingType($attribute, $params) {
        if (!in_array($this->$attribute, array_flip($this->getReadingType()))) {
            $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' must be from specified values.'));
            return false;
        }
    }

    public function validateBitRate($attribute, $params) {
        if (!in_array($this->$attribute, $this->getBitRate())) {
            $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' must be from "0" To "9".'));
            return false;
        }
    }

    public function validateBaudRate($attribute, $params) {
        if (!in_array($this->$attribute, $this->getBaurdRate())) {
            $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' must be from specified values.'));
            return false;
        }
    }

    public function validateDeviceType($attribute, $params) {
        if (!in_array($this->$attribute, array_flip($this->getDeviceType()))) {
            $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' must be from specified values.'));
            return false;
        }
    }

    public function validateIncomingType($attribute, $params) {
        if (!in_array($this->$attribute, array_flip($this->getIncomingType()))) {
            $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' must be from specified values.'));
            return false;
        }
    }

    public function validateIsSnf($attribute, $params) {
        if (!in_array($this->$attribute, ['1', '0'])) {
            $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' must be either "0" or "1".'));
            return false;
        }
    }

    public function validateParity($attribute, $params) {
        if (!in_array($this->$attribute, array_flip($this->getParity()))) {
            $this->addError($attribute, Yii::t('app/validation', $this->getAttributeLabel($attribute) . ' must be from specified values.'));
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'interfacing_device_code' => Yii::t('app', 'Device Code'),
            'baud_rate' => Yii::t('app', 'Baud Rate'),
            'bit_rate' => Yii::t('app', 'Bit Rate'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
            'device_name' => Yii::t('app', 'Device Name'),
            'device_type' => Yii::t('app', 'Device Type'),
            'discard_char' => Yii::t('app', 'Characters of Discarder'),
            'end_char' => Yii::t('app', 'End Char'),
            'flg_sentbox_entry' => Yii::t('app', 'Flg Sentbox Entry'),
            'incoming_data_type' => Yii::t('app', 'Incoming Data Type'),
            'is_active' => Yii::t('app', 'Is Active'),
            'is_delete' => Yii::t('app', 'Is Delete'),
            'is_snf' => Yii::t('app', 'Is Snf'),
            'length' => Yii::t('app', 'Length Of String'),
            'parity' => Yii::t('app', 'Parity'),
            'reading_type' => Yii::t('app', 'Reading Type'),
            'reg_expression' => Yii::t('app', 'Regular Expression'),
            'split_char' => Yii::t('app', 'Split Character'),
            'start_char' => Yii::t('app', 'Start Char'),
            'stop_bit' => Yii::t('app', 'Stop Bit'),
            'sync_status' => Yii::t('app', 'Sync Status'),
            'sync_timestamp' => Yii::t('app', 'Sync Timestamp'),
            'tare' => Yii::t('app', 'Tare'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'device_manufacturer_id' => Yii::t('app', 'Device Manufacturer'),
            'union_code' => Yii::t('app', 'Union Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHardwareConfigs() {
        return $this->hasMany(TblHardwareConfig::className(), ['card_reader_code' => 'interfacing_device_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHardwareConfigs0() {
        return $this->hasMany(TblHardwareConfig::className(), ['ews_code' => 'interfacing_device_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHardwareConfigs1() {
        return $this->hasMany(TblHardwareConfig::className(), ['external_display_code' => 'interfacing_device_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHardwareConfigs2() {
        return $this->hasMany(TblHardwareConfig::className(), ['milk_test1_code' => 'interfacing_device_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHardwareConfigs3() {
        return $this->hasMany(TblHardwareConfig::className(), ['milk_test2_code' => 'interfacing_device_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHardwareConfigHistories() {
        return $this->hasMany(TblHardwareConfigHistory::className(), ['card_reader_code' => 'interfacing_device_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHardwareConfigHistories0() {
        return $this->hasMany(TblHardwareConfigHistory::className(), ['ews_code' => 'interfacing_device_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHardwareConfigHistories1() {
        return $this->hasMany(TblHardwareConfigHistory::className(), ['external_display_code' => 'interfacing_device_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHardwareConfigHistories2() {
        return $this->hasMany(TblHardwareConfigHistory::className(), ['milk_test1_code' => 'interfacing_device_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblHardwareConfigHistories3() {
        return $this->hasMany(TblHardwareConfigHistory::className(), ['milk_test2_code' => 'interfacing_device_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeviceManufacturer() {
        return $this->hasOne(TblDeviceManufacturer::className(), ['id' => 'device_manufacturer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @inheritdoc
     * @return TblInterfacingDeviceQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblInterfacingDeviceQuery(get_called_class());
    }

    public function getBaurdRate() {

        return ['0' => '0', '600' => '600', '1200' => '1200', '2400' => '2400', '4800' => '4800',
            '9600' => '9600', '14400' => '14400', '19200' => '19200', '28800' => '28800', '38400' => '38400',
            '56000' => '56000', '57600' => '57600', '115200' => '115200', '128000' => '128000', '256000' => '256000'];
    }

    public function getBitRate() {
        $array = [];
        for ($i = 0; $i < 10; $i++) {
            $array[] = $i;
        }
        return $array;
    }

    public function getParity() {

        return [0 => 'None', 1 => 'Odd', 2 => 'Even', 3 => 'Mark', 4 => 'Space'];
    }

    public function getReadingType() {
        return [0 => "Read Line", 1 => 'Read Existing', 2 => 'Read Bytes'];
    }

    public function getIncomingType() {
        return [0 => "Plain Text", 1 => 'asci', 2 => 'bcd', 3 => 'hex'];
    }

    public function getCode() {
        $orgCode = (Yii::$app->session->get('organizations_type') == 'UNION') ? Yii::$app->session->get('organizations_code') : '000';

        $len = strlen($orgCode);
        $val = (new \yii\db\Query)
                ->select("MAX(CAST(trim(SUBSTRING(`interfacing_device_code` FROM " . $len . " +1)) AS UNSIGNED)) as interfacing_device_code")
                ->from('tbl_interfacing_device')
                ->where('(CAST(trim(SUBSTRING(interfacing_device_code, 1,' . $len . ')) AS UNSIGNED))="' . trim($orgCode) . '"')
                ->one();
        $code1 = (int) $val['interfacing_device_code'] + 1;

        $value = $orgCode . $code1;

        return $value;
    }

    public function getDeviceType() {
        return [0 => "EWS", 1 => 'Milk Testing Equipment', 2 => 'External Display', 3 => 'Card Reader'];
    }

}
