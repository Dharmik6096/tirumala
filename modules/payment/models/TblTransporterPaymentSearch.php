<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblTransporterPayment;

/**
 * TblTransporterPaymentSearch represents the model behind the search form about `app\modules\payment\models\TblTransporterPayment`.
 */
class TblTransporterPaymentSearch extends TblTransporterPayment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transporter_payment_code', 'total_vehicle', 'no_of_days'], 'integer'],
            [['transporter_code', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'union_code'], 'safe'],
            [['coll_qty', 'coll_kg_fat', 'coll_kg_snf', 'disp_qty', 'disp_kg_fat', 'disp_kg_snf', 'rec_qty', 'rec_kg_fat', 'rec_kg_snf', 'cd_qty_diff', 'cd_kg_fat_diff', 'cd_kg_snf_diff', 'rd_qty_diff', 'rd_kg_fat_diff', 'rd_kg_snf_diff', 'total_amount', 'total_deduction', 'final_amount', 'adjust_amount', 'net_amount'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = TblTransporterPayment::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'transporter_payment_code' => $this->transporter_payment_code,
            'total_vehicle' => $this->total_vehicle,
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
            'no_of_days' => $this->no_of_days,
            'total_amount' => $this->total_amount,
            'total_deduction' => $this->total_deduction,
            'final_amount' => $this->final_amount,
            'adjust_amount' => $this->adjust_amount,
            'net_amount' => $this->net_amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'bmc_code' => $this->bmc_code,
            'union_code' => $this->union_code,
        ]);

        $query->andFilterWhere(['like', 'transporter_code', $this->transporter_code])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
