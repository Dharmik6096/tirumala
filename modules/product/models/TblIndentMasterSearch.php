<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblIndentMaster;

/**
 * TblIndentMasterSearch represents the model behind the search form about `app\modules\product\models\TblIndentMaster`.
 */
class TblIndentMasterSearch extends TblIndentMaster {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['indent_code', 'customer_type', 'customer_code', 'member_code', 'dcs_code', 'bmc_code', 'mcc_plant_code', 'plant_code', 'union_code', 'indent_date', 'product_code', 'status', 'status_date', 'status_by', 'status_remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['qty'], 'number'],
            [['originating_type'], 'integer'],
            [['from_date', 'to_date'], 'safe'],
           
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
        $query = TblIndentMaster::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith(['memberCode', 'productCode']);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        Yii::$app->general->filterByOrg($query, $this, 'tbl_indent_master', 'tbl_indent_master', 'tbl_indent_master');

        // grid filtering conditions
        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 'indent_date', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'indent_date', $to_date]);
        }

        if (!empty($this->indent_date)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), indent_date, 126)', date('Y-m-d', strtotime($this->indent_date))]);
        }

        $query->andFilterWhere(['like', 'indent_code', $this->indent_code])
                ->andFilterWhere(['like', 'tbl_member.member_name', $this->member_code])
                ->andFilterWhere(['like', 'tbl_product.product_name', $this->product_code])
                ->andFilterWhere(['like', 'qty', $this->qty])
                ->andFilterWhere(['like', 'status', $this->status])
                ->andFilterWhere(['like', 'status_by', $this->status_by])
                ->andFilterWhere(['like', 'status_remarks', $this->status_remarks]);

        return $dataProvider;
    }

    public function createsearch($params) {
        $query = TblIndentMaster::find();

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
        $query->andWhere([
            'tbl_indent_master.bmc_code' => $this->bmc_code]);
        if (!empty($this->indent_date)) {
            $query->andFilterWhere(['CAST(indent_date as date)' => date('Y-m-d', strtotime($this->indent_date))]);
        }

        return $dataProvider;
    }

   

}
