<?php

namespace app\modules\transporter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transporter\models\TblMccWiseTransportationCost;

/**
 * TblMccWiseTransportationCostSearch represents the model behind the search form about `app\modules\transporter\models\TblMccWiseTransportationCost`.
 */
class TblMccWiseTransportationCostSearch extends TblMccWiseTransportationCost {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tpt_cost_code', 'originating_type'], 'integer'],
            [['union_code', 'mcc_plant_code', 'plant_code', 'wef_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
            [['primary_tpt_cost'], 'number'],
            [['from_date', 'to_date', 'bmc_code'], 'safe']
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
        $query = TblMccWiseTransportationCost::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, '', 'tbl_mcc_wise_transportation_cost');
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
            'tpt_cost_code' => $this->tpt_cost_code,
        ]);

        $query->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'mcc_plant_code', $this->mcc_plant_code])
                ->andFilterWhere(['like', 'plant_code', $this->plant_code])
                ->andFilterWhere(['like', 'primary_tpt_cost', $this->primary_tpt_cost])
                ->andFilterWhere(['like', 'wef_date', (!empty($this->wef_date)) ? date('Y-m-d', strtotime($this->wef_date)) : '']);
        return $dataProvider;
    }

}
