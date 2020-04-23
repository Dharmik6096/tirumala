<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblMemberPaymentSummaryAlias;

/**
 * TblMemberPaymentSummaryAliasSearch represents the model behind the search form about `app\modules\payment\models\TblMemberPaymentSummaryAlias`.
 */
class TblMemberPaymentSummaryAliasSearch extends TblMemberPaymentSummaryAlias {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['payment_sumary_alias_code', 'member_count', 'payment_cycle_code', 'payment_cycle_applicabilty_code', 'originating_type'], 'integer'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'disburse_date', 'payment_date', 'payment_status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'from_datetime', 'to_datetime', 'from_shift', 'to_shift'], 'safe'],
                [['qty', 'avg_fat', 'avg_snf', 'kg_fat', 'kg_snf', 'avg_rate', 'total_amount', 'total_deduction', 'final_amount', 'disburse_amount', 'total_addition', 'previous_hold', 'previous_due', 'hold_amount', 'net_payable'], 'number'],
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
        $query = TblMemberPaymentSummaryAlias::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode', 'paymentCycleCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_member_payment_summary_alias', 'tbl_member_payment_summary_alias', 'tbl_member_payment_summary_alias');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'payment_status' => $this->payment_status,
        ]);

        $query->andWhere(['tbl_member_payment_summary_alias.union_code' => $this->union_code])
                ->andWhere(['tbl_member_payment_summary_alias.plant_code' => $this->plant_code])
                ->andWhere(['tbl_member_payment_summary_alias.mcc_plant_code' => $this->mcc_plant_code])
                ->andWhere(['tbl_member_payment_summary_alias.bmc_code' => $this->bmc_code])
                ->andWhere(['tbl_member_payment_summary_alias.payment_cycle_code' => $this->payment_cycle_code]);

        return $dataProvider;
    }

}
