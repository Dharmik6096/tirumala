<?php

namespace app\modules\configuration\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\configuration\models\TblMilkQualityParamRange;

/**
 * TblMilkQualityParamRangeSearch represents the model behind the search form about `app\modules\configuration\models\TblMilkQualityParamRange`.
 */
class TblMilkQualityParamRangeSearch extends TblMilkQualityParamRange {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['min_fat', 'max_fat', 'min_snf', 'max_snf', 'min_clr', 'max_clr', 'milk_quality_param_range_code', 'animal_type_code', 'originating_type', 'process_name', 'union_code', 'org_code', 'org_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = TblMilkQualityParamRange::find();

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
            'milk_quality_param_range_code' => $this->milk_quality_param_range_code,
            'animal_type_code' => $this->animal_type_code,
            'min_fat' => $this->min_fat,
            'max_fat' => $this->max_fat,
            'min_snf' => $this->min_snf,
            'max_snf' => $this->max_snf,
            'min_clr' => $this->min_clr,
            'max_clr' => $this->max_clr,
        ]);

        $query->andFilterWhere(['like', 'process_name', $this->process_name])
                ->andFilterWhere(['like', 'org_code', $this->org_code])
                ->andFilterWhere(['like', 'org_type', $this->org_type]);

        return $dataProvider;
    }

}
