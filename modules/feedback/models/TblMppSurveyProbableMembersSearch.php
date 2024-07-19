<?php

namespace app\modules\feedback\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feedback\models\TblMppSurveyProbableMembers;

/**
 * TblMppSurveyProbableMembersSearch represents the model behind the search form about `app\modules\feedback\models\TblMppSurveyProbableMembers`.
 */
class TblMppSurveyProbableMembersSearch extends TblMppSurveyProbableMembers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mpp_survey_members_id','mpp_survey_id','name','mobile_no','milch_animal_cow_cnt','milch_animal_buff_cnt','milch_animal_country_cow_cnt','cow_milk_volume','buff_milk_volume','total_milk_volume','own_milk_consumption','balance_milk','remarks','created_at','created_by','updated_at','updated_by','originating_type','originating_org_code','originating_org_type'], 'safe'],
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
        $query = TblMppSurveyProbableMembers::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'mpp_survey_members_id' => $this->mpp_survey_members_id,
            'mpp_survey_id' => $this->mpp_survey_id,
            'milch_animal_cow_cnt' => $this->milch_animal_cow_cnt,
            'milch_animal_buff_cnt' => $this->milch_animal_buff_cnt,
            'milch_animal_country_cow_cnt' => $this->milch_animal_country_cow_cnt,
            'cow_milk_volume' => $this->cow_milk_volume,
            'buff_milk_volume' => $this->buff_milk_volume,
            'total_milk_volume' => $this->total_milk_volume,
            'own_milk_consumption' => $this->own_milk_consumption,
            'balance_milk' => $this->balance_milk,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'mobile_no', $this->mobile_no])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }
}
