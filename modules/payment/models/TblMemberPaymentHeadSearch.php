<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblMemberPaymentHead;

/**
 * TblMemberPaymentHeadSearch represents the model behind the search form about `app\modules\payment\models\TblMemberPaymentHead`.
 */
class TblMemberPaymentHeadSearch extends TblMemberPaymentHead {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['member_payment_head_code', 'payment_cycle_code', 'bill_head_type'], 'integer'],
                [['bmc_code', 'dcs_code', 'member_code', 'bill_head_code'], 'safe'],
                [['amount'], 'number'],
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
        $query = TblMemberPaymentHead::find();

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
            'member_payment_head_code' => $this->member_payment_head_code,
            'payment_cycle_code' => $this->payment_cycle_code,
            'amount' => $this->amount,
            'bill_head_type' => $this->bill_head_type,
        ]);

        $query->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
                ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'member_code', $this->member_code])
                ->andFilterWhere(['like', 'bill_head_code', $this->bill_head_code]);

        return $dataProvider;
    }

}
