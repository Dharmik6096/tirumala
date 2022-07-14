<?php

namespace app\modules\transporter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transporter\models\TblGateEntry;

/**
 * TblGateEntrySearch represents the model behind the search form about `app\modules\transporter\models\TblGateEntry`.
 */
class TblGateEntrySearch extends TblGateEntry {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['gate_entry_code', 'shift_code', 'responsibility_code', 'originating_type'], 'safe'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'route_code', 'transporter_code', 'vehicle_code', 'date_time_of_collection', 'define_arrival_time', 'actual_arrival_time', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['grace_time', 'late_by_time'], 'safe'],
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
        $query = TblGateEntry::find();

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
            'gate_entry_code' => $this->gate_entry_code,
            'date_time_of_collection' => $this->date_time_of_collection,
            'shift_code' => $this->shift_code,
            'define_arrival_time' => $this->define_arrival_time,
            'actual_arrival_time' => $this->actual_arrival_time,
            'grace_time' => $this->grace_time,
            'late_by_time' => $this->late_by_time,
            'responsibility_code' => $this->responsibility_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'plant_code', $this->plant_code])
                ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
                ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
                ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'route_code', $this->route_code])
                ->andFilterWhere(['like', 'transporter_code', $this->transporter_code])
                ->andFilterWhere(['like', 'vehicle_code', $this->vehicle_code])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
                ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type])
                ->andFilterWhere(['like', 'x_col1', $this->x_col1])
                ->andFilterWhere(['like', 'x_col2', $this->x_col2])
                ->andFilterWhere(['like', 'x_col3', $this->x_col3])
                ->andFilterWhere(['like', 'x_col4', $this->x_col4])
                ->andFilterWhere(['like', 'x_col5', $this->x_col5]);

        return $dataProvider;
    }

    public function createsearch($params) {
        $this->load($params);
        $query = $this->find()->alias('t')->select(['t.gate_entry_code', 't.bmc_code', 't.date_time_of_collection', 't.shift_code', 't.route_code', 't.vehicle_code', 't.define_arrival_time', 't.grace_time', 't.actual_arrival_time']);

        $query->joinWith(['bmcCode', 'routeCode', 'vehicleCode']);
        $query->andWhere(['t.bmc_code' => $this->bmc_code]);

        if (!empty($this->date_time_of_collection)) {
            $query->andFilterWhere(['CAST(date_time_of_collection as date)' => date('Y-m-d', strtotime($this->date_time_of_collection))]);
        }
        $query->andFilterWhere(['shift_code' => $this->shift_code]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
        return $dataProvider;
    }

}
