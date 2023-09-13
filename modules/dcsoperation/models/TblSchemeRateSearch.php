<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblSchemeRate;

/**
 * TblSchemeRateSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblSchemeRate`.
 */
class TblSchemeRateSearch extends TblSchemeRate {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['scheme_rate_code', 'from_shift', 'to_shift', 'is_mcc_wise_rate', 'originating_type'], 'integer'],
                [['from_date', 'to_date', 'rate_class', 'description', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'is_active'], 'safe'],
                [['rtpl'], 'number'],
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
        $query = TblSchemeRate::find();

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
        if (!empty($this->from_date)) {
            $query->andFilterWhere(['cast(from_date as date)' => date('Y-m-d', strtotime($this->from_date))]);
        }
        if (!empty($this->to_date)) {
            $query->andFilterWhere(['cast(from_date as date)' => date('Y-m-d', strtotime($this->to_date))]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'scheme_rate_code' => $this->scheme_rate_code,
            'from_shift' => $this->from_shift,
            'to_shift' => $this->to_shift,
            'rtpl' => $this->rtpl,
            'is_mcc_wise_rate' => $this->is_mcc_wise_rate,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'rate_class', $this->rate_class])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }

}
