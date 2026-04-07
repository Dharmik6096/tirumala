<?php

namespace app\modules\globalmaster\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\globalmaster\models\TblCommitteeMembers;

/**
 * TblCommitteeMembersSearch represents the model behind the search form about `app\modules\globalmaster\models\TblCommitteeMembers`.
 */
class TblCommitteeMembersSearch extends TblCommitteeMembers {
    
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['committee_member_code', 'committee_type_code', 'is_active', 'originating_type'], 'integer'],
            [['union_code', 'dcs_code', 'member_code', 'member_name', 'election_date', 'tenure_from_date', 'tenure_to_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblCommitteeMembers::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_committee_members', 'tbl_committee_members', 'tbl_committee_members');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->election_date)) {
            $query->andFilterWhere(['CAST(election_date as date)' => date('Y-m-d', strtotime($this->election_date))]);
        }
        if (!empty($this->tenure_from_date)) {
            $query->andFilterWhere(['CAST(tenure_from_date as date)' => date('Y-m-d', strtotime($this->tenure_from_date))]);
        }
        if (!empty($this->tenure_to_date)) {
            $query->andFilterWhere(['CAST(tenure_to_date as date)' => date('Y-m-d', strtotime($this->tenure_to_date))]);
        }

        $query->andFilterWhere(['tbl_committee_members.union_code' => $this->union_code])
                ->andFilterWhere(['tbl_committee_members.plant_code' => $this->plant_code])
                ->andFilterWhere(['tbl_committee_members.mcc_plant_code' => $this->mcc_plant_code])
                ->andFilterWhere(['tbl_committee_members.bmc_code' => $this->bmc_code])
                ->andFilterWhere(['tbl_committee_members.dcs_code' => $this->dcs_code])
                ->andFilterWhere(['tbl_committee_members.is_active' => $this->is_active]);

        $query->andFilterWhere(['like', 'member_code', $this->member_code])
                ->andFilterWhere(['like', 'member_name', $this->member_name]);

        return $dataProvider;
    }

}
