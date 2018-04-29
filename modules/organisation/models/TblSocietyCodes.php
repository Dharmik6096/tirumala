<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\organisation\models\TblDcsBmc;

/**
 * This is the model class for table "tbl_society_codes".
 *
 * @property integer $code
 * @property string $dcs_code
 * @property string $bmc_code
 * @property string $union_code
 * @property string $route_code
 * @property string $pooling_point_code
 * @property string $imei_no
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property TblDcsSubcenterBmcInfo $bmcCode
 * @property TblDcs $dcsCode
 * @property TblUnions $unionCode
 */
class TblSocietyCodes extends \app\models\ChildModel
{
    
    public $vendor_code;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_society_codes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dcs_code','union_code'], 'required'],
            [['route_code'],'required','on'=>'societycode'],
            //[['imei_no'],'required','on'=>'societycode'],
            /*[['imei_no'],'unique','skipOnEmpty'=>'true','on'=>'societycode','when' => function ($model, $attribute) {
               return $model->{$attribute} !== $model->getOldAttribute($attribute);
           },],*/
            [['imei_no'], function ($attribute, $params) {
                    $this->valiadteUniqueImei($this, $attribute,$params);
                },'skipOnEmpty'=> true],
            [['dcs_code', 'bmc_code', 'union_code', 'pooling_point_code', 'imei_no', 'created_by', 'updated_by'], 'string'],
            [['created_at', 'updated_at','vendor_code','imei_no', 'bipl_code','route_code'], 'safe']
            
            //[['bmc_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcsBmc::className(), 'targetAttribute' => ['bmc_code' => 'bmc_code']],
            //[['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
            //[['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => Yii::t('app', 'Code'),
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'bmc_code' => Yii::t('app', 'Bmc Code'),
            'bipl_code' => Yii::t('app', 'B Code'),
            'union_code' => Yii::t('app', 'Union Code'),
            'pooling_point_code' => Yii::t('app', 'Pooling Point Code'),
            'imei_no' => Yii::t('app', 'IMEI No'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBmcCode()
    {
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDcsCode()
    {
        return $this->hasOne(TblDcs::className(), ['dcs_code' => 'dcs_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode()
    {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }
    public function getImi($code) {

                $record = $this->find()->where(['dcs_code' => $code])->one();
                if(!empty($record))
                    return $record->imei_no;
                else
                    return '';
            }
    public function getPpCode(){

        $data=$this->find()->select(["MAX(CONVERT(INT,pooling_point_code)) AS pooling_point_code"])->where(['bmc_code'=> $this->bmc_code])->one();   
        if(!empty($data))
            return ((int)$data['pooling_point_code'] + 1);
        else
            return 1;
            
    }

    /**
     * @inheritdoc
     * @return TblSocietyCodesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblSocietyCodesQuery(get_called_class());
    }
    
     public function getBiplCode($code) {
        $data=  $this->find()->select(["convert(int,MAX(substring(bipl_code,7,2))) as bipl_code"])->where(['substring(bipl_code,1,6)'=>trim($code)])->one();
        $new_code = isset($data['bipl_code']) ? ($data['bipl_code'] + 1) : 1;
        return trim($code).str_pad($new_code,2,'0',STR_PAD_LEFT);
    }
    
    public function valiadteUniqueImei($model,$attribute,$params) {

        $primaryKey = $model->tableSchema->primaryKey[0];
        $values = $model->find()->joinWith('dcsCode')->where(['imei_no' => $model->imei_no,'tbl_dcs.is_active'=>1])->andWhere(['<>', $primaryKey, $model->$primaryKey])->count();
        if ($values != 0) {
            $model->addError($attribute, Yii::t('app/validation', $model->getAttributeLabel($attribute) . " '" . $model->imei_no . "'" . ' is already taken.'));
        }
    }
}
