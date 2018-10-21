<?php

namespace app\modules\collection\models;

use Yii;
use app\modules\globalmaster\models\TblAnimalType;
use app\modules\dcsoperation\models\TblShift;
use app\modules\organisation\models\TblDcsBmc;
use app\modules\dcsoperation\models\TblMember;
use app\modules\organisation\models\TblDcs;
/**
 * This is the model class for table "CollectionFarmerLocalSale".
 *
 * @property string $farmerid
 * @property string $vlccid
 * @property string $routeid
 * @property string $bmcid
 * @property integer $sampleno
 * @property string $qty
 * @property string $fat
 * @property string $snf
 * @property string $water
 * @property string $clr
 * @property string $rtpl
 * @property string $amount
 * @property integer $rateid
 * @property string $dtdate
 * @property string $shift
 * @property integer $qtyauto
 * @property integer $qltyauto
 * @property string $qtytime
 * @property string $qltytime
 * @property string $kgltrconst
 * @property string $ltrkgconst
 * @property string $qtymode
 * @property string $qtydecimals
 * @property string $qltydecimals
 * @property string $milktype
 * @property string $milkqtype
 * @property string $createddate
 * @property string $createdby
 * @property string $modifieddate
 * @property string $modifiedby
 * @property string $lastsynchronized
 * @property string $syncdirection
 * @property string $usbflag
 * @property integer $paymentid
 * @property string $StdRate
 * @property string $RateType
 * @property string $KgFatRate
 * @property string $KgSnfRate
 * @property string $RateRecalType
 * @property integer $farmerstatus
 */
class CollectionFarmerLocalSale extends \app\models\ChildModel {

    public $setdcs;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'CollectionFarmerLocalSale';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//            [['farmerid', 'vlccid', 'bmcid', 'sampleno', 'dtdate', 'shift'], 'required'],
            [['farmerid', 'vlccid', 'routeid', 'bmcid', 'shift', 'qtymode', 'qtydecimals', 'qltydecimals', 'milktype', 'milkqtype', 'createdby', 'modifiedby', 'syncdirection', 'usbflag', 'RateType', 'RateRecalType'], 'string'],
            [['sampleno', 'rateid', 'qtyauto', 'qltyauto', 'paymentid', 'farmerstatus'], 'integer'],
            [['qty', 'fat', 'snf', 'water', 'clr', 'rtpl', 'amount', 'kgltrconst', 'ltrkgconst', 'StdRate', 'KgFatRate', 'KgSnfRate'], 'number'],
            [['dtdate', 'qtytime', 'qltytime', 'createddate', 'modifieddate', 'lastsynchronized', 'farmerid', 'vlccid', 'bmcid', 'sampleno', 'dtdate', 'shift'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'farmerid' => Yii::t('app', 'Member'),
            'vlccid' => Yii::t('app', 'DCS'),
            'routeid' => Yii::t('app', 'Route Id'),
            'bmcid' => Yii::t('app', 'BMC'),
            'sampleno' => Yii::t('app', 'Sample No.'),
            'qty' => Yii::t('app', 'Qty'),
            'fat' => Yii::t('app', 'FAT'),
            'snf' => Yii::t('app', 'SNF'),
            'water' => Yii::t('app', 'Water'),
            'clr' => Yii::t('app', 'Clr'),
            'rtpl' => Yii::t('app', 'Rtpl'),
            'amount' => Yii::t('app', 'Amount'),
            'rateid' => Yii::t('app', 'Rate Id'),
            'dtdate' => Yii::t('app', 'Date'),
            'shift' => Yii::t('app', 'Shift'),
            'qtyauto' => Yii::t('app', 'Qtyauto'),
            'qltyauto' => Yii::t('app', 'Qltyauto'),
            'qtytime' => Yii::t('app', 'Qtytime'),
            'qltytime' => Yii::t('app', 'Qltytime'),
            'kgltrconst' => Yii::t('app', 'Kgltrconst'),
            'ltrkgconst' => Yii::t('app', 'Ltrkgconst'),
            'qtymode' => Yii::t('app', 'Qtymode'),
            'qtydecimals' => Yii::t('app', 'Qtydecimals'),
            'qltydecimals' => Yii::t('app', 'Qltydecimals'),
            'milktype' => Yii::t('app', 'Milk Type'),
            'milkqtype' => Yii::t('app', 'Milkqtype'),
            'createddate' => Yii::t('app', 'Createddate'),
            'createdby' => Yii::t('app', 'Createdby'),
            'modifieddate' => Yii::t('app', 'Modifieddate'),
            'modifiedby' => Yii::t('app', 'Modifiedby'),
            'lastsynchronized' => Yii::t('app', 'Lastsynchronized'),
            'syncdirection' => Yii::t('app', 'Syncdirection'),
            'usbflag' => Yii::t('app', 'Usbflag'),
            'paymentid' => Yii::t('app', 'Paymentid'),
            'StdRate' => Yii::t('app', 'Std Rate'),
            'RateType' => Yii::t('app', 'Rate Type'),
            'KgFatRate' => Yii::t('app', 'Kg Fat Rate'),
            'KgSnfRate' => Yii::t('app', 'Kg Snf Rate'),
            'RateRecalType' => Yii::t('app', 'Rate Recal Type'),
            'farmerstatus' => Yii::t('app', 'Farmerstatus'),
        ];
    }

    public function getMilkTypeCode() {
        return $this->hasOne(TblAnimalType::className(), ['animal_type_code' => 'milktype']);
    }

    public function getShiftCode() {
        return $this->hasOne(TblShift::className(), ['id' => 'shift']);
    }

    public function getBmcCode() {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmcid']);
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'vlccid']);
    }

    public function getMemberCode() {
        return $this->hasOne(TblMember::className(), ['member_code' => 'farmerid']);
    }

}
