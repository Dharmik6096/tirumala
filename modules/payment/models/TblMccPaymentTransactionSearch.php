<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblMccPaymentTransaction;

/**
 * TblVspPaymentTransactionSearch represents the model behind the search form about `app\modules\payment\models\TblVspPaymentTransaction`.
 */
class TblMccPaymentTransactionSearch extends TblMccPaymentTransaction {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['mcc_payment_transaction_code', 'mcc_payment_code'], 'integer'],
            [['mcc_bill_head_code'], 'safe'],
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
        $query = TblMccPaymentTransaction::find()->where(['mcc_payment_code' => $this->mcc_payment_code]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        return $dataProvider;
    }

}
