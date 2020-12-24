<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblCollectionPenaltyRate;

/**
 * TblCollectionPenaltyRateSearch represents the model behind the search form about `app\modules\collection\models\TblCollectionPenaltyRate`.
 */
class TblCollectionPenaltyRateSearch extends TblCollectionPenaltyRate {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['penalty_rate_code', 'penalty_type', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'wef_date'], 'safe'],
            [['penalty_rate'], 'number'],
            [['originating_type'], 'integer'],
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
        $query = TblCollectionPenaltyRate::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->wef_date))
            $query->andwhere(['wef_date' => date('Y-m-d', strtotime($this->wef_date))]);
        // grid filtering conditions

        $query->andFilterWhere(['like', 'penalty_rate', $this->penalty_rate])
                ->andFilterWhere(['like', 'penalty_type', $this->penalty_type]);

        return $dataProvider;
    }

}
