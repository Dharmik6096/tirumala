<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblSampleBottleTesting;

/**
 * TblSampleBottleTestingSearch represents the model behind the search form about `app\modules\tankermovement\models\TblSampleBottleTesting`.
 */
class TblSampleBottleTestingSearch extends TblSampleBottleTesting
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sample_bottle_testing_code', 'trip_code', 'bmc_milk_dispatch_code', 'bmc_milk_dispatch_txn_code', 'sample_bottle_testing_date', 'transaction_date', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['milk_quality_type_code', 'milk_type_code', 'originating_type'], 'integer'],
            [['fat', 'snf', 'protein'], 'number'],
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
        $query = TblSampleBottleTesting::find();

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
            'sample_bottle_testing_date' => $this->sample_bottle_testing_date,
            'transaction_date' => $this->transaction_date,
            'milk_quality_type_code' => $this->milk_quality_type_code,
            'milk_type_code' => $this->milk_type_code,
            'fat' => $this->fat,
            'snf' => $this->snf,
            'protein' => $this->protein,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'sample_bottle_testing_code', $this->sample_bottle_testing_code])
            ->andFilterWhere(['like', 'trip_code', $this->trip_code])
            ->andFilterWhere(['like', 'bmc_milk_dispatch_code', $this->bmc_milk_dispatch_code])
            ->andFilterWhere(['like', 'bmc_milk_dispatch_txn_code', $this->bmc_milk_dispatch_txn_code])
            ->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'plant_code', $this->plant_code])
            ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
            ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
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
