<?php

namespace app\modules\clienterp\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\clienterp\models\TblClientErpApiLog;

/**
 * TblClientErpApiLogSearch represents the model behind the search form about `app\modules\clienterp\models\TblClientErpApiLog`.
 */
class TblClientErpApiLogSearch extends TblClientErpApiLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['log_id', 'status_code', 'originating_type'], 'integer'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'end_point', 'request_url', 'request_desc', 'txn_type', 'date1', 'date2', 'desc1', 'desc2', 'request_header', 'request_payload', 'response_payload', 'request_timestamp', 'response_timestamp', 'status_type', 'status_response', 'status_message', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'log_id' => $this->log_id,
            'date1' => $this->date1,
            'date2' => $this->date2,
            'request_timestamp' => $this->request_timestamp,
            'response_timestamp' => $this->response_timestamp,
            'status_code' => $this->status_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'plant_code', $this->plant_code])
            ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
            ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
            ->andFilterWhere(['like', 'end_point', $this->end_point])
            ->andFilterWhere(['like', 'request_url', $this->request_url])
            ->andFilterWhere(['like', 'request_desc', $this->request_desc])
            ->andFilterWhere(['like', 'txn_type', $this->txn_type])
            ->andFilterWhere(['like', 'desc1', $this->desc1])
            ->andFilterWhere(['like', 'desc2', $this->desc2])
            ->andFilterWhere(['like', 'request_header', $this->request_header])
            ->andFilterWhere(['like', 'request_payload', $this->request_payload])
            ->andFilterWhere(['like', 'response_payload', $this->response_payload])
            ->andFilterWhere(['like', 'status_type', $this->status_type])
            ->andFilterWhere(['like', 'status_response', $this->status_response])
            ->andFilterWhere(['like', 'status_message', $this->status_message])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'originating_org_code', $this->originating_org_code])
            ->andFilterWhere(['like', 'originating_org_type', $this->originating_org_type])
            ->andFilterWhere(['like', 'x_col1', $this->x_col1])
            ->andFilterWhere(['like', 'x_col2', $this->x_col2])
            ->andFilterWhere(['like', 'x_col3', $this->x_col3])
            ->andFilterWhere(['like', 'x_col4', $this->x_col4])
            ->andFilterWhere(['like', 'x_col5', $this->x_col5]);

        return $dataProvider;
    }
}
