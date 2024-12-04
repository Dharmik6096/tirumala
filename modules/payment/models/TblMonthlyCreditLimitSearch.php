<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblMonthlyCreditLimit;

class TblMonthlyCreditLimitSearch extends TblMonthlyCreditLimit {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['customer_type', 'customer_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'from_date', 'to_date', 'final_amount', 'milk_amount', 'manual_amount', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'customer_name'], 'safe'],
            [['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'], 'safe'],
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
        $query = TblMonthlyCreditLimit::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);
        $query->joinWith(['dcsCode', 'mainCustomerCode', 'memberCode', 'bmcCode']);

        $this->from_date = empty($this->from_date) ? date('Y-m-01', strtotime('first day of last month')) : $this->from_date;
        $this->to_date = empty($this->to_date) ? date('Y-m-t') : $this->to_date;

        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 'CAST(tbl_monthly_credit_limit.from_date as date)', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'CAST(tbl_monthly_credit_limit.to_date as date)', $to_date]);
        }

        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_name', $this->customer_name], ['like', 'tbl_customer_master.customer_name', $this->customer_name], ['like', 'tbl_member.member_name', $this->customer_name]]);

        $query->andFilterWhere(['like', 'tbl_bmc.bmc_name', $this->bmc_code])
                ->andFilterWhere(['like', 'tbl_monthly_credit_limit.customer_type', $this->customer_type])
                ->andFilterWhere(['like', 'tbl_monthly_credit_limit.customer_code', $this->customer_code]);

        return $dataProvider;
    }

}
