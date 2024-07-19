<?php

namespace app\modules\feedback\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TblEiplAppFeedbackItemSearch represents the model behind the search form about `app\modules\feedback\models\TblEiplAppFeedbackItem`.
 */
class TblMppSurveySearch extends TblMppSurvey
{
    public $f_union_code, $f_plant_code, $f_mcc_code, $from_date, $to_date;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mpp_survey_id','mpp_survey_code','survey_person_code','survey_date','mcc_plant_code','state_code','district_code','sub_district_code','village_code','hamlet_code','mpp_name','pincode','no_of_family_gen','no_of_family_obc','no_of_family_st','no_of_family_sc','no_of_family_other','no_of_family_total','milch_animal_cow_cnt','milch_animal_buff_cnt','milch_animal_country_cow_cnt','milch_animal_cnt_total','non_milch_animal_cow_cnt','non_milch_animal_buff_cnt','non_milch_animal_country_cow_cnt','non_milch_animal_cnt_total','cow_milk_volume','buff_milk_volume','total_milk_volume','nos_of_pouring_members','per_day_milk_sales_volume','expected_pourer_count','expected_milk_volume','created_at','created_by','updated_at','updated_by','originating_type','originating_org_code','originating_org_type'], 'safe'],
            [['f_union_code', 'f_plant_code', 'f_mcc_code', 'from_date', 'to_date'], 'safe'],
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
        $query = TblMppSurvey::find();

        // Load the search parameters into the model
        $this->load($params);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['mccPlantCode', 'surveyPersonCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_mcc_plant', 'tbl_mcc_plant');
        if (!empty($this->survey_date)) {
            $survey_date = date('Y-m-d', strtotime($this->survey_date));
            $query->andFilterWhere(['cast(tbl_mpp_survey.survey_date as date)' => $survey_date]);
        }

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $query->andFilterWhere(['>=', 'cast(tbl_mpp_survey.survey_date as date)', $from_date]);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $query->andFilterWhere(['<=', 'cast(tbl_mpp_survey.survey_date as date)', $to_date]);

        // Apply filtering based on model attributes
        $query->andFilterWhere(['like', 'tbl_mpp_survey.mpp_survey_id', $this->mpp_survey_id])
              ->andFilterWhere(['like', 'tbl_mpp_survey.mpp_survey_code', $this->mpp_survey_code])
              ->andFilterWhere(['like', 'user.name', $this->survey_person_code])
              ->andFilterWhere(['like', 'tbl_mpp_survey.state_code', $this->state_code])
              ->andFilterWhere(['like', 'tbl_mpp_survey.district_code', $this->district_code])
              ->andFilterWhere(['like', 'tbl_mpp_survey.sub_district_code', $this->sub_district_code])
              ->andFilterWhere(['like', 'tbl_mpp_survey.village_code', $this->village_code])
              ->andFilterWhere(['like', 'tbl_mpp_survey.hamlet_code', $this->hamlet_code])
              ->andFilterWhere(['like', 'tbl_mpp_survey.mpp_name', $this->mpp_name])
              ->andFilterWhere(['like', 'tbl_mpp_survey.pincode', $this->pincode])
              ->andFilterWhere(['like', 'tbl_mpp_survey.no_of_family_gen', $this->no_of_family_gen])
              ->andFilterWhere(['like', 'tbl_mpp_survey.no_of_family_obc', $this->no_of_family_obc])
              ->andFilterWhere(['like', 'tbl_mpp_survey.no_of_family_st', $this->no_of_family_st])
              ->andFilterWhere(['like', 'tbl_mpp_survey.no_of_family_sc', $this->no_of_family_sc])
              ->andFilterWhere(['like', 'tbl_mpp_survey.no_of_family_other', $this->no_of_family_other])
              ->andFilterWhere(['like', 'tbl_mpp_survey.no_of_family_total', $this->no_of_family_total])
              ->andFilterWhere(['like', 'tbl_mpp_survey.milch_animal_cow_cnt', $this->milch_animal_cow_cnt])
              ->andFilterWhere(['like', 'tbl_mpp_survey.milch_animal_buff_cnt', $this->milch_animal_buff_cnt])
              ->andFilterWhere(['like', 'tbl_mpp_survey.milch_animal_country_cow_cnt', $this->milch_animal_country_cow_cnt])
              ->andFilterWhere(['like', 'tbl_mpp_survey.milch_animal_cnt_total', $this->milch_animal_cnt_total])
              ->andFilterWhere(['like', 'tbl_mpp_survey.non_milch_animal_cow_cnt', $this->non_milch_animal_cow_cnt])
              ->andFilterWhere(['like', 'tbl_mpp_survey.non_milch_animal_buff_cnt', $this->non_milch_animal_buff_cnt])
              ->andFilterWhere(['like', 'tbl_mpp_survey.non_milch_animal_country_cow_cnt', $this->non_milch_animal_country_cow_cnt])
              ->andFilterWhere(['like', 'tbl_mpp_survey.non_milch_animal_cnt_total', $this->non_milch_animal_cnt_total])
              ->andFilterWhere(['like', 'tbl_mpp_survey.cow_milk_volume', $this->cow_milk_volume])
              ->andFilterWhere(['like', 'tbl_mpp_survey.buff_milk_volume', $this->buff_milk_volume])
              ->andFilterWhere(['like', 'tbl_mpp_survey.total_milk_volume', $this->total_milk_volume])
              ->andFilterWhere(['like', 'tbl_mpp_survey.nos_of_pouring_members', $this->nos_of_pouring_members])
              ->andFilterWhere(['like', 'tbl_mpp_survey.per_day_milk_sales_volume', $this->per_day_milk_sales_volume])
              ->andFilterWhere(['like', 'tbl_mpp_survey.expected_pourer_count', $this->expected_pourer_count])
              ->andFilterWhere(['like', 'tbl_mpp_survey.expected_milk_volume', $this->expected_milk_volume]);
        return $dataProvider;
    }
}
