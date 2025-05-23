<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblVoucherSubLedger;

/**
 * TblVoucherSubLedgerSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblVoucherSubLedger`.
 */
class TblVoucherSubLedgerSearch extends TblVoucherSubLedger {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['created_by', 'updated_by', 'sub_ledger_code', 'originating_org_code', 'originating_org_type', 'voucher_sub_ledger_code', 'narration', 'voucher_code', 'voucher_transaction_code', 'created_at', 'updated_at', 'credit_debit', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblVoucherSubLedger::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['subLedgerCode']);


        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_voucher_sub_ledger.amount' => $this->amount,
            'tbl_voucher_sub_ledger.credit_debit' => $this->credit_debit,
        ]);

        $query->andFilterWhere(['like', 'tbl_voucher_sub_ledger.voucher_sub_ledger_code', $this->voucher_sub_ledger_code])
                ->andFilterWhere(['like', 'tbl_voucher_sub_ledger.narration', $this->narration])
                ->andFilterWhere(['like', 'tbl_sub_ledgers.sub_ledger_name', $this->sub_ledger_code])
                ->andFilterWhere(['like', 'tbl_voucher_sub_ledger.voucher_code', $this->voucher_code])
                ->andFilterWhere(['like', 'tbl_voucher_sub_ledger.voucher_transaction_code', $this->voucher_transaction_code]);

        return $dataProvider;
    }

}
