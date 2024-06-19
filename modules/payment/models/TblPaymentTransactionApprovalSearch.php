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
            [['payment_transaction_approval_code','union_code','plant_code','mcc_plant_code','bmc_code','customer_type','total_amount','total_deduction','final_amount','payment_date','qty','avg_fat','avg_snf','kg_fat','kg_snf','avg_rate','from_date','to_date','total_count','approval_status','remarks','status_date','status_by','created_at','created_by','updated_at','updated_by','originating_org_code','originating_org_type','originating_type','x_col1','x_col2','x_col3','x_col4','x_col5'], 'safe'],
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
    public function search($params, $pending_approval = false) {
        $query = TblPaymentTransactionApproval::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($pending_approval) {
            $approval = new TblProcessApproval();
            $subQuery = $approval->getApproveLavel('tbl_payment_transaction_approval');
            $query->innerJoin(['ap' => $subQuery], 'convert(varchar(max),tbl_payment_transaction_approval.payment_transaction_approval_code) = convert(varchar(max),ap.process_code)')
                    ->addSelect(['tbl_payment_transaction_approval.*', 'ap.process_approval_code as process_approval_code'])
                    ->where(['tbl_payment_transaction_approval.approval_status' => ['Pending','Inprogress']]);
        } else {
            $query->andFilterWhere(['like', 'tbl_payment_transaction_approval.approval_status', $this->approval_status]);
            Yii::$app->general->filterByOrg($query, $this, 'tbl_payment_transaction_approval', 'tbl_payment_transaction_approval', 'tbl_payment_transaction_approval');
        }
        
        if(!empty($this->from_date)){
            $query->andFilterWhere(['>=', 'CAST(tbl_payment_transaction_approval.created_at as date)', date('Y-m-d', strtotime($this->from_date))]);
        }
        if(!empty($this->to_date)){
            $query->andFilterWhere(['<=', 'CAST(tbl_payment_transaction_approval.created_at as date)', date('Y-m-d', strtotime($this->to_date))]);
        }

        $query->andFilterWhere(['=', 'CAST(tbl_payment_transaction_approval.payment_date as date)', !empty($this->payment_date) ? date('Y-m-d', strtotime($this->payment_date)) : NULL]);

        $query->andFilterWhere([
            'tbl_payment_transaction_approval.total_amount' => $this->total_amount,
            'tbl_payment_transaction_approval.total_deduction' => $this->total_deduction,
            'tbl_payment_transaction_approval.final_amount' => $this->final_amount,
            'tbl_payment_transaction_approval.qty' => $this->qty,
            'tbl_payment_transaction_approval.avg_fat' => $this->avg_fat,
            'tbl_payment_transaction_approval.avg_snf' => $this->avg_snf,
            'tbl_payment_transaction_approval.kg_fat' => $this->kg_fat,
            'tbl_payment_transaction_approval.kg_snf' => $this->kg_snf,
            'tbl_payment_transaction_approval.avg_rate' => $this->avg_rate,
            'tbl_payment_transaction_approval.total_count' => $this->total_count,
            'tbl_payment_transaction_approval.status_date' => $this->status_date,
            'tbl_payment_transaction_approval.originating_type' => $this->originating_type,
        ])
        ->andFilterWhere(['like', 'tbl_payment_transaction_approval.customer_type', $this->customer_type])
        ->andFilterWhere(['like', 'tbl_payment_transaction_approval.remarks', $this->remarks]);

        return $dataProvider;
    }
}
