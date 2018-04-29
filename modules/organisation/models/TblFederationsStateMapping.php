<?php

namespace app\modules\organisation\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\ChildModel;
use app\modules\geo\models\TblStates;
use yii\db\Query;
use app\components\GeneralFunctions;
/**
 * This is the model class for table "tbl_federation_state".
 *
 * @property string $federation_code
 * @property string $state_code
 * @property string $created_at
 * @property integer $is_active
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by

 */
class TblFederationsStateMapping extends ChildModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_federation_state';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['federation_code', 'state_code'], 'safe'],
            [['created_at', 'is_active','updated_at', 'created_by','updated_by'], 'safe'],
//            [['federation_code', 'state_code'], 'string', 'max' => 2]

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'federation_code' => Yii::t('app', 'Federation Code'),
            'state_code' => Yii::t('app', 'State Code')

        ];
    }

    /**
     * @inheritdoc
     * @return TblFederationsStateMappingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblFederationsStateMappingQuery(get_called_class());
    }
    public function getStateCode()
    {
        return $this->hasOne(\app\modules\geo\models\TblStates::className(), ['state_code' => 'state_code']);
    }
    public  function getFrStates($fedrCode){

         $Branch =  $this->find()->where(['federation_code'=>$fedrCode])->all();

         $return_array = [];
         $test = [];
         if($Branch){
             foreach ($Branch as $row){
                 if(isset($row->stateCode)){
                     if(isset($row->stateCode->tblDistricts)){
                         foreach ($row->stateCode->tblDistricts as $d){
                             $return_array[$row->state_code.'-'.$row->stateCode->state_name][$d->district_code] = $d->district_name;
                         }
                     }
                 }
             }
         }
         $Branch = ArrayHelper::map($Branch, 'state_code', function ($element) {
                            return $element->stateCode->state_name;
                        });
         return $return_array;
     }
     public function alreadyExist($federation_code){
        return $this->find()->select('state_code')->where(['=','federation_code',$federation_code])->all();
    }
    public function getState($code) {
        $state = new TblStates();
        $state_list = $state->getActiveStates();
        $values = $this->find()->select('state_code')->where(['federation_code' => $code,'is_active'=>1])->asArray()->all();
        $selected = [];
        foreach ($state_list as $key => $row) {
            if (array_search($key, array_column($values, 'state_code')) !== FALSE) {
                //$selected[$key] = ['selected' => 'selected'];
                $selected[] = $key;
            }
        }
        return ['value' => $state_list, 'selected' => $selected];
    }
}
