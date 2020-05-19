<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProductGroup;

/**
 * TblProductGroupSearch represents the model behind the search form about `app\modules\product\models\TblProductGroup`.
 */
class TblProductGroupSearch extends TblProductGroup {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['product_group_code', 'is_active'], 'integer'],
                [['product_group_name', 'created_at', 'created_by', 'updated_at', 'updated_by', 'local_name', 'union_code', 'unit_code', 'ref_code'], 'safe'],
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
        $query = TblProductGroup::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query, $this);
        $query->joinWith(['unionCode', 'unitCode']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_product_group.product_group_code' => $this->product_group_code,
            'tbl_product_group.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_product_group.product_group_name', $this->product_group_name])
                ->andFilterWhere(['like', 'tbl_unions.union_name', $this->union_code])
                ->andFilterWhere(['like', 'tbl_units.unit_name', $this->unit_code])
                ->andFilterWhere(['like', 'tbl_product_group.ref_code', $this->ref_code])
                ->andFilterWhere(['like', 'tbl_product_group.local_name', $this->local_name]);

        return $dataProvider;
    }

}
