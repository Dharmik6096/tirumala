<?php

namespace app\modules\vsp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\vsp\models\TblTransitRecovery;

/**
 * TblTransitRecoverySearch represents the model behind the search form about `app\modules\vsp\models\TblTransitRecovery`.
 */
class TblTransitRecoverySearch extends TblTransitRecovery {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'union_code', 'wef_date', 'rate', 'plus_rate', 'ltr_conversion_rate', 'spilt_day', 'from_date', 'to_date', 'milk_type_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'transit_recovery_code', 'payment_cycle_code', 'originating_type'], 'safe'],
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
        $query = TblTransitRecovery::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith(['dcsCode']);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->from_date)) {
            $query->andFilterWhere(['CAST(from_date as date)' => date('Y-m-d', strtotime($this->from_date))]);
        }
        if (!empty($this->to_date)) {
            $query->andFilterWhere(['CAST(to_date as date)' => date('Y-m-d', strtotime($this->to_date))]);
        }
        if (!empty($this->wef_date)) {
            $query->andFilterWhere(['CAST(wef_date as date)' => date('Y-m-d', strtotime($this->wef_date))]);
        }
        // grid filtering conditions
        $query->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_code])
                ->andFilterWhere(['like', 'payment_cycle_code', $this->payment_cycle_code])
                ->andFilterWhere(['like', 'rate', $this->rate])
                ->andFilterWhere(['like', 'plus_rate', $this->plus_rate])
                ->andFilterWhere(['like', 'ltr_conversion_rate', $this->ltr_conversion_rate])
                ->andFilterWhere(['like', 'spilt_day', $this->spilt_day]);

        return $dataProvider;
    }

}
