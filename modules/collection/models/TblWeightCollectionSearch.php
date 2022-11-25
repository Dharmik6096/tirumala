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

    public $operator_qty, $from_date, $to_date, $from_shift, $to_shift, $ref_code, $bmc_ref_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['uuid', 'producer_flag', 'date_time_of_collection', 'shift_code', 'weight_datetime', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'route_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'device_id', 'version_no', 'vehicle_no', 'ws_code', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'route_arrival_time', 'own_mcc_plant_code', 'own_bmc_code', 'operator_qty', 'customer_code', 'customer_type', 'customer_name', 'from_date', 'to_date', 'from_shift', 'to_shift', 'ref_code', 'bmc_ref_code'], 'safe'],
            [['sample_no', 'milk_type_code', 'milk_quality_type_code', 'qty_mode', 'converted_qty_mode', 'rejected_can', 'qty_auto', 'doc_no', 'originating_type'], 'integer'],
            [['qty', 'converted_qty', 'cans', 'rejected_qty'], 'number'],
            [['sample_no', 'doc_no'], 'trim'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'from_date', 'from_shift'], 'required', 'on' => ['delete']]
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
        $query->joinWith(['dcsCode', 'shiftCode', 'milkTypeCode', 'mainCustomerCode', 'customerType', 'bmcCode', 'memberCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_weight_collection', 'tbl_weight_collection', 'tbl_weight_collection');

        if (!empty($this->qty)) {
            $query->andFilterWhere([$this->operator_qty, 'tbl_weight_collection.qty', $this->qty]);
        }
        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
        $from_date .= ' ' . $from_shift;
        $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
        $to_date .= ' ' . $to_shift;
        $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);

        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_weight_collection.date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);

        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_name', $this->customer_name], ['like', 'tbl_customer_master.customer_name', $this->customer_name], ['like', 'tbl_member.member_name', $this->customer_name]]);
        $query->andFilterWhere(['or', ['like', 'tbl_dcs.ref_code', $this->ref_code], ['like', 'tbl_customer_master.ref_code', $this->ref_code]]);


        $query->andFilterWhere([
            'tbl_weight_collection.sample_no' => $this->sample_no,
            'tbl_weight_collection.doc_no' => $this->doc_no,
            'tbl_weight_collection.qty_auto' => $this->qty_auto,
            'tbl_weight_collection.qty_mode' => $this->qty_mode,
        ]);
        $query->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_shift.shift', $this->shift_code])
                ->andFilterWhere(['like', 'tbl_animal_type.animal_type_name', $this->milk_type_code])
                ->andFilterWhere(['like', 'tbl_weight_collection.sample_no', $this->sample_no])
                ->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->customer_type])
                ->andFilterWhere(['like', 'tbl_weight_collection.customer_code', $this->customer_code])
                ->andFilterWhere(['like', 'tbl_weight_collection.vehicle_no', $this->vehicle_no])
                ->andFilterWhere(['like', 'tbl_weight_collection.cans', $this->cans])
                ->andFilterWhere(['like', 'tbl_bmc.ref_code', $this->bmc_ref_code]);

        //$query->orderBy(['tbl_weight_collection.date_time_of_collection' => SORT_DESC, 'tbl_bmc.bmc_name' => SORT_ASC, 'tbl_weight_collection.doc_no' => SORT_ASC, 'tbl_weight_collection.sample_no' => SORT_ASC]);

        return $dataProvider;
    }

    public function deletesearch($params) {
        $query = TblWeightCollection::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->join('LEFT JOIN', 'tbl_bmc_collection', 'CAST(tbl_bmc_collection.date_time_of_collection as date) = CAST(tbl_weight_collection.date_time_of_collection as date) and tbl_weight_collection.shift_code = tbl_bmc_collection.shift_code and tbl_weight_collection.mcc_plant_code = tbl_bmc_collection.mcc_plant_code and tbl_weight_collection.bmc_code = tbl_bmc_collection.bmc_code and tbl_weight_collection.sample_no = tbl_bmc_collection.sample_no and tbl_weight_collection.doc_no = tbl_bmc_collection.doc_no');
        $this->load($params);

        if (!empty($this->bmc_code)) {
            $query->andWhere(['tbl_weight_collection.bmc_code' => $this->bmc_code]);
        } else {
            $query->andWhere('0=1');
        }
        $query->joinWith(['dcsCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_weight_collection', 'tbl_weight_collection', 'tbl_weight_collection');
        $query->andFilterWhere([
            'tbl_weight_collection.customer_type' => $this->customer_type,
            'tbl_weight_collection.customer_code' => $this->customer_code,
        ]);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_weight_collection', 'tbl_weight_collection', 'tbl_weight_collection');

        if (!empty($this->from_date) && !empty($this->from_shift)) {

            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['tbl_weight_collection.date_time_of_collection' => $from_date]);
        }
        $query->andWhere(['or', ['is', 'tbl_bmc_collection.data_post_status', NULL], ['=', 'tbl_bmc_collection.data_post_status', '0']]);

        return $dataProvider;
    }

}
