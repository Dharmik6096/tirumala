<?php

namespace app\modules\transporter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transporter\models\TblVehicleKmInfo;

/**
 * TblVehicleKmInfoSearch represents the model behind the search form about `app\modules\transportation\models\TblVehicleKmInfo`.
 */
class TblVehicleKmInfoSearch extends TblVehicleKmInfo {

    public $from_date, $to_date, $from_shift, $to_shift;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['km_info_code', 'vehicle_code', 'route_code', 'transporter_code', 'wef_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'safe'],
                [['morning_kms', 'evening_kms', 'extra_kms', 'total_kms'], 'number'],
                [['is_active'], 'integer'],
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
    public function search($params, $groupBy = true) {
        $query = TblVehicleKmInfo::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->from_date = date('Y-m-d', strtotime('-30 days'));
        $this->to_date = date('Y-m-d');

        $query->joinWith(['routeCode']);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        Yii::$app->general->filterByOrg($query, $this);
        if (!empty($this->wef_date))
            $query->andFilterWhere(['and', ['>=', 'tbl_vehicle_km_info.wef_date', date('Y-m-d', strtotime($this->wef_date)) . ' 00:00:00.000'], ['<=', 'tbl_vehicle_km_info.wef_date', date('Y-m-d', strtotime($this->wef_date)) . ' 23:59:59.000']]);

        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'tbl_vehicle_km_info.wef_date', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
            $to_date .= ' ' . $to_shift;
            $query->andFilterWhere(['<=', 'tbl_vehicle_km_info.wef_date', $to_date]);
        }

        $query->andFilterWhere([
            'tbl_vehicle_km_info.vehicle_code' => $this->vehicle_code,
            'tbl_vehicle_km_info.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_vehicle_km_info.km_info_code', $this->km_info_code])
                ->andFilterWhere(['like', 'tbl_vehicle_km_info.route_code', $this->route_code])
                ->andFilterWhere(['like', 'tbl_vehicle_km_info.transporter_code', $this->transporter_code])
                ->andFilterWhere(['like', 'tbl_vehicle_km_info.morning_kms', $this->morning_kms])
                ->andFilterWhere(['like', 'tbl_vehicle_km_info.evening_kms', $this->evening_kms])
                ->andFilterWhere(['like', 'tbl_vehicle_km_info.extra_kms', $this->extra_kms])
                ->andFilterWhere(['like', 'tbl_vehicle_km_info.total_kms', $this->total_kms]);

        if ($groupBy) {
            $subQuery = TblVehicleKmInfo::find()->select(['vehicle_code', 'MAX(wef_date) AS wef_date'])->groupBy('vehicle_code');
            $query->innerJoin(['subQuery' => $subQuery], 'tbl_vehicle_km_info.wef_date = subQuery.wef_date AND tbl_vehicle_km_info.vehicle_code = subQuery.vehicle_code');
            $query->orderBy(['created_at' => SORT_DESC]);
        }
        return $dataProvider;
    }

}
