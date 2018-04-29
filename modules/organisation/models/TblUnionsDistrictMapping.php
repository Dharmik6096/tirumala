<?php

namespace app\modules\organisation\models;

use Yii;
use app\modules\geo\models\TblDistricts;
use app\models\ChildModel;

/**
 * This is the model class for table "tbl_union_district".
 *
 * @property string $union_code
 * @property string $district_code
 * @property string $created_at
 * @property integer $is_active
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by

 */
class TblUnionsDistrictMapping extends ChildModel
{
    public $district_name,$local_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_union_district';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['union_code', 'district_code'], 'safe'],
            [['created_at', 'is_active','updated_at', 'created_by','updated_by','local_name'], 'safe'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'union_code' => Yii::t('app', 'Union Code'),
            'district_code' => Yii::t('app', 'District Code'),

        ];
    }

    /**
     * @inheritdoc
     * @return TblUnionsDistrictMappingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblUnionsDistrictMappingQuery(get_called_class());
    }

     public function alreadyExist($union_code){
        return $this->find()->select('district_code')->where(['=','union_code',$union_code])->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSingleDistrict()
    {
        return $this->hasOne(\app\modules\geo\models\TblDistricts::className(), ['district_code' => 'district_code']);
    }

    public function getDistrict($code) {
        $district = new TblFederationsStateMapping();
        $district_list = $district->getFrStates($code->federation_code);

        $values = $this->find()->select('district_code')->where(['union_code' => $code->union_code,'is_active'=>1])->asArray()->all();
        $selected = [];

         if(!empty($district_list)){
             foreach ($district_list as $key=>$row){
                    $stateCode = explode('-', $key);
                    $district=new TblDistricts();
                    $district_list[$key]=$district->getDistrict($stateCode[0]);
                    foreach ($district_list[$key] as $key1=>$dis){
                        if(array_search($key1, array_column($values, 'district_code'))!==FALSE){
                            $selected[] = $key1;
                        }
                    }
                }
            }
         return ['district_list'=>$district_list,'selected'=>$selected];
    }

    public function getDistrictArray($unionCode,$state,$districtCode=''){

         if(!empty($districtCode)){
             $unionQuery=$this->find()
                ->select(['tbl_union_district.district_code','tbl_districts.district_name','local_name'])
                ->innerJoin('tbl_districts','tbl_districts.district_code=tbl_union_district.district_code')
                ->where(['tbl_union_district.union_code'=>$unionCode,'tbl_districts.state_code'=>$state,'tbl_union_district.district_code'=>$districtCode]);

             $query=$this->find()
                   ->select(['tbl_union_district.district_code','tbl_districts.district_name','local_name'])
                   ->innerJoin('tbl_districts','tbl_districts.district_code=tbl_union_district.district_code')
                   ->where(['tbl_union_district.union_code'=>$unionCode,'tbl_districts.state_code'=>$state,'tbl_union_district.is_active'=>1])
                   ->union($unionQuery);

                if(Yii::$app->session->get('Districts')!==''){
                    $query->andWhere(['tbl_union_district.district_code'=> explode(',', Yii::$app->session->get('Districts'))]);
                }
         }else{

            $query=$this->find()
                   ->select(['tbl_union_district.district_code','tbl_districts.district_name','local_name'])
                   ->innerJoin('tbl_districts','tbl_districts.district_code=tbl_union_district.district_code')
                   ->where(['tbl_union_district.union_code'=>$unionCode,'tbl_districts.state_code'=>$state,'tbl_union_district.is_active'=>1]);
            if(Yii::$app->session->get('Districts')!==''){
                $query->andWhere(['tbl_union_district.district_code'=> explode(',', Yii::$app->session->get('Districts'))]);
            }
         }
         $array=$query->all();        
         $data = \yii\helpers\ArrayHelper::map($array, 'district_code', function($array, $key) {
                    if (!($array['local_name']=='' || $array['local_name']==null))
                        return $array['district_name'] . '(' . $array['local_name'] . ')';
                    else
                        return $array['district_name'];
                });
         asort($data,SORT_NATURAL | SORT_FLAG_CASE);         
         return $data;

     }

     public function getUnionDistrict($unionCode,$state){

        $query=$this->find()
                   ->select(['tbl_union_district.district_code','tbl_districts.district_name'])
                   ->innerJoin('tbl_districts','tbl_districts.district_code=tbl_union_district.district_code')
                   ->where(['tbl_union_district.union_code'=>$unionCode,'tbl_districts.state_code'=>$state,'tbl_union_district.is_active'=>1,'tbl_districts.is_active'=>1]);
        $array=$query->asArray()->all();

        return $array;
     }

     public function getRecord($unionCode,$districtCode){
         return $this->find()->where(['union_code'=>$unionCode,'district_code'=>$districtCode,'is_active'=>1])->one();
     }
}
