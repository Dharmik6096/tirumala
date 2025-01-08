<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblMccShiftEndSummaryAdulterationTest;

/**
 * TblMccShiftEndSummaryAdulterationTestSearch represents the model behind the search form about `app\modules\collection\models\TblMccShiftEndSummaryAdulterationTest`.
 */
class TblMccShiftEndSummaryAdulterationTestSearch extends TblMccShiftEndSummaryAdulterationTest
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mcc_shift_end_summary_adulteration_test_code', 'shift_code', 'milk_type_code', 'taste', 'alcohol', 'cob', 'glucose', 'salt', 'sugar', 'urea', 'starch', 'rosolic_acid', 'h2o2', 'formalin', 'detergent', 'nitrate_comp', 'ammonium_comp', 'originating_type'], 'integer'],
            [['date_time_of_collection', 'malto_dextrin', 'remarks', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'flg_sentbox_entry', 'sync_status', 'sync_timestamp', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['quantity', 'fat', 'snf', 'temperature', 'acidity', 'mbrt', 'protein_chainna', 'br_value', 'rm_value'], 'number'],
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
        $query = TblMccShiftEndSummaryAdulterationTest::find();

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
            'mcc_shift_end_summary_adulteration_test_code' => $this->mcc_shift_end_summary_adulteration_test_code,
            'date_time_of_collection' => $this->date_time_of_collection,
            'shift_code' => $this->shift_code,
            'milk_type_code' => $this->milk_type_code,
            'quantity' => $this->quantity,
            'fat' => $this->fat,
            'snf' => $this->snf,
            'temperature' => $this->temperature,
            'taste' => $this->taste,
            'alcohol' => $this->alcohol,
            'cob' => $this->cob,
            'glucose' => $this->glucose,
            'salt' => $this->salt,
            'sugar' => $this->sugar,
            'urea' => $this->urea,
            'starch' => $this->starch,
            'rosolic_acid' => $this->rosolic_acid,
            'h2o2' => $this->h2o2,
            'formalin' => $this->formalin,
            'detergent' => $this->detergent,
            'nitrate_comp' => $this->nitrate_comp,
            'ammonium_comp' => $this->ammonium_comp,
            'acidity' => $this->acidity,
            'mbrt' => $this->mbrt,
            'protein_chainna' => $this->protein_chainna,
            'br_value' => $this->br_value,
            'rm_value' => $this->rm_value,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sync_timestamp' => $this->sync_timestamp,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'malto_dextrin', $this->malto_dextrin])
            ->andFilterWhere(['like', 'remarks', $this->remarks])
            ->andFilterWhere(['like', 'union_code', $this->union_code])
            ->andFilterWhere(['like', 'plant_code', $this->plant_code])
            ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
            ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
            ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'flg_sentbox_entry', $this->flg_sentbox_entry])
            ->andFilterWhere(['like', 'sync_status', $this->sync_status])
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
