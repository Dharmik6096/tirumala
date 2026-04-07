<?php

namespace app\modules\insurance\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\insurance\models\TblInsuranceDetailSummary;

/**
 * TblInsuranceDetailSummarySearch represents the model behind the search form about `app\modules\insurance\models\TblInsuranceDetailSummary`.
 */
class TblInsuranceDetailSummarySearch extends TblInsuranceDetailSummary {

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['insurance_detail_summary_code', 'insurance_master_code', 'originating_type'], 'integer'],
            [['dcs_code', 'from_date', 'to_date', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
            [['union_code', 'plant_code', 'mcc_plant_code', 'bmc_code'], 'safe'],
            [['to_date', 'from_date'], 'required'],
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
        $query = TblInsuranceDetailSummary::find();

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
            'insurance_detail_summary_code' => $this->insurance_detail_summary_code,
            'insurance_master_code' => $this->insurance_master_code,
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'originating_type' => $this->originating_type,
        ]);

        $query->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
            ->andFilterWhere(['like', 'status', $this->status])
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
