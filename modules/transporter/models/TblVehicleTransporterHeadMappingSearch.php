<?php

namespace app\modules\transporter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transporter\models\TblVehicleTransporterHeadMapping;

/**
 * TblVehicleTransporterHeadMappingSearch represents the model behind the search form about `app\modules\transporter\models\TblVehicleTransporterHeadMapping`.
 */
class TblVehicleTransporterHeadMappingSearch extends TblVehicleTransporterHeadMapping
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_transporter_head_mapping_code', 'transporter_payment_head_code', 'is_active'], 'integer'],
            [['vehicle_code','remarks', 'wef_date', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['amount'], 'number'],
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
        $query = TblVehicleTransporterHeadMapping::find();

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
            'tbl_vehicle_transporter_head_mapping.vehicle_transporter_head_mapping_code' => $this->vehicle_transporter_head_mapping_code,
            'tbl_vehicle_transporter_head_mapping.transporter_payment_head_code' => $this->transporter_payment_head_code,
            'tbl_vehicle_transporter_head_mapping.wef_date' => $this->wef_date,
            'tbl_vehicle_transporter_head_mapping.amount' => $this->amount,
            'tbl_vehicle_transporter_head_mapping.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_vehicle_transporter_head_mapping.remarks', $this->remarks])
            ->andFilterWhere(['like', 'tbl_vehicle_transporter_head_mapping.vehicle_code', $this->vehicle_code]);

        return $dataProvider;
    }
}
