<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblGrnTxn;

/**
 * TblGrnTxnSearch represents the model behind the search form about `app\modules\product\models\TblGrnTxn`.
 */
class TblGrnTxnSearch extends TblGrnTxn {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['grn_txn_code', 'grn_code', 'product_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'union_code'], 'safe'],
            [['unit_code', 'originating_type'], 'integer'],
            [['rate', 'received_qty', 'rejected_qty', 'basic_amount', 'tax', 'gross_amount'], 'number'],
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
        $query = TblGrnTxn::find();

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
            'grn_code' => $this->grn_code,
            'received_qty' => $this->received_qty,
            'rejected_qty' => $this->rejected_qty,
            'basic_amount' => $this->basic_amount,
            'tax' => $this->tax,
            'gross_amount' => $this->gross_amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'grn_txn_code', $this->grn_txn_code])
                ->andFilterWhere(['like', 'grn_code', $this->grn_code])
                ->andFilterWhere(['like', 'product_code', $this->product_code])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type])
                ->andFilterWhere(['like', 'x_col1', $this->x_col1])
                ->andFilterWhere(['like', 'x_col2', $this->x_col2])
                ->andFilterWhere(['like', 'x_col3', $this->x_col3])
                ->andFilterWhere(['like', 'x_col4', $this->x_col4])
                ->andFilterWhere(['like', 'x_col5', $this->x_col5]);

        return $dataProvider;
    }

    public function createsearch($params) {
        $query = TblGrnTxn::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andWhere([
            'grn_code' => $this->grn_code,
        ]);



        return $dataProvider;
    }

}
