<?php

namespace app\modules\dcsoperation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabitity;

/**
 * TblDcsPurchaseRateApplicabititySearch represents the model behind the search form about `app\modules\dcsoperation\models\TblDcsPurchaseRateApplicabitity`.
 */
class TblDcsPurchaseRateApplicabititySearch extends TblDcsPurchaseRateApplicabitity {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                //   [['rate_app_code', 'created_at', 'created_by', 'deleted_at', 'deleted_by', 'updated_at', 'updated_by', 'wef_date', 'dcs_code', 'purchase_rate_code', 'union_code'], 'safe'],
                //   [['is_active'], 'boolean'],
                //   [['shift_code'], 'integer'],
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
        $query = TblDcsPurchaseRateApplicabitity::find();
        $query->where(['purchase_rate_code' => $this->purchase_rate_code]);
        $query->orderBy(['wef_date' => SORT_DESC]);
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
        $query->andFilterWhere([
            'created_at' => $this->created_at,
            'is_active' => $this->is_active,
            'updated_at' => $this->updated_at,
            'wef_date' => $this->wef_date,
            'shift_code' => $this->shift_code,
        ]);

        $query->andFilterWhere(['like', 'rate_app_code', $this->rate_app_code])
                ->andFilterWhere(['like', 'created_by', $this->created_by])
                ->andFilterWhere(['like', 'updated_by', $this->updated_by])
                ->andFilterWhere(['like', 'dcs_code', $this->dcs_code])
                ->andFilterWhere(['like', 'purchase_rate_code', $this->purchase_rate_code])
                ->andFilterWhere(['like', 'union_code', $this->union_code]);

        return $dataProvider;
    }

}
