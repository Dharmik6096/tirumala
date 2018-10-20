<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;

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
class TblMACleaning extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_MA_Cleaning';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//            [['BMCCode', 'PPCode', 'dtdate', 'shift', 'cleaningdatetime', 'CleaningCycles', 'Measuring', 'counter', 'updatedby', 'updateddate'], 'required'],
            [['BMCCode', 'PPCode', 'shift', 'updatedby'], 'string'],
            [['dtdate', 'cleaningdatetime', 'updateddate', 'BMCCode', 'PPCode', 'CleaningCycles', 'Measuring', 'counter', 'updatedby', 'updateddate', 'ref_id'], 'safe'],
            [['CleaningCycles', 'Measuring', 'counter'], 'integer'],
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
            'cleaningdatetime' => Yii::t('app', 'Cleaningdatetime'),
            'CleaningCycles' => Yii::t('app', 'Cleaning Cycles'),
            'Measuring' => Yii::t('app', 'Measuring'),
            'counter' => Yii::t('app', 'Counter'),
            'updatedby' => Yii::t('app', 'Updatedby'),
            'updateddate' => Yii::t('app', 'Updateddate'),
        ];
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
