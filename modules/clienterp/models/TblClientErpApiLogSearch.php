<?php

namespace app\modules\clienterp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\clienterp\models\TblClientErpApiLog;
use app\modules\tankermovement\models\TblMilkVehicleEntryTransaction;

/**
 * TblClientErpApiLogSearch represents the model behind the search form about `app\modules\clienterp\models\TblClientErpApiLog`.
 */
class TblClientErpApiLogSearch extends TblClientErpApiLog
{
    public $erp_process_name, $from_date, $to_date;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['log_id', 'status_code', 'originating_type'], 'integer'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'end_point', 'request_url', 'request_desc', 'txn_type', 'date1', 'date2', 'desc1', 'desc2', 'request_header', 'request_payload', 'response_payload', 'request_timestamp', 'response_timestamp', 'status_type', 'status_response', 'status_message', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['erp_process_name'],'required', 'except' => ['viewLog'], 'message' => 'Process Name cannot be blank.'],
            [['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'from_date', 'to_date'],'safe'],
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
        $query = TblClientErpApiLog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }
        if($this->erp_process_name == 1){
            $query->where(['<>','request_desc','milk receipt']);
        }
        Yii::$app->general->filterByOrg($query, $this, 'tbl_client_erp_api_log', 'tbl_client_erp_api_log', 'tbl_client_erp_api_log');

        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'date1', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'date1', $to_date]);
        }

        if (!empty($this->date2)) {
            $query->andFilterWhere(['date2' => date('Y-m-d', strtotime($this->date2))]);
        }
        
        $query->andFilterWhere([
            'desc2' => $this->desc2,
            'request_timestamp' => $this->request_timestamp,
            'response_timestamp' => $this->response_timestamp,
            'status_code' => $this->status_code,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'end_point', $this->end_point])
            ->andFilterWhere(['like', 'request_url', $this->request_url])
            ->andFilterWhere(['like', 'request_desc', $this->request_desc])
            ->andFilterWhere(['like', 'txn_type', $this->txn_type])
            ->andFilterWhere(['like', 'desc1', $this->desc1])
            ->andFilterWhere(['like', 'request_header', $this->request_header])
            ->andFilterWhere(['like', 'request_payload', $this->request_payload])
            ->andFilterWhere(['like', 'response_payload', $this->response_payload])
            ->andFilterWhere(['like', 'status_response', $this->status_response])
            ->andFilterWhere(['like', 'status_message', $this->status_message]);

        return $dataProvider;
    }
}
