<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\organisation\models\TblDcs;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\dcsoperation\models\TblShift;

/**
 * This is the model class for table "tbl_DPUShiftEndSummary".
 *
 * @property integer $Id
 * @property string $BMCCode
 * @property string $VillageCode
 * @property string $dtdate
 * @property string $shift
 * @property integer $samplecount
 * @property string $Qty
 * @property string $fat
 * @property string $snf
 * @property string $amount
 * @property string $updatedby
 * @property string $updateddate
 */
class TblDpuShiftEndSummary extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_DPUShiftEndSummary';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['BMCCode', 'VillageCode', 'dtdate', 'shift', 'samplecount', 'Qty', 'fat', 'snf', 'amount', 'updatedby', 'updateddate'], 'safe'],
            [['BMCCode', 'VillageCode', 'shift', 'updatedby'], 'string'],
            [['dtdate', 'updateddate', 'ref_id'], 'safe'],
            [['samplecount'], 'integer'],
            [['Qty', 'fat', 'snf', 'amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'Id' => Yii::t('app', 'ID'),
            'BMCCode' => Yii::t('app', 'BMC Name'),
            'VillageCode' => Yii::t('app', 'DCS Name'),
            'dtdate' => Yii::t('app', 'Date'),
            'shift' => Yii::t('app', 'Shift'),
            'samplecount' => Yii::t('app', 'Sample Count'),
            'Qty' => Yii::t('app', 'QTY'),
            'fat' => Yii::t('app', 'FAT'),
            'snf' => Yii::t('app', 'SNF'),
            'amount' => Yii::t('app', 'Amount'),
            'updatedby' => Yii::t('app', 'Updatedby'),
            'updateddate' => Yii::t('app', 'Updateddate'),
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'VillageCode']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'BMCCode']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift']);
    }

}
