<?php

namespace app\modules\bipl\models;

use Yii;
use app\modules\restservices\models\BiplModel;
/**
 * This is the model class for table "bipl_collection".
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
 * @property string $local_code
 * @property string $ext_code
 * @property string $mobile
 * @property string $given_name
 * @property string $family_name
 * @property string $father_name
 * @property string $qty
 * @property string $fat
 * @property string $snf
 * @property string $awm
 * @property string $rate
 * @property string $amt
 * @property string $m_mode
 * @property string $qty_mode
 * @property string $shift
 */
class BiplCollection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bipl_collection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['svc', 'usr', 'pswd', 'census_code','local_code','cp_code','date','time','milk_type','qty', 'fat', 'snf', 'awm', 'rate', 'amt', 'shift','vendor_id'],'required'],
            [['svc', 'usr', 'pswd', 'cp', 'imei', 'mcc', 'cp_code', 'census_code', 'vendor_id', 'milk_type', 'local_code', 'ext_code', 'mobile', 'given_name', 'family_name', 'father_name', 'm_mode', 'qty_mode', 'shift'], 'string'],
            [['date', 'time', 'status', 'entry_datetime', 'response_datetime', 'response_msg', 'dop_milksamplenum'], 'safe'],
            [['qty', 'fat', 'snf', 'awm', 'rate', 'amt'], 'number','min'=>0],
            ['cp_code', 'string', 'min' => 7, 'max' => 12, 'skipOnEmpty'=>true],
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
            ['local_code', 'match', 'pattern' => '/^\d{1,4}$/i','message'=> Yii::t('app/validation','{attribute} must be numeric and length should be max 4 digits'),'skipOnEmpty'=>true],
            ['local_code',function ($attribute, $params) {
                    $bipl=new BiplModel();
                    $bipl->validateLocalCode($this, $attribute,$params);
                },'skipOnError'=>true,'skipOnEmpty'=>true],           
            [['mobile'], function ($attribute, $params) {
                    Yii::$app->general->vaildateMobileNumbers($this, $attribute,$params);
                },'skipOnEmpty'=> true],
            [['given_name','family_name', 'father_name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute,$params);
                },'skipOnEmpty'=> true],
            [['m_mode', 'qty_mode'], 'in', 'range' => ['A','M']],
            ['shift', 'in', 'range' => ['M','E']],
            
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
            'local_code' => Yii::t('app', 'Local Code'),
            'ext_code' => Yii::t('app', 'Ext Code'),
            'mobile' => Yii::t('app', 'Mobile'),
            'given_name' => Yii::t('app', 'Given Name'),
            'family_name' => Yii::t('app', 'Family Name'),
            'father_name' => Yii::t('app', 'Father Name'),
            'qty' => Yii::t('app', 'Qty'),
            'fat' => Yii::t('app', 'FAT'),
            'snf' => Yii::t('app', 'SNF'),
            'awm' => Yii::t('app', 'Awm'),
            'rate' => Yii::t('app', 'Rate'),
            'amt' => Yii::t('app', 'Amt'),
            'm_mode' => Yii::t('app', 'M Mode'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'shift' => Yii::t('app', 'Shift'),
        ];
    }

    /**
     * @inheritdoc
     * @return BiplCollectionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BiplCollectionQuery(get_called_class());
    }
}
