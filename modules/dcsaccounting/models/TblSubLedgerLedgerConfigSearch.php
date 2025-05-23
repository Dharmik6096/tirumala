<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblSubLedgerLedgerConfig;

/**
 * TblSubLedgerLedgerConfigSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblSubLedgerLedgerConfig`.
 */
class TblSubLedgerLedgerConfigSearch extends TblSubLedgerLedgerConfig {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['sub_ledger_type', 'originating_type', 'sub_ledger_ledger_config_code', 'ledger_code', 'originating_org_code', 'originating_org_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblSubLedgerLedgerConfig::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_sub_ledger_ledger_config', 'tbl_sub_ledger_ledger_config', 'tbl_sub_ledger_ledger_config', 'tbl_sub_ledger_ledger_config');
        $query->joinWith(['ledgerCode']);


        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_sub_ledger_ledger_config.sub_ledger_type' => $this->sub_ledger_type
        ]);

        $query->andFilterWhere(['like', 'tbl_sub_ledger_ledger_config.sub_ledger_ledger_config_code', $this->sub_ledger_ledger_config_code])
                ->andFilterWhere(['like', 'tbl_ledgers.ledger_name', $this->ledger_code]);

        return $dataProvider;
    }

}
