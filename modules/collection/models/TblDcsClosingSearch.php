<?php

namespace app\modules\collection\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\collection\models\TblDcsClosing;

/**
 * TblDcsClosingSearch represents the model behind the search form about `app\modules\collection\models\TblDcsClosing`.
 */
class TblDcsClosingSearch extends TblDcsClosing {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dcs_closing_code', 'transaction_date', 'to_date', 'remarks', 'union_code', 'plant_code', 'mcc_plant_code', 'bmc_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'dcs_code'], 'safe'],
            [['to_shift_code', 'milk_type_code', 'originating_type'], 'integer'],
            [['qty', 'fat', 'snf', 'water', 'protein'], 'number'],
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
        $query = TblDcsClosing::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['dcsCode']);
        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs_closing');

        $this->load($params);
        if (!empty($this->to_date)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), to_date, 126)', date('Y-m-d', strtotime($this->to_date))]);
        }
        if (!empty($this->transaction_date)) {
            $query->andFilterWhere(['like', 'CONVERT(VARCHAR(25), transaction_date, 126)', date('Y-m-d', strtotime($this->transaction_date))]);
        }
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions




        return $dataProvider;
    }

}
