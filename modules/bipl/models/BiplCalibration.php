<?php

namespace app\modules\bipl\models;

use Yii;
use app\modules\restservices\models\BiplModel;
/**
 * This is the model class for table "bipl_calibration".
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
 * @property string $milk_type
 * @property string $fat_offset
 * @property string $snf_offset
 */
class BiplCalibration extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bipl_calibration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['svc', 'usr', 'pswd', 'census_code','cp_code','date','time','milk_type','fat_offset', 'snf_offset'],'required'],
            [['svc', 'usr', 'pswd', 'cp', 'imei', 'mcc', 'cp_code', 'census_code', 'vendor_id', 'milk_type'], 'string'],
            [['date', 'time'], 'safe'],
            [['fat_offset', 'snf_offset'], 'number'],
            ['cp_code', 'string', 'length' => 8, 'skipOnEmpty'=>true],
            [['census_code'],function ($attribute, $params) {
                    $bipl=new BiplModel();
                    $bipl->findCensus($this, $attribute,$params);
                },'skipOnEmpty'=>true],
            ['census_code', 'match', 'pattern' => '/^\d{12}$/i','message'=> Yii::t('app/validation','{attribute} must be numeric and length should be exactly 12'),'skipOnEmpty'=>true],
            ['date', 'date','format'=>'php:d.m.Y','on'=>'checkDate'],
            ['time', 'time','format'=>'php:H:i:s'],
            [['milk_type'],function ($attribute, $params) {
                    $bipl=new BiplModel();
                    $bipl->validateMilkType($this, $attribute,$params);
                },'skipOnEmpty'=>true],
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
            'milk_type' => Yii::t('app', 'Milk Type'),
            'fat_offset' => Yii::t('app', 'Fat Offset'),
            'snf_offset' => Yii::t('app', 'Snf Offset'),
        ];
    }

    /**
     * @inheritdoc
     * @return BiplCalibrationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BiplCalibrationQuery(get_called_class());
    }
}
