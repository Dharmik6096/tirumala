<?php

namespace app\modules\feedback\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feedback\models\TblVCGMeetingMaster;

/**
 * TblVCGMeetingMasterSearch represents the model behind the search form about `app\modules\feedback\models\TblVCGMeetingMaster`.
 */
class TblVCGMeetingMasterSearch extends TblVCGMeetingMaster
{
    public $from_date, $to_date;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['VCG_M_Id', 'attandance_count', 'attachment_code', 'originating_type'], 'integer'],
            [['VCG_M_code', 'VCG_date', 'from_time', 'to_time', 'mcc_plant_code', 'bmc_code', 'route_code', 'dcs_code', 'route_supervisor_code', 'pib_office_code', 'status', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'from_date', 'to_date'], 'safe'],
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
        $query = TblVCGMeetingMaster::find();

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

        $query->joinWith(['mccPlantCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_mcc_plant', 'tbl_mcc_plant');

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $query->andFilterWhere(['>=', 'cast(tbl_VCG_meeting_master.VCG_date as date)', $from_date]);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $query->andFilterWhere(['<=', 'cast(tbl_VCG_meeting_master.VCG_date as date)', $to_date]);

        // grid filtering conditions
        $query->andFilterWhere([
            'VCG_M_Id' => $this->VCG_M_Id,
            'VCG_date' => $this->VCG_date,
            'from_time' => $this->from_time,
            'to_time' => $this->to_time,
            'attandance_count' => $this->attandance_count,
            'attachment_code' => $this->attachment_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'VCG_M_code', $this->VCG_M_code])
            ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
            ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
            ->andFilterWhere(['like', 'route_code', $this->route_code])
            ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'route_supervisor_code', $this->route_supervisor_code])
            ->andFilterWhere(['like', 'pib_office_code', $this->pib_office_code])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }
}
