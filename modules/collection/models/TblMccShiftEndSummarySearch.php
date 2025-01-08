<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblMccShiftEndSummary;

/**
 * TblMccShiftEndSummarySearch represents the model behind the search form about `app\modules\collection\models\TblMccShiftEndSummary`.
 */
class TblMccShiftEndSummarySearch extends TblMccShiftEndSummary
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mcc_shift_end_summary_code', 'shift_code', 'milk_type_code', 'originating_type'], 'integer'],
            [['date_time_of_collection', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'flg_sentbox_entry', 'sync_status', 'sync_timestamp', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['quantity', 'fat', 'snf', 'p_quantity', 'p_fat', 'p_snf', 'd_quantity', 'd_fat', 'd_snf'], 'number'],
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
        $query = TblMccShiftEndSummary::find();

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
            'mcc_shift_end_summary_code' => $this->mcc_shift_end_summary_code,
            'date_time_of_collection' => $this->date_time_of_collection,
            'shift_code' => $this->shift_code,
            'milk_type_code' => $this->milk_type_code,
            'quantity' => $this->quantity,
            'fat' => $this->fat,
            'snf' => $this->snf,
            'p_quantity' => $this->p_quantity,
            'p_fat' => $this->p_fat,
            'p_snf' => $this->p_snf,
            'd_quantity' => $this->d_quantity,
            'd_fat' => $this->d_fat,
            'd_snf' => $this->d_snf,
            'sync_timestamp' => $this->sync_timestamp,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'plant_code', $this->plant_code])
            ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
            ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
            ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'flg_sentbox_entry', $this->flg_sentbox_entry])
            ->andFilterWhere(['like', 'sync_status', $this->sync_status])
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
