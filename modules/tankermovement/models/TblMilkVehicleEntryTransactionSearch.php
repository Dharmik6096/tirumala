<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblMilkVehicleEntryTransaction;

/**
 * TblMilkVehicleEntryTransactionSearch represents the model behind the search form about `app\modules\tankermovement\models\TblMilkVehicleEntryTransaction`.
 */
class TblMilkVehicleEntryTransactionSearch extends TblMilkVehicleEntryTransaction {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_vehicle_entry_transaction_code', 'milk_vehicle_entry_code', 'vehicle_entry_chamber_date', 'grn_no', 'chamber_no', 'challan_no', 'source_org_code', 'source_org_type', 'destination_code', 'destination_type', 'entry_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['chamber_quantity', 'fat', 'snf', 'clr', 'water', 'density', 'protein', 'lactose', 'freezing_point', 'mbrt', 'temp', 'acidity'], 'safe'],
            [['milk_quality_type_code', 'milk_type_code', 'originating_type'], 'safe'],
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

        $query = TblMilkVehicleEntryTransaction::find();

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
        $query->andWhere(['milk_vehicle_entry_code' => $this->milk_vehicle_entry_code]);

        return $dataProvider;
    }

}
