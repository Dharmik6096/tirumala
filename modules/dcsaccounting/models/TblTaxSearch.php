<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblTax;

/**
 * TblTaxSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblTax`.
 */
class TblTaxSearch extends TblTax {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['tax_code', 'tax_group_code', 'is_active', 'originating_type'], 'integer'],
                [['tax_name', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5', 'ref_code'], 'safe'],
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
        $query = TblTax::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);
        $query->joinWith(['unionCode', 'taxGroupCode']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions

        $query->andFilterWhere([
            'tbl_tax.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_tax.tax_code', $this->tax_code])
                ->andFilterWhere(['like', 'tbl_tax.tax_name', $this->tax_name])
                ->andFilterWhere(['like', 'tbl_unions.union_name', $this->union_code])
                ->andFilterWhere(['like', 'tbl_tax_group.tax_group_name', $this->tax_group_code])
                ->andFilterWhere(['like', 'tbl_tax.ref_code', $this->ref_code]);

        return $dataProvider;
    }

}
