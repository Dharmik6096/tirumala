<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblTankerRateBased;

/**
 * TblTankerRateBasedSearch represents the model behind the search form about `app\modules\tankermovement\models\TblTankerRateBased`.
 */
class TblTankerRateBasedSearch extends TblTankerRateBased {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['rate_based_code', 'value', 'fat_rate', 'snf_rate', 'milk_quality_type_code', 'milk_type_code', 'created_at', 'updated_at', 'created_by', 'tanker_rate_code', 'updated_by'], 'safe'],
//            [['end_range', 'fixed_point', 'value', 'kg_rate', 'start_range'], 'number'],
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
        $query = TblTankerRateBased::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['milkTypeCode', 'milkQualityTypeCode']);

        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'tbl_animal_type.animal_type_name', $this->milk_type_code])
                ->andFilterWhere(['like', 'tbl_milk_quality_type.milk_quality_type_name', $this->milk_quality_type_code])
                ->andFilterWhere(['like', 'tbl_tanker_rate_based.tanker_rate_code', $this->tanker_rate_code]);

        return $dataProvider;
    }
}
