<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblVspPayment;

/**
 * TblVspPaymentSearch represents the model behind the search form about `app\modules\payment\models\TblVspPayment`.
 */
class TblVspPaymentSearch extends TblVspPayment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vsp_payment_code', 'dcs_payment_cycle_code', 'dcs_payment_cycle_applicabilty_code'], 'integer'],
            [['dcs_code', 'union_code', 'adjust_remark', 'created_at', 'created_by', 'updated_at', 'updated_by', 'status'], 'safe'],
            [['kg_fat', 'kg_snf', 'total_qty', 'total_loss', 'amount', 'addition', 'deduction', 'net_payable', 'adjust_amount', 'final_pay'], 'number'],
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
        $query = TblVspPayment::find();

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
            'vsp_payment_code' => $this->vsp_payment_code,
            'dcs_payment_cycle_code' => $this->dcs_payment_cycle_code,
            'dcs_payment_cycle_applicabilty_code' => $this->dcs_payment_cycle_applicabilty_code,
            'kg_fat' => $this->kg_fat,
            'kg_snf' => $this->kg_snf,
            'total_qty' => $this->total_qty,
            'total_loss' => $this->total_loss,
            'amount' => $this->amount,
            'addition' => $this->addition,
            'deduction' => $this->deduction,
            'net_payable' => $this->net_payable,
            'adjust_amount' => $this->adjust_amount,
            'final_pay' => $this->final_pay,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'adjust_remark', $this->adjust_remark])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
