<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblMemberProvisionalShareDetails;

/**
 * TblMemberProvisionalFamilyDetailsSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblMemberProvisionalFamilyDetails`.
 */
class TblMemberProvisionalShareDetailsSearch extends TblMemberProvisionalShareDetails {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['amount_deposit', 'payable_share_amount', 'admission_fee', 'amount_payable', 'total_amount', 'balance_amount', 'admission_fee_recovery', 'deposit_date', 'created_at', 'updated_at', 'no_of_share_req', 'no_of_share_apply', 'originating_type', 'union_code', 'provisional_member_code', 'mode_of_payment', 'bank_name', 'ref_no', 'created_by', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblMemberProvisionalShareDetails::find();

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
            'provisional_member_code' => $this->provisional_member_code,
        ]);

        return $dataProvider;
    }

}
