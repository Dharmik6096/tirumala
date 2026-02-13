<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TblVendorPaymentHoldReleaseTransactionSearch represents the model behind the search form about `app\modules\payment\models\TblVspPaymentTransaction`.
 */
class TblVendorPaymentHoldReleaseTransactionSearch extends TblVendorPaymentHoldReleaseTransaction {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['vendor_payment_hold_release_transaction_code','vendor_payment_hold_release_code','bill_head_code','bill_head_type','amount','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type','is_reserved'], 'safe'],
            [['vendor_payment_hold_release_transaction_code'], 'integer'],
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
        $query = TblVendorPaymentHoldReleaseTransaction::find()->where(['vendor_payment_hold_release_code' => $this->vendor_payment_hold_release_code]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);


        return $dataProvider;
    }

}
