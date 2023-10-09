<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblTankerRateBased;

/**
 * TblTankerRateBasedSearch represents the model behind the search form about `app\modules\tankermovement\models\TblTankerRateBased`.
 */
class TblTankerRateBasedSearch extends TblTankerRateBased
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rate_based_code', 'value', 'fat_rate','snf_rate', 'milk_quality_type_code', 'milk_type_code',  'created_at','updated_at', 'created_by', 'purchase_rate_code', 'updated_by'], 'safe'],
//            [['end_range', 'fixed_point', 'value', 'kg_rate', 'start_range'], 'number'],
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
        $query = TblTankerRateBased::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['qualityParamCode','milkTypeCode','milkQualityTypeCode']);
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_tanker_rate_based.rate_based_code' => $this->rate_based_code,
            'tbl_tanker_rate_based.end_range' => $this->end_range,
            'tbl_tanker_rate_based.fixed_point' => $this->fixed_point,
            'tbl_tanker_rate_based.value' => $this->value,
            'tbl_tanker_rate_based.kg_rate' => $this->kg_rate,
            'tbl_tanker_rate_based.start_range' => $this->start_range,
          //  'tbl_tanker_rate_based.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'deduction_type', $this->deduction_type])
            ->andFilterWhere(['like', 'ref_type', $this->ref_type])
            ->andFilterWhere(['like', 'tbl_animal_type.animal_type_name', $this->milk_type_code])
                ->andFilterWhere(['like', 'tbl_milk_quality_type.milk_quality_type_name', $this->milk_quality_type_code])
            ->andFilterWhere(['like', 'tbl_quality_param.param', $this->quality_param_code])
            ->andFilterWhere(['like', 'tbl_tanker_rate_based.purchase_rate_code', $this->purchase_rate_code]);

        return $dataProvider;
    }
}
