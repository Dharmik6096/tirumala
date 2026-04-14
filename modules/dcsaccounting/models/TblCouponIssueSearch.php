<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblCouponIssue;

/**
 * TblCouponIssueSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblCouponIssue`.
 */
class TblCouponIssueSearch extends TblCouponIssue {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['amount', 'consumer_type', 'milk_type_code', 'is_active', 'is_delete', 'payment_mode', 'originating_type', 'coupon_issue_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'consumer_code', 'issue_date', 'voucher_code', 'bank_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblCouponIssue::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['unionCode', 'plantCode', 'mccPlantCode', 'bmcCode', 'dcsCode', 'milkTypeCode']);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_coupon_issue', 'tbl_coupon_issue', 'tbl_coupon_issue', 'tbl_coupon_issue');


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_coupon_issue.is_active' => $this->is_active,
            'tbl_coupon_issue.is_delete' => $this->is_delete,
            'tbl_coupon_issue.issue_date' => $this->issue_date,
            'tbl_coupon_issue.payment_mode' => $this->payment_mode,
        ]);

        $query->andFilterWhere(['like', 'tbl_coupon_issue.coupon_issue_code', $this->coupon_issue_code])
                ->andFilterWhere(['like', 'tbl_coupon_issue.consumer_code', $this->consumer_code])
                ->andFilterWhere(['like', 'tbl_animal_type.animal_type_name', $this->milk_type_code])
                ->andFilterWhere(['like', 'tbl_coupon_issue.voucher_code', $this->voucher_code])
                ->andFilterWhere(['like', 'tbl_coupon_issue.amount', $this->amount])
                ->andFilterWhere(['like', 'tbl_coupon_issue.consumer_type', $this->consumer_type])
                ->andFilterWhere(['like', 'tbl_coupon_issue.bank_code', $this->bank_code]);

        return $dataProvider;
    }

}
