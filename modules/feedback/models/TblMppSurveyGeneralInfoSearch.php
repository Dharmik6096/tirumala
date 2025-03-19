<?php

namespace app\modules\feedback\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feedback\models\TblMppSurveyGeneralInfo;

/**
 * TblMppSurveyGeneralInfoSearch represents the model behind the search form about `app\modules\feedback\models\TblMppSurveyGeneralInfo`.
 */
class TblMppSurveyGeneralInfoSearch extends TblMppSurveyGeneralInfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mpp_survey_gn_info_id', 'mpp_survey_id', 'no_male', 'no_female', 'gender_total', 'total_voters', 'total_family', 'total_family_engageding_dairy_business', 'name_of_visiting_officer', 'number_of_visiting_officer', 'pradhan_name', 'pradhan_number', 'power_status', 'post_office', 'health_center', 'artificial_insemination_center', 'animal_health_center', 'status_of_advance_amount_in_thevillage', 'status_of_education_invillage', 'main_crops_of_thevillage', 'green_foddercrops', 'irrigation_facility', 'cowmilkvolume', 'buffmilkvolume', 'totalmilkvolume', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_type', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblMppSurveyGeneralInfo::find();

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
            'mpp_survey_gn_info_id' => $this->mpp_survey_gn_info_id,
            'mpp_survey_id' => $this->mpp_survey_id,
        ]);


        return $dataProvider;
    }
}
