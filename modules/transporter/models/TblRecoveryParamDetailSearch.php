<?php

namespace app\modules\transporter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\transporter\models\TblRecoveryParamDetail;

/**
 * TblRecoveryParamDetailSearch represents the model behind the search form about `app\modules\transporter\models\TblRecoveryParamDetail`.
 */
class TblRecoveryParamDetailSearch extends TblRecoveryParamDetail {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['param_detail_code', 'is_active'], 'integer'],
            [['chilling_cost', 'incentive_value'], 'number'],
            [['wef_date', 'plant_code', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'from_date', 'to_date'], 'safe'],
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
        $query = TblRecoveryParamDetail::find();

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
        Yii::$app->general->filterByOrg($query, $this, '', 'tbl_recovery_param_detail');

      
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
            'param_detail_code' => $this->param_detail_code,
            'is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'plant_code', $this->plant_code])
                ->andFilterWhere(['like', 'union_code', $this->union_code])
                ->andFilterWhere(['like', 'chilling_cost', $this->chilling_cost])
                ->andFilterWhere(['like', 'incentive_value', $this->incentive_value])
                ->andFilterWhere(['like', 'wef_date', ($this->wef_date == '') ? '' : Yii::$app->formatter->asDate($this->wef_date, 'php:Y-m-d')]);


        return $dataProvider;
    }

}
