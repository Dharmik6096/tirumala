<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_MA_CAlibration_Change".
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
class TblMACAlibrationChange extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_MA_CAlibration_Change';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['BMCCode', 'PPCode', 'dtdate', 'shift', 'MilkType'], 'required'],
            [['BMCCode', 'PPCode', 'shift', 'MilkType', 'updatedby'], 'string'],
            [['dtdate', 'updateddate'], 'safe'],
            [['CalibrationFat', 'CalibrationSnf', 'CalibrationWater'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'BMCCode' => 'Bmccode',
            'PPCode' => 'Ppcode',
            'dtdate' => 'Dtdate',
            'shift' => 'Shift',
            'CalibrationFat' => 'Calibration Fat',
            'CalibrationSnf' => 'Calibration Snf',
            'CalibrationWater' => 'Calibration Water',
            'MilkType' => 'Milk Type',
            'updatedby' => 'Updatedby',
            'updateddate' => 'Updateddate',
        ];
    }
}
