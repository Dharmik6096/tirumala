<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblSampleMilkCollection;

/**
 * TblSampleMilkCollectionSearch represents the model behind the search form about `app\modules\collection\models\TblSampleMilkCollection`.
 */
class TblSampleMilkCollectionSearch extends TblSampleMilkCollection {

    public $from_date, $to_date, $from_shift, $to_shift;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['from_date', 'to_date', 'from_shift', 'to_shift', 'sample_milk_collection_code', 'fat', 'snf', 'clr', 'water', 'protein', 'density', 'lactose', 'qty', 'converted_qty', 'rtpl', 'amount', 'shift_code', 'milk_type_code', 'milk_quality_type_code', 'qty_mode', 'converted_qty_mode', 'purchase_rate_code', 'qlty_auto', 'qty_auto', 'milk_analyser_type_code', 'ws_code', 'originating_type', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'route_code', 'date_time_of_collection', 'qlty_time', 'qty_time', 'source_of_milk', 'remarks', 'version_no', 'own_bmc_code', 'own_mcc_plant_code', 'device_lat', 'device_long', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblSampleMilkCollection::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['unionCode', 'plantCode', 'mccPlantCode', 'bmcCode', 'dcsCode', 'routeCode', 'shiftCode', 'milkTypeCode', 'milkQualityCode']);

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d', strtotime('-10 days'));
        $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
        $from_date .= ' ' . $from_shift;
        $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
        $to_date .= ' ' . $to_shift;
        $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_sample_milk_collection', 'tbl_sample_milk_collection', 'tbl_sample_milk_collection', 'tbl_sample_milk_collection');


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->date_time_of_collection)) {
            $query->andFilterWhere(['CAST(date_time_of_collection as date)' => date('Y-m-d', strtotime($this->date_time_of_collection))]);
        }
        if (!empty($this->qlty_time)) {
            $query->andFilterWhere(['CAST(qlty_time as date)' => date('Y-m-d', strtotime($this->qlty_time))]);
        }
        if (!empty($this->qty_time)) {
            $query->andFilterWhere(['CAST(qty_time as date)' => date('Y-m-d', strtotime($this->qty_time))]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_shift.id' => $this->shift_code,
            'milk_type_code' => $this->milk_type_code,
            'milk_quality_type_code' => $this->milk_quality_type_code,
            'qty_mode' => $this->qty_mode,
            'qlty_auto' => $this->qlty_auto,
            'qty_auto' => $this->qty_auto,
        ]);

        $query->andFilterWhere(['like', 'remarks', $this->remarks])
                ->andFilterWhere(['like', 'version_no', $this->version_no])
                ->andFilterWhere(['like', 'device_lat', $this->device_lat])
                ->andFilterWhere(['like', 'device_long', $this->device_long])
                ->andFilterWhere(['like', 'fat', $this->fat])
                ->andFilterWhere(['like', 'snf', $this->snf])
                ->andFilterWhere(['like', 'clr', $this->clr])
                ->andFilterWhere(['like', 'qty', $this->qty])
                ->andFilterWhere(['like', 'converted_qty', $this->converted_qty])
                ->andFilterWhere(['like', 'rtpl', $this->rtpl])
                ->andFilterWhere(['like', 'amount', $this->amount])
                ->andFilterWhere(['like', 'purchase_rate_code', $this->purchase_rate_code]);

        return $dataProvider;
    }

}
