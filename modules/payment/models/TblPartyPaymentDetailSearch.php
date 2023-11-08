<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblPartyPaymentDetail;
class TblPartyPaymentDetailSearch extends TblPartyPaymentDetail {
//    public $from_date, $to_date;
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['payment_detail_code', 'party_payment_code', 'vehicle_code', 'parsing_no', 'from_type', 'from_dest', 'to_type', 'to_dest'], 'safe'],
            [['receipt_datetime', 'dispatch_datetime', 'challan_no', 'grn_no', 'trip_code', 'disp_qty', 'disp_kg_fat', 'disp_kg_snf'], 'safe'],
            [['rec_qty', 'rec_kg_fat', 'rec_kg_snf', 'rd_qty_diff', 'rd_kg_fat_diff', 'rd_kg_snf_diff', 'milk_type_code', 'milk_quality_type_code'], 'safe'],
            [['qty', 'fat', 'snf', 'rtpl', 'amount'], 'safe'],
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['created_by', 'updated_by'], 'string', 'max' => 14],
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
        $query = TblPartyPaymentDetail::find();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
//        $query->joinWith(['dcsCode', 'customerCode', 'customerType']);
//        Yii::$app->general->filterByOrg($query, $this, 'tbl_bonus_payment_summary', 'tbl_bonus_payment_summary', 'tbl_bonus_payment_summary');
//        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_name', $this->customer_name], ['like', 'tbl_customer_master.customer_name', $this->customer_name]]);
//        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_code_ex', $this->customer_ex_code], ['like', 'tbl_customer_master.customer_code_ex', $this->customer_ex_code]]);


//        if (!empty($this->from_date)) {
//            $from_date = date('Y-m-d', strtotime($this->from_date));
//            $query->andFilterWhere(['>=', 'CAST(tbl_bonus_payment_summary.from_datetime as date)', $from_date]);
//        }
//        if (!empty($this->to_date)) {
//            $to_date = date('Y-m-d', strtotime($this->to_date));
//            $query->andFilterWhere(['<=', 'CAST(tbl_bonus_payment_summary.to_datetime as date)', $to_date]);
//        }


//        $query->andFilterWhere(['=', 'CAST(tbl_bonus_payment_summary.payment_date as date)', !empty($this->payment_date) ? date('Y-m-d', strtotime($this->payment_date)) : NULL]);
//
//        $query->andFilterWhere(['like', 'tbl_bonus_payment_summary.payment_type', $this->payment_type])
//                ->andFilterWhere(['like', 'tbl_bonus_payment_summary.kg_fat', $this->kg_fat])
//                ->andFilterWhere(['like', 'tbl_bonus_payment_summary.kg_snf', $this->kg_snf])
//                ->andFilterWhere(['like', 'tbl_bonus_payment_summary.qty', $this->qty])
//                ->andFilterWhere(['like', 'tbl_bonus_payment_summary.amount', $this->amount])
//                ->andFilterWhere(['like', 'tbl_bonus_payment_summary.addition', $this->addition])
//                ->andFilterWhere(['like', 'tbl_bonus_payment_summary.deduction', $this->deduction])
//                ->andFilterWhere(['like', 'tbl_bonus_payment_summary.net_payable', $this->net_payable])
//                ->andFilterWhere(['like', 'tbl_bonus_payment_summary.customer_code', $this->customer_code])
//                ->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->customer_type])
//                ->andFilterWhere(['like', 'tbl_bonus_payment_summary.status', $this->status]);
//
//        $query->orderBy(['tbl_bonus_payment_summary.from_datetime' => SORT_DESC]);

        return $dataProvider;
    }

    public function disbursesearch() {
        $query = TblPartyPaymentDetail::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
//        if (!empty($this->payment_cycle_code)) {
//            $query->Where(['tbl_bonus_payment_summary.from_datetime' => $this->from_datetime]);
//            $query->andWhere(['tbl_bonus_payment_summary.to_datetime' => $this->to_datetime]);
//            $query->andWhere(['tbl_bonus_payment_summary.bmc_code' => $this->bmc_code]);
//            $query->andWhere(['tbl_bonus_payment_summary.payment_type' => $this->payment_type]);
//            $query->andWhere(['tbl_bonus_payment_summary.customer_type' => $this->customer_type]);
//            $query->andWhere(['tbl_bonus_payment_summary.status' => $this->status]);
//            $query->orderBy(['tbl_bonus_payment_summary.bmc_code' => SORT_ASC]);
//        } else {
//            $query->where('0=1');
//        }
        return $dataProvider;
    }

}
