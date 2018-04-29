<?php

namespace app\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\dcsoperation\models\TblShift;

/**
 * This is the model class for table "tbl_dpu_calibration".
 *
 * @property string $dcs_code
 * @property string $date
 * @property string $shift
 * @property string $milk_type_code
 * @property string $fat
 * @property string $snf
 * @property string $water
 * @property string $cycle
 * 
 * @property TblDcs $dcsCode
 * @property TblAnimalType $milkTypeCode
 */
class TblDpuCalibration extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dpu_calibration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dcs_code', 'shift', 'milk_type_code', 'cycle'], 'string'],
            [['date'], 'safe'],
            [['fat', 'snf', 'water'], 'number'],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dcs_code' => Yii::t('app', 'Society Name'),
            'date' => Yii::t('app', 'Date'),
            'shift' => Yii::t('app', 'Shift'),
            'milk_type_code' => Yii::t('app', 'Milk Type'),
            'fat' => Yii::t('app', 'FAT'),
            'snf' => Yii::t('app', 'SNF'),
            'water' => Yii::t('app', 'Water'),
            'cycle' => Yii::t('app', 'Cycle'),
        ];
    }

    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }
     public function getMilkTypeCode()
    {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milk_type_code']);
    }
    
    public function getShiftCode()
    {
        return $this->hasOne(TblShift::className(), ['id' => 'shift']);
    }
    /**
     * @inheritdoc
     * @return TblDpuCalibrationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblDpuCalibrationQuery(get_called_class());
    }
}
