<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblTransporterPaymentHeadDetail;

/**
 * TblTransporterPaymentHeadDetailSearch represents the model behind the search form about `app\modules\payment\models\TblTransporterPaymentHeadDetail`.
 */
class TblTransporterPaymentHeadDetailSearch extends TblTransporterPaymentHeadDetail {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['head_detail_code', 'transporter_payment_code', 'transporter_payment_head_code', 'type'], 'integer'],
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
        $query = TblTransporterPaymentHeadDetail::find();
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
            'head_detail_code' => $this->head_detail_code,
            'transporter_payment_code' => $this->transporter_payment_code,
            'transporter_payment_head_code' => $this->transporter_payment_head_code,
            'type' => $this->type,
            'amount' => $this->amount,
        ]);
        $query->orderBy('type');
        return $dataProvider;
    }

}
