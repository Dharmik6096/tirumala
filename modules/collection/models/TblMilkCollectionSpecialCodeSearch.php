<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblMilkCollectionSpecialCode;

/**
 * TblMilkCollectionSpecialCodeSearch represents the model behind the search form about `app\modules\collection\models\TblMilkCollectionSpecialCode`.
 */
class TblMilkCollectionSpecialCodeSearch extends TblMilkCollectionSpecialCode {

    public $operator_fat, $operator_snf, $operator_qty, $from_date, $to_date, $from_shift, $to_shift, $ref_code, $dcs_name;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['milk_collection_special_code', 'milk_type_code', 'shift_code', 'sample_no', 'qty_mode', 'milk_quality_type_code', 'qlty_auto', 'qty_auto', 'converted_qty_mode', 'dcs_payment_cycle_code', 'milk_analyser_type_code', 'ws_code', 'is_sms_sent', 'is_antibiotic', 'originating_type', 'fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'clr', 'converted_qty', 'protein', 'density', 'lactose', 'incentive', 'deduction', 'total_amount', 'adt_value', 'member_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'date_time_of_collection', 'purchase_rate_code', 'qlty_time', 'qty_time', 'route_code', 'version_no', 'own_bmc_code', 'own_mcc_plant_code', 'adt_param', 'antibiotic', 'other_reading', 'received_timestamp', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'operator_qty', 'operator_fat', 'operator_snf', 'from_date', 'to_date', 'from_shift', 'to_shift', 'ref_code', 'dcs_name'], 'safe'],
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
        $this->load($params);

        $query = TblMilkCollectionSpecialCode::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
        $from_date .= ' ' . $from_shift;
        $query->andFilterWhere(['>=', 'tbl_milk_collection_special_code.date_time_of_collection', $from_date]);


        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
        $to_date .= ' ' . $to_shift;
        $query->andFilterWhere(['<=', 'tbl_milk_collection_special_code.date_time_of_collection', $to_date]);

        $query->joinWith(['dcsCode', 'memberCode']);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_milk_collection_special_code', 'tbl_milk_collection_special_code', 'tbl_milk_collection_special_code');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->fat)) {
            $query->andFilterWhere([$this->operator_fat, 'tbl_milk_collection_special_code.fat', $this->fat]);
        }
        if (!empty($this->snf)) {
            $query->andFilterWhere([$this->operator_snf, 'tbl_milk_collection_special_code.snf', $this->snf]);
        }
        if (!empty($this->qty)) {
            $query->andFilterWhere([$this->operator_qty, 'tbl_milk_collection_special_code.qty', $this->qty]);
        }
        if (!empty($this->amount)) {
            $query->andFilterWhere([$this->operator_amount, 'tbl_milk_collection_special_code.amount', $this->amount]);
        }

        Yii::$app->general->filterByNumber($query, $this, ['rtpl']);
        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_milk_collection_special_code.smilk_collection_special_code' => $this->milk_collection_special_code,
            'tbl_milk_collection_special_code.water' => $this->water,
            'tbl_milk_collection_special_code.sample_no' => $this->sample_no,
            'tbl_milk_collection_special_code.qty_mode' => $this->qty_mode,
        ]);

        if (isset($this->originating_type)) {
            if ($this->originating_type == 5) {
                $query->andFilterWhere(['tbl_milk_collection_special_code.originating_type' => 0]);
            } elseif ($this->originating_type == 1) {
                $query->andFilterWhere(['tbl_milk_collection_special_code.originating_type' => 1]);
            } elseif ($this->originating_type == 2) {
                $query->andFilterWhere(['tbl_milk_collection_special_code.originating_type' => [11, 12, 21, 23]]);
            } elseif ($this->originating_type == 3) {
                $query->where('0=1');
            } elseif ($this->originating_type == 4) {
                $query->andFilterWhere(['tbl_milk_collection_special_code.originating_type' => [13, 22]]);
            }
        }

        $query->andFilterWhere(['like', 'tbl_member.member_name', $this->member_code])
                ->andFilterWhere(['like', 'tbl_milk_collection_special_code.purchase_rate_code', $this->purchase_rate_code])
                ->andFilterWhere(['like', 'tbl_milk_collection_special_code.x_col5', $this->x_col5])
                ->andFilterWhere(['like', 'tbl_milk_collection_special_code.milk_type_code', $this->milk_type_code])
                ->andFilterWhere(['like', 'tbl_milk_collection_special_code.protein', $this->protein])
                ->andFilterWhere(['like', 'tbl_milk_collection_special_code.density', $this->density])
                ->andFilterWhere(['like', 'tbl_milk_collection_special_code.lactose', $this->lactose])
                ->andFilterWhere(['like', 'tbl_milk_collection_special_code.incentive', $this->incentive])
                ->andFilterWhere(['like', 'tbl_milk_collection_special_code.total_amount', $this->total_amount])
                ->andFilterWhere(['like', 'tbl_milk_collection_special_code.deduction', $this->deduction])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_name])
                ->andFilterWhere(['like', 'tbl_dcs.ref_code', $this->ref_code])
                ->andFilterWhere(['like', 'tbl_milk_collection_special_code.originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }

}
