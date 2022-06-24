<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblWeighBridgeData;

/**
 * TblWeighBridgeDataSearch represents the model behind the search form about `app\modules\collection\models\TblWeighBridgeData`.
 */
class TblWeighBridgeDataSearch extends TblWeighBridgeData {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uuid', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'date', 'time', 'vehicle_no', 'location_code', 'location_detail', 'material_type_code', 'gross_weight_time', 'tare_weight_time', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_type', 'originating_org_code', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['vehicle_type_code', 'type', 'originating_type'], 'safe'],
            [['gross_weight', 'tare_weight', 'weight'], 'number'],
            [['from_date', 'to_date'], 'safe']
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
        $query = TblWeighBridgeData::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['vehicleTypeCode']);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        Yii::$app->general->filterByOrg($query, $this, 'tbl_weigh_bridge_data', 'tbl_weigh_bridge_data', 'tbl_weigh_bridge_data');
        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'date', $from_date]);
        }
        if (!empty($this->to_date)) {
            $from_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'date', $from_date]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'uuid', $this->uuid])
                ->andFilterWhere(['like', 'tbl_vehicle_type.vehicle_type_name', $this->vehicle_type_code])
                ->andFilterWhere(['like', 'gross_weight', $this->gross_weight])
                ->andFilterWhere(['like', 'gross_weight_time', $this->gross_weight_time])
                ->andFilterWhere(['like', 'tare_weight', $this->tare_weight])
                ->andFilterWhere(['like', 'tare_weight_time', $this->tare_weight_time])
                ->andFilterWhere(['like', 'weight', $this->weight])
                ->andFilterWhere(['like', 'vehicle_no', $this->vehicle_no])
                ->andFilterWhere(['like', 'location_code', $this->location_code])
                ->andFilterWhere(['like', 'location_detail', $this->location_detail])
                ->andFilterWhere(['like', 'date', (!empty($this->date)) ? date('Y-m-d', strtotime($this->date)) : '']);

        return $dataProvider;
    }

}
