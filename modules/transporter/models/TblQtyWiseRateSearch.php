<?php

namespace app\modules\transporter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transporter\models\TblQtyWiseRate;

/**
 * TblQtyWiseRateSearch represents the model behind the search form about `app\modules\transporter\models\TblQtyWiseRate`.
 */
class TblQtyWiseRateSearch extends TblQtyWiseRate {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['qty_code'], 'integer'],
                [['from_qty', 'to_qty', 'rate'], 'number'],
                [[ 'from_date', 'to_date','rate', 'from_qty', 'to_qty', 'originating_type', 'wef_date', 'vehicle_code', 'transporter_code', 'union_code', 'remarks', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblQtyWiseRate::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'transporter_code' => $this->transporter_code,
            'vehicle_code' => $this->vehicle_code,
            'wef_date' => !empty($this->wef_date) ? date('Y-m-d', strtotime($this->wef_date)) : NULL,
        ]);

        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(wef_date as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(wef_date as date)', $to_date]);
        }
        $query->andFilterWhere(['like', 'rate', $this->rate])
                ->andFilterWhere(['like', 'from_qty', $this->from_qty])
                ->andFilterWhere(['like', 'to_qty', $this->to_qty])
                ->andFilterWhere(['like', 'remarks', $this->remarks]);

        $query->orderBy('wef_date DESC, transporter_code DESC,vehicle_code DESC,from_qty ASC');

        return $dataProvider;
    }

}
