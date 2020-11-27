<?php

namespace app\modules\transporter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transporter\models\TblVehicleTransporterHeadMapping;

/**
 * TblVehicleTransporterHeadMappingSearch represents the model behind the search form about `app\modules\transporter\models\TblVehicleTransporterHeadMapping`.
 */
class TblVehicleTransporterHeadMappingSearch extends TblVehicleTransporterHeadMapping {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['vehicle_transporter_head_mapping_code', 'transporter_payment_head_code', 'is_active'], 'safe'],
            [['vehicle_code', 'remarks', 'wef_date', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['amount', 'transporter_code', 'billing_type', 'route_code', 'from_date', 'to_date'], 'safe'],
            [['amount'], 'number'],
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
        $query = TblVehicleTransporterHeadMapping::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['routeCode']);
        Yii::$app->general->filterByOrg($query, $this);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(wef_date as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(wef_date as date)', $to_date]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_vehicle_transporter_head_mapping.transporter_payment_head_code' => $this->transporter_payment_head_code,
            'tbl_vehicle_transporter_head_mapping.vehicle_code' => $this->vehicle_code,
            'tbl_vehicle_transporter_head_mapping.transporter_code' => $this->transporter_code,
            'tbl_vehicle_transporter_head_mapping.wef_date' => !empty($this->wef_date) ? date('Y-m-d', strtotime($this->wef_date)) : NULL,
            'tbl_vehicle_transporter_head_mapping.amount' => $this->amount,
            'tbl_vehicle_transporter_head_mapping.is_active' => $this->is_active,
            'tbl_vehicle_transporter_head_mapping.billing_type' => $this->billing_type,
        ]);
        $query->andFilterWhere(['like', 'tbl_route_mapping.route_name', $this->route_code]);
        $query->orderBy('wef_date DESC');


        return $dataProvider;
    }

}
