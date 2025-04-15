<?php

namespace app\modules\tankermovement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tankermovement\models\TblVehicleQaInspection;

/**
 * TblVehicleQaInspectionSearch represents the model behind the search form about `app\modules\tankermovement\models\TblVehicleQaInspection`.
 */
class TblVehicleQaInspectionSearch extends TblVehicleQaInspection {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['from_date', 'to_date', 'vehicle_qa_inspection_code', 'originating_type', 'union_code', 'transporter_code', 'vehicle_code', 'trip_code', 'transaction_datetime', 'status', 'remarks', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblVehicleQaInspection::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->from_date = date('Y-m-d', strtotime('-10 days'));
        $this->to_date = date('Y-m-d');
        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_vehicle_qa_inspection');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'vehicle_qa_inspection_code' => $this->vehicle_qa_inspection_code,
        ]);
        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(transaction_datetime as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(transaction_datetime as date)', $to_date]);
        }

        $query->andFilterWhere(['like', 'transporter_code', $this->transporter_code])
                ->andFilterWhere(['like', 'vehicle_code', $this->vehicle_code])
                ->andFilterWhere(['like', 'trip_code', $this->trip_code])
                ->andFilterWhere(['like', 'status', $this->status])
                ->andFilterWhere(['like', 'remarks', $this->remarks]);
        return $dataProvider;
    }

}
