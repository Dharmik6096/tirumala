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

    public $from_date, $to_date, $from_shift, $to_shift;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['gate_entry_code', 'shift_code', 'responsibility_code', 'originating_type'], 'safe'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'route_code', 'transporter_code', 'vehicle_code', 'date_time_of_collection', 'define_arrival_time', 'actual_arrival_time', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
                [['grace_time', 'late_by_time', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'safe'],
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
        Yii::$app->general->filterByOrg($query, $this, 'tbl_gate_entry', 'tbl_gate_entry', 'tbl_gate_entry', 'tbl_gate_entry');

        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['and', ['>=', 'tbl_gate_entry.date_time_of_collection', date('Y-m-d', strtotime($this->date_time_of_collection)) . ' 00:00:00.000'], ['<=', 'date_time_of_collection', date('Y-m-d', strtotime($this->date_time_of_collection)) . ' 23:59:59.000']]);

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
        $from_date .= ' ' . $from_shift;
        $query->andFilterWhere(['>=', 'tbl_gate_entry.date_time_of_collection', $from_date]);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
        $to_date .= ' ' . $to_shift;
        $query->andFilterWhere(['<=', 'tbl_gate_entry.date_time_of_collection', $to_date]);


        // grid filtering conditions
        $query->andFilterWhere([
            'route_code' => $this->route_code,
        ]);

        $query->andFilterWhere(['like', 'define_arrival_time', $this->define_arrival_time])
                ->andFilterWhere(['like', 'actual_arrival_time', $this->actual_arrival_time])
                ->andFilterWhere(['like', 'grace_time', $this->grace_time])
                ->andFilterWhere(['like', 'late_by_time', $this->late_by_time]);

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
