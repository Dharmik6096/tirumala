<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblLocalMilkSaleRate;

/**
 * TblLocalMilkSaleRateSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblLocalMilkSaleRate`.
 */
class TblLocalMilkRateSearch extends TblLocalMilkRate {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_quality_type_code', 'milk_type_code', 'milk_class', 'rate', 'union_code', 'wef_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblLocalMilkRate::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['milkClass']);


        if (Yii::$app->session->get('Unions') !== '') {
            $query->andFilterWhere(['union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        } else
            $query->andFilterWhere(['union_code' => $this->union_code]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'rate' => $this->rate,
        ]);

        $query->andFilterWhere(['like', 'tbl_local_milk_rate.local_milk_rate_code', $this->local_milk_rate_code])
                ->andFilterWhere(['like', 'tbl_local_milk_rate.milk_class', $this->milk_class])
                ->andFilterWhere(['like', 'wef_date', (!empty($this->wef_date)) ? date('Y-m-d', strtotime($this->wef_date)) : '']);

        return $dataProvider;
    }

}
