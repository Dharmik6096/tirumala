<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblProductSaleDetails;

/**
 * TblProductSaleDetailsSearch represents the model behind the search form about `app\modules\payment\models\TblProductSaleDetails`.
 */
class TblProductSaleDetailsSearch extends TblProductSaleDetails {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_sale_transaction_code'], 'integer'],
                [['product_sale_code', 'product_sale_rate_applicability_code', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
                [['rate', 'quantity', 'amount', 'product_code'], 'safe'],
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
        $query = TblProductSaleDetails::find();

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
        $query->joinWith(['productCode']);

        if (isset($params['id'])) {
            $query->andWhere([
                'product_sale_code' => $params['id'],
            ]);
        }
//        Yii::$app->general->filterByNumber($query, $this, ['rate', 'quantity', 'amount']);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_product_sale_transaction.product_sale_transaction_code' => $this->product_sale_transaction_code,
            'tbl_product_sale_transaction.rate' => $this->rate,
            'tbl_product_sale_transaction.quantity' => $this->quantity,
            'tbl_product_sale_transaction.amount' => $this->amount,
        ]);

        $query->andFilterWhere(['like', 'tbl_product_sale_transaction.product_sale_code', $this->product_sale_code])
                ->andFilterWhere(['like', 'tbl_product_sale_transaction.product_sale_rate_applicability_code', $this->product_sale_rate_applicability_code])
                ->andFilterWhere(['like', 'tbl_product.product_name', $this->product_code]);

        return $dataProvider;
    }

}
