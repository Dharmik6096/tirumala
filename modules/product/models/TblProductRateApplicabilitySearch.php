<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProductRateApplicability;

/**
 * TblProductRateApplicabilitySearch represents the model behind the search form about `app\modules\product\models\TblProductRateApplicability`.
 */
class TblProductRateApplicabilitySearch extends TblProductRateApplicability
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_rate_applicability_code', 'wef_date', 'product_rate_code', 'dcs_code', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = TblProductRateApplicability::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['dcsCode']);
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
//            'wef_date' => $this->wef_date,           
        ]);
        
        if(!empty($this->wef_date))
            $query->andFilterWhere(['like', 'wef_date', date('Y-m-d', strtotime($this->wef_date))]);

        $query->andFilterWhere(['like', 'product_rate_applicability_code', $this->product_rate_applicability_code])
            ->andFilterWhere(['like', 'product_rate_code', $this->product_rate_code])
            ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_code])
            ->andFilterWhere(['like', 'union_code', $this->union_code]);
           

        return $dataProvider;
    }
}
