<?php

namespace app\modules\organisation\models;

use Yii;
use app\models\TblUsers;
use yii\helpers\ArrayHelper;
use app\models\ChildModel;
/**
 * This is the model class for table "tbl_routes".
 *
 * @property string $route_code
 * @property string $bmc_code
 * @property integer $capacity
 * @property string $created_at
 * @property integer $is_active
 * @property string $return_time
 * @property string $route_length_kms
 * @property string $route_name
 * @property string $local_name
 * @property string $start_time
 * @property string $updated_at
 * @property string $created_by
 * @property string $union_code
 * @property string $updated_by
 * @property integer $vehicle_type_code
 *
 * @property TblUnions $unionCode
 * @property TblUsers $createdBy
 * @property TblUsers $updatedBy
 * @property TblVehicleType $vehicleType
 * @property TblSubCenter[] $tblSubCenters
 * @property TblSubCenterHistory[] $tblSubCenterHistories
 */
class TblRoutes extends ChildModel {

    public $federation_code;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_routes';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['route_code','union_code', 'federation_code', 'route_name','vehicle_type_code','bmc_code'], 'required'],
            [['route_code', 'route_name'], 'unique','message'=>'{attribute} has already been taken.'],
            [['route_name'], function ($attribute, $params) {
                    Yii::$app->general->validateName($this, $attribute,$params);
                },'skipOnEmpty'=> false],
             [['local_name'], function ($attribute, $params) {
                Yii::$app->general->vaildateLocalField($this, $attribute,$params);
            },'skipOnEmpty'=> false],
            [['start_time','return_time'],'timeValidation'],
            [['start_time'],'rangeValidation'],
            [['capacity'], 'integer', 'max' => 1000, 'min' => 1, 'message' => Yii::t('app/validation', '{attribute} must be a digit. e.g. "01".'), 'tooBig' => '{attribute} Should be less than 999', 'tooSmall' => '{attribute} Should be greater than 1'],
            [['capacity', 'vehicle_type_code'], 'integer'],
            [[ 'is_active','created_at', 'updated_at', 'union_code','federation_code','route_length_kms'], 'safe'],
            [['route_name'], 'string', 'max' => 255],
            [['route_length_kms'], 'integer','message'=> Yii::t('app/validation', '{attribute} must be a digit. e.g. "15"')],
            [['return_time', 'start_time', 'created_by', 'updated_by'], 'string', 'max' => 14],
            [['union_code'], 'string', 'max' => 3],
            [['union_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblUnions::className(), 'targetAttribute' => ['union_code' => 'union_code']],
            [['vehicle_type_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblVehicleType::className(), 'targetAttribute' => ['vehicle_type_code' => 'vehicle_type_code']],
        ];
    }

    public function timeValidation($attribute, $params) {

        if (!empty($this->$attribute)) {
            $starttime = explode(':', $this->$attribute);
            if($starttime[0]>23 || $starttime[1]>59){
                $this->addError($attribute, $this->getAttributeLabel($attribute).' is not valid.');
                return false;
            }
        }
    }

    public function rangeValidation($attribute, $params) {

        if (!empty($this->start_time) && !empty($this->return_time)) {
            $starttime = explode(':', $this->start_time);
            $return_time = explode(':', $this->return_time);
            if($starttime[0]>$return_time[0] || ($starttime[0]==$return_time[0] && $starttime[1]>$return_time[1])){
                $this->addError($attribute, $this->getAttributeLabel($attribute).' can not be greater then Return Time.');
                return false;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'route_code' => Yii::t('app', 'Route Code'),
            'bmc_code' => Yii::t('app', 'BMC'),
            'capacity' => Yii::t('app', 'Capacity'),
            'created_at' => Yii::t('app', 'Created At'),
            'is_active' => Yii::t('app', 'Is Active'),
            'return_time' => Yii::t('app', 'Return Time'),
            'route_length_kms' => Yii::t('app', 'Route Length Kms'),
            'route_name' => Yii::t('app', 'Route Name'),
            'local_name' => Yii::t('app', 'Hindi Name'),
            'start_time' => Yii::t('app', 'Start Time'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'union_code' => Yii::t('app', 'Union'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'vehicle_type_code' => Yii::t('app', 'Vehical Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnionCode() {
        return $this->hasOne(TblUnions::className(), ['union_code' => 'union_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    /*   public function getCreatedBy()
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
    public function getVehicleType() {
        return $this->hasOne(TblVehicleType::className(), ['vehicle_type_code' => 'vehicle_type_code']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSubCenters() {
        return $this->hasMany(TblSubCenter::className(), ['route_code' => 'route_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblSubCenterHistories() {
        return $this->hasMany(TblSubCenterHistory::className(), ['route_code' => 'route_code']);
    }

    /**
     * @inheritdoc
     * @return TblRoutesQuery the active query used by this AR class.
     */
    public static function find() {
        return new TblRoutesQuery(get_called_class());
    }
    public function getDcsCodes() {
        return $this->hasMany(TblDcs::className(), ['route_code' => 'route_code']);
    }
    public function getActiveRoutes() {
        $value = $this->find()->where(['is_active' => 1])->all();
        return ArrayHelper::map($value, 'route_code', 'route_name');
    }
    
    public function getTblDcsBmc(){
        return $this->hasOne(TblDcsBmc::className(), ['bmc_code' => 'bmc_code'])->andwhere(['tbl_dcs_subcenter_bmc_info.is_active' => 1]);
    }

    public function getCode(){
        $data=  $this->find()->select(["MAX(CONVERT(bigint,route_code)) as route_code"])->one();       
        return str_pad(((int)$data['route_code']+1),8,'0',STR_PAD_LEFT);
        
    }

    public  function getRoutes($unionCode){
         $route = $this->find()->where(['union_code'=>$unionCode,'is_active'=>1])->all();
         $route = ArrayHelper::map($route, 'route_code', 'route_name');
         asort($route,SORT_NATURAL | SORT_FLAG_CASE);
         return $route;
     }
     
    public function route($bmc_code){
        $data = $this->find()->where(['bmc_code' => $bmc_code])->all();
        $array = \yii\helpers\ArrayHelper::map($data, 'route_code', 'route_name');
        return $array;
    }
}
