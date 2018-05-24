<?php

namespace app\modules\transporter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transporter\models\TblVehicleKmInfo;

/**
 * TblVehicleKmInfoSearch represents the model behind the search form about `app\modules\transportation\models\TblVehicleKmInfo`.
 */
class TblVehicleKmInfoSearch extends TblVehicleKmInfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['km_info_code', 'vehicle_code', 'route_code', 'transporter_code', 'wef_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'delete_at', 'delete_by'], 'safe'],
            [['morning_kms', 'evening_kms', 'extra_kms', 'total_kms'], 'number'],
            [['is_active'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = TblVehicleKmInfo::find();

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
            'tbl_vehicle_km_info.vehicle_code' => $this->vehicle_code,
            'tbl_vehicle_km_info.wef_date' => $this->wef_date,
            'tbl_vehicle_km_info.morning_kms' => $this->morning_kms,
            'tbl_vehicle_km_info.evening_kms' => $this->evening_kms,
            'tbl_vehicle_km_info.extra_kms' => $this->extra_kms,
            'tbl_vehicle_km_info.total_kms' => $this->total_kms,
            'tbl_vehicle_km_info.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_vehicle_km_info.km_info_code', $this->km_info_code])
            ->andFilterWhere(['like', 'tbl_vehicle_km_info.route_code', $this->route_code])
            ->andFilterWhere(['like', 'tbl_vehicle_km_info.transporter_code', $this->transporter_code]);

        return $dataProvider;
    }
}
