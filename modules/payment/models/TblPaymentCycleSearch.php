<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblPaymentCycle;

/**
 * TblPaymentCycleSearch represents the model behind the search form about `app\modules\payment\models\TblPaymentCycle`.
 */
class TblPaymentCycleSearch extends TblPaymentCycle {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['payment_cycle_code', 'interval_value', 'is_active', 'originating_type'], 'safe'],
                [['union_code', 'from_date', 'from_shift', 'to_date', 'to_shift', 'originating_org_code', 'originating_org_type', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = TblPaymentCycle::find();
        $request = Yii::$app->request->queryParams;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);

        // var_dump($query->all()); exit;
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($request['TblPaymentCycleSearch']['from_date'])) {
            $from_date = date('Y-m-d', strtotime($request['TblPaymentCycleSearch']['from_date']));
            $query->andFilterWhere(['like', 'CAST(from_date AS DATE)', $from_date]);
        }
        if (!empty($request['TblPaymentCycleSearch']['to_date'])) {
            $to_date = date('Y-m-d', strtotime($request['TblDcsPaymentCycleSearch']['to_date']));
            $query->andFilterWhere(['like', 'CAST(tbl_payment_cycle.to_date AS DATE)', $to_date]);
        }

        // grid filtering conditions

        if (!empty($this->from_date))
            $query->andFilterWhere(['like', 'tbl_payment_cycle.from_date', date('Y-m-d', strtotime($this->from_date))]);
        if (!empty($this->to_date))
            $query->andFilterWhere(['like', 'tbl_payment_cycle.to_date', date('Y-m-d', strtotime($this->to_date))]);

        Yii::$app->general->filterByNumber($query, $this, ['interval_value']);

        $query->andFilterWhere([
            'tbl_payment_cycle.is_active' => $this->is_active,
        ]);

        return $dataProvider;
    }

}
