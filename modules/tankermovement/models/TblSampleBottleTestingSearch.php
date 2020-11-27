<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblSampleBottleTesting;

/**
 * TblSampleBottleTestingSearch represents the model behind the search form about `app\modules\tankermovement\models\TblSampleBottleTesting`.
 */
class TblSampleBottleTestingSearch extends TblSampleBottleTesting {

    public $from_date, $to_date, $bmc_ref_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['from_date', 'to_date', 'trip_code', 'sample_bottle_testing_date', 'transaction_date', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'bmc_ref_code'], 'safe'],
            [['fat', 'snf', 'protein', 'sample_no'], 'number'],
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
        $query = TblSampleBottleTesting::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['bmcCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_sample_bottle_testing', 'tbl_sample_bottle_testing', 'tbl_sample_bottle_testing');

        if (!empty($this->from_date)) {
            $from_date = date('Y-m-d', strtotime($this->from_date));
            $query->andFilterWhere(['>=', 'CAST(sample_bottle_testing_date as date)', $from_date]);
        }
        if (!empty($this->to_date)) {
            $to_date = date('Y-m-d', strtotime($this->to_date));
            $query->andFilterWhere(['<=', 'CAST(sample_bottle_testing_date as date)', $to_date]);
        }
        $query->andFilterWhere(['=', 'CAST(sample_bottle_testing_date as date)', !empty($this->sample_bottle_testing_date) ? date('Y-m-d', strtotime($this->sample_bottle_testing_date)) : NULL]);

        // grid filtering conditions
        $query->andFilterWhere([
            'milk_quality_type_code' => $this->milk_quality_type_code,
            'milk_type_code' => $this->milk_type_code,
            'sample_no' => $this->sample_no,
            'fat' => $this->fat,
            'snf' => $this->snf,
            'protein' => $this->protein,
        ]);

        $query->andFilterWhere(['like', 'trip_code', $this->trip_code])
                ->andFilterWhere(['like', 'tbl_bmc.ref_code', $this->bmc_ref_code]);

        return $dataProvider;
    }

}
