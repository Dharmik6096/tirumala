<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblLocalMilkSaleRate;

/**
 * TblLocalMilkSaleRateSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblLocalMilkSaleRate`.
 */
class TblLocalMilkSaleRateSearch extends TblLocalMilkSaleRate {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['local_milk_sale_rate_code', 'wef_date', 'milk_type_code', 'milk_class', 'rate', 'union_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'milk_quality_type_code'], 'safe'],
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
        $query = TblLocalMilkSaleRate::find();
        $query->where(['local_milk_rate_code' => $this->local_milk_rate_code]);
        $query->orderBy(['wef_date' => SORT_DESC]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['dcsCode', 'dcsCode.unionCode', 'milkClass']);

        if (Yii::$app->session->get('Unions') !== '') {
            $query->andFilterWhere(['tbl_unions.union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        } else
            $query->andFilterWhere(['tbl_unions.union_code' => $this->union_code]);

        if (Yii::$app->session->get('Dcs') !== '') {
            $query->andFilterWhere(['tbl_local_milk_sale_rate.dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
        } else
            $query->andFilterWhere(['tbl_local_milk_sale_rate.dcs_code' => $this->dcs_code]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'rate' => $this->rate,
        ]);

        if ((!empty($this->wef_date))) {
            $wef_date = date('Y-m-d', strtotime($this->wef_date));
            $query->andFilterWhere(['like', 'CAST(wef_date AS DATE)', $wef_date]);
        }

        $query->andFilterWhere(['like', 'tbl_local_milk_sale_rate.local_milk_sale_rate_code', $this->local_milk_sale_rate_code]);

        return $dataProvider;
    }

}
