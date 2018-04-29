<?php

namespace app\modules\globalmaster\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\globalmaster\models\TblLedgerType;

/**
 * TblLedgerTypeSearch represents the model behind the search form about `app\models\TblLedgerType`.
 */
class TblLedgerTypeSearch extends TblLedgerType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'is_active', 'is_balance_sheet', 'is_profit_loss'], 'integer'],
            [['ledger_type_code','created_at', 'ledger_type_name', 'updated_at', 'created_by', 'updated_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = TblLedgerType::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['ledger_type_name' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['ledger_type_name'=>SORT_ASC]],
        ]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_ledger_type.is_active' => $this->is_active,
            'tbl_ledger_type.is_balance_sheet' => $this->is_balance_sheet,
            'tbl_ledger_type.is_profit_loss' => $this->is_profit_loss,
        ]);

        $query->andFilterWhere(['like', 'ledger_type_name', $this->ledger_type_name])
                ->andFilterWhere(['like', 'tbl_ledger_type.ledger_type_code', $this->ledger_type_code]);

        return $dataProvider;
    }
}
