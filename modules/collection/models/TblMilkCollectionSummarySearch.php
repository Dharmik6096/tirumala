<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblMilkCollectionSummary;

/**
 * TblMilkCollectionSummarySearch represents the model behind the search form about `app\modules\collection\models\TblMilkCollectionSummary`.
 */
class TblMilkCollectionSummarySearch extends TblMilkCollectionSummary {

    public $operator_fat, $operator_snf, $operator_qty, $ref_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_collection_summary_code', 'shift_code', 'sample_count', 'auto_count', 'manual_count'], 'integer'],
            [['date_time_of_collection', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'originating_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'data_post_status'], 'safe'],
            [['avg_fat', 'avg_snf', 'kg_fat', 'kg_snf', 'total_qty', 'avg_rate', 'total_amount'], 'number'],
            [['from_date', 'to_date', 'from_shift', 'to_shift', 'operator_fat', 'operator_snf', 'operator_qty', 'ref_code', 'data_inserted_from'], 'safe'],
            [['f_union_code', 'f_plant_code', 'f_mcc_code', 'f_bmc_code', 'f_dcs_code'], 'safe'],
            [['f_union_code'], 'required', 'on' => ['listSearch']],
            [['to_date'], function ($attribute, $params) {
                    if (empty($this->f_plant_code)) {
                        Yii::$app->general->dateRangeValidate($this, $attribute, $params, 'from_date', 'to_date', 5, '>', 'Day Difference can not be greater than 5');
                    }
                }, 'on' => 'listSearch'],
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
        $query = TblMilkCollectionSummary::find();

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
        $query->joinWith(['dcsCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');
        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
            $to_date .= ' ' . $to_shift;
            $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);
        }

        if (!empty($this->avg_fat)) {
            $query->andFilterWhere([$this->operator_fat, 'avg_fat', $this->avg_fat]);
        }
        if (!empty($this->avg_snf)) {
            $query->andFilterWhere([$this->operator_snf, 'avg_snf', $this->avg_snf]);
        }
        if (!empty($this->total_qty)) {
            $query->andFilterWhere([$this->operator_qty, 'total_qty', $this->total_qty]);
        }
        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);

        // grid filtering conditions
        $query->andFilterWhere([
            'milk_collection_summary_code' => $this->milk_collection_summary_code,
            'avg_rate' => $this->avg_rate,
            'shift_code' => $this->shift_code,
            'sample_count' => $this->sample_count,
            'auto_count' => $this->auto_count,
            'manual_count' => $this->manual_count,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'plant_code', $this->plant_code])
                ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
                ->andFilterWhere(['like', 'bmc_code', $this->bmc_code])
                ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'tbl_dcs.ref_code', $this->ref_code])
                ->andFilterWhere(['like', 'total_amount', $this->total_amount])
                ->andFilterWhere(['like', 'kg_snf', $this->kg_snf])
                ->andFilterWhere(['like', 'kg_fat', $this->kg_fat]);

        return $dataProvider;
    }

    public function searchrepush($params) {
        $query = TblMilkCollectionSummary::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => FALSE
        ]);

        $this->load($params);

        $query->join('LEFT JOIN', 'tbl_dcs', 'tbl_dcs.dcs_code = tbl_milk_collection_summary.dcs_code');
        Yii::$app->general->filterByOrg($query, $this, 'tbl_milk_collection_summary', 'tbl_milk_collection_summary', 'tbl_milk_collection_summary', 'tbl_milk_collection_summary');

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
        $from_date .= ' ' . $from_shift;
        $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
        $to_date .= ' ' . $to_shift;
        $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);
        $query->andFilterWhere([
            'milk_collection_summary_code' => $this->milk_collection_summary_code,
            'avg_rate' => $this->avg_rate,
            'sample_count' => $this->sample_count,
            'auto_count' => $this->auto_count,
            'manual_count' => $this->manual_count,
            'tbl_milk_collection_summary.data_post_status' => $this->data_post_status
        ]);


        return $dataProvider;
    }

}
