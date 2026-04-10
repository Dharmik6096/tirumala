<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblVoucher;

/**
 * TblVoucherSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblVoucher`.
 */
class TblVoucherSearch extends TblVoucher {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['auto_posted', 'cancelled', 'originating_type', 'voucher_code', 'bill_date', 'voucher_type_code', 'voucher_date', 'bill_no', 'remarks', 'dock_code', 'financial_year_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'originating_org_code', 'originating_org_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'process_reference', 'process_name'], 'safe'],
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
        $query = TblVoucher::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_voucher', 'tbl_voucher', 'tbl_voucher', 'tbl_voucher');
        $query->joinWith(['voucherTypeCode']);


        // grid filtering conditions

        if (!empty($this->bill_date))
            $query->andFilterWhere(['tbl_voucher.bill_date' => date('Y-m-d', strtotime($this->bill_date))]);

        if (!empty($this->voucher_date))
            $query->andFilterWhere(['tbl_voucher.voucher_date' => date('Y-m-d', strtotime($this->voucher_date))]);

        $query->andFilterWhere([
            'tbl_voucher.auto_posted' => $this->auto_posted,
            'tbl_voucher.cancelled' => $this->cancelled
        ]);

        $query->andFilterWhere(['like', 'tbl_voucher_types.voucher_type_name', $this->voucher_type_code])
                ->andFilterWhere(['like', 'tbl_voucher.voucher_code', $this->voucher_code])
                ->andFilterWhere(['like', 'tbl_voucher.bill_no', $this->bill_no])
                ->andFilterWhere(['like', 'tbl_voucher.remarks', $this->remarks])
                ->andFilterWhere(['like', 'tbl_voucher.dock_code', $this->dock_code])
                ->andFilterWhere(['like', 'tbl_voucher.financial_year_code', $this->financial_year_code])
                ->andFilterWhere(['like', 'tbl_voucher.process_reference', $this->process_reference])
                ->andFilterWhere(['like', 'tbl_voucher.process_name', $this->process_name]);

        return $dataProvider;
    }

}
