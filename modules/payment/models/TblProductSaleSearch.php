<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblProductSale;

/**
 * TblProductSaleSearch represents the model behind the search form about `app\modules\payment\models\TblProductSale`.
 */
class TblProductSaleSearch extends TblProductSale
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_sale_code', 'dcs_code', 'union_code', 'member_code', 'sale_date_time', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['amount', 'other_amount', 'discount', 'paid_amount', 'amount_due'], 'number'],
            [['is_installment', 'no_of_installment'], 'integer'],
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
        $query = TblProductSale::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
            Yii::$app->general->filterByOrg($query,$this);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if($this->member_code)
        {
            $query->joinWith(['memberCode']);
            $query->andFilterWhere(['like', 'tbl_member.member_name', $this->member_code]);            
        }
        if(!empty($this->sale_date_time))
            $query->andFilterWhere(['like', 'sale_date_time', date('Y-m-d', strtotime($this->sale_date_time))]);
        // grid filtering conditions
        $query->andFilterWhere([
            //'sale_date_time' => $this->sale_date_time,
            'amount' => $this->amount,
            'other_amount' => $this->other_amount,
            'discount' => $this->discount,
            'paid_amount' => $this->paid_amount,
            'amount_due' => $this->amount_due,
            'is_installment' => $this->is_installment,
            'no_of_installment' => $this->no_of_installment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'product_sale_code', $this->product_sale_code])
            //->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            //->andFilterWhere(['like', 'union_code', $this->union_code])
            //->andFilterWhere(['like', 'member_code', $this->member_code])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
