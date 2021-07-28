<?php

namespace app\modules\transporter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transporter\models\TblTransporterTimeWisePenalty;

/**
 * TblTransporterTimeWisePenaltySearch represents the model behind the search form about `app\modules\transporter\models\TblTransporterTimeWisePenalty`.
 */
class TblTransporterTimeWisePenaltySearch extends TblTransporterTimeWisePenalty {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['penalty_code', 'originating_type'], 'integer'],
            [['union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['penalty_amount', 'minute_limit'], 'number'],
            [['from_date', 'to_date', 'mcc_plant_code', 'plant_code', 'wef_date'], 'safe']
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
        $query = TblTransporterTimeWisePenalty::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, '', 'tbl_transporter_time_wise_penalty');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'wef_date', $from_date]);
        }
        if (!empty($this->to_date)) {
            $from_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'wef_date', $from_date]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'penalty_code' => $this->penalty_code,
            'penalty_amount' => $this->penalty_amount,
            'minute_limit' => $this->minute_limit,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'penalty_amount', $this->penalty_amount])
                ->andFilterWhere(['like', 'minute_limit', $this->minute_limit])
                ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
                ->andFilterWhere(['like', 'plant_code', $this->plant_code])
                ->andFilterWhere(['like', 'wef_date', (!empty($this->wef_date)) ? date('Y-m-d', strtotime($this->wef_date)) : '']);


        return $dataProvider;
    }

}
