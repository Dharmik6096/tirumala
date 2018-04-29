<?php

namespace app\modules\globalmaster\models;
use Yii;
use app\models\ChildModel;
/**
 * This is the model class for table "tbl_land_unit".
 *
 * @property integer $land_unit_code
 * @property double $conversion_factor
 * @property string $created_at
 * @property integer $is_active
 * @property string $land_unit_name
 * @property string $local_name
 * @property integer  $is_default
 * @property string $updated_at
 * @property string $created_by
 * @property integer $land_unit
 * @property string $updated_by
 *
 * @property TblLandUnit $landUnit
 * @property TblLandUnit[] $tblLandUnits
 * @property TblUsers $deletedBy
 * @property TblUsers $createdBy
 * @property TblUsers $updatedBy
 */
class TblLandUnit extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_land_unit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['conversion_factor'], 'number', 'min'=>0],
          //  [['conversion_factor'], 'min'=>0,'message' => '{attribute} must have positive value only'],
            [['is_active','created_at', 'updated_at','is_default'], 'safe'],
            [[ 'land_unit'], 'integer'],
            [['land_unit_name','conversion_factor'], 'required'],
            [['land_unit'], 'required', 'except' => 'importCsv'],
            [['conversion_factor'], 'number','message'=> Yii::t('app/validation', '{attribute} must be a digit. e.g. "5","5.5"')],
            [['land_unit_name'], 'unique'],
            [['land_unit'], 'validateAttribute'],
            [['land_unit_name'], function ($attribute, $params) {
                    Yii::$app->general->validateAlphaNumber($this, $attribute,$params);
                },'skipOnEmpty'=> false],
            [['land_unit_name'], 'string', 'max' => 20],
            [['local_name'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
            },'skipOnEmpty'=> false],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
            [['land_unit'], 'exist', 'skipOnError' => true, 'targetClass' => TblLandUnit::className(), 'targetAttribute' => ['land_unit' => 'land_unit_code']],
          //  [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['created_by' => 'user_id']],
          //  [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => TblUsers::className(), 'targetAttribute' => ['updated_by' => 'user_id']],
        ];
    }

    public function validateAttribute($attribute, $params)
    {
        $check = $this->find()->select('land_unit_code')->where(['land_unit_code'=>$this->$attribute,'is_active'=>1,'is_default'=>1])->count();
        if ($check==0) {
            $this->addError($attribute, Yii::t('app/validation',$this->getAttributeLabel($attribute).' column must be from default record.'));
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'land_unit_code' => Yii::t('app', 'Land Unit Code'),
            'conversion_factor' => Yii::t('app', 'Conversion Factor'),
            'created_at' => Yii::t('app', 'Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'land_unit_name' => Yii::t('app', 'Land Unit'),
            'local_name' => Yii::t('app', 'Local Name'),
            'updated_at' => Yii::t('app', 'Updated At'),
           'created_by' => Yii::t('app', 'Created By'),
            'land_unit' => Yii::t('app', 'Convert To'),
           'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLandUnit()
    {
        return $this->hasOne(TblLandUnit::className(), ['land_unit_code' => 'land_unit']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblLandUnits()
    {
        return $this->hasMany(TblLandUnit::className(), ['land_unit' => 'land_unit_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
  /*  public function getDeletedBy()
    {
        return $this->hasOne(TblUsers::className());
    }
  */

    /**
     * @return \yii\db\ActiveQuery
     */
  /*  public function getCreatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'created_by']);
    }
  */
    /**
     * @return \yii\db\ActiveQuery
     */
  /*  public function getUpdatedBy()
    {
        return $this->hasOne(TblUsers::className(), ['user_id' => 'updated_by']);
    }
   */
    /**
     * @inheritdoc
     * @return TblLandUnitQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblLandUnitQuery(get_called_class());
    }

    public function checkDefault(){

        return ($this->is_default == 1) ? false : true;
    }

    public function getDefaultValues(){

        $data = $this->find()->select(['land_unit_code','land_unit_name','local_name'])->where(['is_active'=>1,'is_default'=>1])->all();
        $values = \yii\helpers\ArrayHelper::map($data, 'land_unit_code', function($array, $key) {
                    if (!empty($array['local_name']))
                        return $array['land_unit_name'] . '(' . $array['local_name'] . ')';
                    else
                        return $array['land_unit_name'];
                });
        return $values;
    }
}
