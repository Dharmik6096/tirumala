<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblLoanProductSaleDetails;

/**
 * TblLoanProductSaleDetailsSearch represents the model behind the search form about `app\modules\payment\models\TblLoanProductSaleDetails`.
 */
class TblLoanProductSaleDetailsSearch extends TblLoanProductSaleDetails {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['sale_detail_code', 'product_code', 'entry_type', 'send_status', 'txfarmer_id'], 'safe'],
                [['dcs_code', 'union_code', 'member_code', 'sale_date_time', 'created_at', 'created_by', 'updated_at', 'updated_by', 'response_datetime', 'picked_datetime', 'resp_desc', 'data_inserted_from', 'dcs_name', 'member_name', 'received_timestamp'], 'safe'],
                [['amount'], 'number'],
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
        $query = TblLoanProductSaleDetails::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);
        $query->joinWith(['unionCode', 'dcsCode', 'productCode', 'memberCode']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->sale_date_time)) {
            $query->andFilterWhere(['like', 'tbl_loan_product_sale_details.sale_date_time', date('Y-m-d', strtotime($this->sale_date_time))]);
        }
        if (!empty($this->response_datetime)) {
            $query->andFilterWhere(['like', 'cast(tbl_loan_product_sale_details.response_datetime as date)', date('Y-m-d', strtotime($this->response_datetime))]);
        }
        if (!empty($this->picked_datetime)) {
            $query->andFilterWhere(['like', 'cast(tbl_loan_product_sale_details.picked_datetime as date)', date('Y-m-d', strtotime($this->picked_datetime))]);
        }
        if (!empty($this->received_timestamp)) {
            $query->andFilterWhere(['like', 'tbl_loan_product_sale_details.received_timestamp', date('Y-m-d', strtotime($this->received_timestamp))]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'sale_detail_code' => $this->sale_detail_code,
            'tbl_product.product_name' => $this->product_code,
            'tbl_loan_product_sale_details.amount' => $this->amount,
            'tbl_loan_product_sale_details.entry_type' => $this->entry_type,
            'tbl_loan_product_sale_details.resp_desc' => $this->resp_desc,
            'tbl_loan_product_sale_details.send_status' => $this->send_status,
            'tbl_loan_product_sale_details.txfarmer_id' => $this->txfarmer_id,
            'tbl_dcs.dcs_name' => $this->dcs_name,
            'tbl_dcs.dcs_code' => $this->dcs_code,
            'tbl_member.member_code' => $this->member_code,
            'tbl_member.member_name' => $this->member_name,
        ]);

        return $dataProvider;
    }

}
