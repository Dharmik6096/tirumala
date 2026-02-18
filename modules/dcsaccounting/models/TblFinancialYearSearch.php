<?php

namespace app\modules\dcsaccounting\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblFinancialYear;

/**
 * TblFinancialYearSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblFinancialYear`.
 */
class TblFinancialYearSearch extends TblFinancialYear {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['code', 'created_at', 'created_by', 'ending_date', 'is_active', 'starting_date', 'updated_at', 'updated_by'], 'safe'],
            [['is_active'], 'boolean'],
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
        $query = TblFinancialYear::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['ending_date' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->starting_date)) {
            $query->andFilterWhere(['=', 'starting_date', date('Y-m-d', strtotime($this->starting_date))]);
        }

        if (!empty($this->ending_date)) {
            $query->andFilterWhere(['=', 'ending_date', date('Y-m-d', strtotime($this->ending_date))]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }

}
