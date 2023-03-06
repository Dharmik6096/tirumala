<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblIndentProduct;

/**
 * TblIndentProductSearch represents the model behind the search form about `app\modules\product\models\TblIndentProduct`.
 */
class TblIndentProductSearch extends TblIndentProduct {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['indent_product_code', 'union_code', 'product_code', 'is_mcc', 'is_warehouse', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['qty'], 'number'],
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
        $query = TblIndentProduct::find();

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
        Yii::$app->general->filterByOrg($query, $this, 'tbl_indent_product');
        $query->joinWith(['productCode']);

        // grid filtering conditions
        $query->andFilterWhere([
            'is_mcc' => $this->is_mcc,
            'is_warehouse' => $this->is_warehouse,
        ]);

        $query->andFilterWhere(['like', 'indent_product_code', $this->indent_product_code])
                ->andFilterWhere(['like', 'qty', $this->qty])
                ->andFilterWhere(['like', 'tbl_product.product_name', $this->product_code])
                ->andFilterWhere(['like', 'is_mcc', $this->is_mcc])
                ->andFilterWhere(['like', 'is_warehouse', $this->is_warehouse])
                ->andFilterWhere(['like', 'qty', $this->qty]);

        return $dataProvider;
    }

}
