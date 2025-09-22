<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblVoucherTypeLedgerConfig;

/**
 * TblVoucherTypeLedgerConfigSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblVoucherTypeLedgerConfig`.
 */
class TblVoucherTypeLedgerConfigSearch extends TblVoucherTypeLedgerConfig {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['voucher_type_ledger_config_code', 'ledger_code', 'originating_org_code', 'originating_org_type', 'created_by', 'updated_by', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'credit_debit', 'voucher_type_code', 'originating_type', 'created_at', 'updated_at', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblVoucherTypeLedgerConfig::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_voucher_type_ledger_config', 'tbl_voucher_type_ledger_config', 'tbl_voucher_type_ledger_config', 'tbl_voucher_type_ledger_config');
        $query->joinWith(['ledgerCode', 'voucherTypeCode']);


        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_voucher_type_ledger_config.credit_debit' => $this->credit_debit
        ]);

        $query->andFilterWhere(['like', 'tbl_voucher_type_ledger_config.voucher_type_ledger_config_code', $this->voucher_type_ledger_config_code])
                ->andFilterWhere(['like', 'tbl_voucher_types.voucher_type_name', $this->voucher_type_code])
                ->andFilterWhere(['like', 'tbl_ledgers.ledger_name', $this->ledger_code]);

        return $dataProvider;
    }

}
