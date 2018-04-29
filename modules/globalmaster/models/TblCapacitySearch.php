<?php

namespace app\modules\globalmaster\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\globalmaster\models\TblCapacity;

/**
 * TblCapacitySearch represents the model behind the search form about `app\modules\globalmaster\models\TblCapacity`.
 */
class TblCapacitySearch extends TblCapacity
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['capacity_code', 'is_active', 'value'], 'integer'],
            [['unit', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = TblCapacity::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'capacity_code' => $this->capacity_code,
            'is_active' => $this->is_active,
            'value' => $this->value,           
        ]);

        $query->andFilterWhere(['like', 'unit', $this->unit]);
            

        return $dataProvider;
    }
}
