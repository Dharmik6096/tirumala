<?php

namespace app\modules\vsp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\vsp\models\TblBillHead;

/**
 * TblBillHeadSearch represents the model behind the search form about `app\modules\vsp\models\TblBillHead`.
 */
class TblBillHeadSearch extends TblBillHead {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bill_head_code', 'bill_head_name', 'created_at', 'created_by', 'updated_at', 'updated_by', 'union_code', 'general_formula_code', 'milk_type_code', 'ledger_code', 'has_sub_ledger', 'credit_debit'], 'safe'],
                [['is_default', 'is_active', 'is_disburse_allowed', 'bill_head_type'], 'integer'],
                [['originating_org_code', 'originating_org_type', 'originating_type', 'default_bill_head_code', 'general_formula', 'sequence_no', 'bill_head_for'], 'safe'],
                [['plant_code', 'mcc_plant_code', 'bmc_code', 'customer_type', 'payment_cycle_code', 'has_slab', 'calculation_based_on', 'is_hold', 'payment_cycle_type'], 'safe'],
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
        $query = TblBillHead::find();
//        $query->andWhere(['!=', 'tbl_bill_head.is_default', 1]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith(['defaultBillHeadCode', 'milkTypeCode']);
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_bill_head.bill_head_for' => $this->bill_head_for,
            'tbl_bill_head.calculation_based_on' => $this->calculation_based_on,
            'tbl_bill_head.is_hold' => $this->is_hold,
            'tbl_bill_head.has_slab' => $this->has_slab,
        ]);

        $query->andFilterWhere(['like', 'tbl_bill_head.bill_head_code', $this->bill_head_code])
                ->andFilterWhere(['like', 'tbl_bill_head.bill_head_name', $this->bill_head_name])
                ->andFilterWhere(['like', 'tbl_bill_head.union_code', $this->union_code])
                ->andFilterWhere(['like', 'tbl_bill_head.bill_head_type', $this->bill_head_type])
                ->andFilterWhere(['like', 'tbl_bill_head.general_formula_code', $this->general_formula_code])
                ->andFilterWhere(['like', 'tbl_bill_head_default.default_bill_head_name', $this->default_bill_head_code])
                ->andFilterWhere(['like', 'tbl_bill_head.general_formula', $this->general_formula])
                ->andFilterWhere(['like', 'tbl_bill_head.sequence_no', $this->sequence_no])
                ->andFilterWhere(['like', 'tbl_bill_head.payment_cycle_type', $this->payment_cycle_type])


        ;

        return $dataProvider;
    }

    public function mappingSearch($params) {
        $query = TblBillHead::find()->select(['tbl_bill_head.union_code', 'tbl_bill_head.bill_head_code', 'tbl_bill_head.bill_head_name', 'm.ledger_code', 'm.has_sub_ledger', 'm.credit_debit'])
                ->leftJoin('tbl_ledger_mapping_bill_head m', 'm.bill_head_code = tbl_bill_head.bill_head_code');

        $dataProvider = new ActiveDataProvider([
            'query' => $query->asArray(),
            'pagination' => false,
        ]);

        $this->load($params);

        Yii::$app->general->filterByOrg($query, $this);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andWhere([
            'tbl_bill_head.bill_head_for' => 'MEMBER',
        ]);

        return $dataProvider;
    }

}
