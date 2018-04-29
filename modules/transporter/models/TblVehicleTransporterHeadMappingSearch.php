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
            'vehicle_transporter_head_mapping_code' => $this->vehicle_transporter_head_mapping_code,
            'transporter_payment_head_code' => $this->transporter_payment_head_code,
            'wef_date' => $this->wef_date,
            'amount' => $this->amount,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'vehicle_code', $this->vehicle_code])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
