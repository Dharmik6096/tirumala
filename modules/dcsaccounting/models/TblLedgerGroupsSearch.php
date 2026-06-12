<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblLedgerGroups;

/**
 * TblLedgerGroupsSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblLedgerGroups`.
 */
class TblLedgerGroupsSearch extends TblLedgerGroups {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['ledger_group_code', 'ledger_type_code', 'is_active', 'originating_type', 'ledger_group_name', 'union_code', 'originating_org_code', 'originating_org_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'ref_code', 'is_cash'], 'safe'],
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
        $query = TblLedgerGroups::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        Yii::$app->general->filterByOrg($query, $this);
        $query->joinWith(['unionCode', 'ledgerTypeCode']);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_ledger_groups.ledger_group_code' => $this->ledger_group_code,
            'tbl_ledger_groups.is_active' => $this->is_active
        ]);

        $query->andFilterWhere(['like', 'tbl_ledger_groups.ledger_group_name', $this->ledger_group_name])
                ->andFilterWhere(['like', 'tbl_ledger_types.ledger_type_name', $this->ledger_type_code])
                ->andFilterWhere(['like', 'tbl_unions.union_name', $this->union_code])
                ->andFilterWhere(['like', 'tbl_ledger_groups.ref_code', $this->ref_code]);

        return $dataProvider;
    }

}
