<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblMemberBillCriteria;

/**
 * TblMemberBillCriteriaSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblMemberBillCriteria`.
 */
class TblMemberBillCriteriaSearch extends TblMemberBillCriteria {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'formula_code', 'created_by', 'updated_by', 'criteria', 'formula', 'bill_criteria_code', 'originating_org_code', 'originating_org_type', 'is_active', 'originating_type', 'created_at', 'updated_at', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblMemberBillCriteria::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_member_bill_criteria', 'tbl_member_bill_criteria', 'tbl_member_bill_criteria', 'tbl_member_bill_criteria');


        // grid filtering conditions
        $query->andFilterWhere([
            'is_active' => $this->is_active
        ]);

        $query->andFilterWhere(['like', 'bill_criteria_code', $this->bill_criteria_code])
                ->andFilterWhere(['like', 'criteria', $this->criteria])
                ->andFilterWhere(['like', 'formula', $this->formula])
                ->andFilterWhere(['like', 'formula_code', $this->formula_code]);

        return $dataProvider;
    }

}
