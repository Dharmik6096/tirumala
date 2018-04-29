<?php

namespace app\modules\hardwareconfigutation\models;

use Yii;

/**
 * This is the model class for table "tbl_device_manufacturer".
 *
 * @property integer $id
 * @property boolean $is_active
 * @property string $manufacturer
 *
 * @property TblInterfacingDevice[] $tblInterfacingDevices
 * @property TblInterfacingDeviceHistory[] $tblInterfacingDeviceHistories
 */
class TblDeviceManufacturer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_device_manufacturer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active'], 'boolean'],
            [['manufacturer'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'is_active' => Yii::t('app', 'Is Active'),
            'manufacturer' => Yii::t('app', 'Manufacturer'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblInterfacingDevices()
    {
        return $this->hasMany(TblInterfacingDevice::className(), ['device_manufacturer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblInterfacingDeviceHistories()
    {
        return $this->hasMany(TblInterfacingDeviceHistory::className(), ['device_manufacturer_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return TblDeviceManufacturerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblDeviceManufacturerQuery(get_called_class());
    }
  }
