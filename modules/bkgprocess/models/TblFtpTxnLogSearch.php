<?php

namespace app\modules\bkgprocess\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\bkgprocess\models\TblFtpTxnLog;

/**
 * TblFtpTxnLogSearch represents the model behind the search form about `app\modules\bkgprocess\models\TblFtpTxnLog`.
 */
class TblFtpTxnLogSearch extends TblFtpTxnLog {

    public $dcs_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['ftp_txn_log_id', 'total_count', 'success_count', 'error_count', 'file_status', 'status', 'file_creator_id', 'dcs_code'], 'safe'],
            [['txn_type', 'file_path', 'module_name', 'module_code', 'mcc_plant_code', 'union_code', 'txn_datetime', 'created_at', 'created_by', 'local_path', 'ftp_type', 'ftp_host', 'ftp_username', 'ftp_password', 'ftp_port', 'ftp_path', 'updated_at', 'updated_by', 'file_name', 'old_file_path', 'old_local_path', 'f_union_code', 'f_plant_code', 'f_mcc_code', 'from_date', 'to_date', 'from_shift', 'to_shift'], 'safe'],
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
        $this->load($params);
        $query = TblFtpTxnLog::find();
        $query->where(['txn_type' => 'EIPL']);
        $query->joinWith(['mccPlantCode', 'creatorId']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        if (Yii::$app->session->get('Unions') !== '')
            $query->andFilterWhere(['tbl_ftp_txn_log.union_code' => explode(',', Yii::$app->session->get('Unions'))]);
        if (!empty($this->f_union_code))
            $query->andFilterWhere(['tbl_ftp_txn_log.union_code' => $this->f_union_code]);
        if (Yii::$app->session->get('Plant') !== '')
            $query->andFilterWhere(['tbl_mcc_plant.plant_code' => explode(',', Yii::$app->session->get('Plant'))]);
        if (!empty($this->f_plant_code))
            $query->andFilterWhere(['tbl_mcc_plant.plant_code' => $this->f_plant_code]);
        if (Yii::$app->session->get('MCC') !== '')
            $query->andFilterWhere(['tbl_ftp_txn_log.mcc_plant_code' => explode(',', Yii::$app->session->get('MCC'))]);
        if (!empty($this->f_mcc_code))
            $query->andFilterWhere(['tbl_ftp_txn_log.mcc_plant_code' => $this->f_mcc_code]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (empty($this->from_date)) {
            $this->from_date = date('Y-m-d');
        }

        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['=', 'tbl_file_creator.applicable_date', $from_date]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'module_name', $this->module_name])
                ->andFilterWhere(['like', 'file_name', $this->file_name]);

        return $dataProvider;
    }

    public function listsearch($params) {
        $query = TblFtpTxnLog::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        $query->joinWith(['dcsCode', 'creatorId']);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(tbl_file_creator.applicable_date as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(tbl_file_creator.applicable_date as date)', $to_date]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'module_code' => $this->dcs_code,
        ]);


        $query->andFilterWhere(['like', 'total_count', $this->total_count])
                ->andFilterWhere(['like', 'success_count', $this->success_count])
                ->andFilterWhere(['like', 'error_count', $this->error_count])
                ->andFilterWhere(['like', 'file_name', $this->file_name])
                ->andFilterWhere(['like', 'file_status', $this->file_status]);
        return $dataProvider;
    }

}
