<?php

namespace app\modules\organisation\models;

use Yii;

/**
 * This is the model class for table "tbl_vehicle_type".
 *
 * @property integer $vehicle_id
 * @property boolean $is_active
 * @property string $vehicle_type
 */
class TblVehicleType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_vehicle_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active'], 'boolean'],
            [['vehicle_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vehicle_id' => Yii::t('app', 'Vehicle ID'),
            'is_active' => Yii::t('app', 'Is Active'),
            'vehicle_type' => Yii::t('app', 'Vehicle Type'),
        ];
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblRoutes()
    {
        return $this->hasMany(TblRoutes::className(), ['vehicle_type_code' => 'vehicle_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblRoutesHistories()
    {
        return $this->hasMany(TblRoutesHistory::className(), ['vehicle_type_code' => 'vehicle_id']);
    }
    
    /**
     * @inheritdoc
     * @return TblVehicleTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblVehicleTypeQuery(get_called_class());
    }
}
