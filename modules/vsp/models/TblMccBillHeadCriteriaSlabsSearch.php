<?php

namespace app\modules\vsp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\vsp\models\TblMccBillHeadCriteriaSlabs;

/**
 * TblVspBillHeadCriteriaSlabsSearch represents the model behind the search form about `app\modules\vsp\models\TblVspBillHeadCriteriaSlabs`.
 */
class TblMccBillHeadCriteriaSlabsSearch extends TblMccBillHeadCriteriaSlabs {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['criteria_slab_code', 'criteria_code','mcc_bill_head_code', 'general_formula_code', 'formula_with_val', 'for_what', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['criteria_code', 'originating_type'], 'integer'],
            [['from_val', 'to_val'], 'number'],
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
        $query = TblMccBillHeadCriteriaSlabs::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'mcc_bill_head_code' => $this->mcc_bill_head_code,
            'from_val' => $this->from_val,
            'to_val' => $this->to_val,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'criteria_slab_code', $this->criteria_slab_code])
                ->andFilterWhere(['like', 'criteria_code', $this->criteria_code])
                ->andFilterWhere(['like', 'general_formula_code', $this->general_formula_code])
                ->andFilterWhere(['like', 'formula_with_val', $this->formula_with_val])
                ->andFilterWhere(['like', 'for_what', $this->for_what])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }

    public function createsearch($params) {
        $query = TblVspBillHeadCriteriaSlabs::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        if (!empty($this->criteria_code)) {
            $query->andFilterWhere([
                'criteria_code' => $this->criteria_code,
            ]);
        } else {
            $query->where('0=1');
        }

        return $dataProvider;
    }

}
