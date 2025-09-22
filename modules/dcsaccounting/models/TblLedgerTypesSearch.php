<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblLedgerTypes;

/**
 * TblLedgerTypesSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblLedgerTypes`.
 */
class TblLedgerTypesSearch extends TblLedgerTypes {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['ledger_type_code', 'balance_sheet', 'profit_loss', 'is_active', 'originating_type', 'union_code', 'ledger_type_name', 'originating_org_code', 'originating_org_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblLedgerTypes::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);
        $query->joinWith(['unionCode']);



        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_ledger_types.ledger_type_code' => $this->ledger_type_code,
            'tbl_ledger_types.balance_sheet' => $this->balance_sheet,
            'tbl_ledger_types.profit_loss' => $this->profit_loss,
            'tbl_ledger_types.is_active' => $this->is_active
        ]);

        $query->andFilterWhere(['like', 'tbl_ledger_types.ledger_type_name', $this->ledger_type_name])
                ->andFilterWhere(['like', 'tbl_unions.union_name', $this->union_code]);


        return $dataProvider;
    }

}
