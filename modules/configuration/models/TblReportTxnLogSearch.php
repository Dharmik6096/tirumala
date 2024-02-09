<?php

namespace app\modules\configuration\models;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\configuration\models\TblReportTxnLog;

/**
 * TblMilkCollectionConfigSearch represents the model behind the search form about `app\modules\configuration\models\TblMilkCollectionConfig`.
 */
class TblReportTxnLogSearch extends TblReportTxnLog {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['created_at', 'updated_at', 'pick_datetime', 'cron_pick_datetime', 'response_datetime', 'report_type', 'report_title',
            'sp_name_or_report_path', 'input_param', 'search_param', 'export_file_name', 'file_type', 'file_name', 'file_path',
            'user_code', 'union_code', 'created_by', 'updated_by', 'response_msg', 'status', 'from_date', 'to_date', 'decrypt_data'], 'safe'],
            [['from_date','to_date'],'required','on'=>['block_request']],
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
        $query = TblReportTxnLog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $defaultFromDate = date('Y-m-d', strtotime('-15 days'));
        $defaultToDate = date('Y-m-d');
        
        $this->load($params);
        $query->andWhere(['tbl_report_txn_log.user_code' => Yii::$app->session->get('UserCode')]);

        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(tbl_report_txn_log.created_at as date)', $from_date]);
        } else {
            $this->from_date = $defaultFromDate;
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(tbl_report_txn_log.created_at as date)', $to_date]);
        } else {
            $this->to_date = $defaultToDate;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'report_type' => $this->report_type,
            'report_title' => $this->report_title,
            'response_msg' => $this->response_msg,
            'status' => $this->status,
        ]);
        if (!empty($this->created_at)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), tbl_report_txn_log.created_at, 126)', date('Y-m-d', strtotime($this->created_at))]);
        }
        $query->andFilterWhere(['like', 'report_type', $this->report_type])
                ->andFilterWhere(['like', 'report_title', $this->report_title])
                ->andFilterWhere(['like', 'response_msg', $this->response_msg])
                ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
    
     public function searchPending($params) {
        $query = TblReportTxnLog::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
             $query->where('0=1');
            return $dataProvider;
        }

//        if(empty($this->from_date)){
//            $query->where('0=1');
//            return $dataProvider;
//        }
        
        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(tbl_report_txn_log.created_at as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(tbl_report_txn_log.created_at as date)', $to_date]);
        }
        
        if(!empty($this->user_code)){
            $query->andWhere(['tbl_report_txn_log.user_code' => $this->user_code]);
        }
//         grid filtering conditions
        $query->andFilterWhere([
            'status' => '0',
        ]);
        $query->andFilterWhere(['like', 'report_type', $this->report_type])
                ->andFilterWhere(['like', 'report_title', $this->report_title]);
        return $dataProvider;
    }


}
