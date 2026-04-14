<?php

namespace app\modules\payment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\payment\models\TblDcsPaymentCycleApplicability;

/**
 * TblDcsPaymentCycleApplicabilitySearch represents the model behind the search form about `app\modules\dcsoperation\models\TblDcsPaymentCycleApplicability`.
 */
class TblDcsPaymentCycleApplicabilitySearch extends TblDcsPaymentCycleApplicability {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['payment_cycle_applicabilty_code', 'is_lock', 'data_lock', 'dcs_payment_cycle_code'], 'integer'],
                [['dcs_code', 'from_date', 'to_date', 'payment_cycle_code'], 'safe'],
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
        $query = TblDcsPaymentCycleApplicability::find();
        $request = Yii::$app->request->queryParams;
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['dcsCode']);

        $query->joinWith(['dcsCode']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($request['TblDcsPaymentCycleApplicabilitySearch']['from_date'])) {
            $from_date = date('Y-m-d', strtotime($request['TblDcsPaymentCycleApplicabilitySearch']['from_date']));
            $query->andFilterWhere(['like', 'CAST(from_date AS DATE)', $from_date]);
        }
        if (!empty($request['TblDcsPaymentCycleApplicabilitySearch']['to_date'])) {
            $to_date = date('Y-m-d', strtotime($request['TblDcsPaymentCycleApplicabilitySearch']['to_date']));
            $query->andFilterWhere(['like', 'CAST(to_date AS DATE)', $to_date]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'dcs_payment_cycle_code' => $this->dcs_payment_cycle_code,
            'payment_cycle_applicabilty_code' => $this->payment_cycle_applicabilty_code,
//            'from_date' => $this->from_date,
//            'to_date' => $this->to_date,
            'is_lock' => $this->is_lock,
            'data_lock' => $this->data_lock,
        ]);

        if (!empty($this->from_date))
            $query->andFilterWhere(['like', 'from_date', date('Y-m-d', strtotime($this->from_date))]);
        if (!empty($this->to_date))
            $query->andFilterWhere(['like', 'to_date', date('Y-m-d', strtotime($this->to_date))]);

        $query->andFilterWhere(['like', 'tbl_dcs.dcs_name', $this->dcs_code]);

        return $dataProvider;
    }

}
