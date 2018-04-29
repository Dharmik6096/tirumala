<?php

namespace app\modules\globalmaster\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\globalmaster\models\TblAnimalType;

/**
 * TblAnimalTypeSearch represents the model behind the search form about `app\models\TblAnimalType`.
 */
class TblAnimalTypeSearch extends TblAnimalType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['is_active', 'is_milch'], 'integer'],
            [['animal_type_code', 'animal_type_name', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'safe'],
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
        $query = TblAnimalType::find();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['animal_type_name' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tbl_animal_type.is_active' => $this->is_active,
            'tbl_animal_type.is_milch' => $this->is_milch,
        ]);

        $query->andFilterWhere(['like', 'tbl_animal_type.animal_type_name', $this->animal_type_name])
            ->andFilterWhere(['like', 'tbl_animal_type.animal_type_code', $this->animal_type_code]);

        return $dataProvider;
    }
}
