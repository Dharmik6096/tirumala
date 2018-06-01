<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\organisation\models\TblDcsBmc;
/**
 * This is the model class for table "testingvillagequality".
 *
 * @property string $shift
 * @property string $dtdate
 * @property integer $sampleno
 * @property string $mccid
 * @property double $fat
 * @property double $snf
 * @property double $water
 * @property string $qtime
 * @property integer $retestcount
 * @property string $modifieddate
 * @property string $ModifyBy
 * @property string $ModifyDate
 * @property string $CreatedBy
 * @property string $CreatedDate
 */
class Testingvillagequality extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'testingvillagequality';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shift', 'dtdate', 'sampleno', 'mccid'], 'required'],
            [['shift', 'mccid', 'ModifyBy', 'CreatedBy'], 'string'],
            [['dtdate', 'qtime', 'modifieddate', 'ModifyDate', 'CreatedDate'], 'safe'],
            [['sampleno', 'retestcount'], 'integer'],
            [['fat', 'snf', 'water'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'shift' => Yii::t('app', 'Shift'),
            'dtdate' => Yii::t('app', 'Date'),
            'sampleno' => Yii::t('app', 'Sample No'),
            'mccid' => Yii::t('app', 'BMC'),
            'fat' => Yii::t('app', 'FAT'),
            'snf' => Yii::t('app', 'SNF'),
            'water' => Yii::t('app', 'Water'),
            'qtime' => Yii::t('app', 'Qtime'),
            'retestcount' => Yii::t('app', 'Retestcount'),
            'modifieddate' => Yii::t('app', 'Modifieddate'),
            'ModifyBy' => Yii::t('app', 'Modify By'),
            'ModifyDate' => Yii::t('app', 'Modify Date'),
            'CreatedBy' => Yii::t('app', 'Created By'),
            'CreatedDate' => Yii::t('app', 'Created Date'),
        ];
    }
    
    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'mccid']);
    }
}
