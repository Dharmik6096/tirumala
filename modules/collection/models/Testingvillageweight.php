<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblDcs;
use app\modules\globalmaster\models\TblAnimalType;

/**
 * This is the model class for table "testingvillageweight".
 *
 * @property string $mccid
 * @property string $routeid
 * @property string $vlccid
 * @property string $producerflag
 * @property integer $sampleno
 * @property string $dtdate
 * @property string $shift
 * @property string $milktype
 * @property string $milkqtype
 * @property string $qtymode
 * @property string $qty
 * @property double $cans
 * @property string $FaultFlag
 * @property integer $rejected_can
 * @property string $quantity_reject_total
 * @property string $createddate
 * @property string $createdby
 * @property string $modifiedby
 * @property string $modifieddate
 */
class Testingvillageweight extends \app\models\ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'testingvillageweight';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mccid', 'sampleno', 'dtdate', 'shift'], 'required'],
            [['mccid', 'routeid', 'vlccid', 'producerflag', 'shift', 'milktype', 'milkqtype', 'qtymode', 'FaultFlag', 'createdby', 'modifiedby'], 'string'],
            [['sampleno', 'rejected_can'], 'integer'],
            [['dtdate', 'createddate', 'modifieddate'], 'safe'],
            [['qty', 'cans', 'quantity_reject_total'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mccid' => Yii::t('app', 'Mccid'),
            'routeid' => Yii::t('app', 'Routeid'),
            'vlccid' => Yii::t('app', 'TMCC'),
            'producerflag' => Yii::t('app', 'Producerflag'),
            'sampleno' => Yii::t('app', 'Sample No'),
            'dtdate' => Yii::t('app', 'Date'),
            'shift' => Yii::t('app', 'Shift'),
            'milktype' => Yii::t('app', 'Milk Type'),
            'milkqtype' => Yii::t('app', 'Milkqtype'),
            'qtymode' => Yii::t('app', 'Qtymode'),
            'qty' => Yii::t('app', 'Qty'),
            'cans' => Yii::t('app', 'Cans'),
            'FaultFlag' => Yii::t('app', 'Fault Flag'),
            'rejected_can' => Yii::t('app', 'Rejected Can'),
            'quantity_reject_total' => Yii::t('app', 'Quantity Reject Total'),
            'createddate' => Yii::t('app', 'Createddate'),
            'createdby' => Yii::t('app', 'Createdby'),
            'modifiedby' => Yii::t('app', 'Modifiedby'),
            'modifieddate' => Yii::t('app', 'Modifieddate'),
        ];
    }
    
    
    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift']);
    }
    
    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'vlccid']);
    }
    
    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milktype']);
    }
}
