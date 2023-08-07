<?php

namespace app\modules\complaint\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\complaint\models\TblComplainEscalation;

/**
 * TblComplainEscalationSearch represents the model behind the search form about `app\modules\complaint\models\TblComplainEscalation`.
 */
class TblComplainEscalationSearch extends TblComplainEscalation {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['complain_escalation_code', 'is_active', 'union_code', 'escalation_name', 'escalation_remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type'], 'safe'],
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
        $query = TblComplainEscalation::find();

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
            'complain_escalation_code' => $this->complain_escalation_code,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'escalation_name', $this->escalation_name])
                ->andFilterWhere(['like', 'escalation_remarks', $this->escalation_remarks]);

        return $dataProvider;
    }

}
