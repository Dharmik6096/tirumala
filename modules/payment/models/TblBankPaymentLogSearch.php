<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblBankPaymentLog;

/**
 * TblBankPaymentLogSearch represents the model behind the search form about `app\modules\payment\models\TblBankPaymentLog`.
 */
class TblBankPaymentLogSearch extends TblBankPaymentLog {

    /**
     * @inheritdoc
     */
    public $from_date, $to_date;

    public function rules() {
        return [
            [['log_id', 'dcs_payment_cycle_code', 'status'], 'integer'],
            [['union_code', 'file_path', 'payment_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'file_name', 'customer_id', 'corelation_file_id', 'corporate_file_id', 'file_status', 'file_status_desc', 'file_error_code', 'file_error_desc', 'no_of_txn', 'utr_ref_no', 'instrument_number', 'file_status_code', 'from_date', 'to_date'], 'safe'],
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
        $query = TblBankPaymentLog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        Yii::$app->general->filterByOrg($query, $this);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 'tbl_bank_payment_log.payment_date', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'tbl_bank_payment_log.payment_date', $to_date]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'log_id' => $this->log_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'file_name', $this->file_name])
                ->andFilterWhere(['like', 'file_status', $this->file_status])
                ->andFilterWhere(['like', 'file_status_desc', $this->file_status_desc])
                ->andFilterWhere(['like', 'file_error_code', $this->file_error_code])
                ->andFilterWhere(['like', 'file_error_desc', $this->file_error_desc])
                ->andFilterWhere(['like', 'no_of_txn', $this->no_of_txn])
                ->andFilterWhere(['like', 'file_status_code', $this->file_status_code])
                ->andFilterWhere(['like', 'utr_ref_no', $this->utr_ref_no])
                ->andFilterWhere(['like', 'instrument_number', $this->instrument_number]);

        return $dataProvider;
    }

}
