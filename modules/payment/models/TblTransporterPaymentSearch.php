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
            [['union_code', 'transporter_code', 'from_date', 'to_date', 'adjust_remark', 'bank_name', 'bank_code', 'branch_name', 'branch_code', 'ifsc', 'bank_account_no', 'payment_date', 'status', 'disburse_date', 'utr_no', 'reference_no', 'route_code', 'process_date', 'reject_reason', 'bank_status', 'payment_transaction_code', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query->joinWith(['transporterCode', 'routeCode']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->from_date) && !empty($this->to_date)) {
            $query->andWhere(['or',
                ['between', 'from_date', date('Y-m-d', strtotime($this->from_date)), date('Y-m-d', strtotime($this->to_date))],
                ['between', 'to_date', date('Y-m-d', strtotime($this->from_date)), date('Y-m-d', strtotime($this->to_date))]]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'transporter_payment_code' => $this->transporter_payment_code,
            'tbl_transporter_payment.transporter_type' => $this->transporter_type,
//            'from_date' => $this->from_date,
//            'to_date' => $this->to_date,
            'coll_qty' => $this->coll_qty,
            'coll_kg_fat' => $this->coll_kg_fat,
            'coll_kg_snf' => $this->coll_kg_snf,
            'disp_qty' => $this->disp_qty,
            'disp_kg_fat' => $this->disp_kg_fat,
            'disp_kg_snf' => $this->disp_kg_snf,
            'rec_qty' => $this->rec_qty,
            'rec_kg_fat' => $this->rec_kg_fat,
            'rec_kg_snf' => $this->rec_kg_snf,
            'cd_qty_diff' => $this->cd_qty_diff,
            'cd_kg_fat_diff' => $this->cd_kg_fat_diff,
            'cd_kg_snf_diff' => $this->cd_kg_snf_diff,
            'rd_qty_diff' => $this->rd_qty_diff,
            'rd_kg_fat_diff' => $this->rd_kg_fat_diff,
            'rd_kg_snf_diff' => $this->rd_kg_snf_diff,
            'avg_rate' => $this->avg_rate,
            'previous_hold' => $this->previous_hold,
            'previous_due' => $this->previous_due,
            'hold_amount' => $this->hold_amount,
            'is_verified' => $this->is_verified,
            'disburse_amount' => $this->disburse_amount,
            'disburse_date' => $this->disburse_date,
            'process_date' => $this->process_date,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
//                ->andFilterWhere(['like', 'tbl_transporter.transporter_name', $this->transporter_code])
                ->andFilterWhere(['like', 'tbl_transporter_payment.transporter_code', $this->transporter_code])
                ->andFilterWhere(['like', 'adjust_remark', $this->adjust_remark])
                ->andFilterWhere(['like', 'tbl_transporter_payment.status', $this->status])
                ->andFilterWhere(['like', 'tbl_route_mapping.route_name', $this->route_code])
                ->andFilterWhere(['like', 'reject_reason', $this->reject_reason])
                ->andFilterWhere(['like', 'payment_transaction_code', $this->payment_transaction_code])
                ->andFilterWhere(['like', 'payment_date', ($this->payment_date == '') ? '' : Yii::$app->formatter->asDate($this->payment_date, 'php:Y-m-d')])
                ->andFilterWhere(['like', 'no_of_days', $this->no_of_days])
                ->andFilterWhere(['like', 'total_kms', $this->total_kms])
                ->andFilterWhere(['like', 'total_amount', $this->total_amount])
                ->andFilterWhere(['like', 'total_deduction', $this->total_deduction])
                ->andFilterWhere(['like', 'total_addition', $this->total_addition])
                ->andFilterWhere(['like', 'net_amount', $this->net_amount])
                ->andFilterWhere(['like', 'adjust_amount', $this->adjust_amount])
                ->andFilterWhere(['like', 'final_amount', $this->final_amount])
                ->andFilterWhere(['like', 'total_qty', $this->total_qty]);
        return $dataProvider;
    }

}
