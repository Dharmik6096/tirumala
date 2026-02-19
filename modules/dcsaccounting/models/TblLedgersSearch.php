<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblLedgers;

/**
 * TblLedgersSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblLedgers`.
 */
class TblLedgersSearch extends TblLedgers {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['has_sub_ledger', 'ledger_group_code', 'is_active', 'originating_type', 'ledger_code', 'ledger_name', 'originating_org_code', 'originating_org_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblLedgers::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->andFilterWhere(['tbl_ledgers.union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        $query->joinWith(['ledgerGroupCode']);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_ledgers.has_sub_ledger' => $this->has_sub_ledger,
            'tbl_ledgers.is_active' => $this->is_active
        ]);

        $query->andFilterWhere(['like', 'tbl_ledgers.ledger_code', $this->ledger_code])
                ->andFilterWhere(['like', 'tbl_ledger_groups.ledger_group_name', $this->ledger_group_code])
                ->andFilterWhere(['like', 'tbl_ledgers.ledger_name', $this->ledger_name]);

        return $dataProvider;
    }

}
