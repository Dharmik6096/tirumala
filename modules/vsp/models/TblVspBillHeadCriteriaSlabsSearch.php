<?php

namespace app\modules\vsp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\vsp\models\TblVspBillHeadCriteriaSlabs;

/**
 * TblVspBillHeadCriteriaSlabsSearch represents the model behind the search form about `app\modules\vsp\models\TblVspBillHeadCriteriaSlabs`.
 */
class TblVspBillHeadCriteriaSlabsSearch extends TblVspBillHeadCriteriaSlabs {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['vsp_slab_code', 'vsp_criteria_code', 'general_formula_code', 'formula_with_val', 'for_what', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['bill_head_code', 'originating_type'], 'integer'],
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
        $query->andFilterWhere([
            'bill_head_code' => $this->bill_head_code,
            'from_val' => $this->from_val,
            'to_val' => $this->to_val,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'vsp_slab_code', $this->vsp_slab_code])
                ->andFilterWhere(['like', 'vsp_criteria_code', $this->vsp_criteria_code])
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
        if (!empty($this->vsp_criteria_code)) {
            $query->andFilterWhere([
                'vsp_criteria_code' => $this->vsp_criteria_code,
            ]);
        } else {
            $query->where('0=1');
        }

        return $dataProvider;
    }

}
