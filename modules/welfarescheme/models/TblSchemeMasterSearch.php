<?php

namespace app\modules\welfarescheme\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\welfarescheme\models\TblSchemeMaster;

/**
 * TblSchemeMasterSearch represents the model behind the search form about `app\modules\welfarescheme\models\TblSchemeMaster`.
 */
class TblSchemeMasterSearch extends TblSchemeMaster {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['scheme_id', 'is_active', 'originating_type'], 'integer'],
                [['min_pouring_day', 'min_pouring_qty', 'scheme_value', 'wef_date', 'from_date', 'to_date', 'scheme_id', 'is_active', 'originating_type', 'scheme_name', 'start_date', 'end_date', 'remarks', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type'], 'safe'],
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
        $query = TblSchemeMaster::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);

        if (!empty($this->from_date) && !empty($this->to_date)) {
            $query->andWhere(['or',
                    ['between', 'tbl_scheme_master.start_date', date('Y-m-d', strtotime($this->from_date)), date('Y-m-d', strtotime($this->to_date))],
                    ['between', 'tbl_scheme_master.end_date', date('Y-m-d', strtotime($this->from_date)), date('Y-m-d', strtotime($this->to_date))]]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->wef_date))
            $query->andFilterWhere(['like', 'tbl_scheme_criteria.wef_date', date('Y-m-d', strtotime($this->wef_date))]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_scheme_master.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'scheme_name', $this->scheme_name])
                ->andFilterWhere(['like', 'scheme_id', $this->scheme_id])
                ->andFilterWhere(['like', 'remarks', $this->remarks])
                ->andFilterWhere(['like', 'tbl_scheme_criteria.min_pouring_day', $this->min_pouring_day])
                ->andFilterWhere(['like', 'tbl_scheme_criteria.min_pouring_qty', $this->min_pouring_qty])
                ->andFilterWhere(['like', 'tbl_scheme_criteria.scheme_value', $this->scheme_value]);

        return $dataProvider;
    }

}
