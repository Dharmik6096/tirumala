<?php

namespace app\modules\feedback\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feedback\models\TblMRGMeetingMOM;

/**
 * TblMRGMeetingMOMSearch represents the model behind the search form about `app\modules\feedback\models\TblMRGMeetingMOM`.
 */
class TblMRGMeetingMOMSearch extends TblMRGMeetingMOM
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MRG_MOM_id', 'MRG_M_Id', 'mom_type_id', 'status', 'originating_type'], 'integer'],
            [['month', 'concern', 'dcs_code', 'discussions', 'remarks_actions', 'process_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'flg_sentbox_entry'], 'safe'],
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
        $query = TblMRGMeetingMOM::find();

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
            'MRG_MOM_id' => $this->MRG_MOM_id,
            'MRG_M_Id' => $this->MRG_M_Id,
            'month' => $this->month,
            'mom_type_id' => $this->mom_type_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'concern', $this->concern])
            ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'discussions', $this->discussions])
            ->andFilterWhere(['like', 'remarks_actions', $this->remarks_actions])
            ->andFilterWhere(['like', 'process_type', $this->process_type])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'flg_sentbox_entry', $this->flg_sentbox_entry]);

        return $dataProvider;
    }
}
