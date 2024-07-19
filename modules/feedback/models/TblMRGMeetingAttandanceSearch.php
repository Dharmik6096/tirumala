<?php

namespace app\modules\feedback\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feedback\models\TblMRGMeetingAttandance;

/**
 * TblMRGMeetingAttandanceSearch represents the model behind the search form about `app\modules\feedback\models\TblMRGMeetingAttandance`.
 */
class TblMRGMeetingAttandanceSearch extends TblMRGMeetingAttandance
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MRG_M_attandance_id', 'MRG_M_Id', 'is_present', 'attachment_code', 'originating_type'], 'integer'],
            [['mcc_plant_code', 'bmc_code', 'route_code', 'dcs_code', 'member_code', 'reason', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblMRGMeetingAttandance::find();

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
            'MRG_M_attandance_id' => $this->MRG_M_attandance_id,
            'MRG_M_Id' => $this->MRG_M_Id,
            'is_present' => $this->is_present,
            'attachment_code' => $this->attachment_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
            ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
            ->andFilterWhere(['like', 'route_code', $this->route_code])
            ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'member_code', $this->member_code])
            ->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }
}
