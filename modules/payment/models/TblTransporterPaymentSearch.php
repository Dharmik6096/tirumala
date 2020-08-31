<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblTransporterPayment;

/**
 * TblTransporterPaymentSearch represents the model behind the search form about `app\modules\payment\models\TblTransporterPayment`.
 */
class TblTransporterPaymentSearch extends TblTransporterPayment {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['transporter_payment_code', 'transporter_type', 'is_verified'], 'integer'],
            [['union_code', 'transporter_code', 'from_date', 'to_date', 'adjust_remark', 'bank_name', 'bank_code', 'branch_name', 'branch_code', 'ifsc', 'bank_account_no', 'payment_date', 'status', 'disburse_date', 'utr_no', 'reference_no', 'route_code', 'process_date', 'reject_reason', 'bank_status', 'payment_transaction_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'bill_no', 'fixed_rent', 'fuel_consumption', 'vehicle_average', 'fuel_rate', 'vehicle_code', 'fixed_amount', 'route_name'], 'safe'],
            [['coll_qty', 'coll_kg_fat', 'coll_kg_snf', 'disp_qty', 'disp_kg_fat', 'disp_kg_snf', 'rec_qty', 'rec_kg_fat', 'rec_kg_snf', 'cd_qty_diff', 'cd_kg_fat_diff', 'cd_kg_snf_diff', 'rd_qty_diff', 'rd_kg_fat_diff', 'rd_kg_snf_diff', 'no_of_days', 'total_kms', 'avg_rate', 'total_qty', 'total_amount', 'total_deduction', 'total_addition', 'net_amount', 'previous_hold', 'previous_due', 'hold_amount', 'adjust_amount', 'final_amount', 'disburse_amount'], 'number'],
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
        $query = TblTransporterPayment::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['transporterCode', 'routeCode', 'vehicleCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_transporter_payment', 'tbl_transporter_payment', 'tbl_transporter_payment');
        if (!empty($this->from_date) && !empty($this->to_date)) {
            $query->andWhere(['or',
                ['between', 'tbl_transporter_payment.from_date', date('Y-m-d', strtotime($this->from_date)), date('Y-m-d', strtotime($this->to_date))],
                ['between', 'tbl_transporter_payment.to_date', date('Y-m-d', strtotime($this->from_date)), date('Y-m-d', strtotime($this->to_date))]]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_transporter_payment.transporter_type' => $this->transporter_type,
        ]);

        $query->andFilterWhere(['like', 'tbl_transporter.transporter_name', $this->transporter_code])
                ->andFilterWhere(['like', 'tbl_transporter_payment.adjust_remark', $this->adjust_remark])
                ->andFilterWhere(['like', 'tbl_transporter_payment.status', $this->status])
                ->andFilterWhere(['like', 'tbl_route_mapping.ref_code', $this->route_code])
                ->andFilterWhere(['like', 'tbl_route_mapping.route_name', $this->route_name])
                ->andFilterWhere(['like', 'tbl_transporter_payment.payment_date', ($this->payment_date == '') ? '' : Yii::$app->formatter->asDate($this->payment_date, 'php:Y-m-d')])
                ->andFilterWhere(['like', 'tbl_transporter_payment.no_of_days', $this->no_of_days])
                ->andFilterWhere(['like', 'tbl_transporter_payment.total_kms', $this->total_kms])
                ->andFilterWhere(['like', 'tbl_transporter_payment.total_amount', $this->total_amount])
                ->andFilterWhere(['like', 'tbl_transporter_payment.total_deduction', $this->total_deduction])
                ->andFilterWhere(['like', 'tbl_transporter_payment.total_addition', $this->total_addition])
                ->andFilterWhere(['like', 'tbl_transporter_payment.net_amount', $this->net_amount])
                ->andFilterWhere(['like', 'tbl_transporter_payment.adjust_amount', $this->adjust_amount])
                ->andFilterWhere(['like', 'tbl_transporter_payment.final_amount', $this->final_amount])
                ->andFilterWhere(['like', 'tbl_transporter_payment.total_qty', $this->total_qty])
                ->andFilterWhere(['like', 'tbl_transporter_payment.rec_kg_fat', $this->rec_kg_fat])
                ->andFilterWhere(['like', 'tbl_transporter_payment.rec_kg_snf', $this->rec_kg_snf])
                ->andFilterWhere(['like', 'tbl_transporter_payment.bill_no', $this->bill_no])
                ->andFilterWhere(['like', 'tbl_vehicle_master.parsing_no', $this->vehicle_code])
                ->andFilterWhere(['like', 'tbl_transporter_payment.fixed_rent', $this->fixed_rent])
                ->andFilterWhere(['like', 'tbl_transporter_payment.fuel_consumption', $this->fuel_consumption])
                ->andFilterWhere(['like', 'tbl_transporter_payment.vehicle_average', $this->vehicle_average])
                ->andFilterWhere(['like', 'tbl_transporter_payment.fuel_rate', $this->fuel_rate])
                ->andFilterWhere(['like', 'tbl_transporter_payment.fixed_amount', $this->fixed_amount])

        ;
        return $dataProvider;
    }

}
