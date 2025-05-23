<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblSubLedgerOpeningBalance;

/**
 * TblSubLedgerOpeningBalanceSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblSubLedgerOpeningBalance`.
 */
class TblSubLedgerOpeningBalanceSearch extends TblSubLedgerOpeningBalance {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['credit_debit', 'auto_manual', 'originating_type', 'created_at', 'updated_at', 'sub_ledger_opening_balance_code', 'ledger_code', 'sub_ledger_code', 'originating_org_code', 'originating_org_type', 'financial_year_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_by', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['balance'], 'number'],
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
        $query = TblSubLedgerOpeningBalance::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_sub_ledger_opening_balance', 'tbl_sub_ledger_opening_balance', 'tbl_sub_ledger_opening_balance', 'tbl_sub_ledger_opening_balance');
        $query->joinWith(['ledgerCode', 'subLedgerCode']);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_sub_ledger_opening_balance.credit_debit' => $this->credit_debit,
            'tbl_sub_ledger_opening_balance.balance' => $this->balance,
            'tbl_sub_ledger_opening_balance.auto_manual' => $this->auto_manual
        ]);

        $query->andFilterWhere(['like', 'tbl_sub_ledger_opening_balance.sub_ledger_opening_balance_code', $this->sub_ledger_opening_balance_code])
                ->andFilterWhere(['like', 'tbl_ledgers.ledger_name', $this->ledger_code])
                ->andFilterWhere(['like', 'tbl_sub_ledgers.sub_ledger_name', $this->sub_ledger_code])
                ->andFilterWhere(['like', 'tbl_sub_ledger_opening_balance.financial_year_code', $this->financial_year_code]);

        return $dataProvider;
    }

}
