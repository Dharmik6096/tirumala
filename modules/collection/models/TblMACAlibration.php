<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
/**
 * This is the model class for table "tbl_MA_CAlibration".
 *
 * @property integer $id
 * @property string $BMCCode
 * @property string $PPCode
 * @property string $dtdate
 * @property string $shift
 * @property string $CalibrationFat
 * @property string $CalibrationSnf
 * @property string $CalibrationWater
 * @property string $MilkType
 * @property string $updatedby
 * @property string $updateddate
 */
class TblMACAlibration extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_MA_CAlibration';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//            [['BMCCode', 'PPCode', 'dtdate', 'shift', 'MilkType'], 'required'],
            [['BMCCode', 'PPCode', 'shift', 'MilkType', 'updatedby'], 'string'],
            [['dtdate', 'updateddate', 'BMCCode', 'PPCode', 'shift', 'MilkType', 'ref_id'], 'safe'],
            [['CalibrationFat', 'CalibrationSnf', 'CalibrationWater'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'BMCCode' => Yii::t('app', 'BMC'),
            'PPCode' => Yii::t('app', 'DCS'),
            'dtdate' => Yii::t('app', 'Date'),
            'shift' => Yii::t('app', 'Shift'),
            'CalibrationFat' => Yii::t('app', 'FAT'),
            'CalibrationSnf' => Yii::t('app', 'SNF'),
            'CalibrationWater' => Yii::t('app', 'Calibration Water'),
            'MilkType' => Yii::t('app', 'Milk Type'),
            'updatedby' => Yii::t('app', 'Updatedby'),
            'updateddate' => Yii::t('app', 'Updateddate'),
        ];
    }

    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'MilkType']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'PPCode']);
    }
     public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'BMCCode']);
    }

}
