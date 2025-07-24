<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblMilkVehicleEntryQlty;

/**
 * TblMilkVehicleEntryQltySearch represents the model behind the search form about `app\modules\tankermovement\models\TblMilkVehicleEntryQlty`.
 */
class TblMilkVehicleEntryQltySearch extends TblMilkVehicleEntryQlty {

    public $parsing_no, $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_vehicle_entry_qlty_code', 'originating_type'], 'integer'],
            [['union_code', 'plant_code', 'vehicle_code', 'arrival_datetime', 'trip_code', 'chamber_no', 'status', 'status_datetime', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'parsing_no', 'from_date', 'to_date', 'lot_datetime', 'lot_no', 'tested_by', 'verified_by'], 'safe'],
            [['fat', 'snf', 'clr', 'water', 'density', 'protein', 'lactose', 'freezing_point', 'mbrt', 'temp', 'acidity'], 'number'],
            [['trip_code'], 'required', 'on' => 'update'],
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
    public function search($params, $grid = true) {
        $query = TblMilkVehicleEntryQlty::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if ($grid) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(arrival_datetime as date)', $from_date]);

            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(arrival_datetime as date)', $to_date]);
        }

        Yii::$app->general->filterByOrg($query, $this, 'tbl_milk_vehicle_entry_qlty', 'tbl_milk_vehicle_entry_qlty');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
        if (empty($this->trip_code) && empty($this->status)) {
            $query->andWhere(['LOWER(status)' => 'pending']);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'vehicle_code' => $this->vehicle_code,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'chamber_no', $this->chamber_no])
                ->andFilterWhere(['like', 'trip_code', $this->trip_code]);

        return $dataProvider;
    }

}
