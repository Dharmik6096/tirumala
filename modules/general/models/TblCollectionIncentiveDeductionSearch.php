<?php

namespace app\modules\general\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\general\models\TblCollectionIncentiveDeduction;

/**
 * TblCollectionIncentiveDeductionSearch represents the model behind the search form about `app\modules\general\models\TblCollectionIncentiveDeduction`.
 */
class TblCollectionIncentiveDeductionSearch extends TblCollectionIncentiveDeduction {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['incentive_deduction_id', 'scheme_type', 'shift_code'], 'integer'],
            [['dcs_code', 'from_time', 'to_time', 'from_date', 'to_date', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['amount'], 'number'],
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
        $query = TblCollectionIncentiveDeduction::find();

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

        $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
        $query->andFilterWhere(['>=', 'cast(from_date as date)', $from_date]);

        $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
        $query->andFilterWhere(['<=', 'cast(to_date as date)', $to_date]);

        $query->andFilterWhere([
            'dcs_code' => $this->dcs_code,
        ]);

        return $dataProvider;
    }

}
