<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblLedgerMappingBillHead;
use app\modules\vsp\models\TblBillHead;

/**
 * TblLedgerMappingBillHeadSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblLedgerMappingBillHead`.
 */
class TblLedgerMappingBillHeadSearch extends TblLedgerMappingBillHead {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['type', 'has_sub_ledger', 'credit_debit', 'originating_type', 'ledger_mapping_bill_head_code', 'ledger_code', 'bill_head_code', 'bill_criteria_code', 'originating_org_code', 'originating_org_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblLedgerMappingBillHead::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

//        Yii::$app->general->filterByOrg($query, $this, 'tbl_ledger_mapping_bill_head', 'tbl_ledger_mapping_bill_head', 'tbl_ledger_mapping_bill_head', 'tbl_ledger_mapping_bill_head');
        $unions = Yii::$app->session->get('Unions');
        if (!empty($unions)) {
            $query->andFilterWhere(['tbl_ledger_mapping_bill_head.union_code' => explode(',', $unions)]);
        }
        
        $query->joinWith(['ledgerCode', 'billHeadCode', 'billCriteriaCode']);


        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_ledger_mapping_bill_head.type' => $this->type,
            'tbl_ledger_mapping_bill_head.has_sub_ledger' => $this->has_sub_ledger,
            'tbl_ledger_mapping_bill_head.credit_debit' => $this->credit_debit
        ]);

        $query->andFilterWhere(['like', 'tbl_ledger_mapping_bill_head.ledger_mapping_bill_head_code', $this->ledger_mapping_bill_head_code])
                ->andFilterWhere(['like', 'tbl_ledgers.ledger_name', $this->ledger_code])
                ->andFilterWhere(['like', 'tbl_member_bill_head.bill_head_name', $this->bill_head_code])
                ->andFilterWhere(['like', 'tbl_member_bill_criteria.criteria', $this->bill_criteria_code]);

        return $dataProvider;
    }

    public function mappingSearch($params) {
        $query = TblBillHead::find()->alias('bh')->select(['bh.bill_head_code', 'bh.bill_head_name', 'bh.union_code', 'lmbh.ledger_code', 'lmbh.has_sub_ledger', 'lmbh.credit_debit'])
                ->leftJoin(['lmbh' => 'tbl_ledger_mapping_bill_head'], 'lmbh.bill_head_code = bh.bill_head_code')
                ->where(['bh.bill_head_for' => 'MEMBER']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->load($params);

        $unions = Yii::$app->session->get('Unions');
        if (!empty($unions)) {
            $query->andFilterWhere(['bh.union_code' => explode(',', $unions)]);
        }

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

}
