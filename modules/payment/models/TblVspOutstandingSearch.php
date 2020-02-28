<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblVspOutstanding;

/**
 * TblVspOutstandingSearch represents the model behind the search form about `app\modules\payment\models\TblVspOutstanding`.
 */
class TblVspOutstandingSearch extends TblVspOutstanding {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['vsp_outstanding_code', 'payment_cycle_code', 'from_date', 'to_date'], 'safe'],
            [['union_code', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['hold_amount', 'due_amount', 'plant_code', 'mcc_code', 'bmc_code', 'customer_type', 'customer_code', 'customer_name', 'transaction_date'], 'safe'],
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
        $query = TblVspOutstanding::find();

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
        $query->joinWith(['dcsCode', 'mainCustomerCode', 'customerType']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_vsp_outstanding', 'tbl_vsp_outstanding', 'tbl_vsp_outstanding');

        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_name', $this->customer_name], ['like', 'tbl_customer_master.customer_name', $this->customer_name]]);

        if (!empty($this->transaction_date))
            $query->andFilterWhere(['=', 'transaction_date', date('Y-m-d', strtotime($this->transaction_date))]);
        // grid filtering conditions


        if (!empty($this->from_date)) {
            $query->andFilterWhere(['>=', 'transaction_date', date('Y-m-d', strtotime($this->from_date))]);
        }

        if (!empty($this->to_date)) {
            $query->andFilterWhere(['<=', 'transaction_date', date('Y-m-d', strtotime($this->to_date))]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'hold_amount' => $this->hold_amount,
            'due_amount' => $this->due_amount,
        ]);
        $query->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->customer_type])
                ->andFilterWhere(['like', 'tbl_vsp_outstanding.customer_code', $this->customer_code]);

        return $dataProvider;
    }

}
