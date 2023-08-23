<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblTransporterPaymentDetail;

/**
 * TblTransporterPaymentDetailSearch represents the model behind the search form about `app\modules\payment\models\TblTransporterPaymentDetail`.
 */
class TblTransporterPaymentDetailSearch extends TblTransporterPaymentDetail {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['payment_detail_code', 'transporter_payment_code'], 'integer'],
                [['vehicle_code', 'parsing_no', 'from_type', 'from_dest', 'to_type', 'to_dest', 'dispatch_date', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
                [['coll_qty', 'coll_kg_fat', 'coll_kg_snf', 'disp_qty', 'disp_kg_fat', 'disp_kg_snf', 'rec_qty', 'rec_kg_fat', 'rec_kg_snf', 'cd_qty_diff', 'cd_kg_fat_diff', 'cd_kg_snf_diff', 'rd_qty_diff', 'rd_kg_fat_diff', 'rd_kg_snf_diff', 'avg_rate', 'morning_qty', 'evening_qty', 'qty', 'morning_kms', 'evening_kms', 'extra_kms', 'total_kms', 'km_rate', 'fuel_rate', 'fuel_consumption', 'amount', 'toll_amount', 'fastag_amount', 'fixed_amount', 'other_amount', 'total_amount'], 'number'],
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
        $query = TblTransporterPaymentDetail::find();
        $query->where(['transporter_payment_code' => $this->transporter_payment_code]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'payment_detail_code' => $this->payment_detail_code,
            'transporter_payment_code' => $this->transporter_payment_code,
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
            'morning_qty' => $this->morning_qty,
            'evening_qty' => $this->evening_qty,
            'qty' => $this->qty,
            'morning_kms' => $this->morning_kms,
            'evening_kms' => $this->evening_kms,
            'extra_kms' => $this->extra_kms,
            'total_kms' => $this->total_kms,
            'km_rate' => $this->km_rate,
            'fuel_rate' => $this->fuel_rate,
            'fuel_consumption' => $this->fuel_consumption,
            'amount' => $this->amount,
            'toll_amount' => $this->toll_amount,
            'fastag_amount' => $this->fastag_amount,
            'fixed_amount' => $this->fixed_amount,
            'other_amount' => $this->other_amount,
            'total_amount' => $this->total_amount,
            'dispatch_date' => $this->dispatch_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'vehicle_code', $this->vehicle_code])
                ->andFilterWhere(['like', 'parsing_no', $this->parsing_no])
                ->andFilterWhere(['like', 'from_type', $this->from_type])
                ->andFilterWhere(['like', 'from_dest', $this->from_dest])
                ->andFilterWhere(['like', 'to_type', $this->to_type])
                ->andFilterWhere(['like', 'to_dest', $this->to_dest])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by]);
        $query->orderBy('vehicle_code,dispatch_date,dispatch_datetime');
        return $dataProvider;
    }

}
