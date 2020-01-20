<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblWeightCollection;

/**
 * TblWeightCollectionSearch represents the model behind the search form about `app\modules\collection\models\TblWeightCollection`.
 */
class TblWeightCollectionSearch extends TblWeightCollection {

    public $operator_qty;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uuid', 'producer_flag', 'date_time_of_collection', 'shift_code', 'weight_datetime', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'route_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'device_id', 'version_no', 'vehicle_no', 'ws_code', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'route_arrival_time', 'own_mcc_plant_code', 'own_bmc_code', 'operator_qty', 'customer_code', 'customer_type', 'customer_name'], 'safe'],
            [['sample_no', 'milk_type_code', 'milk_quality_type_code', 'qty_mode', 'converted_qty_mode', 'rejected_can', 'qty_auto', 'doc_no', 'originating_type'], 'integer'],
            [['qty', 'converted_qty', 'cans', 'rejected_qty'], 'number'],
            [['sample_no', 'doc_no'], 'trim']
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
        $query = TblWeightCollection::find();
        $request = Yii::$app->request->queryParams;

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
        $query->joinWith(['dcsCode', 'shiftCode', 'milkTypeCode', 'mainCustomerCode', 'customerType']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_weight_collection','tbl_weight_collection','tbl_weight_collection');

        if (!empty($this->qty)) {
            $query->andFilterWhere([$this->operator_qty, 'tbl_weight_collection.qty', $this->qty]);
        }
        if (!empty($request['min_date']) && !empty($request['max_date'])) {
            $start_date = date('Y-m-d', strtotime($request['min_date']));
            $end_date = date('Y-m-d', strtotime($request['max_date']));
            if ($start_date != $end_date)
                $query->andFilterWhere(['between', 'CAST(tbl_weight_collection.date_time_of_collection AS DATE)', $start_date, $end_date]);
            else
                $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_weight_collection.date_time_of_collection, 126)', $start_date]);
        }
        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_weight_collection.date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);

        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_name', $this->customer_name], ['like', 'tbl_customer_master.customer_name', $this->customer_name]]);


        $query->andFilterWhere([
            'tbl_weight_collection.sample_no' => $this->sample_no,
            'tbl_weight_collection.doc_no' => $this->doc_no,
        ]);
        $query->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_shift.shift', $this->shift_code])
                ->andFilterWhere(['like', 'tbl_animal_type.animal_type_name', $this->milk_type_code])
                ->andFilterWhere(['like', 'tbl_weight_collection.sample_no', $this->sample_no])
                ->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->customer_type])
                ->andFilterWhere(['like', 'tbl_weight_collection.customer_code', $this->customer_code]);


        return $dataProvider;
    }

}
