<?php

namespace app\modules\collection\models;

use Yii;

/**
 * This is the model class for table "tbl_MA_Cleaning".
 *
 * @property integer $id
 * @property string $BMCCode
 * @property string $PPCode
 * @property string $dtdate
 * @property string $shift
 * @property string $cleaningdatetime
 * @property integer $CleaningCycles
 * @property integer $Measuring
 * @property integer $counter
 * @property string $updatedby
 * @property string $updateddate
 */
class TblMACleaning extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_MA_Cleaning';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['BMCCode', 'PPCode', 'dtdate', 'shift', 'cleaningdatetime', 'CleaningCycles', 'Measuring', 'counter', 'updatedby', 'updateddate'], 'required'],
            [['BMCCode', 'PPCode', 'shift', 'updatedby'], 'string'],
            [['dtdate', 'cleaningdatetime', 'updateddate','BMCCode', 'PPCode','CleaningCycles', 'Measuring', 'counter', 'updatedby', 'updateddate'], 'safe'],
            [['CleaningCycles', 'Measuring', 'counter'], 'integer'],
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
            'cleaningdatetime' => 'Cleaningdatetime',
            'CleaningCycles' => 'Cleaning Cycles',
            'Measuring' => 'Measuring',
            'counter' => 'Counter',
            'updatedby' => 'Updatedby',
            'updateddate' => 'Updateddate',
        ];
    }
}
