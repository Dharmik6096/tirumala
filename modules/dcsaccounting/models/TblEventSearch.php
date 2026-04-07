<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblEvent;

/**
 * TblEventSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblEvent`.
 */
class TblEventSearch extends TblEvent {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['event_code', 'ledger_credit', 'ledger_debit', 'event_code_default', 'sub_ledger_credit', 'sub_ledger_debit', 'is_active', 'originating_type', 'description', 'event_name', 'originating_org_code', 'originating_org_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblEvent::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_event', 'tbl_event', 'tbl_event', 'tbl_event');


        // grid filtering conditions
        $query->andFilterWhere([
            'event_code' => $this->event_code,
            'ledger_credit' => $this->ledger_credit,
            'ledger_debit' => $this->ledger_debit,
            'event_code_default' => $this->event_code_default,
            'sub_ledger_credit' => $this->sub_ledger_credit,
            'sub_ledger_debit' => $this->sub_ledger_debit
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'event_name', $this->event_name]);

        return $dataProvider;
    }

}
