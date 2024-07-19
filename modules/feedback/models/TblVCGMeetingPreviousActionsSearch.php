<?php

namespace app\modules\feedback\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feedback\models\TblVCGMeetingPreviousActions;

/**
 * TblVCGMeetingPreviousActionsSearch represents the model behind the search form about `app\modules\feedback\models\TblVCGMeetingPreviousActions`.
 */
class TblVCGMeetingPreviousActionsSearch extends TblVCGMeetingPreviousActions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VCG_previous_actions_id', 'VCG_M_Id', 'VCG_M_feedback_id', 'VCG_M_MOM_id', 'isclose', 'originating_type'], 'integer'],
            [['description', 'type', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'flg_sentbox_entry'], 'safe'],
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
        $query = TblVCGMeetingPreviousActions::find();

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
            'VCG_previous_actions_id' => $this->VCG_previous_actions_id,
            'VCG_M_Id' => $this->VCG_M_Id,
            'VCG_M_feedback_id' => $this->VCG_M_feedback_id,
            'VCG_M_MOM_id' => $this->VCG_M_MOM_id,
            'isclose' => $this->isclose,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'flg_sentbox_entry', $this->flg_sentbox_entry]);

        return $dataProvider;
    }
}
