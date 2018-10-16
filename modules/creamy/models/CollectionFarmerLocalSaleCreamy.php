<?php

namespace app\modules\creamy\models;

use Yii;

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
 * @property integer $data_post_status
 */
class CollectionFarmerLocalSaleCreamy extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function getDb() {
        return Yii::$app->get('db_creamy'); // second database
    }

    public static function tableName() {
        return 'CollectionFarmerLocalSale';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['farmerid', 'vlccid', 'bmcid', 'sampleno', 'dtdate', 'shift'], 'required'],
            [['farmerid', 'vlccid', 'routeid', 'bmcid', 'shift', 'qtymode', 'qtydecimals', 'qltydecimals', 'milktype', 'milkqtype', 'createdby', 'modifiedby', 'syncdirection', 'usbflag', 'RateType', 'RateRecalType'], 'string'],
            [['sampleno', 'rateid', 'qtyauto', 'qltyauto', 'paymentid', 'farmerstatus', 'data_post_status'], 'integer'],
            [['qty', 'fat', 'snf', 'water', 'clr', 'rtpl', 'amount', 'kgltrconst', 'ltrkgconst', 'StdRate', 'KgFatRate', 'KgSnfRate'], 'number'],
            [['dtdate', 'qtytime', 'qltytime', 'createddate', 'modifieddate', 'lastsynchronized'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'farmerid' => 'Farmerid',
            'vlccid' => 'Vlccid',
            'routeid' => 'Routeid',
            'bmcid' => 'Bmcid',
            'sampleno' => 'Sampleno',
            'qty' => 'Qty',
            'fat' => 'Fat',
            'snf' => 'Snf',
            'water' => 'Water',
            'clr' => 'Clr',
            'rtpl' => 'Rtpl',
            'amount' => 'Amount',
            'rateid' => 'Rateid',
            'dtdate' => 'Dtdate',
            'shift' => 'Shift',
            'qtyauto' => 'Qtyauto',
            'qltyauto' => 'Qltyauto',
            'qtytime' => 'Qtytime',
            'qltytime' => 'Qltytime',
            'kgltrconst' => 'Kgltrconst',
            'ltrkgconst' => 'Ltrkgconst',
            'qtymode' => 'Qtymode',
            'qtydecimals' => 'Qtydecimals',
            'qltydecimals' => 'Qltydecimals',
            'milktype' => 'Milktype',
            'milkqtype' => 'Milkqtype',
            'createddate' => 'Createddate',
            'createdby' => 'Createdby',
            'modifieddate' => 'Modifieddate',
            'modifiedby' => 'Modifiedby',
            'lastsynchronized' => 'Lastsynchronized',
            'syncdirection' => 'Syncdirection',
            'usbflag' => 'Usbflag',
            'paymentid' => 'Paymentid',
            'StdRate' => 'Std Rate',
            'RateType' => 'Rate Type',
            'KgFatRate' => 'Kg Fat Rate',
            'KgSnfRate' => 'Kg Snf Rate',
            'RateRecalType' => 'Rate Recal Type',
            'farmerstatus' => 'Farmerstatus',
            'data_post_status' => 'Data Post Status',
        ];
    }

}
