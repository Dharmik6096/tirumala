<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblFormulaMaster;

/**
 * TblFormulaMasterSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblFormulaMaster`.
 */
class TblFormulaMasterSearch extends TblFormulaMaster {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['formula_code', 'wef_date', 'formula_description', 'milk_type_code', 'rate_type_code', 'formula', 'created_at', 'updated_at', 'created_by', 'updated_by', 'union_code'], 'safe'],
            [['is_active'], 'integer'],
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
        $query = TblFormulaMaster::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);

        $query->joinWith(['rateType', 'milkTypeCode']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_formula.is_active' => $this->is_active,
        ]);

        if (!empty($this->wef_date))
            $query->andwhere(['wef_date' => date('Y-m-d', strtotime($this->wef_date))]);

        $query->andFilterWhere(['like', 'formula_code', $this->formula_code])
                ->andFilterWhere(['like', 'formula_description', $this->formula_description])
                ->andFilterWhere(['like', 'tbl_rate_type.rate_type', $this->rate_type_code])
                ->andFilterWhere(['like', 'tbl_animal_type.animal_type_name', $this->milk_type_code])
                ->andFilterWhere(['like', 'formula', $this->formula]);

        return $dataProvider;
    }

}
