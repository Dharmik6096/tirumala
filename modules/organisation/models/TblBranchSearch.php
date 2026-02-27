<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblBranch;

/**
 * TblBranchSearch represents the model behind the search form about `app\modules\organisation\models\TblBranch`.
 */
class TblBranchSearch extends TblBranch {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['branch_code', 'address', 'branch_name', 'district_code', 'created_at', 'ifsc', 'pincode', 'updated_at', 'bank_code', 'created_by', 'sub_district_code', 'updated_by', 'village_code', 'valid_from', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['is_active'], 'integer'],
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
        $query = TblBranch::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['branch_name' => SORT_ASC]],
        ]);

        $this->load($params);
        $query->andFilterwhere(['bank_code' => $this->bank_code]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_branch.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_branch.branch_code', $this->branch_code])
                ->andFilterWhere(['like', 'branch_name', $this->branch_name])
                ->andFilterWhere(['like', 'ifsc', $this->ifsc]);

        return $dataProvider;
    }

}
