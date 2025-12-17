<?php

namespace app\modules\general\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\general\models\TblApprovalStagesDetail;

/**
 * TblApprovalStagesDetailSearch represents the model behind the search form about `app\modules\general\models\TblApprovalStagesDetail`.
 */
class TblApprovalStagesDetailSearch extends TblApprovalStagesDetail {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['approval_stages_detail_code', 'approval_stages_code', 'level', 'originating_type'], 'integer'],
            [['level_priority', 'approval_mode', 'approval_type', 'login_type', 'user_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'department'], 'safe'],
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
        $query = TblApprovalStagesDetail::find();

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
            'approval_stages_detail_code' => $this->approval_stages_detail_code,
            'approval_stages_code' => $this->approval_stages_code,
            'level' => $this->level,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'level_priority', $this->level_priority])
                ->andFilterWhere(['like', 'approval_mode', $this->approval_mode])
                ->andFilterWhere(['like', 'approval_type', $this->approval_type])
                ->andFilterWhere(['like', 'login_type', $this->login_type])
                ->andFilterWhere(['like', 'user_code', $this->user_code])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }

    public function createsearch($params) {
        $query = TblApprovalStagesDetail::find();

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
        $query->andWhere([
            'tbl_approval_stages_detail.approval_stages_code' => $this->approval_stages_code]);

        return $dataProvider;
    }

}
