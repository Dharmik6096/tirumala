<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblMemberPaymentHeadSummary;

/**
 * TblMemberPaymentHeadSummarySearch represents the model behind the search form about `app\modules\payment\models\TblMemberPaymentHeadSummary`.
 */
class TblMemberPaymentHeadSummarySearch extends TblMemberPaymentHeadSummary {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['member_payment_head_summary_code', 'payment_cycle_code', 'bill_head_type'], 'integer'],
                [['bmc_code', 'dcs_code', 'bill_head_code'], 'safe'],
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
        $query = TblMemberPaymentHeadSummary::find();

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
        $query->andWhere([
            'payment_cycle_code' => $this->payment_cycle_code,
            'dcs_code' => $this->dcs_code,
            'bmc_code' => $this->bmc_code,
        ]);

        return $dataProvider;
    }

}
