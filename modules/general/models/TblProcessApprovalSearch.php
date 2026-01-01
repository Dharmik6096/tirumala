<?php

namespace app\modules\general\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\general\models\TblProcessApproval;

/**
 * TblProcessApprovalSearch represents the model behind the search form about `app\modules\general\models\TblProcessApproval`.
 */
class TblProcessApprovalSearch extends TblProcessApproval {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['process_approval_code', 'level', 'status', 'originating_type'], 'integer'],
                [['process_code', 'process_name', 'approval_mode', 'level_priority', 'login_type', 'user_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'remarks', 'status_date', 'status_by', 'department'], 'safe'],
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
        $query = TblProcessApproval::find();

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
            'process_approval_code' => $this->process_approval_code,
            'level' => $this->level,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
            'process_code' => $this->process_code,
        ]);

        $query->andFilterWhere(['like', 'process_name', $this->process_name])
                ->andFilterWhere(['like', 'approval_mode', $this->approval_mode])
                ->andFilterWhere(['like', 'level_priority', $this->level_priority])
                ->andFilterWhere(['like', 'login_type', $this->login_type])
                ->andFilterWhere(['like', 'user_code', $this->user_code])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }

}
