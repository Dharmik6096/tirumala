<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\geo\models\TblVillages;
use app\models\ChildModel;
use app\components\GeneralFunctions;
/**
 * This is the model class for table "tbl_dcs_village".
 *
 * @property string $dcs_code
 * @property string $village_code
 * @property string $created_at
 * @property integer $is_active
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 */
class TblDcsVillageMapping extends ChildModel
{
    public $village_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_dcs_village';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dcs_code', 'village_code'], 'safe'],
            [['dcs_code'],'validateDcs'],
            [['created_at','is_active','updated_at', 'created_by','updated_by'], 'safe'],
            [['dcs_code'], 'exist', 'skipOnError' => true, 'targetClass' => TblDcs::className(), 'targetAttribute' => ['dcs_code' => 'dcs_code']],
//            [['village_code'], 'string', 'max' => 6],
        ];
    }

    public function validateDcs($attribute, $params) {
        if(!empty($this->dcs_code)){
            $check = $this->find()->where(['dcs_code'=>$this->dcs_code,'village_code'=> $this->village_code])->count();
            if($check!=0){
                $this->addError($attribute, Yii::t('app/validation', " Dcs Code '".$this->dcs_code."' and Village Code '".$this->village_code."'". ' has already been taken.'));
                return false;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dcs_code' => Yii::t('app', 'Dcs Code'),
            'village_code' => Yii::t('app', 'Village Code'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblDcsVillageMappingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblDcsVillageMappingQuery(get_called_class());
    }

    public function getVillageCode()
    {
        return $this->hasOne(TblVillages::className(), ['village_code' => 'village_code']);
    }

    public function getDistrictVillages($dcsCode,$unionCode){
        $list = TblUnionsDistrictMapping::find()->where(['union_code'=>$unionCode])->all();
        $dcsVillages = TblDcsVillageMapping::find()->select('village_code')->where(['dcs_code'=>$dcsCode,'is_active'=>1])->asArray()->all();
        $return_array = [];
        $selected = [];
         foreach ($list as $row){
             if(isset($row->singleDistrict)){
                 if(isset($row->singleDistrict->tblSubDistricts)){
                     foreach ($row->singleDistrict->tblSubDistricts as $sub){
                        if(isset($sub->tblVillages)){
                            foreach ($sub->tblVillages as $v){
                                $return_array[$row->district_code.'-'.$row->singleDistrict->district_name][$v->village_code] = $v->village_name;
                                if(array_search($v->village_code, array_column($dcsVillages, 'village_code'))!==FALSE){
                                        $selected[]=$v->village_code;
                                        //$selected[$v->village_code] = ['selected' => 'selected'];
                                }
                            }
                        }
                     }
                 }
             }
         }
         return ['villages'=>$return_array,'selected'=>$selected];
//         return $return_array;
    }

    public function addDcsVillageMapping($dcsCode,$villageCode,$operation){

        $this->dcs_code = $dcsCode;
        $this->village_code = $villageCode;
        $this->is_active=1;
        Yii::$app->operation->defaults($this, $operation);
        $this->save();
        
    }

    public function getAssignedValues($code){
        $data = $this->getRecords($code);
        $values = \yii\helpers\ArrayHelper::map($data, 'village_code', 'village_code');
        return ['object'=>$data,'array'=>$values];
    }

    public function getRecords($code){
        $data = $this->find()->where(['dcs_code' => $code,'is_active'=>1])->all();
        return $data;
    }

   

    public function getVillageArray($dcsCode,$subDistrictCode){

        $query=$this->find()
                   ->select(['tbl_dcs_village.village_code','tbl_villages.village_name'])
                   ->innerJoin('tbl_villages','tbl_villages.village_code=tbl_dcs_village.village_code')
                   ->where(['tbl_dcs_village.dcs_code'=>$dcsCode,'tbl_villages.sub_district_code'=>$subDistrictCode,'tbl_dcs_village.is_active'=>1]);
        $array=$query->all();
        $data = \yii\helpers\ArrayHelper::map($array, 'village_code', function($array,$key) {
                 return $array['village_name'];
             });
        asort($data,SORT_NATURAL | SORT_FLAG_CASE);
        return $data;
    }

    public function getRecord($dcsCode,$villageCode){
         return $this->find()->where(['dcs_code'=>$dcsCode,'village_code'=>$villageCode,'is_active'=>1])->one();
     }
}
