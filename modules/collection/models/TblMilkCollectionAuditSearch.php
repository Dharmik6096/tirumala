<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblMilkCollectionAudit;

/**
 * TblMilkCollectionAuditSearch represents the model behind the search form about `app\modules\collection\models\TblMilkCollectionAudit`.
 */
class TblMilkCollectionAuditSearch extends TblMilkCollectionAudit {

    /**
     * @inheritdoc
     */
    public $union_code, $operator_fat, $operator_snf, $operator_qty, $from_date, $to_date, $from_shift, $to_shift, $ref_code;

    public function rules() {
        return [
            [['milk_collection_audit_code', 'sample_no', 'ack'], 'integer'],
            [['milk_type_code', 'sample_no', 'ack', 'data_post_status', 'qty_mode', 'no_of_can', 'milk_quality_type_code', 'qlty_auto', 'qty_auto', 'is_approved', 'ftp_txn_log_id', 'originating_type', 'converted_qty_mode', 'dcs_payment_cycle_code', 'milk_analyser_type_code', 'ws_code', 'send_status', 'is_provisional', 'txfarmer_id', 'is_rate_recalc'], 'safe'],
            [['fat', 'snf', 'water', 'qty', 'rtpl', 'amount', 'clr', 'converted_qty', 'protein', 'density', 'lactose', 'incentive', 'deduction', 'total_amount', 'adt_value', 'dpu_rtpl', 'dpu_amount', 'dpu_incentive', 'dpu_deduction', 'dpu_total_amount'], 'safe'],
            [['date_time_of_collection', 'date_time_of_recieve', 'dt_date', 'sms_timestamp', 'qlty_time', 'qty_time', 'created_at', 'updated_at', 'picked_datetime', 'response_datetime', 'received_timestamp'], 'safe'],
            [['remarks', 'device_lat', 'device_long', 'mob_lat', 'mob_long'], 'safe'],
            [['member_code', 'version_no'], 'safe'],
            [['dcs_code', 'bmc_code', 'plant_code', 'mcc_plant_code'], 'safe'],
            [['name', 'operator_fat', 'operator_snf', 'operator_qty', 'from_date', 'to_date', 'from_shift', 'to_shift', 'ref_code'], 'safe'],
            [['mobile_no', 'sms_mobile', 'resp_status', 'resp_desc', 'ftp_txn_file_name', 'error_desc', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'adt_param'], 'safe'],
            [['auto_flag', 'soc_bmc_flag'], 'safe'],
            [['shift_code'], 'safe'],
            [['village_code'], 'safe'],
            [['type_of_data_receive', 'error_log', 'sms_msgid', 'data_inserted_from'], 'safe'],
            [['purchase_rate_code', 'purchase_rate_code_old'], 'safe'],
            [['sms_status', 'route_code', 'tag_1', 'tag_2', 'own_bmc_code', 'own_mcc_plant_code'], 'safe'],
            [['sms_errorlog'], 'safe'],
            [['status'], 'safe'],
            [['created_by', 'updated_by'], 'safe'],
            [['data_post_id'], 'safe'],
            [['last_edited_type', 'sync_status'], 'safe'],
            [['union_code'], 'safe'],
            [['originating_org_code', 'originating_org_type'], 'safe'],
            [['cannumber', 'is_sms_sent', 'antibiotic', 'is_antibiotic', 'scheme_rate', 'scheme_rate_code', 'actual_rate', 'x_col6'], 'safe'],
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
        $query = TblMilkCollectionAudit::find();
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
            $query->andFilterWhere([$this->operator_fat, 'tbl_milk_collection_audit.fat', $this->fat]);
        }
        if (!empty($this->snf)) {
            $query->andFilterWhere([$this->operator_snf, 'tbl_milk_collection_audit.snf', $this->snf]);
        }
        if (!empty($this->qty)) {
            $query->andFilterWhere([$this->operator_qty, 'tbl_milk_collection_audit.qty', $this->qty]);
        }


        Yii::$app->general->filterByNumber($query, $this, ['rtpl']);
        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_milk_collection_audit.milk_collection_audit_code' => $this->milk_collection_audit_code,
            'tbl_milk_collection_audit.water' => $this->water,
            'tbl_milk_collection_audit.date_time_of_recieve' => $this->date_time_of_recieve,
            'tbl_milk_collection_audit.sample_no' => $this->sample_no,
            'tbl_milk_collection_audit.ack' => $this->ack,
            'tbl_milk_collection_audit.qty_mode' => $this->qty_mode,
        ]);
        if (isset($this->originating_type)) {
            if ($this->originating_type == 5) {
                $query->andFilterWhere(['tbl_milk_collection_audit.originating_type' => 0]);
            } elseif ($this->originating_type == 1) {
                $query->andFilterWhere(['tbl_milk_collection_audit.originating_type' => 1]);
            } elseif ($this->originating_type == 2) {
                $query->andFilterWhere(['tbl_milk_collection_audit.originating_type' => [11, 12, 21, 23]]);
            } elseif ($this->originating_type == 3) {
                $query->where('0=1');
            } elseif ($this->originating_type == 4) {
                $query->andFilterWhere(['tbl_milk_collection_audit.originating_type' => [13, 22]]);
            }
        }

        $query->andFilterWhere(['like', 'tbl_member.member_name', $this->member_code])
                ->andFilterWhere(['like', 'tbl_milk_collection_audit.name', $this->name])
                ->andFilterWhere(['like', 'tbl_milk_collection_audit.mobile_no', $this->mobile_no])
                ->andFilterWhere(['like', 'tbl_milk_collection_audit.auto_flag', $this->auto_flag])
                ->andFilterWhere(['like', 'tbl_milk_collection_audit.village_code', $this->village_code])
                ->andFilterWhere(['like', 'tbl_milk_collection_audit.type_of_data_receive', $this->type_of_data_receive])
                ->andFilterWhere(['like', 'tbl_milk_collection_audit.purchase_rate_code', $this->purchase_rate_code])
                ->andFilterWhere(['like', 'tbl_milk_collection_audit.error_log', $this->error_log])
                ->andFilterWhere(['like', 'tbl_milk_collection_audit.milk_type_code', $this->milk_type_code])
                ->andFilterWhere(['like', 'tbl_milk_collection_audit.protein', $this->protein])
                ->andFilterWhere(['like', 'tbl_milk_collection_audit.density', $this->density])
                ->andFilterWhere(['like', 'tbl_milk_collection_audit.lactose', $this->lactose])
                ->andFilterWhere(['like', 'tbl_milk_collection_audit.incentive', $this->incentive])
                ->andFilterWhere(['like', 'tbl_milk_collection_audit.total_amount', $this->total_amount])
                ->andFilterWhere(['like', 'tbl_milk_collection_audit.deduction', $this->deduction])
                ->andFilterWhere(['like', 'tbl_dcs.ref_code', $this->ref_code])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_milk_collection_audit.originating_org_type', $this->originating_org_type])
                ->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_name]);
        return $dataProvider;
    }

}
