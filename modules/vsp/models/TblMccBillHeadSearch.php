<?php

namespace app\modules\vsp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\vsp\models\TblMccBillHead;

/**
 * TblMccBillHeadSearch represents the model behind the search form about `app\modules\vsp\models\TblMccBillHead`.
 */
class TblMccBillHeadSearch extends TblMccBillHead {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['mcc_bill_head_code', 'bill_head_name', 'union_code', 'general_formula_code', 'general_formula', 'general_formula_comma', 'bill_head_for', 'payment_cycle_type', 'calculation_based_on', 'originating_org_code', 'originating_org_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'default_bill_head_code', 'milk_type_code'], 'safe'],
            [['is_disburse_allowed', 'bill_head_type', 'is_default', 'is_active', 'sequence_no', 'has_slab', 'is_hold', 'originating_type'], 'integer'],
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
        $query = TblMccBillHead::find();
//        $query->andWhere(['!=', 'tbl_mcc_bill_head.is_default', 1]);
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
        $query->joinWith(['defaultBillHeadCode']);
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_mcc_bill_head.bill_head_for' => $this->bill_head_for,
            'tbl_mcc_bill_head.calculation_based_on' => $this->calculation_based_on,
            'tbl_mcc_bill_head.is_hold' => $this->is_hold,
            'tbl_mcc_bill_head.has_slab' => $this->has_slab,
            'tbl_mcc_bill_head.mcc_bill_head_code' => $this->mcc_bill_head_code,
        ]);

        $query->andFilterWhere(['like', 'tbl_mcc_bill_head.bill_head_name', $this->bill_head_name])
                ->andFilterWhere(['like', 'tbl_mcc_bill_head.union_code', $this->union_code])
                ->andFilterWhere(['like', 'tbl_mcc_bill_head.bill_head_type', $this->bill_head_type])
                ->andFilterWhere(['like', 'tbl_mcc_bill_head.general_formula_code', $this->general_formula_code])
                ->andFilterWhere(['like', 'tbl_mcc_bill_head_default.default_bill_head_name', $this->default_bill_head_code])
                ->andFilterWhere(['like', 'tbl_mcc_bill_head.general_formula', $this->general_formula])
                ->andFilterWhere(['like', 'tbl_mcc_bill_head.sequence_no', $this->sequence_no])
                ->andFilterWhere(['like', 'tbl_mcc_bill_head.payment_cycle_type', $this->payment_cycle_type]);

        return $dataProvider;
    }

}
