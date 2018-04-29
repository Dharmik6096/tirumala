<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblDcsPayment;

/**
 * TblMemberPaymentSearch represents the model behind the search form about `app\modules\payment\models\TblMemberPayment`.
 */
class TblDcsPaymentSearch extends TblDcsPayment
{
    public $payment_cycle;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dcs_payment_code', 'dcs_payment_cycle_applicabilty_code', 'ack'], 'integer'],
            [['union_code', 'dcs_code', 'disburse_date', 'payment_date', 'approved_by', 'status', 'transfer_mode', 'error_code', 'error_log','payment_cycle'], 'safe'],
            [['total_amount', 'total_deduction', 'final_amount', 'disburse_amount'], 'number'],
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
        $query = TblMemberPayment::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate() || empty($this->payment_cycle)) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'dcs_payment_code' => $this->dcs_payment_code,
            'dcs_payment_cycle_applicabilty_code' => $this->dcs_payment_cycle_applicabilty_code,
            'total_amount' => $this->total_amount,
            'total_deduction' => $this->total_deduction,
            'final_amount' => $this->final_amount,
            'disburse_amount' => $this->disburse_amount,
            'disburse_date' => $this->disburse_date,
            'payment_date' => $this->payment_date,
            'ack' => $this->ack,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'approved_by', $this->approved_by])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'transfer_mode', $this->transfer_mode])
            ->andFilterWhere(['like', 'error_code', $this->error_code])
            ->andFilterWhere(['like', 'error_log', $this->error_log]);

        return $dataProvider;
    }
}
