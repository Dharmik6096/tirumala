<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblMilkVehicleEntryTransaction;
use yii\db\Expression;

/**
 * TblMilkVehicleEntryTransactionSearch represents the model behind the search form about `app\modules\tankermovement\models\TblMilkVehicleEntryTransaction`.
 */
class TblMilkVehicleEntryTransactionSearch extends TblMilkVehicleEntryTransaction {
    public $erp_process_name, $from_date, $to_date;
    public $operator_fat, $operator_snf, $operator_chamber_quantity, $receipt_datetime, $trip_code, $receipt_at_code, $dispatch_from_code;
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
            [['operator_fat', 'operator_snf', 'operator_chamber_quantity', 'trip_code', 'receipt_at_code', 'dispatch_from_code'], 'safe'],
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
        $vehicleEntryModel = new TblMilkVehicleEntrySearch();
        $query = TblMilkVehicleEntryTransaction::find()->alias('t');

        $query->select([
            'MAX(t.milk_vehicle_entry_code) as milk_vehicle_entry_code',
            'MAX(mve.receipt_at) as receipt_at',
            'MAX(mve.receipt_at_code) as receipt_at_code',
            'MAX(mve.dispatch_from) as dispatch_from',
            'MAX(mve.dispatch_from_code) as dispatch_from_code',
            'MAX(mve.receipt_datetime) as receipt_datetime',
            't.source_org_code',
            't.source_org_type',
            'mve.trip_code',
            'SUM(t.chamber_quantity) AS chamber_quantity',
            'AVG(t.fat) AS fat',
            'AVG(t.snf) AS snf',
            'AVG(t.clr) AS clr',
            'MAX(mve.receipt_datetime) AS receipt_datetime',
            'MAX(t.entry_type) AS entry_type',
            'MAX(t.grn_no) AS grn_no',
            'MAX(t.milk_quality_type_code) AS milk_quality_type_code',
            new Expression("STRING_AGG(t.milk_type_code, ', ') AS milk_type_code"),
            new Expression("STRING_AGG(t.challan_no, ', ') AS challan_no"),
            new Expression("STRING_AGG(t.chamber_no, ', ') AS chamber_no"),
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
            'sort' => false 
        ]);

        $this->load($params);
        $query->innerJoin('tbl_milk_vehicle_entry as mve','mve.milk_vehicle_entry_code = t.milk_vehicle_entry_code');
        $query->joinWith(['milkVehicleEntryCode.plantCodeDest as pdest','milkVehicleEntryCode.plantCodeSource as psource', 'milkVehicleEntryCode.bmcCodeDest as bdest', 'milkVehicleEntryCode.bmcCodeSource as bsource', 'milkVehicleEntryCode.partyMasterCodeDest as pardest', 'milkVehicleEntryCode.partyMasterCodeSource as parsource']);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }
        
        $query->where(['and',
            ['<>', 't.status', '0'],
            ['is not', 't.status', null]
        ]);

        Yii::$app->general->filterByOrg($query, $this, 'mve', 'mve');

        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 'cast(mve.receipt_datetime as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'cast(mve.receipt_datetime as date)', $to_date]);
        }

        if (!empty($this->fat)) {
            $query->andFilterWhere([$this->operator_fat, 't.fat', $this->fat]);
        }

        if (!empty($this->snf)) {
            $query->andFilterWhere([$this->operator_snf, 't.snf', $this->snf]);
        }

        if (!empty($this->chamber_quantity)) {
            $query->andFilterWhere([$this->operator_chamber_quantity, 't.chamber_quantity', $this->chamber_quantity]);
        }

        $query->andFilterWhere([
            't.entry_type' => $this->entry_type
        ]);

        $query->andFilterWhere(['or',
            ['like', 'pdest.sap_vendor_code', $this->receipt_at_code],
            ['like', 'bdest.sap_vendor_code', $this->receipt_at_code],
            ['like', 'pardest.sap_vendor_code', $this->receipt_at_code],
        ]);

        $query->andFilterWhere(['or',
            ['like', 'psource.sap_vendor_code', $this->dispatch_from_code],
            ['like', 'bsource.sap_vendor_code', $this->dispatch_from_code],
            ['like', 'parsource.sap_vendor_code', $this->dispatch_from_code],
        ]);

        $query->andFilterWhere(['like', 'mve.trip_code', $this->trip_code])
                ->andFilterWhere(['like', 't.grn_no', $this->grn_no])
                ->andFilterWhere(['like', 't.chamber_no', $this->chamber_no]);

        $query->groupBy(['t.source_org_code', 't.source_org_type', 'mve.trip_code']);
        return $dataProvider;
    }


    public function searchMilkReceiptView()
    {
        $query = TblMilkVehicleEntryTransaction::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }
        
        $query->where(['milk_vehicle_entry_code' => $this->milk_vehicle_entry_code, 'source_org_code' => $this->source_org_code, 'source_org_type' => $this->source_org_type]);

        return $dataProvider;
    }

}
