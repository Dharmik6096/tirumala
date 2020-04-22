<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblMemberPaymentSummary;
use app\modules\payment\models\TblMemberPaymentSummaryAlias;

/**
 * TblMemberPaymentSummarySearch represents the model behind the search form about `app\modules\payment\models\TblMemberPaymentSummary`.
 */
class TblMemberPaymentSummarySearch extends TblMemberPaymentSummary {

    public $from_date, $to_date, $dcs_name, $ex_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['payment_sumary_code', 'member_count', 'payment_cycle_code', 'payment_cycle_applicabilty_code'], 'integer'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'disburse_date', 'payment_date', 'payment_status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'from_date', 'to_date', 'dcs_name', 'originating_org_code', 'originating_org_type', 'originating_type', 'ex_code', 'from_datetime', 'to_datetime'], 'safe'],
                [['qty', 'avg_fat', 'avg_snf', 'kg_fat', 'kg_snf', 'avg_rate', 'total_amount', 'total_deduction', 'final_amount', 'disburse_amount', 'total_addition', 'previous_hold', 'previous_due', 'hold_amount', 'net_payable', 'additional_pay'], 'number'],
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
        $this->load($params);

        $pendingDataQuery = TblMemberPaymentSummaryAlias::find();
        $this->appendQuery($pendingDataQuery, 'tbl_member_payment_summary_alias');

        $query = TblMemberPaymentSummary::find();
        $this->appendQuery($query, 'tbl_member_payment_summary');

        $query->union($pendingDataQuery);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        return $dataProvider;
    }

    public function appendQuery($query, $tableName) {
        $query->select([$tableName . '.union_code', $tableName . '.plant_code', $tableName . '.mcc_plant_code', $tableName . '.bmc_code', $tableName . '.dcs_code', $tableName . '.payment_cycle_code', $tableName . '.payment_date', $tableName . '.member_count', $tableName . '.kg_fat', $tableName . '.kg_snf', $tableName . '.qty', $tableName . '.avg_fat', $tableName . '.avg_snf', $tableName . '.avg_rate', $tableName . '.total_amount', $tableName . '.total_addition', $tableName . '.total_deduction', $tableName . '.previous_hold', $tableName . '.previous_due', $tableName . '.net_payable', $tableName . '.hold_amount', $tableName . '.additional_pay', $tableName . '.final_amount', $tableName . '.payment_status']);
        $query->joinWith(['dcsCode']);
        Yii::$app->general->filterByOrg($query, $this, $tableName, $tableName, $tableName);

        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 'cast(' . $tableName . '.from_datetime as date)', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'cast(' . $tableName . '.from_datetime as date)', $to_date]);
        }
        $query->andFilterWhere(['=', 'CAST(' . $tableName . '.payment_date as date)', !empty($this->payment_date) ? date('Y-m-d', strtotime($this->payment_date)) : NULL]);
        // grid filtering conditions
        $query->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_name])
                ->andFilterWhere(['like', $tableName . '.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', $tableName . '.mcc_plant_code', $this->mcc_plant_code])
                ->andFilterWhere(['like', $tableName . '.bmc_code', $this->bmc_code])
                ->andFilterWhere(['like', $tableName . '.kg_fat', $this->kg_fat])
                ->andFilterWhere(['like', $tableName . '.kg_snf', $this->kg_snf])
                ->andFilterWhere(['like', $tableName . '.qty', $this->qty])
                ->andFilterWhere(['like', $tableName . '.avg_fat', $this->avg_fat])
                ->andFilterWhere(['like', $tableName . '.avg_snf', $this->avg_snf])
                ->andFilterWhere(['like', $tableName . '.avg_rate', $this->avg_rate])
                ->andFilterWhere(['like', $tableName . '.total_amount', $this->total_amount])
                ->andFilterWhere(['like', $tableName . '.total_addition', $this->total_addition])
                ->andFilterWhere(['like', $tableName . '.total_deduction', $this->total_deduction])
                ->andFilterWhere(['like', $tableName . '.previous_hold', $this->previous_hold])
                ->andFilterWhere(['like', $tableName . '.previous_due', $this->previous_due])
                ->andFilterWhere(['like', $tableName . '.net_payable', $this->net_payable])
                ->andFilterWhere(['like', $tableName . '.hold_amount', $this->hold_amount])
                ->andFilterWhere(['like', $tableName . '.additional_pay', $this->additional_pay])
                ->andFilterWhere(['like', $tableName . '.final_amount', $this->final_amount])
                ->andFilterWhere(['like', $tableName . '.payment_status', $this->payment_status])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_code_ex', $this->ex_code]);
    }

}
