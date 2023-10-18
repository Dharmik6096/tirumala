<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblPartyPayment;
use app\modules\payment\models\TblPartyPaymentHeadDetail;
class TblPartyPaymentHeadDetailSearch extends TblPartyPaymentHeadDetail {
    public $party_payment_code;
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['head_detail_code', 'party_payment_code', 'payment_head_code', 'payment_head_type', 'amount'], 'safe'],
            [['originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['originating_org_code', 'originating_org_type'], 'string', 'max' => 25],
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
        $query = TblPartyPaymentHeadDetail::find();
        $query->where(['party_payment_code' => $this->party_payment_code]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE
        ]);

        $this->load($params);
//        echo "<pre>";
//        print_r($params);
//        die;
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'head_detail_code' => $this->head_detail_code,
            'party_payment_code' => $this->party_payment_code,
            'payment_head_code' => $this->payment_head_code,
            'amount' => $this->amount,
        ]);
        $query->orderBy('head_detail_code');
        return $dataProvider;
    }

}
