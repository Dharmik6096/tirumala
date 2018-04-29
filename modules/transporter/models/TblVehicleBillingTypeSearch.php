<?php

namespace app\modules\transporter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transporter\models\TblVehicleBillingType;

/**
 * TblVehicleBillingTypeSearch represents the model behind the search form about `app\modules\transporter\models\TblVehicleBillingType`.
 */
class TblVehicleBillingTypeSearch extends TblVehicleBillingType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vehicle_billing_code', 'vehicle_code', 'billing_type_code'], 'integer'],
            [['wef_date', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'delete_at', 'delete_by'], 'safe'],
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
        $query = TblVehicleBillingType::find();

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
            'vehicle_billing_code' => $this->vehicle_billing_code,
            'vehicle_code' => $this->vehicle_code,
            'billing_type_code' => $this->billing_type_code,
            'wef_date' => $this->wef_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'delete_at' => $this->delete_at,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'delete_by', $this->delete_by]);

        return $dataProvider;
    }
}
