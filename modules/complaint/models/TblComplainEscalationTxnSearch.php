<?php

namespace app\modules\complaint\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\complaint\models\TblComplainEscalationTxn;

/**
 * TblComplainEscalationTxnSearch represents the model behind the search form about `app\modules\complaint\models\TblComplainEscalationTxn`.
 */
class TblComplainEscalationTxnSearch extends TblComplainEscalationTxn {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['user_type', 'complain_escalation_txn_code', 'complain_escalation_code', 'escalation_time', 'level', 'originating_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'department'], 'safe'],
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
        $query = TblComplainEscalationTxn::find();

        // add conditions that should always apply here
        $query->orderBy(['level' => SORT_ASC]);

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
            'complain_escalation_txn_code' => $this->complain_escalation_txn_code,
            'complain_escalation_code' => $this->complain_escalation_code,
            'escalation_time' => $this->escalation_time,
            'level' => $this->level,
        ]);

        $query->andFilterWhere(['like', 'user_type', $this->user_type]);

        return $dataProvider;
    }

}
