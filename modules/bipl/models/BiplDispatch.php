<?php

namespace app\modules\bipl\models;

use Yii;
use app\modules\restservices\models\BiplModel;
/**
 * This is the model class for table "bipl_dispatch".
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
 * @property string $given_name
 * @property string $family_name
 * @property string $father_name
 * @property string $d_qty
 * @property string $d_fat
 * @property string $d_snf
 * @property string $d_awm
 * @property string $m_mode
 * @property string $qty_mode
 * @property string $shift
 * @property integer $c_samples
 * @property integer $status
 */
class BiplDispatch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bipl_dispatch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['svc', 'usr', 'pswd', 'census_code','local_code','cp_code','date','time','milk_type','d_qty', 'd_fat', 'd_snf','m_mode','shift'],'required'],
            [['svc', 'usr', 'pswd', 'cp', 'imei', 'mcc', 'cp_code', 'vendor_id', 'milk_type', 'ext_code', 'given_name', 'family_name', 'father_name', 'm_mode', 'qty_mode', 'shift'], 'string'],
            [['date', 'time','c_samples','status'], 'safe'],
            [['d_qty', 'd_fat', 'd_snf', 'd_awm'], 'number','min'=>0],
            [['c_samples'], 'integer','min'=>0],
            ['cp_code', 'string', 'length' => 8, 'skipOnEmpty'=>true],
            [['census_code'],function ($attribute, $params) {
                    $bipl=new BiplModel();
                    $bipl->findCensus($this, $attribute,$params);
                },'skipOnEmpty'=>true],
            ['census_code', 'match', 'pattern' => '/^\d{12}$/i','message'=> Yii::t('app/validation','{attribute} must be numeric and length should be exactly 12'),'skipOnEmpty'=>true],
            //['milk_type', 'in', 'range' => ['Cow', 'cow', 'Buffellow','buffellow', 'Mix', 'mix']],
            ['shift', 'in', 'range' => ['M','E']],
            [['m_mode', 'qty_mode'], 'in', 'range' => ['A','M']],
            [['given_name','family_name', 'father_name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute,$params);
                },'skipOnEmpty'=> true],
            [['milk_type'],function ($attribute, $params) {
                    $bipl=new BiplModel();
                    $bipl->validateMilkType($this, $attribute,$params);
                },'skipOnEmpty'=>true],
            ['local_code', 'match', 'pattern' => '/^\d{1,4}$/i','message'=> Yii::t('app/validation','{attribute} must be numeric and length should be max 4 digits'),'skipOnEmpty'=>true],
//                        ['local_code',function ($attribute, $params) {
//                    $bipl=new BiplModel();
//                    $bipl->validateLocalCode($this, $attribute,$params);
//                },'skipOnError'=>true,'skipOnEmpty'=>true],
            ['date', 'date','format'=>'php:d.m.Y','on'=>'checkDate'],
            ['time', 'time','format'=>'php:H:i:s'],
            /*[['mobile'], function ($attribute, $params) {
                    Yii::$app->general->vaildateMobileNumbers($this, $attribute,$params);
                },'skipOnEmpty'=> true],*/
            
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
            'given_name' => Yii::t('app', 'Given Name'),
            'family_name' => Yii::t('app', 'Family Name'),
            'father_name' => Yii::t('app', 'Father Name'),
            'd_qty' => Yii::t('app', 'D Qty'),
            'd_fat' => Yii::t('app', 'D Fat'),
            'd_snf' => Yii::t('app', 'D Snf'),
            'd_awm' => Yii::t('app', 'D Awm'),
            'm_mode' => Yii::t('app', 'M Mode'),
            'qty_mode' => Yii::t('app', 'Qty Mode'),
            'shift' => Yii::t('app', 'Shift'),
            'c_samples'=>Yii::t('app', 'No of Samples'),
        ];
    }

    /**
     * @inheritdoc
     * @return BiplDispatchQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BiplDispatchQuery(get_called_class());
    }
}
