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
    public $erp_process_name, $from_date, $to_date;
    public $operator_fat, $operator_snf, $operator_chamber_quantity;
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_vehicle_entry_transaction_code', 'milk_vehicle_entry_code', 'vehicle_entry_chamber_date', 'grn_no', 'chamber_no', 'challan_no', 'source_org_code', 'source_org_type', 'destination_code', 'destination_type', 'entry_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['chamber_quantity', 'fat', 'snf', 'clr', 'water', 'density', 'protein', 'lactose', 'freezing_point', 'mbrt', 'temp', 'acidity'], 'safe'],
            [['milk_quality_type_code', 'milk_type_code', 'originating_type'], 'safe'],
            [['erp_process_name'],'required',  'except' => ['view'], 'message' => 'Process Name cannot be blank.'],
            [['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],'safe'],
            [['operator_fat', 'operator_snf', 'operator_chamber_quantity'], 'safe'],
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
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andWhere(['milk_vehicle_entry_code' => $this->milk_vehicle_entry_code]);

        return $dataProvider;
    }

    public function searchMilkReceipt($params)
    {
        $query = TblMilkVehicleEntryTransaction::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $query->joinWith(['milkVehicleEntryCode']);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }
        $query->where(['and', ['<>', 'tbl_milk_vehicle_entry_transaction.status', '0'], ['is not', 'tbl_milk_vehicle_entry_transaction.status', null]]);
        
        Yii::$app->general->filterByOrg($query, $this, 'tbl_milk_vehicle_entry', 'tbl_milk_vehicle_entry', 'tbl_milk_vehicle_entry');
        
        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(tbl_milk_vehicle_entry.receipt_datetime as date)', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(tbl_milk_vehicle_entry.receipt_datetime as date)', $to_date]);
        }

        if (!empty($this->fat)) {
            $query->andFilterWhere([$this->operator_fat, 'tbl_milk_vehicle_entry_transaction.fat', $this->fat]);
        }
        if (!empty($this->snf)) {
            $query->andFilterWhere([$this->operator_snf, 'tbl_milk_vehicle_entry_transaction.snf', $this->snf]);
        }
        if (!empty($this->chamber_quantity)) {
            $query->andFilterWhere([$this->operator_chamber_quantity, 'tbl_milk_vehicle_entry_transaction.chamber_quantity', $this->chamber_quantity]);
        }

        $query->andFilterWhere(['like', 'tbl_milk_vehicle_entry_transaction.entry_type', $this->entry_type])
                ->andFilterWhere(['like', 'tbl_milk_vehicle_entry_transaction.grn_no', $this->grn_no])
                ->andFilterWhere(['like', 'tbl_milk_vehicle_entry_transaction.challan_no', $this->challan_no])
                ->andFilterWhere(['like', 'tbl_milk_vehicle_entry_transaction.milk_type_code', $this->milk_type_code])
                ->andFilterWhere(['like', 'tbl_milk_vehicle_entry_transaction.milk_quality_type_code', $this->milk_quality_type_code])
                ->andFilterWhere(['like', 'tbl_milk_vehicle_entry_transaction.chamber_no', $this->chamber_no])
                ->andFilterWhere(['like', 'tbl_milk_vehicle_entry_transaction.clr', $this->clr])
                ->andFilterWhere(['like', 'tbl_milk_vehicle_entry_transaction.density', $this->density])
                ->andFilterWhere(['like', 'tbl_milk_vehicle_entry_transaction.protein', $this->protein])
                ->andFilterWhere(['like', 'tbl_milk_vehicle_entry_transaction.lactose', $this->lactose])
                ->andFilterWhere(['like', 'tbl_milk_vehicle_entry_transaction.freezing_point', $this->freezing_point])
                ->andFilterWhere(['like', 'tbl_milk_vehicle_entry_transaction.mbrt', $this->mbrt])
                ->andFilterWhere(['like', 'tbl_milk_vehicle_entry_transaction.acidity', $this->acidity])
                ->andFilterWhere(['like', 'tbl_milk_vehicle_entry_transaction.mbrt', $this->mbrt]);
        return $dataProvider;
    }

}
