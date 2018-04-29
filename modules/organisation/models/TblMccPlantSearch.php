<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblMccPlant;

/**
 * TblMccPlantSearch represents the model behind the search form about `app\modules\organisation\models\TblMccPlant`.
 */
class TblMccPlantSearch extends TblMccPlant
{
    public $federation_code;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mcc_plant_code','plant_code','federation_code', 'contact_person', 'created_at', 'created_by', 'description', 'email',  'mobile_no', 'name', 'updated_at', 'updated_by', 'district_code', 'hamlet_code', 'state_code', 'sub_district_code', 'union_code', 'village_code','capacity', 'valid_from'], 'safe'],
            [['is_active'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TblMccPlant::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'sort'=> ['defaultOrder' => ['created_at'=>SORT_DESC]],
        ]);

        $this->load($params);

        if(Yii::$app->session->get('Unions')!=='' && empty($this->union_code)){
            $query->andFilterWhere([ 'tbl_mcc_plant.union_code'=>explode(',',Yii::$app->session->get('Unions'))]);
        }
        else{
            $query->andFilterWhere(['tbl_mcc_plant.union_code'=> $this->union_code]);
        }
            
        if(!empty($this->plant_code)){
            $query->joinWith(['plantCode']);
            $query->andFilterWhere(['like', 'tbl_plant.name', $this->plant_code]);
        }
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
//        if(!empty($this->capacity)){
//            $query->joinWith(['capacity0']);
//            $query->andFilterWhere([
//            'tbl_capacity.value'=> $this->capacity
//        ]);
//        }
        if(!empty($this->capacity)){
            $query->joinWith(['capacity0']);
            if(preg_match('/^[1-9][0-9]*$/', $this->capacity)){
                $query->andFilterWhere(['tbl_capacity.value'=> $this->capacity]);
            }else{
                $query->andFilterWhere(['like', 'tbl_capacity.value', $this->capacity]);
            }
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_mcc_plant.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_mcc_plant.mcc_plant_code', $this->mcc_plant_code])
            ->andFilterWhere(['like', 'tbl_mcc_plant.contact_person', $this->contact_person])
            ->andFilterWhere(['like', 'tbl_mcc_plant.description', $this->description])
            ->andFilterWhere(['like', 'tbl_mcc_plant.email', $this->email])
            ->andFilterWhere(['like', 'tbl_mcc_plant.mobile_no', $this->mobile_no])
            ->andFilterWhere(['like', 'tbl_mcc_plant.name', $this->name])
            ->andFilterWhere(['like', 'tbl_mcc_plant.district_code', $this->district_code])
            ->andFilterWhere(['like', 'tbl_mcc_plant.hamlet_code', $this->hamlet_code])
            ->andFilterWhere(['like', 'tbl_mcc_plant.state_code', $this->state_code])
            ->andFilterWhere(['like', 'tbl_mcc_plant.sub_district_code', $this->sub_district_code])
            ->andFilterWhere(['like', 'tbl_mcc_plant.village_code', $this->village_code]);

        return $dataProvider;
    }
    
    public function mccSearch($params){
        $query = TblMccPlant::find();
        $this->load($params);
        $query->where(['plant_code' => $this->plant_code]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }
}
