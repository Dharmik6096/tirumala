<?php

namespace app\modules\transporter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transporter\models\TblVehicleExtraQtyDaywise;

/**
 * TblVehicleExtraQtyDaywiseSearch represents the model behind the search form about `app\modules\transporter\models\TblVehicleExtraQtyDaywise`.
 */
class TblVehicleExtraQtyDaywiseSearch extends TblVehicleExtraQtyDaywise {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['extra_qty_code', 'originating_type'], 'integer'],
                [['additional_qty', 'deduction_qty', 'rate'], 'number'],
                [['extra_qty_code', 'originating_type', 'additional_qty', 'deduction_qty', 'rate', 'date', 'vehicle_code', 'transporter_code', 'union_code', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'from_date', 'to_date'], 'safe'],
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
        $query = TblVehicleExtraQtyDaywise::find();

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
        Yii::$app->general->filterByOrg($query, $this);

        // grid filtering conditions
        $query->andFilterWhere([
            'extra_qty_code' => $this->extra_qty_code,
            'additional_qty' => $this->additional_qty,
            'deduction_qty' => $this->deduction_qty,
            'rate' => $this->rate,
        ]);
        if (!empty($this->date))
            $query->andFilterWhere(['and', ['>=', 'date', date('Y-m-d', strtotime($this->date))], ['<=', 'date', date('Y-m-d', strtotime($this->date))]]);

        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(date as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(date as date)', $to_date]);
        }
        $query->andFilterWhere(['like', 'vehicle_code', $this->vehicle_code])
                ->andFilterWhere(['like', 'transporter_code', $this->transporter_code])
                ->andFilterWhere(['like', 'remarks', $this->remarks])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }

}
