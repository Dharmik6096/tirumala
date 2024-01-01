<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblPaymentHeadTransaction;

/**
 * TblVehicleTransporterHeadMappingSearch represents the model behind the search form about `app\modules\transporter\models\TblVehicleTransporterHeadMapping`.
 */
class TblPaymentHeadTransactionSearch extends TblPaymentHeadTransaction {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['payment_head_code', 'payment_head_type', 'applicable_date', 'applicable_code', 'applicable_for', 'amount', 'remarks', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'payment_type'], 'safe'],
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
        $query = TblPaymentHeadTransaction::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith(['paymentHeadType','partyName']);

        // grid filtering conditions
        $query->andFilterWhere([
            'payment_type' => $this->payment_type,
        ]);
        $query->andFilterWhere(['applicable_date' => !empty($this->applicable_date) ? date('Y-d-m', strtotime($this->applicable_date)) : NULL,])
                ->andFilterWhere(['like', 'tbl_payment_head.payment_head_name', $this->payment_head_code])
                ->andFilterWhere(['like', 'tbl_party_master.party_name', $this->applicable_code]);
        return $dataProvider;
    }

}
