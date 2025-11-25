<?php

namespace app\modules\vsp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\vsp\models\TblBillHeadDetail;
use app\modules\vsp\models\TblBillHeadInstallment;

/**
 * TblBillHeadDetailSearch represents the model behind the search form about `app\modules\vsp\models\TblBillHeadDetail`.
 */
class TblBillHeadDetailSearch extends TblBillHeadDetail {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['bill_head_detail_code', 'payment_cycle_code', 'is_installment', 'is_active'], 'integer'],
                [['union_code', 'bill_head_code', 'dcs_code', 'amount', 'no_installment', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
                [['customer_type', 'customer_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'customer_name', 'from_date', 'to_date', 'transaction_date', 'remarks'], 'safe'],
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
        $query = TblBillHeadDetail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
//        $query->joinWith(['dcsCode', 'mainCustomerCode', 'customerType', 'billHeadCode', 'memberCode']);
        $query->leftJoin('tbl_dcs', 'tbl_dcs.dcs_code = tbl_bill_head_detail.customer_code')
                ->leftJoin('tbl_customer_master', 'tbl_customer_master.customer_code = tbl_bill_head_detail.customer_code')
                ->leftJoin('tbl_customer_type', ['tbl_customer_type.customer_type' => 'tbl_bill_head_detail.customer_type', 'tbl_customer_type.union_code' => 'tbl_bill_head_detail.union_code'])
                ->leftJoin('tbl_bill_head', 'tbl_bill_head.bill_head_code = tbl_bill_head_detail.bill_head_code')
                ->leftJoin('tbl_member', 'tbl_member.member_code = tbl_bill_head_detail.customer_code');
        Yii::$app->general->filterByOrg($query, $this, 'tbl_bill_head_detail', 'tbl_bill_head_detail', 'tbl_bill_head_detail');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }



//        if (!empty($this->from_date)) {
//            $from_date = date('Y-m-d', strtotime($this->from_date));
//            $query->andFilterWhere(['>=', 'tbl_bill_head_installment.installment_date', $from_date]);
//        }
//        if (!empty($this->to_date)) {
//            $to_date = date('Y-m-d', strtotime($this->to_date));
//            $query->andFilterWhere(['<=', 'tbl_bill_head_installment.installment_date', $to_date]);
//        }

        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 'tbl_bill_head_detail.transaction_date', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'tbl_bill_head_detail.transaction_date', $to_date]);
        }
        if (!empty($this->transaction_date))
            $query->andFilterWhere(['and', ['>=', 'tbl_bill_head_detail.transaction_date', date('Y-m-d', strtotime($this->transaction_date))], ['<=', 'transaction_date', date('Y-m-d', strtotime($this->transaction_date))]]);


        $query->andFilterWhere(['or', ['like', 'tbl_dcs.dcs_name', $this->customer_name], ['like', 'tbl_customer_master.customer_name', $this->customer_name], ['like', 'tbl_member.member_name', $this->customer_name]]);
        $query->andFilterWhere(['or', ['like', 'tbl_customer_type.customer_desc', $this->customer_type], ['like', 'tbl_bill_head_detail.customer_type', $this->customer_type]]);

        $query->andFilterWhere(['like', 'tbl_bill_head_detail.union_code', $this->union_code])
                ->andFilterWhere(['like', 'tbl_bill_head.bill_head_name', $this->bill_head_code])
                ->andFilterWhere(['like', 'tbl_bill_head_detail.dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_bill_head_detail.amount', $this->amount])
                ->andFilterWhere(['like', 'tbl_bill_head_detail.no_installment', $this->no_installment])
                //                ->andFilterWhere(['like', 'tbl_customer_type.customer_desc', $this->customer_type])
                ->andFilterWhere(['like', 'tbl_bill_head_detail.customer_code', $this->customer_code])
                ->andFilterWhere(['like', 'tbl_bill_head_detail.remarks', $this->remarks]);

        // $query->orderBy(['tbl_bill_head_installment.installment_date' => SORT_DESC, 'tbl_customer_type.customer_desc' => SORT_ASC, 'tbl_bill_head_detail.customer_code' => SORT_ASC]);
        return $dataProvider;
    }

    public function gridsearch($params) {
        $this->load($params);
        $query = TblBillHeadDetail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
        $query->join('LEFT JOIN', 'tbl_customer_type', 'tbl_customer_type.customer_type = tbl_bill_head_detail.customer_type AND tbl_customer_type.union_code = tbl_bill_head_detail.union_code');
        $query->join('LEFT JOIN', 'tbl_bill_head_installment', 'tbl_bill_head_installment.bill_head_detail_code = tbl_bill_head_detail.bill_head_detail_code');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // Yii::$app->general->filterByOrg($query, $this);
//        $query->andWhere(['tbl_bill_head_detail.payment_cycle_code' => $this->payment_cycle_code]);
        $query->andWhere(['tbl_bill_head_detail.bmc_code' => $this->bmc_code]);
//        $query->andFilterWhere(['bmc_code' => $this->bmc_code])
        $query->andFilterWhere(['like', 'tbl_bill_head_detail.customer_type', $this->customer_type]);

        $query->orderBy(['tbl_bill_head_installment.installment_date' => SORT_DESC, 'tbl_customer_type.customer_desc' => SORT_ASC, 'tbl_bill_head_detail.customer_code' => SORT_ASC]);
        return $dataProvider;
    }

    public function installmentsearch($params) {
        $this->load($params);
        $query = TblBillHeadInstallment::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andWhere([
            'bill_head_detail_code' => $this->bill_head_detail_code
        ]);
        return $dataProvider;
    }

    public function membergridsearch($params) {
        $this->load($params);
        $query = TblBillHeadDetail::find();
        $query->andWhere(['tbl_bill_head_detail.customer_type' => 'member']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE,
        ]);
//        $query->joinWith(['installmentCode']);
        $query->join('LEFT JOIN', 'tbl_bill_head_installment', 'tbl_bill_head_installment.bill_head_detail_code = tbl_bill_head_detail.bill_head_detail_code')
                ->join('LEFT JOIN', 'tbl_dcs', 'tbl_dcs.dcs_code = tbl_bill_head_detail.dcs_code');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        Yii::$app->general->filterByOrg($query, $this);
//        $query->andWhere(['tbl_bill_head_detail.payment_cycle_code' => $this->payment_cycle_code]);
//        $query->andFilterWhere(['bmc_code' => $this->bmc_code]);
        $query->andWhere(['tbl_bill_head_detail.bmc_code' => $this->bmc_code]);

        $query->orderBy(['tbl_bill_head_installment.installment_date' => SORT_DESC, 'tbl_bill_head_detail.customer_code' => SORT_ASC]);
        return $dataProvider;
    }

}
