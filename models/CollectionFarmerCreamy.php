<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "collectionfarmer".
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
class CollectionFarmerCreamy extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function getDb() {
        return Yii::$app->get('db_creamy'); // second database
    }

    public static function tableName() {
        return 'collectionfarmer';
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
            'farmerid' => Yii::t('app', 'Farmerid'),
            'vlccid' => Yii::t('app', 'Vlccid'),
            'routeid' => Yii::t('app', 'Routeid'),
            'bmcid' => Yii::t('app', 'Bmcid'),
            'sampleno' => Yii::t('app', 'Sampleno'),
            'qty' => Yii::t('app', 'Qty'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
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
            'usbflag' => Yii::t('app', 'Usbflag'),
            'paymentid' => Yii::t('app', 'Paymentid'),
            'StdRate' => Yii::t('app', 'Std Rate'),
            'RateType' => Yii::t('app', 'Rate Type'),
            'KgFatRate' => Yii::t('app', 'Kg Fat Rate'),
            'KgSnfRate' => Yii::t('app', 'Kg Snf Rate'),
            'RateRecalType' => Yii::t('app', 'Rate Recal Type'),
            'farmerstatus' => Yii::t('app', 'Farmerstatus'),
            'data_post_status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @inheritdoc
     * @return CollectionFarmerCreamyQuery the active query used by this AR class.
     */
    public static function find() {
        return new CollectionFarmerCreamyQuery(get_called_class());
    }

    public function getNewDcs($vlccid = []) {
        $date = date('Y-m-d H:i:s', strtotime('-3 hours'));
        $data1 = $this->find()
                ->where(['or', ['data_post_status' => 0], ['data_post_status' => NULL]])
                ->andWhere(['vlccid' => $vlccid])
                ->andWhere(['>=', 'dtdate', '2018-07-20 13:00:00'])
                ->limit(80)
                ->orderby('dtdate ASC')
                ->all();
        $data2 = $this->find()
                ->where(['data_post_status' => 3])
                ->andWhere(['vlccid' => ['1011618', '1011647', '1011648', '1015576']])
                ->andWhere(['<=', 'modifieddate', $date])
                ->limit(20)
                ->orderby('dtdate ASC')
                ->all();
        $result = array_merge($data1, $data2);
        return $result;
    }

    public function updateDcs($farmer_id, $vlcc_id, $sample_id, $dtdate_id, $shift_id) {
        return $this->updateAll(['data_post_status' => 1], ['vlccid' => $vlcc_id, 'farmerid' => $farmer_id, 'sampleno' => $sample_id, 'dtdate' => $dtdate_id, 'shift' => $shift_id]);
    }

}
