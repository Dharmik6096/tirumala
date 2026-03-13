<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblLedgerMappingEvent;
use app\modules\dcsaccounting\models\TblEvent;

/**
 * TblLedgerMappingEventSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblLedgerMappingEvent`.
 */
class TblLedgerMappingEventSearch extends TblLedgerMappingEvent {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['ledger_mapping_event_code', 'credit_sub_ledger', 'debit_sub_ledger', 'event_code', 'event_code_default', 'voucher_type_code', 'originating_type', 'credit_ledger_code', 'debit_ledger_code', 'originating_org_code', 'originating_org_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblLedgerMappingEvent::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        //        Yii::$app->general->filterByOrg($query, $this, 'tbl_ledger_mapping_event', 'tbl_ledger_mapping_event', 'tbl_ledger_mapping_event', 'tbl_ledger_mapping_event');
        $unions = Yii::$app->session->get('Unions');
        if (!empty($unions)) {
            $query->andFilterWhere(['tbl_ledger_mapping_event.union_code' => explode(',', $unions)]);
        }
        $query->joinWith(['creditLedgerCode as credit', 'debitLedgerCode as debit', 'eventCode', 'voucherTypeCode']);


        // grid filtering conditions


        $query->andFilterWhere(['like', 'tbl_ledger_mapping_event.ledger_mapping_event_code', $this->ledger_mapping_event_code])
                ->andFilterWhere(['like', 'credit.ledger_name', $this->credit_ledger_code])
                ->andFilterWhere(['like', 'debit.ledger_name', $this->debit_ledger_code])
                ->andFilterWhere(['like', 'tbl_ledger_mapping_event.credit_sub_ledger', $this->credit_sub_ledger])
                ->andFilterWhere(['like', 'tbl_ledger_mapping_event.debit_sub_ledger', $this->debit_sub_ledger])
                ->andFilterWhere(['like', 'tbl_event.event_name', $this->event_code])
                ->andFilterWhere(['like', 'tbl_ledger_mapping_event.event_code_default', $this->event_code_default])
                ->andFilterWhere(['like', 'tbl_voucher_types.voucher_type_name', $this->voucher_type_code]);

        return $dataProvider;
    }

    public function mappingSearch($params) {
        $query = TblEvent::find()->alias('e')->select(['e.event_code', 'e.event_code_default', 'e.event_name', 'e.union_code', 'e.ledger_credit', 'e.ledger_debit', 'e.sub_ledger_credit', 'e.sub_ledger_debit', 'lme.credit_ledger_code', 'lme.credit_sub_ledger', 'lme.debit_ledger_code', 'lme.debit_sub_ledger', 'lme.voucher_type_code'])
                ->leftJoin(['lme' => 'tbl_ledger_mapping_event'], 'lme.event_code = e.event_code');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->load($params);

        $unions = Yii::$app->session->get('Unions');
        if (!empty($unions)) {
            $query->andFilterWhere(['e.union_code' => explode(',', $unions)]);
        }

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

}
