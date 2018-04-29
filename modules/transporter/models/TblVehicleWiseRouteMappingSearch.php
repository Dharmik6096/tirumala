<?php

namespace app\modules\transporter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transporter\models\TblVehicleWiseRouteMapping;

/**
 * TblVehicleWiseRouteMappingSearch represents the model behind the search form about `app\modules\transporter\models\TblVehicleWiseRouteMapping`.
 */
class TblVehicleWiseRouteMappingSearch extends TblVehicleWiseRouteMapping
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_wise_route_code', 'vehicle_code', 'route_code', 'is_active'], 'integer'],
            [['wef_date'], 'safe'],
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
        $query = TblVehicleWiseRouteMapping::find();

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
            'vehicle_wise_route_code' => $this->vehicle_wise_route_code,
            'vehicle_code' => $this->vehicle_code,
            'route_code' => $this->route_code,
            'wef_date' => $this->wef_date,
            'is_active' => $this->is_active,
        ]);

        return $dataProvider;
    }
}
