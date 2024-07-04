<?php

namespace app\modules\feedback\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feedback\models\TblMRGMeetingMaster;

/**
 * TblMRGMeetingMasterSearch represents the model behind the search form about `app\modules\feedback\models\TblMRGMeetingMaster`.
 */
class TblMRGMeetingMasterSearch extends TblMRGMeetingMaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MRG_M_Id', 'attandance_count', 'originating_type'], 'integer'],
            [['MRG_M_code', 'MRG_date', 'from_time', 'to_time', 'm_from_date', 'm_to_date', 'pib_office_code', 'area_office_code', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblMRGMeetingMaster::find();

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
            'MRG_M_Id' => $this->MRG_M_Id,
            'MRG_date' => $this->MRG_date,
            'from_time' => $this->from_time,
            'to_time' => $this->to_time,
            'm_from_date' => $this->m_from_date,
            'm_to_date' => $this->m_to_date,
            'attandance_count' => $this->attandance_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'MRG_M_code', $this->MRG_M_code])
            ->andFilterWhere(['like', 'pib_office_code', $this->pib_office_code])
            ->andFilterWhere(['like', 'area_office_code', $this->area_office_code])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }
}
