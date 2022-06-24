<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblVehicleTollDetail;

/**
 * TblVehicleTollDetailSearch represents the model behind the search form about `app\modules\payment\models\TblVehicleTollDetail`.
 */
class TblVehicleTollDetailSearch extends TblVehicleTollDetail {

    public $from_date, $to_date, $f_union_code;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['toll_detail_code'], 'integer'],
            [['dispatch_date', 'vehicle_code', 'parsing_no', 'from_type', 'from_dest', 'to_type', 'to_dest', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['toll_amount', 'fastag_amount', 'weighing_cost'], 'number'],
            [['f_union_code', 'from_date', 'to_date'], 'safe'],
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
        $query = TblVehicleTollDetail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->joinWith(['plantCodeSource ps', 'plantCodeDest pd', 'mccPlantCodeSource ms', 'mccPlantCodeDest md', 'customerCodeSource cs', 'customerCodeDest cd']);
        Yii::$app->general->DeliveryChallanOrgFilter($query, 'tbl_vehicle_toll_detail', 'from_dest', 'to_dest');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'dispatch_date', $from_date]);
        }
        if (!empty($this->to_date)) {
            $from_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'dispatch_date', $from_date]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'toll_detail_code' => $this->toll_detail_code,
        ]);

        $query->andFilterWhere(['like', 'parsing_no', $this->parsing_no])
                ->andFilterWhere(['like', 'toll_amount', $this->toll_amount])
                ->andFilterWhere(['like', 'weighing_cost', $this->weighing_cost])
                ->andFilterWhere(['like', 'fastag_amount', $this->fastag_amount])
                ->andFilterWhere(['like', 'from_type', $this->from_type])
                ->andFilterWhere(['like', 'from_dest', $this->from_dest])
                ->andFilterWhere(['like', 'to_type', $this->to_type])
                ->andFilterWhere(['like', 'to_dest', $this->to_dest])
                ->andFilterWhere(['like', 'dispatch_date', ($this->dispatch_date == '') ? '' : Yii::$app->formatter->asDate($this->dispatch_date, 'php:Y-m-d')]);


        return $dataProvider;
    }

}
