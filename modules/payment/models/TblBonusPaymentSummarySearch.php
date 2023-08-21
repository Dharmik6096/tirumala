<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblBonusPaymentSummary;

/**
 * TblBonusPaymentSummarySearch represents the model behind the search form about `app\modules\payment\models\TblBonusPaymentSummary`.
 */
class TblBonusPaymentSummarySearch extends TblBonusPaymentSummary
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bonus_payment_summary_code', 'from_shift', 'to_shift', 'originating_type'], 'integer'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'customer_type', 'customer_code', 'payment_type', 'from_datetime', 'to_datetime', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['kg_fat', 'kg_snf', 'avg_fat', 'avg_snf', 'qty', 'amount', 'addition', 'deduction', 'net_payable'], 'number'],
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
        $query = TblBonusPaymentSummary::find();

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
            'bonus_payment_summary_code' => $this->bonus_payment_summary_code,
            'from_datetime' => $this->from_datetime,
            'from_shift' => $this->from_shift,
            'to_datetime' => $this->to_datetime,
            'to_shift' => $this->to_shift,
            'kg_fat' => $this->kg_fat,
            'kg_snf' => $this->kg_snf,
            'avg_fat' => $this->avg_fat,
            'avg_snf' => $this->avg_snf,
            'qty' => $this->qty,
            'amount' => $this->amount,
            'addition' => $this->addition,
            'deduction' => $this->deduction,
            'net_payable' => $this->net_payable,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'plant_code', $this->plant_code])
            ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
            ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
            ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'customer_type', $this->customer_type])
            ->andFilterWhere(['like', 'customer_code', $this->customer_code])
            ->andFilterWhere(['like', 'payment_type', $this->payment_type])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type]);

        return $dataProvider;
    }
}
