<?php

namespace app\modules\creamy\models;

use Yii;

/**
 * This is the model class for table "collectionvillage".
 *
 * @property string $vlccid
 * @property string $routeid
 * @property string $bmcid
 * @property string $producerflag
 * @property integer $sampleno
 * @property string $qty
 * @property string $fat
 * @property string $snf
 * @property integer $can
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
 * @property integer $paymentid
 * @property string $StdRate
 * @property string $RateType
 * @property string $KgFatRate
 * @property string $KgSnfRate
 * @property string $DiffType
 * @property string $PenaltyPerLtr
 * @property string $DiffQty
 * @property string $LabPenaltyPerLtr
 * @property string $RateRecalType
 * @property integer $VspDetailCode
 * @property string $AppType
 * @property integer $MsgDelivered
 * @property integer $isRealtimeSync
 * @property integer $data_post_status
 */
class CollectionvillageCreamy extends \app\models\ChildModel {

    public static function getDb() {
        return Yii::$app->get('db_creamy'); // second database
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'collectionvillage';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['vlccid', 'bmcid', 'producerflag', 'sampleno', 'dtdate', 'shift'], 'required'],
            [['vlccid', 'routeid', 'bmcid', 'producerflag', 'shift', 'qtymode', 'qtydecimals', 'qltydecimals', 'milktype', 'milkqtype', 'createdby', 'modifiedby', 'syncdirection', 'RateType', 'DiffType', 'RateRecalType', 'AppType'], 'string'],
            [['sampleno', 'can', 'rateid', 'qtyauto', 'qltyauto', 'paymentid', 'VspDetailCode', 'MsgDelivered', 'isRealtimeSync', 'data_post_status'], 'integer'],
            [['qty', 'fat', 'snf', 'water', 'clr', 'rtpl', 'amount', 'kgltrconst', 'ltrkgconst', 'StdRate', 'KgFatRate', 'KgSnfRate', 'PenaltyPerLtr', 'DiffQty', 'LabPenaltyPerLtr'], 'number'],
            [['dtdate', 'qtytime', 'qltytime', 'createddate', 'modifieddate', 'lastsynchronized'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'vlccid' => Yii::t('app', 'Vlccid'),
            'routeid' => Yii::t('app', 'Routeid'),
            'bmcid' => Yii::t('app', 'Bmcid'),
            'producerflag' => Yii::t('app', 'Producerflag'),
            'sampleno' => Yii::t('app', 'Sampleno'),
            'qty' => Yii::t('app', 'Qty'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'can' => Yii::t('app', 'Can'),
            'water' => Yii::t('app', 'Water'),
            'clr' => Yii::t('app', 'Clr'),
            'rtpl' => Yii::t('app', 'Rtpl'),
            'amount' => Yii::t('app', 'Amount'),
            'rateid' => Yii::t('app', 'Rateid'),
            'dtdate' => Yii::t('app', 'Dtdate'),
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
            'milktype' => Yii::t('app', 'Milktype'),
            'milkqtype' => Yii::t('app', 'Milkqtype'),
            'createddate' => Yii::t('app', 'Createddate'),
            'createdby' => Yii::t('app', 'Createdby'),
            'modifieddate' => Yii::t('app', 'Modifieddate'),
            'modifiedby' => Yii::t('app', 'Modifiedby'),
            'lastsynchronized' => Yii::t('app', 'Lastsynchronized'),
            'syncdirection' => Yii::t('app', 'Syncdirection'),
            'paymentid' => Yii::t('app', 'Paymentid'),
            'StdRate' => Yii::t('app', 'Std Rate'),
            'RateType' => Yii::t('app', 'Rate Type'),
            'KgFatRate' => Yii::t('app', 'Kg Fat Rate'),
            'KgSnfRate' => Yii::t('app', 'Kg Snf Rate'),
            'DiffType' => Yii::t('app', 'Diff Type'),
            'PenaltyPerLtr' => Yii::t('app', 'Penalty Per Ltr'),
            'DiffQty' => Yii::t('app', 'Diff Qty'),
            'LabPenaltyPerLtr' => Yii::t('app', 'Lab Penalty Per Ltr'),
            'RateRecalType' => Yii::t('app', 'Rate Recal Type'),
            'VspDetailCode' => Yii::t('app', 'Vsp Detail Code'),
            'AppType' => Yii::t('app', 'App Type'),
            'MsgDelivered' => Yii::t('app', 'Msg Delivered'),
            'isRealtimeSync' => Yii::t('app', 'Is Realtime Sync'),
            'data_post_status' => Yii::t('app', 'Data Post Status'),
        ];
    }

}
