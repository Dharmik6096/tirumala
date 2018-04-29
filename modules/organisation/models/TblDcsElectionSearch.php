<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblDcsElection;

/**
 * TblDcsElectionSearch represents the model behind the search form about `app\modules\organisation\models\TblDcsElection`.
 */
class TblDcsElectionSearch extends TblDcsElection {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['election_id', 'is_active', 'is_delete'], 'integer'],
            [['dcs_code', 'election_date', 'tenure_from', 'tenure_to', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = TblDcsElection::find()->where(['is_delete' => 0]);

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
            'election_id' => $this->election_id,
            'election_date' => $this->election_date,
            'tenure_from' => $this->tenure_from,
            'tenure_to' => $this->tenure_to,
            'is_active' => $this->is_active,
            'is_delete' => $this->is_delete,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'remarks', $this->remarks])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }

}
