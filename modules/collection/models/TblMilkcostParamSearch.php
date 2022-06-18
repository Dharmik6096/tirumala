<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblMilkcostParam;

/**
 * TblMilkcostParamSearch represents the model behind the search form about `app\modules\collection\models\TblMilkcostParam`.
 */
class TblMilkcostParamSearch extends TblMilkcostParam {

    public $from_date, $to_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['milkcost_param_code', 'originating_type', 'from_date', 'to_date'], 'safe'],
                [['chilling_rate', 'primary_tpt_cost', 'commission_percentage', 'labour_charge', 'service_charge'], 'safe'],
                [['wef_date', 'union_code', 'plant_code', 'mcc_plant_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblMilkcostParam::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_milkcost_param', 'tbl_milkcost_param');

        if (!empty($this->from_date)) {
            $from_date = !empty($this->from_date) ? date('Y-m-d', strtotime($this->from_date)) : date('Y-m-d');
            $query->andFilterWhere(['>=', 'cast(wef_date as date)', $from_date]);
        }

        if (!empty($this->to_date)) {
            $to_date = !empty($this->to_date) ? date('Y-m-d', strtotime($this->to_date)) : date('Y-m-d');
            $query->andFilterWhere(['<=', 'cast(wef_date as date)', $to_date]);
        }
        $query->andFilterWhere([
            'wef_date' => !empty($this->wef_date) ? date('Y-m-d', strtotime($this->wef_date)) : NULL,
        ]);

        $query->andFilterWhere(['like', 'chilling_rate', $this->chilling_rate])
                ->andFilterWhere(['like', 'primary_tpt_cost', $this->primary_tpt_cost])
                ->andFilterWhere(['like', 'commission_percentage', $this->commission_percentage])
                ->andFilterWhere(['like', 'labour_charge', $this->labour_charge])
                ->andFilterWhere(['like', 'service_charge', $this->service_charge]);

        return $dataProvider;
    }

}
