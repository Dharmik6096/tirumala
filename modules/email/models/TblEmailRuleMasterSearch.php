<?php

namespace app\modules\email\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\email\models\TblEmailRuleMaster;

/**
 * TblEmailRuleMasterSearch represents the model behind the search form about `app\modules\email\models\TblEmailRuleMaster`.
 */
class TblEmailRuleMasterSearch extends TblEmailRuleMaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email_rule_master_id', 'frequency', 'interval', 'no_of_email', 'is_active'], 'integer'],
            [['email', 'mobile', 'message', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'rule_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = TblEmailRuleMaster::find();

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
        $query->joinWith(['emailProcess']);
        // grid filtering conditions
        $query->andFilterWhere([
            'email_rule_master_id' => $this->email_rule_master_id,
//            'rule_id' => $this->rule_id,
            'frequency' => $this->frequency,
            'interval' => $this->interval,
            'no_of_email' => $this->no_of_email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'email_subject', $this->email_subject])
            ->andFilterWhere(['like', 'email_body', $this->email_body])
            ->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'tbl_email_process_master.process_name', $this->rule_id])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
