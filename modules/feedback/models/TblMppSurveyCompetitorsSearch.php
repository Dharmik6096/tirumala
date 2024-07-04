<?php

namespace app\modules\feedback\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feedback\models\TblMppSurveyCompetitors;

/**
 * TblMppSurveyCompetitorsSearch represents the model behind the search form about `app\modules\feedback\models\TblMppSurveyCompetitors`.
 */
class TblMppSurveyCompetitorsSearch extends TblMppSurveyCompetitors
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mpp_survey_competitors_id', 'mpp_survey_id', 'competitor_id', 'producter_count', 'originating_type'], 'safe'],
            [['milk_volume', 'milk_rate'], 'safe'],
            [['other_input_services', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblMppSurveyCompetitors::find();

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
            'mpp_survey_competitors_id' => $this->mpp_survey_competitors_id,
            'mpp_survey_id' => $this->mpp_survey_id,
            'competitor_id' => $this->competitor_id,
            'producter_count' => $this->producter_count,
            'milk_volume' => $this->milk_volume,
            'milk_rate' => $this->milk_rate,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'other_input_services', $this->other_input_services])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }
}
