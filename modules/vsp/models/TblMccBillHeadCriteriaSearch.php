<?php

namespace app\modules\vsp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\vsp\models\TblMccBillHeadCriteria;
//use app\modules\vsp\models\TblMccBillHeadCriteriaSlabs;

/**
 * TblVspBillHeadCriteriaSearch represents the model behind the search form about `app\modules\vsp\models\TblVspBillHeadCriteria`.
 */
class TblMccBillHeadCriteriaSearch extends TblMccBillHeadCriteria {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['criteria_code', 'criteria_name', 'general_formula_code', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['mcc_bill_head_code', 'originating_type'], 'safe'],
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
        $query = TblVspBillHeadCriteria::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith(['mccBillHead', 'generalFormula']);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_mcc_bill_head_criteria');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions

        $query->andFilterWhere(['like', 'criteria_code', $this->criteria_code])
                ->andFilterWhere(['like', 'criteria_name', $this->criteria_name])
                ->andFilterWhere(['like', 'tbl_general_formula.formula', $this->general_formula_code])
                ->andFilterWhere(['like', 'tbl_mcc_bill_head.mcc_bill_head_name', $this->mcc_bill_head_code]);

        return $dataProvider;
    }

}