<?php

namespace app\modules\feedback\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feedback\models\TblEiplAppFeedbackMasterTxn;

/**
 * TblEiplAppFeedbackMasterTxnSearch represents the model behind the search form about `app\modules\feedback\models\TblEiplAppFeedbackMasterTxn`.
 */
class TblEiplAppFeedbackMasterTxnSearch extends TblEiplAppFeedbackMasterTxn
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Id', 'feedback_master_id'], 'integer'],
            [['feedback_message', 'feedback_message_datetime', 'name', 'originator_type', 'originator_code', 'replier_type', 'replier_code', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = TblEiplAppFeedbackMasterTxn::find();

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
            'Id' => $this->Id,
            'feedback_master_id' => $this->feedback_master_id,
            'feedback_message_datetime' => $this->feedback_message_datetime,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'feedback_message', $this->feedback_message])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'originator_type', $this->originator_type])
            ->andFilterWhere(['like', 'originator_code', $this->originator_code])
            ->andFilterWhere(['like', 'replier_type', $this->replier_type])
            ->andFilterWhere(['like', 'replier_code', $this->replier_code])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
