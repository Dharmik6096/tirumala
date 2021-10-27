<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblMilkCollectionCreamBaseData;
use yii\db\Expression;
use yii\db\ActiveQuery;
use yii\data\ArrayDataProvider;

/**
 * TblMilkCollectionSearch represents the model behind the search form about `app\modules\collection\models\TblMilkCollection`.
 */
class TblMilkCollectionCreamBaseDataSearch extends TblMilkCollectionCreamBaseData {

    /**
     * @inheritdoc
     */
    public $union_code;
    public $operator_fat, $operator_snf, $operator_qty, $operator_amount;
    public $from_date, $to_date, $from_shift, $to_shift, $sap_collection_type, $sap_data_post_status, $ref_code;

    public function rules() {
        return [
                [['milk_collection_code', 'sample_no', 'ack'], 'integer'],
                [['member_code', 'dcs_code', 'name', 'mobile_no', 'auto_flag', 'shift_code', 'date_time_of_collection', 'date_time_of_recieve', 'village_code', 'type_of_data_receive', 'purchase_rate_code', 'error_log', 'soc_bmc_flag', 'union_code', 'min_date', 'max_date', 'f_plant_code', 'f_mcc_code'], 'safe'],
                [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'milk_type_code', 'operator_fat', 'operator_snf', 'operator_qty', 'operator_amount', 'from_date', 'to_date', 'from_shift', 'to_shift', 'sap_collection_type', 'sap_data_post_status', 'mcc_plant_code', 'bmc_code', 'ref_code'], 'safe'],
                [['protein', 'density', 'lactose', 'incentive', 'deduction', 'total_amount', 'qty_mode', 'originating_org_type', 'originating_type'], 'safe'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'safe'],
                [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'required', 'on' => ['deleteMilkCollection']],
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
        $query = TblMilkCollectionCreamBaseData::find();
        $request = Yii::$app->request->queryParams;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['dcsCode', 'memberCode', 'milkTypeCode']);


        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
        $from_date .= ' ' . $from_shift;
        $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
        $to_date .= ' ' . $to_shift;
        $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if ($this->shift_code != 3) {
            $query->andFilterWhere(['like', 'shift_code', $this->shift_code]);
        }

        //  Yii::$app->general->filterByDropdownRange($query, $this, ['fat', 'snf', 'qty', 'amount']);
        if (!empty($this->fat)) {
            $query->andFilterWhere([$this->operator_fat, 'tbl_milk_collection_cream_base_data.fat', $this->fat]);
        }
        if (!empty($this->snf)) {
            $query->andFilterWhere([$this->operator_snf, 'tbl_milk_collection_cream_base_data.snf', $this->snf]);
        }
        if (!empty($this->qty)) {
            $query->andFilterWhere([$this->operator_qty, 'tbl_milk_collection_cream_base_data.qty', $this->qty]);
        }
        if (!empty($this->amount)) {
            $query->andFilterWhere([$this->operator_amount, 'tbl_milk_collection_cream_base_data.amount', $this->amount]);
        }


        Yii::$app->general->filterByNumber($query, $this, ['rtpl']);
        if (!empty($this->date_time_of_collection)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);
        } else {
            $query->andWhere(['CAST(date_time_of_collection as date)' => Yii::$app->formatter->asDatetime('now', 'php:Y-m-d')]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_milk_collection_cream_base_data.milk_collection_code' => $this->milk_collection_code,
            'tbl_milk_collection_cream_base_data.water' => $this->water,
            'tbl_milk_collection_cream_base_data.date_time_of_recieve' => $this->date_time_of_recieve,
            'tbl_milk_collection_cream_base_data.sample_no' => $this->sample_no,
            'tbl_milk_collection_cream_base_data.ack' => $this->ack,
            'tbl_milk_collection_cream_base_data.qty_mode' => $this->qty_mode,
        ]);
        if (isset($this->originating_type)) {
            if ($this->originating_type == 5) {
                $query->andFilterWhere(['tbl_milk_collection_cream_base_data.originating_type' => 0]);
            } elseif ($this->originating_type == 1) {
                $query->andFilterWhere(['tbl_milk_collection_cream_base_data.originating_type' => 1]);
            } elseif ($this->originating_type == 2) {
                $query->andFilterWhere(['tbl_milk_collection_cream_base_data.originating_type' => [11, 12, 21, 23]]);
            } elseif ($this->originating_type == 3) {
                $query->where('0=1');
            } elseif ($this->originating_type == 4) {
                $query->andFilterWhere(['tbl_milk_collection_cream_base_data.originating_type' => [13, 22]]);
            }
        }

        $query->andFilterWhere(['like', 'tbl_member.member_name', $this->member_code])
                ->andFilterWhere(['like', 'tbl_milk_collection_cream_base_data.name', $this->name])
                ->andFilterWhere(['like', 'tbl_milk_collection_cream_base_data.mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'tbl_milk_collection_cream_base_data.auto_flag', $this->auto_flag])
                ->andFilterWhere(['like', 'tbl_milk_collection_cream_base_data.village_code', $this->village_code])
                ->andFilterWhere(['like', 'tbl_milk_collection_cream_base_data.type_of_data_receive', $this->type_of_data_receive])
                ->andFilterWhere(['like', 'tbl_milk_collection_cream_base_data.purchase_rate_code', $this->purchase_rate_code])
                ->andFilterWhere(['like', 'tbl_milk_collection_cream_base_data.error_log', $this->error_log])
                ->andFilterWhere(['like', 'tbl_milk_collection_cream_base_data.milk_type_code', $this->milk_type_code])
                ->andFilterWhere(['like', 'tbl_milk_collection_cream_base_data.protein', $this->protein])
                ->andFilterWhere(['like', 'tbl_milk_collection_cream_base_data.density', $this->density])
                ->andFilterWhere(['like', 'tbl_milk_collection_cream_base_data.lactose', $this->lactose])
                ->andFilterWhere(['like', 'tbl_milk_collection_cream_base_data.incentive', $this->incentive])
                ->andFilterWhere(['like', 'tbl_milk_collection_cream_base_data.total_amount', $this->total_amount])
                ->andFilterWhere(['like', 'tbl_milk_collection_cream_base_data.deduction', $this->deduction])
                ->andFilterWhere(['like', 'tbl_dcs.ref_code', $this->ref_code])
                ->andFilterWhere(['like', 'tbl_milk_collection_cream_base_data.originating_org_type', $this->originating_org_type]);
        return $dataProvider;
    }

    public function deletesearch($params) {
        $this->load($params);
        $query = TblMilkCollection::find();

        // add conditions that should always apply here

        $query->andWhere([
            'tbl_milk_collection.bmc_code' => $this->bmc_code]);

        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $from_shift = \Yii::$app->general->getshift($this->from_shift);
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'tbl_milk_collection.date_time_of_collection', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $to_shift = \Yii::$app->general->getshift(strtoupper($this->to_shift));
            $to_date .= ' ' . $to_shift;
            $query->andFilterWhere(['<=', 'tbl_milk_collection.date_time_of_collection', $to_date]);
        }

        $query->andFilterWhere(['tbl_milk_collection.member_code' => $this->member_code])
                ->andFilterWhere(['tbl_milk_collection.dcs_code' => $this->dcs_code]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
        $query->andWhere(['or',
                ['tbl_milk_collection.rtpl' => 0], ['tbl_milk_collection.amount' => 0]]);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }

}
