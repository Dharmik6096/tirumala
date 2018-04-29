<?php

namespace app\modules\product\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\product\models\TblProductGroup;

/**
 * TblProductGroupSearch represents the model behind the search form about `app\modules\product\models\TblProductGroup`.
 */
class TblProductGroupSearch extends TblProductGroup
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_group_code', 'is_active'], 'integer'],
            [['product_group_name', 'created_at', 'created_by', 'updated_at', 'updated_by', 'local_name'], 'safe'],
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
        $query = TblProductGroup::find();

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
            'product_group_code' => $this->product_group_code,
            'created_at' => $this->created_at,
            'is_active' => $this->is_active,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'product_group_name', $this->product_group_name])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'local_name', $this->local_name]);

        return $dataProvider;
    }
}
