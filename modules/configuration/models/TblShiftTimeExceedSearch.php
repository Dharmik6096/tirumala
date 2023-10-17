<?php

namespace app\modules\configuration\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\configuration\models\TblShiftTimeExceed;

/**
 * TblShiftTimeExceedSearch represents the model behind the search form about `app\modules\configuration\models\TblShiftTimeExceed`.
 */
class TblShiftTimeExceedSearch extends TblShiftTimeExceed {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['shift_time_exceed_code', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'org_type', 'org_code', 'date_time_of_collection', 'standard_time', 'exceed_time', 'remarks', 'status', 'status_datetime', 'status_by', 'status_remarks', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'shift_code', 'originating_type'], 'safe'],
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
        $query = TblShiftTimeExceed::find();

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
            'date_time_of_collection' => $this->date_time_of_collection,
            'shift_code' => $this->shift_code,
            'standard_time' => $this->standard_time,
            'exceed_time' => $this->exceed_time,
            'status_datetime' => $this->status_datetime,
        ]);

        $query->andFilterWhere(['like', 'shift_time_exceed_code', $this->shift_time_exceed_code])
                ->andFilterWhere(['like', 'org_type', $this->org_type])
                ->andFilterWhere(['like', 'org_code', $this->org_code])
                ->andFilterWhere(['like', 'remarks', $this->remarks])
                ->andFilterWhere(['like', 'status', $this->status])
                ->andFilterWhere(['like', 'status_by', $this->status_by])
                ->andFilterWhere(['like', 'status_remarks', $this->status_remarks]);

        return $dataProvider;
    }

}
