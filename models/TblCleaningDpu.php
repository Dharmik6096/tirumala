<?php

namespace app\models;

use Yii;
use app\modules\geo\models\TblVillages;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblDcs;
/**
 * This is the model class for table "tblCleaningDpu".
 *
 * @property string $dcs_code
 * @property string $Dtdate
 * @property string $Shift
 * @property string $c1Date
 * @property integer $c1cycle
 * @property integer $c1testing
 * @property string $c2Date
 * @property integer $c2cycle
 * @property integer $c2testing
 * @property string $c3Date
 * @property integer $c3cycle
 * @property integer $c3testing
 * @property string $c4Date
 * @property integer $c4cycle
 * @property integer $c4testing
 * @property string $c5Date
 * @property integer $c5cycle
 * @property integer $c5testing
 * @property string $CycleNo
 * @property integer $Counter
 * @property string $CreatedDate
 * @property string $ModifyDate
 */
class TblCleaningDpu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tblCleaningDpu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dcs_code', 'Shift'/*,'CycleNo'*/], 'string'],
            [['Dtdate', 'c1Date', 'c2Date', 'c3Date', 'c4Date', 'c5Date', 'CreatedDate', 'ModifyDate'], 'safe'],
            [['c1cycle', 'c1testing', 'c2cycle', 'c2testing', 'c3cycle', 'c3testing', 'c4cycle', 'c4testing', 'c5cycle', 'c5testing', 'Counter'], 'integer'],
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
            'Dtdate' => Yii::t('app', 'Date'),
            'Shift' => Yii::t('app', 'Shift'),
            'c1Date' => Yii::t('app', 'C1 Date'),
            'c1cycle' => Yii::t('app', 'C1cycle'),
            'c1testing' => Yii::t('app', 'C1testing'),
            'c2Date' => Yii::t('app', 'C2 Date'),
            'c2cycle' => Yii::t('app', 'C2cycle'),
            'c2testing' => Yii::t('app', 'C2testing'),
            'c3Date' => Yii::t('app', 'C3 Date'),
            'c3cycle' => Yii::t('app', 'C3cycle'),
            'c3testing' => Yii::t('app', 'C3testing'),
            'c4Date' => Yii::t('app', 'C4 Date'),
            'c4cycle' => Yii::t('app', 'C4cycle'),
            'c4testing' => Yii::t('app', 'C4testing'),
            'c5Date' => Yii::t('app', 'C5 Date'),
            'c5cycle' => Yii::t('app', 'C5cycle'),
            'c5testing' => Yii::t('app', 'C5testing'),
//			'CycleNo'=>Yii::t('app', 'CycleNo'),
            'Counter' => Yii::t('app', 'Counter'),
            'CreatedDate' => Yii::t('app', 'Created Date'),
            'ModifyDate' => Yii::t('app', 'Modify Date'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblCleaningDpuQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblCleaningDpuQuery(get_called_class());
    }
    
    public function getShiftCode()
    {
        return $this->hasOne(TblShift::className(), ['id' => 'Shift']);
    }
    
    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }
}
