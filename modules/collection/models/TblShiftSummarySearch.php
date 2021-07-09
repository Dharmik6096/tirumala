<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblShiftSummary;

/**
 * TblShiftSummarySearch represents the model behind the search form about `app\modules\collection\models\TblShiftSummary`.
 */
class TblShiftSummarySearch extends TblShiftSummary
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'shift_date', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'device_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'flg_sentbox_entry', 'sync_status', 'sync_timestamp', 'status_datetime'], 'safe'],
            [['shift_code', 'type', 'doc_no', 'status', 'check_count'], 'integer'],
            [['avg_fat', 'avg_snf', 'quantity', 'amount'], 'number'],
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
        $query = TblShiftSummary::find();

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
            'shift_date' => $this->shift_date,
            'shift_code' => $this->shift_code,
            'avg_fat' => $this->avg_fat,
            'avg_snf' => $this->avg_snf,
            'quantity' => $this->quantity,
            'amount' => $this->amount,
            'type' => $this->type,
            'doc_no' => $this->doc_no,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sync_timestamp' => $this->sync_timestamp,
            'status' => $this->status,
            'status_datetime' => $this->status_datetime,
            'check_count' => $this->check_count,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'plant_code', $this->plant_code])
            ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
            ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
            ->andFilterWhere(['like', 'x_col1', $this->x_col1])
            ->andFilterWhere(['like', 'x_col2', $this->x_col2])
            ->andFilterWhere(['like', 'x_col3', $this->x_col3])
            ->andFilterWhere(['like', 'x_col4', $this->x_col4])
            ->andFilterWhere(['like', 'x_col5', $this->x_col5])
            ->andFilterWhere(['like', 'device_id', $this->device_id])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'flg_sentbox_entry', $this->flg_sentbox_entry])
            ->andFilterWhere(['like', 'sync_status', $this->sync_status]);

        return $dataProvider;
    }
}
