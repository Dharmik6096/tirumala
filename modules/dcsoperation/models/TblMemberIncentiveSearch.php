<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblMemberIncentive;

/**
 * TblMemberIncentiveSearch represents the model behind the search form about `app\modules\dcsoperation\models\TblMemberIncentive`.
 */
class TblMemberIncentiveSearch extends TblMemberIncentive {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['member_incentive_code', 'pouring_days', 'total_qty', 'avg_fat', 'avg_snf', 'milk_amount', 'incentive_amount', 'originating_type', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'dcs_code', 'member_code', 'member_vendor_code', 'from_date', 'to_date', 'bonus_criteria', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblMemberIncentive::find();

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
        if (!empty($this->from_date)) {
            $query->andFilterWhere(['CAST(from_date as date)' => date('Y-m-d', strtotime($this->from_date))]);
        }
        if (!empty($this->to_date)) {
            $query->andFilterWhere(['CAST(to_date as date)' => date('Y-m-d', strtotime($this->to_date))]);
        }

        $query->andFilterWhere(['like', 'total_qty', $this->total_qty])
                ->andFilterWhere(['like', 'pouring_days', $this->pouring_days])
                ->andFilterWhere(['like', 'avg_fat', $this->avg_fat])
                ->andFilterWhere(['like', 'avg_snf', $this->avg_snf])
                ->andFilterWhere(['like', 'milk_amount', $this->milk_amount])
                ->andFilterWhere(['like', 'incentive_amount', $this->incentive_amount])
                ->andFilterWhere(['like', 'member_vendor_code', $this->member_vendor_code])
                ->andFilterWhere(['like', 'bonus_criteria', $this->bonus_criteria]);

        return $dataProvider;
    }

}
