<?php

namespace app\modules\dcsaccounting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\dcsaccounting\models\TblBasicTax;

/**
 * TblBasicTaxSearch represents the model behind the search form about `app\modules\dcsaccounting\models\TblBasicTax`.
 */
class TblBasicTaxSearch extends TblBasicTax {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['basic_tax_code', 'is_active', 'originating_type'], 'integer'],
                [['basic_tax_name', 'union_code', 'created_at', 'created_by', 'updated_at', 'updated_by', 'originating_org_code', 'originating_org_type', 'x_col1', 'x_col2', 'x_col3', 'x_col4', 'x_col5'], 'safe'],
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
        $query = TblBasicTax::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);
        $query->joinWith(['unionCode']);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'tbl_basic_tax.is_active' => $this->is_active,
        ]);
        $query->andFilterWhere(['like', 'tbl_basic_tax.basic_tax_name', $this->basic_tax_name])
                ->andFilterWhere(['like', 'tbl_basic_tax.basic_tax_code', $this->basic_tax_code])
                ->andFilterWhere(['like', 'tbl_unions.union_name', $this->union_code]);

        return $dataProvider;
    }

}
