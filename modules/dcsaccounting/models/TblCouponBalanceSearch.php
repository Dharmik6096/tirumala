<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblCouponBalance;

/**
 * TblCouponBalanceSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblCouponBalance`.
 */
class TblCouponBalanceSearch extends TblCouponBalance {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['balance', 'consumer_type', 'milk_type_code', 'originating_type', 'coupon_balance_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'consumer_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblCouponBalance::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['unionCode', 'plantCode', 'mccPlantCode', 'bmcCode', 'dcsCode', 'milkTypeCode', 'memberCode', 'mainCustomerCode']);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_coupon_balance', 'tbl_coupon_balance', 'tbl_coupon_balance', 'tbl_coupon_balance');


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['or', ['like', 'tbl_member.member_name', $this->consumer_code], ['like', 'tbl_customer_master.customer_name', $this->consumer_code]]);

        $query->andFilterWhere(['like', 'coupon_balance_code', $this->coupon_balance_code])
                ->andFilterWhere(['like', 'tbl_animal_type.animal_type_name', $this->milk_type_code])
                ->andFilterWhere(['like', 'consumer_type', $this->consumer_type])
                ->andFilterWhere(['like', 'balance', $this->balance]);

        return $dataProvider;
    }

}
