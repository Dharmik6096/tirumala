<?php

namespace app\modules\transporter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transporter\models\TblRouteWiseLateArrival;

/**
 * TblRouteWiseLateArrivalSearch represents the model behind the search form about `app\modules\transporter\models\TblRouteWiseLateArrival`.
 */
class TblRouteWiseLateArrivalSearch extends TblRouteWiseLateArrival {

    public $from_date, $to_date, $from_shift, $to_shift;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['arrival_code', 'shift_code', 'responsibility', 'originating_type'], 'integer'],
            [['route_code', 'transporter_code', 'vehicle_code', 'date_time_of_collection', 'define_arrival_time', 'actual_arrival_time', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['grace_time', 'late_by_time'], 'number'],
            [['from_date', 'to_date', 'from_shift', 'to_shift', 'vts_arrival_time', 'vts_late_by_time'], 'safe']
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
        $query = TblRouteWiseLateArrival::find();

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
        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $from_shift = \Yii::$app->general->getshift($this->from_shift);
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $to_shift = \Yii::$app->general->getshift($this->to_shift);
            $to_date .= ' ' . $to_shift;
            $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'arrival_code' => $this->arrival_code,
            'shift_code' => $this->shift_code,
//            'define_arrival_time' => $this->define_arrival_time,
//            'actual_arrival_time' => $this->actual_arrival_time,
//            'grace_time' => $this->grace_time,
//            'late_by_time' => $this->late_by_time,
            'responsibility' => $this->responsibility,
        ]);

        $query->andFilterWhere(['like', 'route_code', $this->route_code])
                ->andFilterWhere(['like', 'transporter_code', $this->transporter_code])
                ->andFilterWhere(['like', 'vehicle_code', $this->vehicle_code])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'define_arrival_time', $this->define_arrival_time])
                ->andFilterWhere(['like', 'actual_arrival_time', $this->actual_arrival_time])
                ->andFilterWhere(['like', 'grace_time', $this->grace_time])
                ->andFilterWhere(['like', 'late_by_time', $this->late_by_time])
                ->andFilterWhere(['like', 'date_time_of_collection', ($this->date_time_of_collection == '') ? '' : Yii::$app->formatter->asDate($this->date_time_of_collection, 'php:Y-m-d')])
                ->andFilterWhere(['like', 'vts_arrival_time', $this->vts_arrival_time])
                ->andFilterWhere(['like', 'vts_late_by_time', $this->vts_late_by_time]);

        return $dataProvider;
    }

}
