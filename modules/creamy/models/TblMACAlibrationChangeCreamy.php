<?php

namespace app\modules\creamy\models;

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
 * @property integer $data_post_status
 */
class TblMACAlibrationChangeCreamy extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    
     public static function getDb() {
        return Yii::$app->get('db_creamy'); // second database
    }
    
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
            [['data_post_status'], 'integer'],
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
            'data_post_status' => 'Data Post Status',
        ];
    }
}
