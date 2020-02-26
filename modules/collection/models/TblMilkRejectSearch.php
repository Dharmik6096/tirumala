<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblMilkReject;

/**
 * TblMilkRejectSearch represents the model behind the search form about `app\modules\collection\models\TblMilkReject`.
 */
class TblMilkRejectSearch extends TblMilkReject {

    public $from_date, $to_date, $from_shift, $to_shift;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['milk_reject_code', 'shift_code', 'milk_type_code', 'no_of_can'], 'integer'],
            [['source_org_type', 'source_org_code', 'dest_org_type', 'dest_org_code', 'date_time_of_collection', 'return_type', 'action_taken', 'remarks', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['fat', 'snf', 'qty', 'clr'], 'number'],
            [['from_date', 'to_date', 'from_shift', 'to_shift', 'sample_no', 'clr'], 'safe']
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
        $query = TblMilkReject::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_milk_reject', 'tbl_milk_reject', 'tbl_milk_reject');
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->date_time_of_collection))
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), date_time_of_collection, 126)', date('Y-m-d', strtotime($this->date_time_of_collection))]);

        if (!empty($this->from_date) || !empty($this->from_shift)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $from_shift = !empty($this->from_shift) ? \Yii::$app->general->getshift($this->from_shift) : '06:00:00';
            $from_date .= ' ' . $from_shift;
            $query->andFilterWhere(['>=', 'date_time_of_collection', $from_date]);
        }

        if (!empty($this->to_date) || !empty($this->to_shift)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $to_shift = !empty($this->to_shift) ? \Yii::$app->general->getshift($this->to_shift) : '18:00:00';
            $to_date .= ' ' . $to_shift;
            $query->andFilterWhere(['<=', 'date_time_of_collection', $to_date]);
        }
        $query->andFilterWhere(['like', 'source_org_type', $this->source_org_type])
                ->andFilterWhere(['like', 'source_org_code', $this->source_org_code])
                ->andFilterWhere(['like', 'dest_org_type', $this->dest_org_type])
                ->andFilterWhere(['like', 'dest_org_code', $this->dest_org_code])
                ->andFilterWhere(['like', 'return_type', $this->return_type])
                ->andFilterWhere(['like', 'action_taken', $this->action_taken])
                ->andFilterWhere(['like', 'remarks', $this->remarks])
                ->andFilterWhere(['like', 'sample_no', $this->sample_no])
                ->andFilterWhere(['like', 'clr', $this->clr])
                ->andFilterWhere(['like', 'fat', $this->fat])
                ->andFilterWhere(['like', 'snf', $this->snf])
                ->andFilterWhere(['like', 'qty', $this->qty])
                ->andFilterWhere(['like', 'union_code', $this->union_code]);

        return $dataProvider;
    }

}
