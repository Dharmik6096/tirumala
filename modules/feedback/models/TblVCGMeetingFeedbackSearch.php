<?php

namespace app\modules\feedback\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feedback\models\TblVCGMeetingFeedback;

/**
 * TblVCGMeetingFeedbackSearch represents the model behind the search form about `app\modules\feedback\models\TblVCGMeetingFeedback`.
 */
class TblVCGMeetingFeedbackSearch extends TblVCGMeetingFeedback
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VCG_M_feedback_id', 'VCG_M_Id', 'originating_type'], 'integer'],
            [['month', 'feedback_desc', 'member_code', 'type', 'status', 'action_taken', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'flg_sentbox_entry'], 'safe'],
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
        $query = TblVCGMeetingFeedback::find();

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
            'VCG_M_feedback_id' => $this->VCG_M_feedback_id,
            'VCG_M_Id' => $this->VCG_M_Id,
            'month' => $this->month,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'feedback_desc', $this->feedback_desc])
            ->andFilterWhere(['like', 'member_code', $this->member_code])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'action_taken', $this->action_taken])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'flg_sentbox_entry', $this->flg_sentbox_entry]);

        return $dataProvider;
    }
}
