<?php

namespace app\modules\feedback\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\feedback\models\TblMRGMeetingMaster;

/**
 * TblMRGMeetingMasterSearch represents the model behind the search form about `app\modules\feedback\models\TblMRGMeetingMaster`.
 */
class TblMRGMeetingMasterSearch extends TblMRGMeetingMaster {

    public $area_office_name, $pib_office_name;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['MRG_M_Id', 'attandance_count', 'originating_type'], 'integer'],
            [['MRG_M_code', 'MRG_date', 'from_time', 'to_time', 'm_from_date', 'm_to_date', 'pib_office_code', 'area_office_code', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'area_office_name', 'pib_office_name', 'status'], 'safe'],
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

        $query->joinWith(['pibOfficeCode']);
        $query->innerJoin('tbl_MRG_meeting_org_mapping', 'tbl_MRG_meeting_org_mapping.MRG_M_Id = tbl_mrg_meeting_master.MRG_M_Id');
        $query->innerJoin('tbl_mcc_plant as mcc', 'mcc.mcc_plant_code = tbl_MRG_meeting_org_mapping.mcc_plant_code');
        if (Yii::$app->session->get('Unions') !== '') {
            $query->andFilterWhere(['mcc.union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        }
        if (Yii::$app->session->get('Plant') !== '') {
            $query->andFilterWhere(['mcc.plant_code' => explode(',', Yii::$app->session->get('Plant'))]);
        }
        if (Yii::$app->session->get('MCC') !== '') {
            $query->andFilterWhere(['tbl_MRG_meeting_org_mapping.mcc_plant_code' => explode(',', Yii::$app->session->get('MCC'))]);
        }
        if (Yii::$app->session->get('BMC') !== '') {
            $query->andFilterWhere(['tbl_MRG_meeting_org_mapping.bmc_code' => explode(',', Yii::$app->session->get('BMC'))]);
        }
        if (Yii::$app->session->get('Dcs') !== '') {
            $query->andFilterWhere(['tbl_MRG_meeting_org_mapping.dcs_code' => explode(',', Yii::$app->session->get('Dcs'))]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_MRG_meeting_master.MRG_M_Id' => $this->MRG_M_Id,
            'tbl_MRG_meeting_master.MRG_date' => $this->MRG_date,
            'tbl_MRG_meeting_master.from_time' => $this->from_time,
            'tbl_MRG_meeting_master.to_time' => $this->to_time,
            'tbl_MRG_meeting_master.m_from_date' => $this->m_from_date,
            'tbl_MRG_meeting_master.m_to_date' => $this->m_to_date,
            'tbl_MRG_meeting_master.attandance_count' => $this->attandance_count,
        ]);

        $query->andFilterWhere(['like', 'tbl_MRG_meeting_master.MRG_M_code', $this->MRG_M_code])
                ->andFilterWhere(['like', 'tbl_MRG_meeting_master.pib_office_code', $this->pib_office_code])
                ->andFilterWhere(['like', 'tbl_MRG_meeting_master.area_office_code', $this->area_office_code])
                ->andFilterWhere(['like', 'user.name', $this->area_office_name])
                ->andFilterWhere(['like', 'user.name', $this->pib_office_name])
                ->andFilterWhere(['like', 'tbl_MRG_meeting_master.status', $this->status])
                ->andFilterWhere(['like', 'tbl_MRG_meeting_master.remarks', $this->remarks]);

        return $dataProvider;
    }

}
