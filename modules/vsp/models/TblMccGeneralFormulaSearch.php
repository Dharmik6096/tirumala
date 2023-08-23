<?php

namespace app\modules\vsp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\vsp\models\TblGeneralFormula;

/**
 * TblGeneralFormulaSearch represents the model behind the search form about `app\modules\vsp\models\TblGeneralFormula`.
 */
class TblMccGeneralFormulaSearch extends TblGeneralFormula {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['general_formula_code', 'formula', 'formula_name', 'formula_description', 'created_at', 'created_by', 'updated_at', 'updated_by', 'union_code'], 'safe'],
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
        $query = TblMccGeneralFormula::find();

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
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'general_formula_code', $this->general_formula_code])
                ->andFilterWhere(['like', 'formula', $this->formula])
                ->andFilterWhere(['like', 'formula_name', $this->formula_name])
                ->andFilterWhere(['like', 'formula_description', $this->formula_description])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'union_code', $this->union_code]);

        return $dataProvider;
    }

}
