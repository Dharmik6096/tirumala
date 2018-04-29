<?php

namespace app\modules\bipl\models;

use Yii;
use app\modules\restservices\models\BiplModel;
/**
 * This is the model class for table "bipl_cleaning".
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
 * @property string $no_of_cycles
 */
class BiplCleaning extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bipl_cleaning';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['svc', 'usr', 'pswd', 'census_code','cp_code','date','time','no_of_cycles'],'required'],
            [['svc', 'usr', 'pswd', 'cp', 'imei', 'mcc', 'cp_code', 'census_code', 'vendor_id'], 'string'],
            [['date', 'time'], 'safe'],
            ['cp_code', 'string', 'length' => 8, 'skipOnEmpty'=>true],
            [['census_code'],function ($attribute, $params) {
                    $bipl=new BiplModel();
                    $bipl->findCensus($this, $attribute,$params);
                },'skipOnEmpty'=>true],
            ['census_code', 'match', 'pattern' => '/^\d{12}$/i','message'=> Yii::t('app/validation','{attribute} must be numeric and length should be exactly 12'),'skipOnEmpty'=>true],
            ['date', 'date','format'=>'php:d.m.Y','on'=>'checkDate'],
            ['time', 'time','format'=>'php:H:i:s'],
            [['no_of_cycles'], 'integer','min'=>0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'svc' => Yii::t('app', 'Svc'),
            'usr' => Yii::t('app', 'Usr'),
            'pswd' => Yii::t('app', 'Pswd'),
            'cp' => Yii::t('app', 'Cp'),
            'imei' => Yii::t('app', 'Imei'),
            'mcc' => Yii::t('app', 'Mcc'),
            'cp_code' => Yii::t('app', 'Cp Code'),
            'census_code' => Yii::t('app', 'Census Code'),
            'vendor_id' => Yii::t('app', 'Vendor ID'),
            'date' => Yii::t('app', 'Date'),
            'time' => Yii::t('app', 'Time'),
            'no_of_cycles' => Yii::t('app', 'No Of Cycles'),
        ];
    }

    /**
     * @inheritdoc
     * @return BiplCleaningQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BiplCleaningQuery(get_called_class());
    }
}
