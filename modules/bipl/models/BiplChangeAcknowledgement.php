<?php

namespace app\modules\bipl\models;

use Yii;
use app\modules\restservices\models\BiplModel;

/**
 * This is the model class for table "bipl_change_acknowledgement".
 *
 * @property integer $id
 * @property string $svc
 * @property string $usr
 * @property string $pswd
 * @property string $cp
 * @property string $imei
 * @property string $mcc
 * @property string $cp_code
 * @property string $census_code
 * @property string $vendor_id
 * @property string $date
 * @property string $time
 * @property string $file_name
 */
class BiplChangeAcknowledgement extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'bipl_change_acknowledgement';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['svc', 'census_code', 'cp_code', 'date', 'time', 'file_name'], 'required'],
                [['svc', 'usr', 'pswd', 'cp', 'imei', 'mcc', 'cp_code', 'census_code', 'vendor_id', 'file_name'], 'string'],
                [['date', 'time'], 'safe'],
                ['cp_code', 'string', 'length' => 8, 'skipOnEmpty' => true],
            /*    [['census_code'], function ($attribute, $params) {
              $bipl = new BiplModel();
              $bipl->findCensus($this, $attribute, $params);
              }, 'skipOnEmpty' => true],
              ['census_code', 'match', 'pattern' => '/^\d{12}$/i','message'=> Yii::t('app/validation','{attribute} must be numeric and length should be exactly 12'),'skipOnEmpty'=>true], */
                ['date', 'date', 'format' => 'php:d.m.Y', 'on' => 'checkDate'],
                ['time', 'time', 'format' => 'php:H:i:s'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'svc' => Yii::t('app', 'Svc'),
            'usr' => Yii::t('app', 'User'),
            'pswd' => Yii::t('app', 'Password'),
            'cp' => Yii::t('app', 'CP'),
            'imei' => Yii::t('app', 'IMEI'),
            'mcc' => Yii::t('app', 'MCC'),
            'cp_code' => Yii::t('app', 'CP Code'),
            'census_code' => Yii::t('app', 'Census Code'),
            'vendor_id' => Yii::t('app', 'Vendor'),
            'date' => Yii::t('app', 'Date'),
            'time' => Yii::t('app', 'Time'),
            'file_name' => Yii::t('app', 'File Name'),
        ];
    }

    /**
     * @inheritdoc
     * @return BiplChangeAcknowledgementQuery the active query used by this AR class.
     */
    public static function find() {
        return new BiplChangeAcknowledgementQuery(get_called_class());
    }

}
