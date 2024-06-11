<?php

namespace app\modules\payment\models;

use app\modules\general\models\TblProcessApproval;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblPaymentTransactionApproval;

/**
 * TblPaymentTransactionApprovalSearch represents the model behind the search form about `app\modules\payment\models\TblPaymentTransactionApproval`.
 */
class TblPaymentTransactionApprovalSearch extends TblPaymentTransactionApproval
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_transaction_approval_code','union_code','plant_code','mcc_plant_code','bmc_code','customer_type','total_amount','total_deduction','final_amount','payment_date','qty','avg_fat','avg_snf','kg_fat','kg_snf','avg_rate','from_date','to_date','total_count','approval_status','remarks','status_date','status_by','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type','x_col1','x_col2','x_col3','x_col4','x_col5'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function searchPending($params)
    {
        $query = TblPaymentTransactionApproval::find();

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
            'total_amount' => $this->total_amount,
            'total_deduction' => $this->total_deduction,
            'final_amount' => $this->final_amount,
            'payment_date' => $this->payment_date,
            'qty' => $this->qty,
            'avg_fat' => $this->avg_fat,
            'avg_snf' => $this->avg_snf,
            'kg_fat' => $this->kg_fat,
            'kg_snf' => $this->kg_snf,
            'avg_rate' => $this->avg_rate,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'total_count' => $this->total_count,
            'status_date' => $this->status_date,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'payment_transaction_approval_code', $this->payment_transaction_approval_code])
            ->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'plant_code', $this->plant_code])
            ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
            ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
            ->andFilterWhere(['like', 'customer_type', $this->customer_type])
            ->andFilterWhere(['like', 'approval_status', $this->approval_status])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'status_by', $this->status_by]);

        return $dataProvider;
    }

    public function search($params, $pending_approval = false) {
        $query = TblPaymentTransactionApproval::find()->alias('pta');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_ASC]],
        ]);

        $this->load($params);

        if ($pending_approval) {
            $approval = new TblProcessApproval();
            $subQuery = $approval->getApproveLavel('payment_transaction_approval');
            $query->innerJoin(['ap' => $subQuery], 'convert(varchar(max),pta.payment_transaction_approval_code) = convert(varchar(max),ap.process_code)');
            $query->addSelect(['pta.*', 'ap.process_approval_code as process_approval_code']);
            $this->approval_status = ['Pending','Inprogress'];
            $query->where(['pta.approval_status' => $this->approval_status]);
        }

        Yii::$app->general->filterByOrg($query, $this);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['=', 'CAST(pta.payment_date as date)', !empty($this->payment_date) ? date('Y-m-d', strtotime($this->payment_date)) : NULL]);

        $query->andFilterWhere([
            'pta.total_amount' => $this->total_amount,
            'pta.total_deduction' => $this->total_deduction,
            'pta.final_amount' => $this->final_amount,
            'pta.qty' => $this->qty,
            'pta.avg_fat' => $this->avg_fat,
            'pta.avg_snf' => $this->avg_snf,
            'pta.kg_fat' => $this->kg_fat,
            'pta.kg_snf' => $this->kg_snf,
            'pta.avg_rate' => $this->avg_rate,
            'pta.from_date' => $this->from_date,
            'pta.to_date' => $this->to_date,
            'pta.total_count' => $this->total_count,
            'pta.status_date' => $this->status_date,
            'pta.originating_type' => $this->originating_type,
        ]);
        $query->andFilterWhere(['like', 'pta.customer_type', $this->customer_type])
                ->andFilterWhere(['like', 'pta.remarks', $this->remarks]);

        return $dataProvider;
    }
}
