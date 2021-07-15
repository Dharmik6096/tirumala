<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblAnalyzerCleaning;

/**
 * TblAnalyzerCleaningSearch represents the model behind the search form about `app\modules\collection\models\TblAnalyzerCleaning`.
 */
class TblAnalyzerCleaningSearch extends TblAnalyzerCleaning {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['analyzer_cleaning_code', 'date_time_of_cleaning', 'date_time_of_actual_cleaning', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'data_inserted_from'], 'safe'],
            [['shift_code', 'cycle', 'counter', 'measuring', 'originating_type', 'txfarmer_id'], 'integer'],
            [['from_date', 'to_date'], 'safe']
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
        $query = TblAnalyzerCleaning::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['dcsCode', 'bmcCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(date_time_of_cleaning as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(date_time_of_cleaning as date)', $to_date]);
        }
        if (!empty($this->date_time_of_cleaning)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), date_time_of_cleaning, 126)', date('Y-m-d', strtotime($this->date_time_of_cleaning))]);
        }
        $query->andFilterWhere(['like', 'cycle', $this->cycle])
                ->andFilterWhere(['like', 'measuring', $this->measuring])
                ->andFilterWhere(['like', 'counter', $this->counter]);

        return $dataProvider;
    }

}
