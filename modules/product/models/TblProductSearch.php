<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProduct;

/**
 * TblProductSearch represents the model behind the search form about `app\modules\product\models\TblProduct`.
 */
class TblProductSearch extends TblProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_code', 'is_active'], 'integer'],
            [['product_group_code','product_name', 'description', 'created_at', 'created_by', 'updated_at', 'updated_by', 'local_name', 'union_code'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = TblProduct::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        Yii::$app->general->filterByOrg($query,$this);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        if(!empty($this->product_group_code))
        {
            $query->joinWith(['productGroupCode']);
        }
        
        Yii::$app->general->filterByOrg($query,$this);
        
        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_product.product_code' => $this->product_code,
            'tbl_product.is_active' => $this->is_active,
        ]);

        $query->andFilterWhere(['like', 'tbl_product.product_name', $this->product_name])
            ->andFilterWhere(['like', 'tbl_product.description', $this->description])
            ->andFilterWhere(['like', 'tbl_product_group.product_group_name', $this->product_group_code])
            ->andFilterWhere(['like', 'tbl_product.union_code', $this->union_code])
            ->andFilterWhere(['like', 'tbl_product.local_name', $this->local_name]);

        return $dataProvider;
    }
}
