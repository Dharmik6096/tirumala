<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblVspPaymentTransaction;

/**
 * TblVspPaymentTransactionSearch represents the model behind the search form about `app\modules\payment\models\TblVspPaymentTransaction`.
 */
class TblVspPaymentTransactionSearch extends TblVspPaymentTransaction {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tbl_vsp_payment_transaction_code', 'vsp_payment_code'], 'integer'],
            [['bill_head_code'], 'safe'],
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
        $query = TblVspPaymentTransaction::find()->where(['vsp_payment_code' => $this->vsp_payment_code]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        return $dataProvider;
    }

}
