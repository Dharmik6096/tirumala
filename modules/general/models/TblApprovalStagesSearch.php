<?php

namespace app\modules\general\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\general\models\TblApprovalStages;

/**
 * TblApprovalStagesSearch represents the model behind the search form about `app\modules\general\models\TblApprovalStages`.
 */
class TblApprovalStagesSearch extends TblApprovalStages {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['approval_stages_code', 'originating_type'], 'integer'],
            [['union_code', 'process_name', 'approval_mode', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblApprovalStages::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['processCode']);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'approval_mode' => $this->approval_mode,
        ]);

        $query->andFilterWhere(['like', 'tbl_approval_stages_process.process_desc', $this->process_name])
                ->andFilterWhere(['like', 'remarks', $this->remarks]);

        return $dataProvider;
    }

}
