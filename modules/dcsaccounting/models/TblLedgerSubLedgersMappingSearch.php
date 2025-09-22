<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblLedgerSubLedgersMapping;

/**
 * TblLedgerSubLedgersMappingSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblLedgerSubLedgersMapping`.
 */
class TblLedgerSubLedgersMappingSearch extends TblLedgerSubLedgersMapping {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'ledger_code', 'created_by', 'updated_by', 'sub_ledger_code', 'originating_org_code', 'originating_org_type', 'ledger_sub_ledgers_mapping_code', 'created_at', 'updated_at', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblLedgerSubLedgersMapping::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere(['like', 'tbl_ledger_sub_ledgers_mapping.ledger_code', $this->ledger_code])
                ->andFilterWhere(['like', 'tbl_ledger_sub_ledgers_mapping.sub_ledger_code', $this->sub_ledger_code]);

        return $dataProvider;
    }

}
