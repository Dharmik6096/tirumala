<?php

namespace app\modules\bkgprocess\models;

use Yii;
use app\modules\organisation\models\TblDcs;

/**
 * This is the model class for table "bipl_ftp_collection".
 *
 * @property integer $id
 * @property string $cp_code
 * @property string $date
 * @property string $time
 * @property string $milk_type
 * @property string $local_code
 * @property string $extended_code
 * @property string $quantity
 * @property string $fat
 * @property string $snf
 * @property string $awm
 * @property string $amount
 * @property string $quantity_mode
 * @property string $measurement_mode
 * @property string $shift
 * @property string $rate
 * @property string $census_code
 * @property string $mobile
 * @property string $given_name
 * @property string $fathers_name
 * @property string $family_name
 * @property string $process_type
 * @property string $processed_at
 * @property integer $process_id
 * @property string $person_position
 * @property string $route_code
 * @property string $dop_goodcans
 * @property string $dop_badcans
 * @property string $dop_starttime
 * @property string $dop_milksamplenum
 * @property string $dop_milksamplestatus
 *
 * @property TblProcessedFiles $process
 */
class BiplFtpCollection extends \app\models\ChildModel {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'bipl_ftp_collection';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['status'], 'default', 'value' => 0],
            [['entry_datetime'], 'default', 'value' => date('Y-m-d H:i:s')],
            [['cp_code', 'local_code', 'date', 'time', 'milk_type', 'quantity', 'fat', 'snf', 'awm', 'rate', 'amount', 'shift'], 'required'],
            [['cp_code', 'milk_type', 'extended_code', 'quantity_mode', 'measurement_mode', 'shift', 'census_code', 'mobile', 'given_name', 'fathers_name', 'family_name', 'process_type'], 'safe'],
            [['date', 'time', 'processed_at', 'person_position', 'route_code', 'dop_goodcans', 'dop_badcans', 'dop_starttime', 'dop_milksamplenum', 'dop_milksamplestatus', 'dcs_code'], 'safe'],
            [['quantity', 'fat', 'snf', 'awm', 'amount', 'rate'], 'number', 'min' => 0],
            [['process_id'], 'integer'],
            [['local_code'], 'integer', 'on' => 'checkDate'],
//            [['cp_code'], function ($attribute, $params) {
//                    Yii::$app->general->findCensus($this, $attribute);
//                }, 'skipOnEmpty' => true],
//            [['milk_type'], function ($attribute, $params) {
//                    Yii::$app->general->validateBiplMilkType($this, $attribute);
//                }, 'skipOnEmpty' => true],
            ['date', 'date', 'format' => 'php:d.m.Y', 'on' => 'checkDate'],
            ['time', 'time', 'format' => 'php:H:i:s'],
            ['local_code', 'match', 'pattern' => '/^\d{1,4}$/i', 'message' => Yii::t('app/validation', '{attribute} must be numeric and length should be max 4 digits'), 'skipOnEmpty' => true],
//            [['local_code'], function ($attribute, $params) {
//                    Yii::$app->general->validateLocalCode($this, $attribute);
//                }, 'skipOnError' => true, 'skipOnEmpty' => true],
            [['measurement_mode', 'quantity_mode'], 'in', 'range' => ['AUTOMATIC', 'MANUAL']],
            ['shift', 'in', 'range' => ['M', 'E']],];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'cp_code' => Yii::t('app', 'Cp Code'),
            'date' => Yii::t('app', 'Date'),
            'time' => Yii::t('app', 'Time'),
            'milk_type' => Yii::t('app', 'Milk Type'),
            'local_code' => Yii::t('app', 'Local Code'),
            'extended_code' => Yii::t('app', 'Extended Code'),
            'quantity' => Yii::t('app', 'Quantity'),
            'fat' => Yii::t('app', 'Fat'),
            'snf' => Yii::t('app', 'Snf'),
            'awm' => Yii::t('app', 'Awm'),
            'amount' => Yii::t('app', 'Amount'),
            'quantity_mode' => Yii::t('app', 'Quantity Mode'),
            'measurement_mode' => Yii::t('app', 'Measurement Mode'),
            'shift' => Yii::t('app', 'Shift'),
            'rate' => Yii::t('app', 'Rate'),
            'census_code' => Yii::t('app', 'Census Code'),
            'mobile' => Yii::t('app', 'Mobile'),
            'given_name' => Yii::t('app', 'Given Name'),
            'fathers_name' => Yii::t('app', 'Fathers Name'),
            'family_name' => Yii::t('app', 'Family Name'),
            'process_type' => Yii::t('app', 'Process Type'),
            'processed_at' => Yii::t('app', 'Processed At'),
            'process_id' => Yii::t('app', 'Process ID'),
            'person_position' => Yii::t('app', 'Person Position'),
            'route_code' => Yii::t('app', 'Route Code'),
            'dop_goodcans' => Yii::t('app', 'Dop Goodcans'),
            'dop_badcans' => Yii::t('app', 'Dop Badcans'),
            'dop_starttime' => Yii::t('app', 'Dop Starttime'),
            'dop_milksamplenum' => Yii::t('app', 'Dop Milksamplenum'),
            'dop_milksamplestatus' => Yii::t('app', 'Dop Milksamplestatus'),
        ];
    }

    public function getDcsCode() {
        return $this->hasOne(TblDcs::className(), ['ref_code' => 'cp_code']);
    }

}
