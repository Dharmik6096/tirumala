<?php

namespace app\modules\organisation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\organisation\models\TblRouteMapping;

/**
 * TblRouteMappingSearch represents the model behind the search form about `app\modules\organisation\models\TblRouteMapping`.
 */
class TblRouteMappingSearch extends TblRouteMapping {

    public $unit;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['route_code', 'morning_start_time', 'morning_end_time', 'route_name', 'union_code', 'local_name', 'evening_start_time', 'evening_end_time', 'route_type', 'from_type', 'from_dest', 'to_type', 'to_dest', 'created_at', 'created_by', 'updated_at', 'updated_by', 'unit', 'valid_from'], 'safe'],
            [['capacity', 'vehicle_type_code', 'is_active'], 'integer'],
            [['route_length_kms'], 'number'],
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
        $query = TblRouteMapping::find();
        $query->distinct('tbl_route_mapping.route_code');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode']);
        Yii::$app->general->filterByOrg($query, $this);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->capacity)) {
            $query->joinWith(['capacity0']);
            $query->andFilterWhere([
                'tbl_capacity.value' => $this->capacity
            ]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            //'capacity' => $this->capacity,
            'tbl_route_mapping.route_length_kms' => $this->route_length_kms,
            'tbl_route_mapping.vehicle_type_code' => $this->vehicle_type_code,
            'tbl_route_mapping.tbl_route_mapping.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_route_mapping.route_code', $this->route_code])
                ->andFilterWhere(['like', 'tbl_route_mapping.morning_start_time', $this->morning_start_time])
                ->andFilterWhere(['like', 'tbl_route_mapping.morning_end_time', $this->morning_end_time])
                ->andFilterWhere(['like', 'tbl_route_mapping.route_name', $this->route_name])
                ->andFilterWhere(['like', 'tbl_route_mapping.local_name', $this->local_name])
                ->andFilterWhere(['like', 'tbl_route_mapping.evening_start_time', $this->evening_start_time])
                ->andFilterWhere(['like', 'tbl_route_mapping.evening_end_time', $this->evening_end_time])
                ->andFilterWhere(['like', 'tbl_route_mapping.route_type', $this->route_type])
                ->andFilterWhere(['like', 'tbl_route_mapping.from_type', $this->from_type])
                ->andFilterWhere(['like', 'tbl_route_mapping.from_dest', $this->from_dest])
                ->andFilterWhere(['like', 'tbl_route_mapping.to_type', $this->to_type])
                ->andFilterWhere(['like', 'tbl_route_mapping.to_dest', $this->to_dest]);

        return $dataProvider;
    }

}
