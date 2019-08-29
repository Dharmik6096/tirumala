<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblDcsMilkDispatch;

/**
 * TblDcsMilkDispatchSearch represents the model behind the search form about `app\modules\collection\models\TblDcsMilkDispatch`.
 */
class TblDcsMilkDispatchSearch extends TblDcsMilkDispatch {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_milk_dispatch_code', 'shift_code', 'dispatch_type', 'destination_type', 'originating_type'], 'safe'],
            [['challan_no', 'date_time_of_dispatch', 'destination_code', 'vehicle_no', 'vehicle_in_time', 'vehicle_out_time', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'route_code', 'remarks'], 'safe'],
            [['from_date', 'to_date', 'from_shift', 'to_shift'], 'safe'],
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
        $query = TblDcsMilkDispatch::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'date_time_of_dispatch', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
            $to_date .= ' ' . $to_shift;
            $query->andFilterWhere(['<=', 'date_time_of_dispatch', $to_date]);
        }
        if (!empty($this->date_time_of_dispatch))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), date_time_of_dispatch, 126)', date('Y-m-d', strtotime($this->date_time_of_dispatch))]);


        // grid filtering conditions
        $query->andFilterWhere([
            'dcs_milk_dispatch_code' => $this->dcs_milk_dispatch_code,
            'shift_code' => $this->shift_code,
            'dispatch_type' => $this->dispatch_type,
            'destination_type' => $this->destination_type,
        ]);

        $query->andFilterWhere(['like', 'challan_no', $this->challan_no])
                ->andFilterWhere(['like', 'destination_code', $this->destination_code])
                ->andFilterWhere(['like', 'vehicle_no', $this->vehicle_no])
                ->andFilterWhere(['like', 'vehicle_in_time', $this->vehicle_in_time])
                ->andFilterWhere(['like', 'vehicle_out_time', $this->vehicle_out_time]);
        return $dataProvider;
    }

}
