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
            [['bill_head_code', 'bill_head_name', 'created_at', 'created_by', 'updated_at', 'updated_by', 'union_code', 'general_formula_code'], 'safe'],
            [['is_default', 'is_active', 'is_disburse_allowed', 'bill_head_type'], 'integer'],
            [['originating_org_code', 'originating_org_type', 'originating_type', 'default_bill_head_code', 'general_formula', 'sequence_no'], 'safe']
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
        $query->joinWith(['defaultBillHeadCode']);
        // grid filtering conditions
        $query->andFilterWhere([
            'is_default' => $this->is_default,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_disburse_allowed' => $this->is_disburse_allowed,
//            'bill_head_type' => $this->bill_head_type,
        ]);

        $query->andFilterWhere(['like', 'bill_head_code', $this->bill_head_code])
                ->andFilterWhere(['like', 'bill_head_name', $this->bill_head_name])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'tbl_bill_head.bill_head_type', $this->bill_head_type])
                ->andFilterWhere(['like', 'general_formula_code', $this->general_formula_code])
                ->andFilterWhere(['like', 'tbl_bill_head_default.default_bill_head_name', $this->default_bill_head_code])
                ->andFilterWhere(['like', 'general_formula', $this->general_formula])
                ->andFilterWhere(['like', 'sequence_no', $this->sequence_no]);

        return $dataProvider;
    }

}
