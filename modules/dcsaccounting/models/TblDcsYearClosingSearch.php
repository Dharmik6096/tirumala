<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblDcsYearClosing;

/**
 * TblDcsYearClosingSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblDcsYearClosing`.
 */
class TblDcsYearClosingSearch extends TblDcsYearClosing {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['dcs_year_closing_code', 'closing_date', 'financial_year_code', 'originating_type', 'originating_org_code', 'originating_org_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblDcsYearClosing::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        Yii::$app->general->filterByOrg($query, $this, 'tbl_dcs_year_closing', 'tbl_dcs_year_closing', 'tbl_dcs_year_closing', 'tbl_dcs_year_closing');

        if (!empty($this->closing_date))
            $query->andFilterWhere(['tbl_dcs_year_closing.closing_date' => date('Y-m-d', strtotime($this->closing_date))]);


        $query->andFilterWhere(['like', 'tbl_dcs_year_closing.dcs_year_closing_code', $this->dcs_year_closing_code])
                ->andFilterWhere(['like', 'tbl_dcs_year_closing.financial_year_code', $this->financial_year_code]);

        return $dataProvider;
    }

}
