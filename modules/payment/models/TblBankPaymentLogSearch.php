<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblBankPaymentLog;


/**
 * TblBankPaymentLogSearch represents the model behind the search form about `app\modules\payment\models\TblBankPaymentLog`.
 */
class TblBankPaymentLogSearch extends TblBankPaymentLog
{
    /**
     * @inheritdoc
     */
    public $from_date, $to_date;
    public function rules()
    {
        return [
            [['log_id', 'dcs_payment_cycle_code', 'status'], 'integer'],
            [['union_code', 'file_path', 'payment_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'file_name', 'customer_id', 'corelation_file_id', 'corporate_file_id', 'file_status', 'file_status_desc', 'file_error_code', 'file_error_desc', 'no_of_txn', 'utr_ref_no', 'instrument_number', 'file_status_code','from_date', 'to_date'], 'safe'],
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
    public function search($params)
    {
        $query = TblBankPaymentLog::find();

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
         
       Yii::$app->general->filterByOrg($query, $this);
       
       if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'tbl_bank_payment_log.created_at', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'tbl_bank_payment_log.created_at', $to_date]);
        }
        
        // grid filtering conditions
        $query->andFilterWhere([
            'log_id' => $this->log_id,
//            'dcs_payment_cycle_code' => $this->dcs_payment_cycle_code,
            'status' => $this->status,
            'payment_date' => $this->payment_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'file_path', $this->file_path])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'file_name', $this->file_name])
            ->andFilterWhere(['like', 'customer_id', $this->customer_id])
            ->andFilterWhere(['like', 'corelation_file_id', $this->corelation_file_id])
            ->andFilterWhere(['like', 'corporate_file_id', $this->corporate_file_id])
            ->andFilterWhere(['like', 'file_status', $this->file_status])
            ->andFilterWhere(['like', 'file_status_desc', $this->file_status_desc])
            ->andFilterWhere(['like', 'file_error_code', $this->file_error_code])
            ->andFilterWhere(['like', 'file_error_desc', $this->file_error_desc])
            ->andFilterWhere(['like', 'no_of_txn', $this->no_of_txn])
            ->andFilterWhere(['like', 'utr_ref_no', $this->utr_ref_no])
            ->andFilterWhere(['like', 'instrument_number', $this->instrument_number])
            ->andFilterWhere(['like', 'file_status_code', $this->file_status_code])
            ->andFilterWhere(['like', 'dcs_payment_cycle_code', $this->dcs_payment_cycle_code]);

        return $dataProvider;
    }
}
